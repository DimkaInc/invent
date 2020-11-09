<?php

namespace app\controllers;

use Yii;
use app\models\Locations;
use app\models\LocationsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;

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
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => [ 'POST' ],
                ],
            ],
        ];
    }

    /**
     * Добавление места разположения
     *
     * @param array $options
     *        string 'name'    - наименование места расположения
     *        string|NULL  'region'    - наименование региона/подразделения
     *        integer|NULL 'region_id' - идентификатор региона/подразделения
     * @return integer|boolean - идентификатор записи места расположения или FALSE
     */
    public static function addIfNeed($options)
    {
        $result = [
            'id'    => FALSE,
            'error' => Yii::t('locations', 'Locations: Key field missing "location" and "region" :') . print_r($options, TRUE),
        ];
        if (is_array($options) && isset($options[ 'location' ]) && (isset($options[ 'region' ]) || isset($options[ 'region_id' ])))
        {
            if (isset($options[ 'region' ]))
            {
                $region = RegionsController::addIfNeed($options);
            }
            else
            {
                $region[ 'id' ] = $options[ 'region_id' ];
            }
            if ($region[ 'id' ] !== FALSE) {
                // Ищем расположение, совпадающее по наименованию и региону/подразделению
                $model = Locations::find()
                    ->where([ 'like', 'name', $options[ 'location' ]])
                    ->andWhere([ 'region_id' => $region[ 'id' ]])
                    ->all();
                if (count($model) > 0)
                {
                    // Если нашли, возвращаем идентификатор записи
                    $result[ 'id' ] = $model[0]->id;
                    $result[ 'error' ] = '';
                }
                else
                {
                    // Не нашли, пробуем добавить место расположения
                    $model = new Locations();
                    $model->name = $options[ 'location' ];
                    $model->region_id = $region[ 'id' ];
                    if($model->validate() && $model->save())
                    {
                        // Если удалось сохранить, вернём идентификатор места расположения
                        $result[ 'id' ] = $model->id;
                        $result[ 'error' ] = '';
                    }
                    else
                    {
                        $result[ 'error' ] = Yii::t('locations', 'Error to create location "{location}"', $options) . print_r($model->errors(), TRUE);
                    }
                }
            }
            else
            {
                $result[ 'error' ] = $region[ 'error' ];
            }
        }
        return $result; // Записать не удалось, вернём FALSE
    }

    /**
     * Список всех мест/размещений.
     * @return mixed
     */
    public function actionIndex()
    {
        if (! User::canPermission('createRecord'))
        {
            return $this->redirect(['site/index']);
        }

        $searchModel = new LocationsSearch();
        if (isset(Yii::$app->request->queryParams['id'])) {
            $id = Yii::$app->request->queryParams['id'];
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            //$dataProvider->query->select([Locations::tableName() . '.id', Locations::tableName() . '.name']);
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
     * Показ одного места размещения (не используется).
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если отсутствует место/размещение
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
     * Создание нового места/размещения.
     * В случае успешного создания,  происходит переход к списку всех мест/размещений.
     * @return mixed
     */
    public function actionCreate()
    {
        if (! User::canPermission('createRecord'))
        {
            return $this->redirect(['site/index']);
        }
        $model = new Locations();

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect([ 'index', 'id' => $model->id ]);
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
     * Удаение существующего места/размещения.
     * В случае успешного удаления, происходит переход к списку всех мест/размещений.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException Усли отсутствует место/размещение
     */
    public function actionDelete($id)
    {
        if (! User::canPermission('updateRecord'))
        {
            return $this->redirect(['index']);
        }
        $this->findModel($id)->delete();

        return $this->redirect([ 'index' ]);
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
        if (($model = Locations::findOne($id)) !== null)
        {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('locations', 'The requested page does not exist.'));
    }
}
