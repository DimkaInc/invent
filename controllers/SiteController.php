<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

use yii\data\SqlDataProvider;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $count = Yii::$app->db->createCommand('
            SELECT COUNT(*) FROM regions
        ')->queryScalar();

        $dataProvider = new SqlDataProvider([
            'sql' => "
                SELECT
                    r.name AS rname,
                    COUNT(i.id) AS icount
                FROM regions AS r
                    LEFT JOIN locations AS l
                        ON l.region_id = r.id
                    LEFT JOIN items AS i
                        ON i.location_id = l.id
                GROUP BY
                    rname
                ORDER BY
                    rname
            ",
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => [
                    'rname',
                    'icount',
                ],
            ],
        ]);

        $count = Yii::$app->db->createCommand('
            SELECT COUNT(*) FROM types
        ')->queryScalar();

        $dataProviderg = new SqlDataProvider([
            'sql' => "
                SELECT
                    t.name AS tname,
                    COUNT(i.id) AS icount
                FROM types AS t
                    LEFT JOIN items AS i
                        ON i.type_id = t.id
                GROUP BY
                    tname
                ORDER BY
                    tname
            ",
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'attributes' => [
                    'tname',
                    'icount',
                ],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'dataProviderg' => $dataProviderg,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSay($message = "Привет")
    {
        return $this->render("say", ["message" => $message]);
    }
}
