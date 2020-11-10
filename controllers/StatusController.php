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
        $result = [
            'id' => FALSE,
            'error' => Yii::t('status', 'Status: Key field missing "status" :') . print_r($options, TRUE),
        ];
        if (is_array($options) && isset($options[ 'status' ]))
        $model = Status::find()
            ->where([ 'like', 'name', $options[ 'status' ]])
            ->all();
        if (count($model) > 0)
        {
            $result[ 'id' ] = $model[0]->id;
            $result [ 'error' ] = '';
        }
        else
        {
            $model = new Status();
            $model->name = $options[ 'status' ];
            if ($model->validate() && $model->save())
            {
                $result[ 'id' ] = $model->id;
                $result[ 'error' ] = '';
            }
            else
            {
                $result[ 'error' ] = Yii::t('status', 'Failed to add entry "{status}"', $options) . print_r($model->errors, TRUE);
            }
        }
        return $result;
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
        if (isset(Yii::$app->request->queryParams['id'])) {
            $id = Yii::$app->request->queryParams['id'];
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            //$dataProvider->query->select(Status::tableName() . '.id');
            $pageSize = $dataProvider->pagination->pageSize;
            $dataProvider->pagination = FALSE;
            $rows = $dataProvider->getModels();
            $page = 0;
            foreach ($rows as $key => $val) {
                if ($id == $val->id) {
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
