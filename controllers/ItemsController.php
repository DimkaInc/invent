<?php

namespace app\controllers;

use Yii;
use app\models\Items;
use app\models\Moving;
use app\models\ItemsSearch;
use app\models\MovingSearch;
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
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionPrint()
    {
            $models = Items::find()->all();

            $pdf = Yii::$app->pdf;

            $pdf->methods['SetHeader'] = Yii::t('items', 'Items');

            $pdf->content = $this->renderPartial('print', ['models' => $models]);

        return $pdf->render();
//        return $this->renderPartial('print', ['models' => $models]);
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
        $modelm = new Moving();
        $model->myMessage = '';
//        $model->isNewRecord = true;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Удалось сохранить, создаём первую запись движения
            
            if ($modelm->load(Yii::$app->request->post())) {
            
                $modelm->item_id = $model->id;
                $modelm->comment = 'Поступление';

                if ( $modelm->save() ) {                  // Пробуем сохранить движение
                    return $this->redirect(['index', 'id' => $model->id]); // Если удалось, показываем список оборудования
                } else {
                    $this->findModel($model->id)->delete();  // Иначе удаляем созданную запись предмета/оборудования
                    unset($model->id);                      // Очищаем идентификатор предмета/оборудования
                    $model->isNewRecord = true;
                    return $this->render('create', [        // Показываем форму создания нового предмета/оборудования
                        'model' => $model,
                        'modelm' => $modelm,
                    ]);
                }
            } else {
                $this->findModel($model->id)->delete();  // Иначе удаляем созданную запись предмета/оборудования
                unset($model->id);                      // Очищаем идентификатор предмета/оборудования
                $model->isNewRecord = true;
                return $this->render('create', [        // Показываем форму создания нового предмета/оборудования
                    'model' => $model,
                    'modelm' => $modelm,
                ]);
            }
        } else { // не удалось сохранить - отображаем форму создания нового предмета/оборудования
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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        }

        $searchModelM = new MovingSearch(['item_id' => $model->id]);
        $dataProviderM = $searchModelM->search(Yii::$app->request->queryParams);

         return $this->render('update', [
            'searchModelM' => $searchModelM,
            'dataProviderM' => $dataProviderM,
            'model' => $model,
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

        return $this->redirect(['index']);
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
        if (($model = Items::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
