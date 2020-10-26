<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

/**
 * Консольный контроллер для инициализации контроля доступа на основе ролей (RBAC)
 * Для работы необходимо выполнить следующик команды из корня проекта:
 * ./yii migrate --migrationPath=@yii/rbac/migrations
 * ./yii rbac/init
 */

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // Добавляем разрешение "createRecord"
        $createRecord = $auth->createPermission('createRecord');
        $createRecord->description = 'Create a record';
        $auth->add($createRecord);

        // Добавляем разрешение "updateRecord"
        $updateRecord = $auth->createPermission('updateRecord');
        $updateRecord->description = 'Update record';
        $auth->add($updateRecord);

        // Добавляем разрешение "takingInventory"
        $takingInventory = $auth->createPermission('takingInventory');
        $takingInventory->description = 'Taking inventory';
        $auth->add($takingInventory);

        // Добавляем роль "woker" и даём разрешений "createRecord", "takingInventory"
        $woker = $auth->createRole('woker');
        $auth->add($woker);
        $auth->addChild($woker, $createRecord);
        $auth->addChild($woker, $takingInventory);

        // Добавляем роль "admin" и даём разрешение "updateRecord"
        // А так же все разрешения роли "woker"
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updateRecord);
        $auth->addChild($admin, $woker);

        // Назначение ролей пользователям. 1 и 2 это IDs возвращаемые IdentityInterface::getId()
        // Обычно реализуемый в модели User
        $auth->assign($woker, 2);
        $auth->assign($admin, 1);
    }
}
