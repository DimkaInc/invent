<?php

use yii\helpers\Html;
use kartik\mpdf\Pdf;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/test_db.php';

require_once __DIR__ . '/myfunctions.php';

/**
 * Application configuration shared by all test types
 */
return [
    'id' => 'basic-tests',
    'name' => 'Inventory',
    'version' => '1.27',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'language' => 'ru-RU',
    'components' => [
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
                        'items'     => 'items.php',         // Для предметов/оборудования
                        'locations' => 'locations.php',     // Для мета расположения
                        'moving'    => 'moving.php',        // Для перемещений
                        'regions'   => 'regions.php',       // Для регионов/подразделений
                        'status'    => 'status.php',        // Для состояний предметов/оборудования
                        'types'     => 'types.php',         // Для типов предметов/оборудования
                        'yii'       => 'yii.php',
                    ],
                ],
            ],
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
                'SetHeader' => [Yii::t('app', 'Inventory')], // Верхний колонтитул
                'SetFooter' => ['{PAGENO}'],                     // Нижний колонтитул
            ],
            // refer settings section for all configuration options
        ],

        'db' => $db,
        'mailer' => [
            'useFileTransport' => true,
        ],
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        // Авторизация пользователей из БД
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => [ 'admin', 'woker' ],
        ], // */

        'user' => [
            'identityClass' => 'app\models\User',
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
            // but if you absolutely need it set cookie domain to localhost
            /*
            'csrfCookie' => [
                'domain' => 'localhost',
            ],
            */
        ],
    ],
    'params' => $params,
];
