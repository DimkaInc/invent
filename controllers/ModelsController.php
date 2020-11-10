<?php

namespace app\controllers;

use Yii;
use app\models\Models;
use app\models\ModelsSearch;
use app\models\User;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ModelsController implements the CRUD actions for Models model.
 */
class ModelsController extends Controller
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

    public function addIfNeed($options)
    {
        $result = [
            'id' => FALSE,
            'error' => Yii::t('models', 'Models: Key field "model", "type" missing: ') . print_r($options, TRUE),
        ];
        // Если указан инвентарный номер
        if (is_array($options) && isset($options[ 'model' ]))
        {
            $model = Models::find()
                ->where([ 'name' => $options[ 'model' ]])->all(); // Ищем наименование модели предмета/обрудования

            if (count($model) > 0) // Записи найдены, выводим первую совпавшую
            {
                $result[ 'id' ] = $model[ 0 ]->id;
                $result[ 'error' ] = '';
            }
            else
            {
                $type = TypesController::addIfNeed($options); // Найдём или добавим тип
                // Если тип не добавили
                if($type[ 'id' ] === FALSE)
                {
                    $result[ 'error' ] = '<br />' . $type[ 'error' ];
                    //$type[ 'id' ] = NULL; // сделаем его пустым
                }
                else
                {
                    // Создаём новую запись модели предмета/оборудования
                    $model = new Models();
                    $model->name        = $options[ 'model' ]; // Сетевое имя
                    $model->type_id     = isset($type[ 'id' ]) ? $type[ 'id' ] : NULL;                 // Идентификатор типа
                    $model->product     = isset($options[ 'product' ]) ? $options[ 'product' ] : NULL; // Код оборудования
                    $model->modelnumber = isset($options[ 'modelnum' ]) ? $options[ 'modelnum' ] : NULL; // Номер модели
                    // Сохраняем запись
                    if ($model->validate() && $model->save())
                    {
                        $result[ 'id' ] = $model->id; // Возвращаем идентификатор записанного оборудования
                        $result[ 'error' ] = '';
                    }
                    else
                    {
                        $result[ 'error' ] .= Yii::t('models', 'Models: Failed to add entry: ') . print_r($model->errors, TRUE);
                    }
                }

            }
        }
        return $result;
    }

    /**
     * Lists all Models models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (! User::canPermission('createRecord') )
        {
            return $this->redirect(['site/index']);
        }
        $searchModel = new ModelsSearch();
        if (isset(Yii::$app->request->queryParams['id']))
        {
            $id = Yii::$app->request->queryParams['id'];
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $pageSize = $dataProvider->pagination->pageSize;
            $dataProvider->pagination = FALSE;
            $rows = $dataProvider->getModels();
            $page = 0;
            foreach ($rows as $key => $val)
            {
                if ($id == $val->id)
                {
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
     * Displays a single Models model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (! User::canPermission('updateRecord') )
        {
            return $this->redirect(['index']);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Models model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (! User::canPermission('createRecord') )
        {
            return $this->redirect(['site/index']);
        }
        $model = new Models();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Models model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (! User::canPermission('updateRecord') )
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
     * Deletes an existing Models model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (! User::canPermission('updateRecord') )
        {
            return $this->redirect(['index']);
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Models model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Models the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Models::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
