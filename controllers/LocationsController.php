<?php

namespace app\controllers;

use Yii;
use app\models\Locations;
use app\models\LocationsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LocationsController implements the CRUD actions for Locations model.
 */
class LocationsController extends Controller
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
     * Список всех мест/размещений.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LocationsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Показ одного места размещения (не используется).
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если отсутствует место/размещение
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Создание нового места/размещения.
     * В случае успешного создания,  происходит переход к списку всех мест/размещений.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Locations();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Изменение существующего места/размещения.
     * В случаае успешного изменения, происходит переход к списку всех мест/размещений.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если отсутствует место/размещение
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Удаение существующего места/размещения.
     * В случае успешного удаления, происходит переход к списку всех мест/размещений.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException Усли отсутствует место/размещение
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Locations model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Locations the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Locations::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('locations', 'The requested page does not exist.'));
    }
}
