<?php

use yii\db\Migration;
use yii\helper\Security;

use app\models\User;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m201019_061204_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = User::tableName();
        $this->createTable($table, [
            'id' => 'SERIAL',
            'username' => $this->string(128)->notNull()->unique(),
            'password' => $this->string(128)->notNull(),
            'auth_key' => $this->string(),
            'access_token' => $this->string(),
            'password_reset_token' => $this->string(),

        ]);
        $this->addCommentOnTable( $table, 'Таблица пользователей');
        $this->addCommentOnColumn( $table, 'id', 'Идентификатор записи (неизменяемое)');
        $this->addCommentOnColumn( $table, 'username', 'Имя пользователя');
        $this->addCommentOnColumn( $table, 'password', 'Пароль');
        $this->addCommentOnColumn( $table, 'auth_key', 'Ключ авторизации');
        $this->addCommentOnColumn( $table, 'password_reset_token', 'Флаг сброса пароля');
        // Создание основного ключа
        $this->addPrimaryKey('pk-users-id', $table, 'id');

        // Добавление администратора
        $this->insert($table, [
            'username' => 'admin',
            'password' => Yii::$app->getSecurity()->generatePasswordHash('admin'),
            'access_token' => Yii::$app->getSecurity()->generateRandomString(),
            ]);

        // Добавление пользователя
        $this->insert($table, [
            'username' => 'user',
            'password' => Yii::$app->getSecurity()->generatePasswordHash('user'),
            'access_token' => Yii::$app->getSecurity()->generateRandomString(),
            ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = User::tableName();
        // Удаление основного ключа
        $this->dropPrimaryKey('pk-users-id', $table );
        // Удаление таблицы
        $this->dropTable($table);
    }
}
