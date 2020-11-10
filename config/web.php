<?php

use yii\helpers\Html;
use kartik\mpdf\Pdf;
//use yii\rbac\Role;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

require_once __DIR__ . '/myfunctions.php';

$config = [
    'version' => '1.31',
    'name' => 'Inventory',
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'language' => 'ru-RU',
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '4u6WczdipA-FIzbuf8PYgjoiid_6zdNy',
        ],
        'pdf' => [                                 // Формирование PDF по умолчанию
            'mode' => Pdf::MODE_UTF8,              // Кодировка
            'class' => Pdf::className(),
            'format' => Pdf::FORMAT_A4,            // Лист А4
            'orientation' => Pdf::ORIENT_PORTRAIT, // Напраление - по вертикали большая сторона
            'destination' => Pdf::DEST_BROWSER,    // Результат показать в браузере
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetHeader' => [Yii::t('app', Yii::$app->name)], // Верхний колонтитул
                'SetFooter' => ['{PAGENO}'],                     // Нижний колонтитул
            ],
            // refer settings section for all configuration options
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        // Авторизация пользователей из БД
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => [ 'admin', 'woker' ],
        ], // */

        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

        'i18n' => [ // Переводы сообщений
            'translations' => [
                '*' => [                                 // Для всех разделов
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',            // Исходный текст на английском
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'app'       => 'app.php',           // Для преложения
                        'app/error' => 'error.php',         // Для ошибок
                        'contact'   => 'contact.php',       // Для обратной связи
                        'import'    => 'import.php',        // Для импорта. Обозначение полей таблицы
                        'items'     => 'items.php',         // Для предметов/оборудования
                        'locations' => 'locations.php',     // Для мета расположения
                        'models'    => 'models.php',        // Для моделей предметов/оборудования
                        'moving'    => 'moving.php',        // Для перемещений
                        'regions'   => 'regions.php',       // Для регионов/подразделений
                        'status'    => 'status.php',        // Для состояний предметов/оборудования
                        'types'     => 'types.php',         // Для типов предметов/оборудования
                        'users'     => 'users.php',         // Для пользователей
                    ],
                ],
            ],
        ],
        'db' => $db,
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.68', ],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.68'],
    ];
}

return $config;
