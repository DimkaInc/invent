<?php

namespace app\controllers;

use Yii;
use app\models\Moving;
use app\models\MovingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MovingController implements the CRUD actions for Moving model.
 */
class MovingController extends Controller
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

    /**
     * Показ всех перемещений.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MovingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Показ одного перемещения (не используется).
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если отсутствует перемещение
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Создание новго перемещения.
     * В случае успешного создания, происходит переход к конкретному оборудованию.
     * @return mixed
     */
    public function actionCreate($item_id)
    {
        $model = new Moving();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['items/update', 'id' => $model->item_id]);
        }

        $model->item_id = $item_id;
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Изменение перемещения.
     * В случае успешного изменения, происходит переход к конкретному оборудованию.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если отсутствует перемещение
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['items/update', 'id' => $model->item_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Удаление существующего перемещения.
     * В случае успешного удаления, происходит переход к редактированию предмета/оборудования.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если отсутствует перемещение
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $item_id = $model->item_id;
        $model->delete();

        return $this->redirect(['items/update', 'id' => $item_id]);
    }

    /**
     * Finds the Moving model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Moving the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Moving::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
