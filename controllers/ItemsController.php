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
     *        integer|NULL 'type_id'
     *        string|NULL 'typeName'
     *        string|NULL 'name'
     *        string|NULL 'os'
     *        string|NULL 'mac'
     *        string|NULL 'serial'
     *        string|NULL 'product'
     *        string|NULL 'modelnum'
     * @return integer|FALSE
     */
    public static function addIfNeed($options)
    {
        if (is_array($options) && isset($options[ 'invent' ]))
        {
            $item = Items::find()
                ->where([ 'like', 'invent', $options[ 'invent' ]]);
            if (isset($options[ 'serial' ])) {
                $item = $item->andWhere([ 'like', 'serial', $options[ 'serial' ]]);
            }
            $item = $item->all();

            if (count($item) > 0)
            {
                return $item[0]->id;
            }
            if (isset($options[ 'model' ]))
            {
                if (isset($options[ 'typeName' ]))
                {
                    $type_id = TypesController::addIfNeed($options[ 'typeName' ]);
                    if($type_id === FALSE)
                    {
                        $type_id = NULL;
                    }
                }
                else
                {
                    $type_id = isset($options[ 'type_id' ]) ? $options[ 'type_id' ] : NULL;
                }
                $item = new Items();
                $item->name        = isset($options[ 'name' ]) ? $options[ 'name' ] : NULL;
                $item->model       = isset($options[ 'model' ]) ? $options[ 'model' ] : NULL;
                $item->invent      = isset($options[ 'invent' ]) ? $options[ 'invent' ] : NULL;
                $item->comment     = isset($options[ 'comment' ]) ? $options[ 'comment' ] : NULL;
                $item->type_id     = $type_id;
                $item->os          = isset($options[ 'os' ]) ? $options[ 'os' ] : NULL;
                $item->mac         = isset($options[ 'mac' ]) ? $options[ 'mac' ] : NULL;
                $item->serial      = isset($options[ 'serial' ]) ? $options[ 'serial' ] : NULL;
                $item->product     = isset($options[ 'product' ]) ? $options[ 'product' ] : NULL;
                $item->modelnumber = isset($options[ 'modelnumber' ]) ? $options[ 'modelnumber' ] : NULL;
                $item->checked     = false;

                if ($item->validate() && $item->save())
                {
                    return $item->id;
                }
            }
        }
        return FALSE;
    }

    /**
     * Формирование PDF файла для печати QR-кодов для наклеек
     * @param integer|array|null id
     * @return mixed
     */
    public function actionPrint()
    {
        if (! User::canPermission('takingInventory') ) {
            return $this->redirect(['site/index']);
        }
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
        if (! User::canPermission('takingInventory') ) {
            return $this->redirect(['site/index']);
        }
        // Запрос на получение списка идентификаторов предметов/оборудования, которые списаны
        $modelS = Moving::find()
            ->select('item_id')
            ->joinWith('status')
            ->Where([ 'ilike', Status::tableName() . '.name', 'Списано' ]);

        // Получаем список всех предметов/оборудования, кроме списанного
        $model = Items::find()
            ->select('id')
            ->innerJoin([ 'm' => $modelS ], 'not m.item_id = id')
            ->all();

        // Устанавливаем флаг непроинвентаризированных для всех предметов/оборудования из полученного списка.
        Items::updateAll([ 'checked' => false ], [ 'in', 'id', $model ]);

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
        /*
        if (! User::canPermission('takingInventory') ) {
            return $this->redirect(['site/index']);
        } // */
        $model = new Check();
        $message = '';
        if ($model->load(Yii::$app->request->post()))
        {
            if ((! empty($model->qrcheck)) && strpos($model->qrcheck, ',') !== false)
            {
                $keys = explode(',', $model->qrcheck);
                Items::updateAll([ 'checked' => true ], [ 'invent' => trim($keys[ 0 ]), 'serial' => trim($keys[ 1 ]) ]);
                $items = Items::find()->where([ 'invent' => trim($keys[ 0 ]), 'serial' => trim($keys[ 1 ]) ])->all();
                //$message = '[0] = "' . $keys[0] . '", [1] = "' . $keys[1] . '"<br />';
                foreach ($items as $row)
                {
                    $message .= $row->model . ' (' . $row->id . ')';
                }
                if ($message != '')
                    $message = Yii::t('items', 'Checked item(s): ') . $message;
                $model->qrcheck = '';
            }
        }
        $searchModel = new ItemsSearch();
        $dataProvider = $searchModel->noinvent($model);

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
        if (! User::canPermission('createRecord') ) {
            return $this->redirect(['site/index']);
        }
        $searchModel = new ItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Импорт данных из файла csv
     * Структура файла данных при выгрузке из 1С:
     * | № п/п |  | Предмет/оборудование |  |  |  |  |  |  | Инвентарный номер | Материально отвественное лицо |  |  | Место размещения | Регион/подразделение | Количество |
     * | 0     | 1| 2                    | 3| 4| 5| 6| 7| 8| 9                 |10                             |11|12|13                |14                    |15          |
     * Так как 1С из коробки не умеет выгружать форму в .csv, то приходится сначала выгрузить в .xls(x), и уже из MS Excel/Lible office Calc сохранять в .csv
     */
    public function actionImport()
    {
        if (! User::canPermission('updateRecord') ) {
            return $this->redirect(['site/index']);
        }
        $model   = new Import();
        $count   = 0;
        $counti  = 0;
        $skip    = 0;
        $existi  = 0;
        $errors  = '';
        $message = '';
        $searchModel = new ItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if (Yii::$app->request->isPost)
        {
            $model->filecsv = UploadedFile::getInstance($model, 'filecsv');
            if ($model->upload())
            {
                $handle = fopen('upload/' . $model->filecsv->baseName . '.' . $model->filecsv->extension, 'r');
                if ($handle !== FALSE)
                {
                    while (($row = fgetcsv($handle, 1024, ';')) !== false )
                    {
                        if (intval($row[ 0 ]) . '' == $row[ 0 ])
                        {
                            $location = $row[ 13 ];
                            $region   = $row[ 14 ];
                            $count++;
                            $location_id = LocationsController::addIfNeed([ 'name' => $location, 'region' => $region ]);
                            if ($location_id !== FALSE)
                            {
                                $invent  = $row[ 9 ];
                                if (count(Items::find()->where([ 'like', 'invent', $invent ])->all()) == 0)
                                {
                                    $model_  = $row[ 2 ];
                                    $comment = Yii::t('moving', 'Imported. {comment}', [ 'comment' => $row[ 10 ] ]);
                                    $item_id = $this::addIfNeed([ 'invent' => $invent, 'model' => $model_, 'comment' => $comment ]);
                                    if ( $item_id !== FALSE)
                                    {
                                        $date = date('d.m.Y');
                                        $state_id = StatusController::addIfNeed([ 'name' => 'Склад' ]);
                                        if ($state_id === FALSE)
                                        {
                                            $state_id = NULL;
                                        } // Состояние предмета/оборудование

                                        $moving = new Moving();
                                        $moving->date = $date;
                                        $moving->item_id = $item_id;
                                        $moving->state_id = $state_id;
                                        $moving->location_id = $location_id;
                                        $moving->comment = $comment;
                                        if ($moving->validate() && $moving->save())
                                        {
                                            $counti++;
                                        } // Добавление перемещение
                                        else
                                        {
                                            Items::find([ 'id' => $item_id ])->delete();
                                            $skip++;
                                            $errors .= '<br>Движение: ' . implode(';', $row);
                                        } // Не удалось добавить перемещение
                                    }
                                } // Предмет/оборудование добавлено
                                else
                                {
                                    $existi++;
                                } // Предмет/оборудование уже есть
                            }
                            else
                            {
                                $skip++;
                                $errors .= '<br>Место расположения: ' . implode(';', $row);
                            } // не удалось найти или добавить место размещения
                        } // Строка с данными
                    } // Перебор строк файла
                    fclose($handle);
                }
                $message .= Yii::t('items', 'Read {count} records.<br />Imported {counti} Items.<br />Exists {exist} Items.<br />Error read {skip} records.<br />{errors}', 
                    [ 'counti' => $counti, 'count' => $count, 'exist' => $existi, 'skip' => $skip, 'errors' => $errors ]);
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
            return $this->redirect(['index']);
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
            return $this->redirect(['site/index']);
        }
        $model = new Items(); // Новый предмет/оборудование
        $model->checked = true;
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
                    $model->isNewRecord = true;
                    return $this->render('create', [         // Показываем форму создания нового предмета/оборудования
                        'model'  => $model,
                        'modelm' => $modelm,
                    ]);
                }
            } else
            {
                $this->findModel($model->id)->delete();  // Иначе удаляем созданную запись предмета/оборудования
                unset($model->id);                      // Очищаем идентификатор предмета/оборудования
                $model->isNewRecord = true;
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
