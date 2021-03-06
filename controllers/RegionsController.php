<?php

namespace app\controllers;

use Yii;
use app\models\Regions;
use app\models\RegionsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;

/**
 * RegionsController implements the CRUD actions for Regions model.
 */
class RegionsController extends Controller
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
     * Добавление региона/подразделения
     *
     * @param array $options
              string 'name'    - наименование
     * @return integer|boolean - Идентификатор записи или FALSE, если записать не удалось
     */
    public function addIfNeed($options)
    {
        $result = [
            'id' => FALSE,
            'error' => Yii::t('regions', 'Regions: Key field "region" missing: ') . print_r($options, TRUE),
        ];
        if (is_array($options) && isset($options[ 'region' ]))
        {
            // Ищем регион
            $model = Regions::find()
                ->where(['like', 'name', $options[ 'region' ]])
                ->all();
            if (count($model) > 0)
            {
                $result[ 'id' ] = $model[0]->id; // Нашёлся, вернём первый найденный
                $result[ 'error' ] = '';
            }
            else
            {
                $model = new Regions();   // Не нашёлся, добавляем новый
                $model->name = $options[ 'region' ];
                if ($model->validate() && $model->save()) // Пробуем записать
                {
                    $result[ 'id' ] = $model->id; // Если удалось записать, возвращаем идентификатор
                    $result[ 'error' ] = '';
                }
                else
                {
                    $result[ 'error' ] = Yii::t('regions', 'Regions: can\'t add region "{region}": ', $options) . print_r($model->errors, TRUE);
                }
            }
        }
        return $result; // Если не удалась запись, вернём FALSE
    }

    /**
     * Список всех регионов/подразделений.
     * @return mixed
     */
    public function actionIndex()
    {
        if (! User::canPermission('createRecord'))
        {
            return $this->redirect(['site/index']);
        }
        $searchModel  = new RegionsSearch();
        if (isset(Yii::$app->request->queryParams['id'])) {
            $id = Yii::$app->request->queryParams['id'];
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            //$dataProvider->query->select(Regions::tableName() . '.id');
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
     * Плказ одного региона/подразделения (не используется).
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если отсутствует регион/подразделение
     */
    public function actionView($id)
    {
        if (! User::canPermission('updateRecord'))
        {
            return $this->redirect(['site/index']);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Создание нового региона/подразделения.
     * В случае успешного создания региона/подразделения, происходит переход к списку всех регионов/подразделений.
     * @return mixed
     */
    public function actionCreate()
    {
        if (! User::canPermission('createRecord'))
        {
            return $this->redirect(['site/index']);
        }
        $model = new Regions();

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect([ 'index', 'id' => $model->id ]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Изменение существующего региона/подразделения.
     * В случае успешного редактирования, происходит переход к списку всех ергионов/подразделений.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если отсутствует регион/подразделение
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
     * Удаление существующего региона/подразделения.
     * В случае успешного удаления, происходит переход к списку всех регоинов/подразделений.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если отсутсвует регион/подразделение
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
     * Finds the Regions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Regions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Regions::findOne($id)) !== null)
        {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('regions', 'The requested page does not exist.'));
    }
}
