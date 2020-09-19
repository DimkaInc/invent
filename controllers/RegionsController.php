<?php

namespace app\controllers;

use Yii;
use app\models\Regions;
use app\models\RegionsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
        if (is_array($options) && isset($options[ 'name' ]))
        {
            // Ищем регион
            $region = Regions::find()
                ->where(['like', 'name', $options[ 'name' ]])
                ->all();
            if (count($region) > 0)
            {
                return $region[0]->id; // Нашёлся, вернём первый найденный
            }
            $region = new Regions();   // Не нашёлся, добавляем новый
            $region->name = $options[ 'name' ];
            if ($region->validate() && $region->save()) // Пробуем записать
            {
                return $region->id; // Если удалось записать, возвращаем идентификатор
            }
        }
        return FALSE; // Если не удалась запись, вернём FALSE
    }

    /**
     * Список всех регионов/подразделений.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new RegionsSearch();
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
