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
use app\models\UserSearch;

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
     *
     * @return response|string
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
     * Добавление нового пользователя
     */
    public function actionCreateusers()
    {
        // Если пользователь не администратор, отправим на стартовую
        if (! User::canPermission('updateRecord'))
        {
            return $this->goHome();
        }
        $model = new User;
        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $model->setPassword($model->password);
            if ($model->save())
            {
                $id = $model->id;
                $auth = Yii::$app->authManager;
                $role = $auth->getRole('woker');
                $auth->assign($role, $id);
                Yii::$app->session->setFlash('success', Yii::t('users', 'User {name} is created', [ 'name' => $model->name ]));
            }
            $model->password = '';
            return $this->refresh();
        }
        return $this->render('createuser', [ 'model' => $model ]);
    }

    /**
     * Страница сброса паролей для всех пользователей
     *
     * @return response|string
     */
    public function actionResetuser()
    {
        // Если пользователь не администратор, отправим на стартовую
        if (! User::canPermission('updateRecord'))
        {
            return $this->goHome();
        }

        // формируем список пользователей и показываем его
        $searchModel  = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('resetuser', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Сброс пароля конкретного пользователя
     *
     * @param integer $id - идентификатор пользователя
     * @return response|string
     */
    public function actionReset($id)
    {
        if (! User::canPermission('updateRecord') ) {
            return $this->redirect(['index']);
        }
        $model = User::findOne($id);
        if ($model !== null)
        {
            $model->setPassword($model->username);
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('users', 'The password for the "{name}" has been reset. Now it matches the login.', [ 'name' => $model->username ]));
                $this->refresh();
            }
        }

        return $this->redirect([ 'resetuser' ]);
        
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
