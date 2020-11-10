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
        $result = [
            'id' => FALSE,
            'error' => Yii::t('types', 'Types: key field "type" missing') . print_r($options, TRUE),
        ];
        if (is_array($options) && isset($options[ 'type' ]))
        {
            $model = Types::find()
                ->where([ 'like', 'name', $options[ 'type' ] ])
                ->all();
            if (count($model) > 0)
            {
                $result['id'] = $model[0]->id;
                $result['error'] = '';
            }
            else
            {
                $model = new Types();
                $model->name = $options[ 'type' ];
                if ($model->validate() && $model->save())
                {
                    $result['id'] = $model->id;
                    $result['error'] = '';
                }
                else
                {
                    $result['error'] = Yii::t('types', 'Failed to add entry {type}', $options) . print_r($model->errors['name'], TRUE);
                }
            }
        }
        return $result;
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
        if (isset(Yii::$app->request->queryParams['id'])) {
            $id = Yii::$app->request->queryParams['id'];
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            //$dataProvider->query->select(Types::tableName() . '.id');
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
