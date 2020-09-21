<?php
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/test_db.php';

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
