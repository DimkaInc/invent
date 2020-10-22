<?php

namespace app\controllers;

use Yii;
use app\models\Types;
use app\models\TypesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;

/**
 * TypesController implements the CRUD actions for Types model.
 */
class TypesController extends Controller
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
     * Добавление типа, если необходимо
     * @param array $options
     *        string 'name' - наименование
     * @return integer|boolean - Идентификатор типа или FALSE в случае неудачи
     */
     public function addIfNeed($options)
     {
        if (is_array($options) && isset($options[ 'name' ]))
        {
            $type = Types::find()
                ->where([ 'like', 'name', $options[ 'name' ] ])
                ->all();
            if (count($type) > 0)
            {
                return $type[0]->id;
            }
            $type = new Types();
            $type->name = $options[ 'name' ];
            if ($type->validate() && $type->save())
            {
                return $type->id;
            }
        }
        return FALSE;
     }

    /**
     * Список всех типов предметов/оборудования.
     * @return mixed
     */
    public function actionIndex()
    {
        if (! User::canPermission('createRecord'))
        {
            return $this->redirect(['site/index']);
        }
        $searchModel  = new TypesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Показ одного типа предмета/оборудования (не используется).
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если отсутствует тип предмета/оборудования
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
     * Создание нового типа предмета/оборудования.
     * При успешном создании, происходит переход к списку всех типов предметов/оборудования.
     * @return mixed
     */
    public function actionCreate()
    {
        if (! User::canPermission('createRecord'))
        {
            return $this->redirect(['site/index']);
        }
        $model = new Types();

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect([ 'index', 'id' => $model->id ]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Изменение существующего типа предмета/оборудования.
     * При успешном изменении, происходит переход к списку всех типов предметов/оборудования.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если отсутствует тип предмета/оборудования
     */
    public function actionUpdate($id)
    {
        if (! User::canPermission('updateRecord'))
        {
            return $this->redirect(['site/index']);
        }
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect([ 'index', 'id' => $model->id ]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Удаление существующего типа предмета/оборудования.
     * При успешном удалении, происходит переход к списку всех типов предметов/оборудования.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если отсутствует тип предмета/оборудования
     */
    public function actionDelete($id)
    {
        if (! User::canPermission('updateRecord'))
        {
            return $this->redirect(['site/index']);
        }
        $this->findModel($id)->delete();

        return $this->redirect([ 'index' ]);
    }

    /**
     * Finds the Types model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Types the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Types::findOne($id)) !== null)
        {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
