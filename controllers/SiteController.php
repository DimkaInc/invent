<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;

use app\models\Site;

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
                'only'  => [ 'logout' ],
                'rules' => [
                    [
                        'actions' => [ 'logout' ],
                        'allow'   => true,
                        'roles'   => [ '@' ],
                    ],
                ],
            ],
            'verbs'    => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => [ 'post' ],
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
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Показ стартовой страницы.
     *
     * @return string
     */
    public function actionIndex()
    {

        return $this->render('index', [
            'dataProvider'      => Site::regionsDataProvider(),
            'dataProviderTypes' => Site::typesDataProvider(),
        ]);
    }

    /**
     * Смена пароля залогинившегося пользователя
     */
    public function actionChangepassword()
    {
        // Если пользователь не вошёл, отправим на стартовую
        if (Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }

        // Получим модель Учётных записей
        $model = Yii::$app->user->identity;
        // Включим сценарий смены пароля
        $model->setScenario('changePassword');
        
        // Загрузим и проверим данные из формы
        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            // Проверка прошла успешно, потому сохраним новый пароль
            $model->setPassword($model->new_password);
            $model->save(FALSE);
            // Сообщим, что пароль изменили
            Yii::$app->session->setFlash('success', Yii::t('users', 'You have successfully change your password.'));
            // Останемся на странице
            return $this->refresh();
        }
        // Покажем форму для смены пароля
        return $this->render('changepassword', [ 'model' => $model ]);
    }
    
    /**
     * Вход пользователем.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login())
        {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Выход пользователем.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Поках страницы с обратной связью.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail']))
        {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Показ страницы описания.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
