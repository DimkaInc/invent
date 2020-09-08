<?php

namespace app\controllers;

use Yii;
use app\models\Items;
use app\models\Check;
use app\models\Moving;
use app\models\Locations;
use app\models\ItemsCheck;
use app\models\ItemsSearch;
use app\models\MovingSearch;
use app\models\Status;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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
     * Формирование PDF файла для печати QR-кодов для наклеек
     * @param integer|array|null id
     * @return mixed
     */
    public function actionPrint()
    {
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
        $searchModel = new ItemsCheck();
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
        $searchModel = new ItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
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
