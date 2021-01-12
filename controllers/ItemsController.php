<?php

namespace app\controllers;

use Yii;
use app\models\Items;
use app\models\Import;
use app\models\Check;
use app\models\Moving;
use app\models\Locations;
use app\models\ItemsSearch;
use app\models\MovingSearch;
use app\models\Status;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;

use kartik\mpdf\Pdf;
use \phpexcel;

#require "/vendor/phpoffice/phpexcel/Classes/PHPExcel.php";

/**
 * ItemsController implements the CRUD actions for Items model.
 */
class ItemsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => [ 'POST' ],
                ],
            ],
        ];
    }

    /**
     * Добавление предмета/оборудование если его нет
     * @param array $options
     *        string 'invent'
     *        string 'model'
     *        string 'comment'
     *        string|NULL 'type'
     *        string|NULL 'netname'
     *        string|NULL 'os'
     *        string|NULL 'mac'
     *        string|NULL 'serial'
     *        string|NULL 'product'
     *        string|NULL 'modelnumber'
     * @return integer|FALSE
     */
    public static function addIfNeed($options)
    {
        $result = [
            'id' => FALSE,
            'error' => Yii::t('items', 'Items: Key field missing "invent" :') . print_r($options, TRUE),
        ];
        // Если указан инвентарный номер
        if (is_array($options) && isset($options[ 'invent' ]))
        {
            $item = Items::find()
                ->where([ 'invent' => $options[ 'invent' ]]); // Ищем оборудование с инвентарным номером.
            // Если указан серийный номер
            if (isset($options[ 'serial' ])) {
                $item = $item->andWhere([ 'like', 'serial', $options[ 'serial' ]]); // Ищем дополнительно с серийным номером
            }
            $item = $item->all(); // Получаем все записи

            if (count($item) > 0) // Записи найдены, выводим первую совпавшую
            {
                $result[ 'id' ] = $item[ 0 ]->id;
                $result[ 'error' ] = '';
            }
            else
            {
                // Внесённого оборудования не найдено. Добавим новую запись
                if (isset($options[ 'model' ]))
                {
                    $model = ModelsController::addIfNeed($options);
                    if ($model[ 'id' ] === FALSE)
                    {
                        $result[ 'error' ] .= '<br />' . $model[ 'error' ];
                    }
                    else
                    {
                        // Создаём новую запись предмета/оборудования
                        $item = new Items();
                        $item->name        = isset($options[ 'netName' ]) ? $options[ 'netName' ] : NULL; // Сетевое имя

                        $item->model_id    = $model[ 'id' ];                                              // идентификатор модели (Подготовлено для преобразования)
                        $item->invent      = isset($options[ 'invent' ]) ? $options[ 'invent' ] : NULL;   // Инвентарный номер
                        $item->comment     = isset($options[ 'comment' ]) ? $options[ 'comment' ] : NULL; // Коментарий
                        $item->os          = isset($options[ 'os' ]) ? $options[ 'os' ] : NULL;           // Операционная система
                        $item->mac         = isset($options[ 'mac' ]) ? $options[ 'mac' ] : NULL;         // MAC-адрес
                        $item->serial      = isset($options[ 'serial' ]) ? $options[ 'serial' ] : NULL;   // Серийный номер
                        $item->checked     = 2;                                                           // Не инвентризирован (требует внимания после импорта)
                        // Сохраняем запись
                        if ($item->validate() && $item->save())
                        {
                            $result[ 'id' ] = $item->id; // Возвращаем идентификатор записанного оборудования
                            $result[ 'error' ] = '';
                        }
                        else
                        {
                            $result[ 'error' ] .= Yii::t('items', 'Items: Failed to add entry: ') . print_r($item->errors, TRUE) . '<br />';
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Формирование PDF файла для печати QR-кодов для наклеек
     * @param integer|array|null id
     * @return mixed
     */
    public function actionPrint()
    {
        if (! User::canPermission('takingInventory') ) {
            return Yii::$app->response->redirect(['site/index']);
        }
//        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
//        Yii::$app->response->headers->add('Content-Type', 'application/pdf');
        // Список предметов/оборудования, если есть
        $id = Yii::$app->request->get('id');

        $models = Items::find();
        if (isset($id))
            if (is_array($id))
            {
                $models = $models->where([ 'in', 'id', $id ]); // Несколько предметов/оборудования
            } else
            {
                $models = $models->where([ 'id' => $id ]); // Один предмет/оборудование
            }
        $models = $models->all(); // Формирование списка

        $pdf = Yii::$app->pdf; // Pабота с PDF

        $pdf->methods[ 'SetHeader' ] = ''; // Yii::t('items', 'Items');
        $pdf->methods[ 'SetFooter' ] = ''; // ['{PAGENO}'];
        // Границы листа
        $pdf->marginLeft   = 5;
        $pdf->marginRight  = 5;
        $pdf->marginTop    = 9;
        $pdf->marginBottom = 15;
        // Имя файла для выгрузки, по умолчанию document.pdf
        $pdf->filename     = Yii::t('app', Yii::$app->name) . ' (' . Yii::t('items', 'Items') . ').pdf';

        // Заполнение страницы данными
        $pdf->content = $this->renderPartial('print', [ 'models' => $models ]);


        // Выгрузка PDF
        return $pdf->render();
    }

    /**
     * Процедура начала инвентаризации.
     * @return mixed
     */
    public function actionStart_checking()
    {
        // Проверка доступа для проведения инвентаризации
        if (! User::canPermission('takingInventory') ) {
            // Переход к списку предметов/оборудования, если доступ не разрешён.
            return $this->redirect(['index']);
        }
        // Запрос на получение списка идентификаторов предметов/оборудования, которые списаны
        $modelS = Moving::find()
            ->select('item_id')
            ->joinWith('status')
            ->Where([ 'ilike', Status::tableName() . '.name', 'Списано' ])
            ->orWhere( [ 'checked' => 2 ] );

        // Получаем список всех предметов/оборудования, кроме списанного
        $model = Items::find()
            ->select('id')
            ->innerJoin([ 'm' => $modelS ], 'not m.item_id = id')
            ->all();

        // Устанавливаем флаг непроинвентаризированных для всех предметов/оборудования из полученного списка.
        Items::updateAll([ 'checked' => 0 ], [ 'in', 'id', $model ]);

        // Переход к списку предметов/оборудования.
        return $this->redirect([ 'index' ]);
    }

    /**
     * Инвентаризация
     * @param string|null $qrcheck считанный QR-код
     * @return mixed
     */
     public function actionCheck()
     {
        // Проверка доступа для проведения инвентаризации
        if (! User::canPermission('takingInventory') ) {
            // Показ стартовой страницы, если доступ не разрешён.
            return $this->redirect(['site/index']);
        }

        $model = new Check();
        $message = '';
        if ($model->load(Yii::$app->request->post()))
        {
            if ((! empty($model->qrcheck)) && strpos($model->qrcheck, ',') !== false)
            {
                $keys = explode(',', $model->qrcheck);
                $color=''; // Цветовая метка по умолчанию
                // Получаем все предметы/оборудование с совпадением инвентарного и серийного номеров
                $items = Items::find()->where([ 'invent' => trim($keys[ 0 ]), 'serial' => trim($keys[ 1 ]) ])->all();
                if (count($items) > 0 )
                {
                    foreach ($items as $row)
                    {
                        if ( $row->checked == 1 )
                        {
                            // Этот инвентарный номер был учтён
                            $color = ' color="#FF0000"';
                        }
                        // Ищем местоположение
                        $moving_id = Moving::find()->select('MAX(id) as id')->groupBy([ 'item_id' ])->where([ 'item_id' => $row->id ])->one()->id;
                        $moving = Moving::find()->where([ 'id' => $moving_id ])->one();
                        // Показываем название и местоположение
                        $message .= $row->models->name . ' (' . $moving->locations->name  . ' [' . $moving->regions->name . '])';
                    }
                }
                else
                {
                    // Неизвестный инвентарный номер
                    $color = ' color = "#FF8830"';
                    $message .= Yii::t('items', 'Not found!') . ' ' . Yii::t('items', 'Inventory number') . ': ' . trim($keys[ 0 ]);
                }
                // Отмечаем проинвентаризированными все найденные предметы/оборудование
                Items::updateAll([ 'checked' => 1 ], [ 'invent' => trim($keys[ 0 ]), 'serial' => trim($keys[ 1 ]) ]);

                if ($message != '')
                    if ( $color == ' color = "#FF8830"')
                    {
                        $message = '<font' . $color . '>' . $message . '</font>';
                    }
                    else
                    {
                        $message = '<font' . $color . '>' . Yii::t('items', 'Checked item(s): ') . $message . '</font>';
                    }
                $model->qrcheck = '';
            }
        }
        $searchModel = new ItemsSearch();
        //$dataProvider = $searchModel->noinvent($model);
        $dataProvider = $searchModel->noinvent(Yii::$app->request->queryParams);

        return $this->render('check', [
            'message'      => $message,
            'model'        => $model,
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
     }

    /**
     * Список всех предметов/оборудования.
     * @return mixed
     */
    public function actionIndex()
    {
        if (! User::canPermission('createRecord') )
        {
            return $this->redirect(['site/index']);
        }
        if (isset($_GET[ 'pageSize' ]))
        {
            Yii::$app->session['pageSize'] = (int) $_GET[ 'pageSize' ];
            unset($_GET[ 'pageSize' ]);
        }
        $searchModel = new ItemsSearch();
        if (isset(Yii::$app->request->queryParams['id']))
        {
            $id = Yii::$app->request->queryParams['id'];
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $pageSize = $dataProvider->pagination->pageSize;
            $dataProvider->pagination = FALSE;
            $rows = $dataProvider->getModels();
            $page = 0;
            foreach ($rows as $key => $val)
            {
                if ($id == $val->id)
                {
                    $page = ceil(($key + 1) / $pageSize);
                    break;
                }
            }
            return $this->redirect(['index', 'page' => $page]);
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Импортирование строк товаров из массива
     */
    public function doImport($arrayRows)
    {
        // Инициализация счётчиков
        $arrayReturn = [
            'countRows'     => count($arrayRows),
            'countImported' => 0,
            'countExists'   => 0,
            'countErrors'   => 0,
            'errors'        => '',
        ];

        // Проверка наличия ключевых полей
        if ((!isset($arrayRows[ 0 ][ 'model' ]))
            || (!isset($arrayRows[ 0 ][ 'type' ]))
            || (!isset($arrayRows[ 0 ][ 'invent' ]))
            || (!isset($arrayRows[ 0 ][ 'location' ]))
            || (!isset($arrayRows[ 0 ][ 'region' ]))
            || (!isset($arrayRows[ 0 ][ 'date' ]))
        )
        {
            // Сообщение об ошибке
            $arrayReturn[ 'countErrors' ] = count($arrayRows);
            $arrayReturn[ 'errors' ] .= '<br />' . Yii::t('import', 'Skip all. Key column(s) "model", "type", "invent", "location", "region", "date" not found: ') . print_r($arrayRows[0], TRUE);
        }
        else
        {
            // Просмотрим весь массив
            foreach($arrayRows as $row)
            {
                // ПОлучим местоположения
                $location = LocationsController::addIfNeed($row); // Получение идентификатора расположения
                if ( $location[ 'id' ] === FALSE)
                {
                    // Сообщим об ошибке
                    $arrayReturn[ 'countErrors' ]++;
                    $arrayReturn[ 'errors' ] .= '<br />' . $location[ 'error' ];
                }
                else
                {
                    // Попробуем найти или добавить предмет/оборудование
                    $item = $this->addIfNeed($row);
                    if ($item[ 'id' ] === FALSE)
                    {
                        $arrayReturn[ 'countErrors' ]++;
                        $arrayReturn[ 'errors' ] .= '<br />' . $item[ 'error' ];
                    }
                    else
                    {
                        // Проверка, что предмет/оборудование уже были в базе
                        $item = Items::find()->where([ 'id' => $item[ 'id' ]])->one();
                        if ($item->checked < 2)
                        {
                            $arrayReturn[ 'countExists' ]++;
                        }
                        else
                        {
                            $state = isset($row[ 'status' ]) ? StatusController::addIfNeed($row) : StatusController::addIfNeed([ 'status' => 'Склад' ]);
                            if ( $state[ 'id' ] === FALSE )
                            {
                                // Сообщим об ошибке
                                $arrayReturn[ 'countErrors' ]++;
                                $arrayReturn[ 'errors' ] .= '<br />' . $state[ 'error' ];
                            }
                            else
                            {
                                // Новый предмет/оборудование. Пробуем добавить первое перемещение
                                // Проверка что последнее перемещение такое же, как и импортируемое
                                $moving = Moving::find()->where([ 'item_id' => $item[ 'id' ]])->all();
                                $flag = false;
                                if (count($moving) > 0)
                                {
                                    // Признак, что последнее перемещение совпало с импортируемым
                                    $flag = $moving[ count($moving) - 1 ]->location_id == $location[ 'id' ];
                                }
                                // Проверим, что существующая запись перемещения больше добавляемой
                                $moving = Moving::find()->where([ 'item_id' => $item[ 'id' ]])->andWhere([ '>', 'date', $row[ 'date' ]])->all();
                                if ($flag || count($moving) > 0)
                                {
                                    // Добавлять запись до существующих нельзя.
                                    $arrayReturn[ 'countExists' ]++;
                                }
                                else
                                {
                                    // Проверим, есть ли уже такое перемещение
                                    $moving = Moving::find()->where([ 'item_id' => $item[ 'id' ], 'date' => $row[ 'date' ]])->orderBy([ 'id' => SORT_ASC ])->all();
                                    if (count($moving) == 0)
                                    {
                                        // Такой транзакции нет ещё.
                                        $moving = new Moving();
                                        $moving->date        = $row[ 'date' ];
                                        $moving->state_id    = $state[ 'id' ];
                                        $moving->item_id     = $item[ 'id' ];
                                        $moving->location_id = $location[ 'id' ];
                                        $moving->comment     = Yii::t('import', 'Import: {comment}', $row);

                                        if ($moving->validate() && $moving->save())
                                        {
                                            // Записаали первое движение
                                            $arrayReturn[ 'countImported' ]++;
                                        }
                                        else
                                        {
                                            // Запись не удалась, пробуем удалить предмет/оборудование
                                            Items::find()->where([ 'id' => $item[ 'id' ], 'checked' => 2 ])->one()->delete();
                                            // Сообщим об ошибке
                                            $arrayReturn[ 'countErrors' ]++;
                                            $arrayReturn[ 'errors' ] .= '<br />' . Yii::t('import', 'Moving: {date} (') . $moving->errors['date'][0]. Yii::t('import', '), Inventory number:{invent}, model: {model}, location: {location} ( {region} )' , $row);
                                        }
                                    }
                                    else
                                    {
                                        // Такое перемещение уже было
                                        $arrayReturn[ 'countExists' ]++;
                                        // Удаление дубликатов, если вдруг они образовались
                                        if (count($moving) > 1)
                                        {
                                            // переберём все записи со второй  и удалим их.
                                            for ($i = 1; $i < count($moving); $i++)
                                            {
                                                $moving[$i]->delete();
                                            }
                                        }
                                    }
                                }
                                unset($moving);
                            }
                        }
                    }
                }
            }
        }

        // Возврат результата импорта
        return $arrayReturn;
    }

    /**
     * Импорт данных из файла csv
     * Структура файла данных при выгрузке из 1С: (Колонки могут меняться.
     * | № п/п |  | Предмет/оборудование |  |  |  |  |  |  | Инвентарный номер | Материально отвественное лицо |  |  | Место размещения | Регион/подразделение | Количество |
     * Так как 1С из коробки не умеет выгружать форму в .csv, то приходится сначала выгрузить в .xls(x), и уже из MS Excel/Lible office Calc сохранять в .csv
     */
    public function actionImport()
    {
        if (! User::canPermission('updateRecord') ) {
            return $this->redirect(['site/index']);
        }
        $model   = new Import();
        $message = '';
        $searchModel = new ItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if (Yii::$app->request->isPost)
        {
            $rows = [];
            $columns = [];
            $columnsNames = [
                'npp'         => Yii::t('import', 'No. in order'),
                'model'       => Yii::t('import', 'Primary means'),
                'netname'     => Yii::t('import', 'Network name'),
                'invent'      => Yii::t('import', 'Inventory number'),
                'comment'     => Yii::t('import', 'Financially responsible person'),
                'os'          => Yii::t('import', 'Operation system'),
                'mac'         => Yii::t('import', 'MAC address'),
                'serial'      => Yii::t('import', 'Serial number'),
                'product'     => Yii::t('import', 'Product number'),
                'modelnumber' => Yii::t('import', 'Model number'),
                'date'        => Yii::t('import', 'Date of acceptance for registration'),
                'location'    => Yii::t('import', 'Location'),
                'region'      => Yii::t('import', 'Region'),
                'type'        => Yii::t('import', 'Type'),
                'status'      => Yii::t('import', 'State'),
            ];
            $model->filecsv = UploadedFile::getInstance($model, 'filecsv');
            if ($model->upload())
            {
                $fileName = 'upload/' . $model->filecsv->baseName . '.' . $model->filecsv->extension;
                $handle = fopen($fileName, 'r');
                if ($handle !== FALSE)
                {
                    if (strcasecmp($model->filecsv->extension, 'csv') === 0 )
                    {
                        // Построчное чтение CSV файла
                        while (($row = fgetcsv($handle, 2048, ';')) !== false )
                        {
                            // Пока не собраны индексы столбцов из шапки
                            if (count($columns) == 0)
                            {
                                // Ищем строку с заголовком таблицы
                                if ( stripos($row[0], $columnsNames[ 'npp' ]) !== FALSE )
                                {
                                    // Перебираем все колонки
                                    foreach ($row as $key => $item)
                                    {
                                        // Перебираем все названия заголовков колонок
                                        foreach($columnsNames as $name => $text)
                                        {
                                            // Если название совпало,
                                            if (stripos($item, $text) !== FALSE)
                                            {
                                                // Сохраняем индек колонки
                                                $columns[ $name ] = $key;
                                            }
                                        }
                                    }
                                }
                            }
                            else
                            {
                                // Перебираем предметы/оборудование (Номер по порядку должен быть целым числом)
                                if (ctype_digit(str_replace(' ', '', $row[ $columns[ 'npp' ]])))
                                {
                                    // Заполняем очередную строку для таблицы
                                    $line = [];
                                    foreach ($columns as $key => $index)
                                    {
                                        $line[ $key ] = trim(str_replace("\t", ' ', $row[ $index ])); // Заменяем табуляторы на пробелы и удаляем ведущие и ведомые пробелы.
                                    }
                                    // Обработка даты
                                    if (isset($line[ 'date' ]))
                                    {
                                        if ($line[ 'date' ] == '#NULL!') $line[ 'date' ] = date('d.m.Y');
                                    }
                                    else
                                    {
                                        $line[ 'date' ] = date('d.m.Y');
                                    }
                                    array_push($rows, $line);
                                }
                            }
                        } // Перебор строк файла
                    }
                    else // xls(x) файлы
                    {
                        $inputFileType = \PHPExcel_IOFactory::identify($fileName); // Получение типа данных в файле
                        $excelReader = \PHPExcel_IOFactory::createReader($inputFileType); // Создание потока чтения из файла
                        $excelObj = $excelReader->load($fileName); // Открытие файла
                        $worksheet = $excelObj->getSheet(0);       // Работаем только с первым листом (обычно туда выгружает 1С)
                        // Индексы ячеек

                        // Цикл по всем строкам
                        foreach ($worksheet->getRowIterator() as $row)
                        {
                            $cellIterator = $row->getCellIterator(); // Получаем итератор ячеек в строке
                            $cellIterator->setIterateOnlyExistingCells(FALSE); // Указываем проверять даже не установленные ячейки

                            if (count($columns) == 0) // Пока не найдена шапка, проверяем строку
                            {
                                $flag = FALSE;
                                foreach ($cellIterator as $key => $item)
                                {
                                    if (($key == 'A') && (stripos($item->getCalculatedValue(), $columnsNames[ 'npp' ]) !== FALSE)) $flag = TRUE;
                                    if ($flag)
                                    {
                                        foreach ($columnsNames as $name => $text)
                                        {
                                            if (stripos($item->getCalculatedValue(), $text) !== FALSE)
                                            {
                                                $columns[ $name ] = $key;
                                            }
                                        }
                                    }
                                }
                            }
                            else
                            {
                                $flag = FALSE;
                                $line = [];
                                foreach ($cellIterator as $key => $item)
                                {
                                    if ($key == $columns[ 'npp' ])
                                    {
                                        $npp = str_replace(' ', '', $item->getCalculatedValue());
                                        if (ctype_digit($npp)) $flag = TRUE;
                                    }
                                    if ($flag)
                                    {
                                        foreach($columns as $keym => $index)
                                        {
                                            if ($index == $key) $line[ $keym ] = trim(str_replace("\t", ' ', $item->getCalculatedValue()));
                                        }
                                    }
                                }
                                if ($flag)
                                {
                                    if (isset($line[ 'date' ]))
                                    {
                                        if ($line[ 'date' ] == '#NULL!') $line[ 'date' ] = date('d.m.Y');
                                    }
                                    else
                                    {
                                        $line[ 'date' ] = date('d.m.Y');
                                    }
                                    array_push($rows, $line);
                                }
                            }
                        }
                    }
                    fclose($handle);
                    $res = $this->doImport($rows);
                }
                $message .= Yii::t('items', 'Read {countRows} records.<br />Imported {countImported} Items.<br />Exists {countExists} Items.<br />Error read {countErrors} records.<br />{errors}', $res);
            }
        }
        return $this->render('import',[
            'message' => $message,
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Показ одного предмета/оборудования. (не используется)
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если предмет/оборудование отсутствует
     */
    public function actionView($id)
    {
        if (! User::canPermission('updateRecord') ) {
            return $this->redirect([ 'index', 'id' => $id ]);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Создание нового предмета/оборудования.
     * @return mixed
     */
    public function actionCreate()
    {
        if (! User::canPermission('createRecord') ) {
            return $this->redirect([ 'site/index' ]);
        }
        $model = new Items(); // Новый предмет/оборудование
        $model->checked = 1;
        $modelm = new Moving();
        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            // Удалось сохранить, создаём первую запись движения
            if ($modelm->load(Yii::$app->request->post()))
            {
                $modelm->item_id = $model->id;
                $modelm->comment = 'Поступление';

                if ( $modelm->save() ) // Пробуем сохранить движение
                {
                    return $this->redirect([ 'index', 'id' => $model->id ]); // Если удалось, показываем список оборудования
                } else
                {
                    $this->findModel($model->id)->delete();  // Иначе удаляем созданную запись предмета/оборудования
                    unset($model->id);                       // Очищаем идентификатор предмета/оборудования
                    $model->isNewRecord = TRUE;
                    return $this->render('create', [         // Показываем форму создания нового предмета/оборудования
                        'model'  => $model,
                        'modelm' => $modelm,
                    ]);
                }
            } else
            {
                $this->findModel($model->id)->delete();  // Иначе удаляем созданную запись предмета/оборудования
                unset($model->id);                      // Очищаем идентификатор предмета/оборудования
                $model->isNewRecord = TRUE;
                return $this->render('create', [        // Показываем форму создания нового предмета/оборудования
                    'model'  => $model,
                    'modelm' => $modelm,
                ]);
            }
        } else // не удалось сохранить - отображаем форму создания нового предмета/оборудования
        {
            return $this->render('create', [
                'model'  => $model,
                'modelm' => $modelm,
            ]);
        }

    }

    /**
     * Добавление новой записи как копии существующей
     * @param integer $id - идентификатор существующей записи предмета/оборудования
     * @return mixed
     */
    public function actionAddcopy($is)
    {
        if (! User::canPermission('createRecord') )
        {
            return $this->redirect([ 'site/index' ]);
        }
        $origin = Items::find()->where([ 'id' => $is ])->one();
        $model = new Items();
        $model->checked = 1;
        $modelm = new Moving();
        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            // Удалось сохранить, создаём первую запись движения
            if ($modelm->load(Yii::$app->request->post()))
            {
                $modelm->item_id = $model->id;
                $modelm->comment = 'Поступление';

                if ( $modelm->save() ) // Пробуем сохранить движение
                {
                    return $this->redirect([ 'index', 'id' => $model->id ]); // Если удалось, показываем список оборудования
                } else
                {
                    $this->findModel($model->id)->delete();  // Иначе удаляем созданную запись предмета/оборудования
                    unset($model->id);                       // Очищаем идентификатор предмета/оборудования
                    $model->isNewRecord = TRUE;
                    return $this->render('create', [         // Показываем форму создания нового предмета/оборудования
                        'model'  => $model,
                        'modelm' => $modelm,
                    ]);
                }
            } else
            {
                $this->findModel($model->id)->delete();  // Иначе удаляем созданную запись предмета/оборудования
                unset($model->id);                      // Очищаем идентификатор предмета/оборудования
                $model->isNewRecord = TRUE;
                return $this->render('create', [        // Показываем форму создания нового предмета/оборудования
                    'model'  => $model,
                    'modelm' => $modelm,
                ]);
            }

        } else
        {
            $model->isNewRecord = TRUE;
            $model->model_id = $origin->model_id;
            $model->invent = $origin->invent;
            return $this->render('create', [
                'model' => $model,
                'modelm' => $modelm,
            ]);
        }
    }

    /**
     * Изменение существующего предмета/оборудвания.
     * Если премет/обрудование сохранён, то возвращаемся на страницу списка всех предметов/оборудования.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если предмет/оборудование отсутствует
     */
    public function actionUpdate($id)
    {
        if (! User::canPermission('updateRecord') ) {
            return $this->redirect(['index']);
        }
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect([ 'index', 'id' => $model->id ]);
        }

        $searchModelM = new MovingSearch([ 'item_id' => $model->id ]);
        $dataProviderM = $searchModelM->search(Yii::$app->request->queryParams);

         return $this->render('update', [
            'searchModelM'  => $searchModelM,
            'dataProviderM' => $dataProviderM,
            'model'         => $model,
        ]);
    }

    /**
     * Удаляет сушествующий предмет/оборудование.
     * Если премет/обрудование удалён, то возвращаемся на страницу списка всех предметов/оборудования.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (! User::canPermission('updateRecord') ) {
            return $this->redirect(['site/index']);
        }
        $this->findModel($id)->delete();

        return $this->redirect([ 'index' ]);
    }

    /**
     * Finds the Items model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Items the loaded model
     * @throws NotFoundHttpException если предмет/оборудование отсутствует
     */
    protected function findModel($id)
    {
        if (($model = Items::findOne($id)) !== null)
        {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
