<?php

namespace app\controllers;

use Yii;
use app\models\Reports;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;

use kartik\mpdf\Pdf;
use \phpexcel;

#require "/vendor/phpoffice/phpexcel/Classes/PHPExcel.php";

/**
 * ItemsController implements the CRUD actions for Items model.
 */
class ReportsController extends Controller
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
                ],
            ],
        ];
    }

    /**
     * Формирование PDF файла для печати QR-кодов для наклеек
     * @param integer|array|null id
     * @return mixed
     */
    public function actionPrint()
    {
        if (! User::canPermission('takingInventory') ) {
            return $this->redirect(['site/index']);
        }
        // Список предметов/оборудования, если есть
        $id = Yii::$app->request->get('id');

        $models = Items::find();
        if (isset($id))
            if (is_array($id))
            {
                $models = $models->where([ 'in', 'id', $id ]); // Несколько предметов/оборудования
            } else
            {
                $models = $models->where([ 'id' => $id ]); // Один предмет/оборудование
            }
        $models = $models->all(); // Формирование списка

        $pdf = Yii::$app->pdf; // Pабота с PDF

        $pdf->methods[ 'SetHeader' ] = ''; // Yii::t('items', 'Items');
        $pdf->methods[ 'SetFooter' ] = ''; // ['{PAGENO}'];
        // Границы листа
        $pdf->marginLeft   = 5;
        $pdf->marginRight  = 5;
        $pdf->marginTop    = 9;
        $pdf->marginBottom = 15;
        // Имя файла для выгрузки, по умолчанию document.pdf
        $pdf->filename     = Yii::t('app', Yii::$app->name) . ' (' . Yii::t('items', 'Items') . ').pdf';

        // Заполнение страницы данными
        $pdf->content = $this->renderPartial('print', [ 'models' => $models ]);

        // Выгрузка PDF
        return $pdf->render();
    }

    /**
     * Список всех предметов/оборудования.
     * @return mixed
     */
    public function actionIndex()
    {
        if (! User::canPermission('createRecord') )
        {
            return $this->redirect(['site/index']);
        }
        $searchModel = new Reports();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = FALSE;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}
