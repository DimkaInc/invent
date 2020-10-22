<?php

namespace app\controllers;

use Yii;
use app\models\Status;
use app\models\StatusSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;

/**
 * StatusController implements the CRUD actions for Status model.
 */
class StatusController extends Controller
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
     * Добавление в случае необходимости состояния
     * @param array $options
     *        string 'name'  - Наименование состояния
     * @return mixed
     */
    public function addIfNeed($options)
    {
        if (is_array($options) && isset($options[ 'name' ]))
        $status = Status::find()
            ->where([ 'like', 'name', $options[ 'name' ]])
            ->all();
        if (count($status) > 0)
        {
            return $status[0]->id;
        }
        $status = new Status();
        $status->name = $options[ 'name' ];
        if ($status->validate() && $status->save())
        {
            return $status->id;
        }
        return FALSE;
    }

    /**
     * Показ всех состояний предметов/оборудования.
     * @return mixed
     */
    public function actionIndex()
    {
        if (! User::canPermission('createRecord'))
        {
            return $this->redirect(['site/index']);
        }
        $searchModel = new StatusSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Показ одного состояния предмета/оборудования (не используется).
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если отсутствует состояние
     */
    public function actionView($id)
    {
        if (! User::canPermission('updateRecord'))
        {
            return $this->redirect(['index']);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Создание новго состояния предмета/оборудования.
     * В случае успешного создания, переход осуществляется к списку всех состояний предметов/оборудования.
     * @return mixed
     */
    public function actionCreate()
    {
        if (! User::canPermission('createRecord'))
        {
            return $this->redirect(['site/index']);
        }
        $model = new Status();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Изменение состояния предмета/оборудования.
     * В случае успешного изменения, переход осуществляется к списку всех состояний предметов/оборудования.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если отсутствует состояние предмета/оборудования
     */
    public function actionUpdate($id)
    {
        if (! User::canPermission('updateRecord'))
        {
            return $this->redirect(['index']);
        }
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Status model.
     * В случае успешного удаления, переход осуществляется к списку всех состояний предметов/оборудования.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если отсутствует состояние предмета/оборудования
     */
    public function actionDelete($id)
    {
        if (! User::canPermission('updateRecord'))
        {
            return $this->redirect(['index']);
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Status model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Status the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Status::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
