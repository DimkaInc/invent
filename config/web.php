<?php

use yii\helpers\Html;
use kartik\mpdf\Pdf;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

function showUrlUpdate($name, $data)
{
    return Html::a(Html::encode($name), 
        ['update',
            'id' => $data->id,
        ]);
}


$config = [
    'version' => '1.27',
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
                'app*' => [                                 // Для приложения
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',            // Исходный текст на английском
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'app'       => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
                'contact*' => [                             // Для формы обратой связи
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'app'   => 'contact.app',
                    ],
                ],
                'items*' => [                               // Для предметов/оборудования
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'app' => 'items.app',
                    ],
                ],
                'locations*' => [                           // Для мест размещения
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'app' => 'locations.app',
                    ],
                ],
                'moving*' => [                              // Для перемещений
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'app'   => 'moving.app',
                    ],
                ],
                'regions*' => [                             // Для регионов/подразделений
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'app' => 'regions.app',
                    ],
                ],
                'status*' => [                              // Для состояний предметов/оборудования
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'app' => 'status.app',
                    ],
                ],
                'types*' => [                               // Для типов оборудования
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'app' => 'types.app',
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
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.68'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.68'],
    ];
}

return $config;
