<?php

use yii\db\Migration;
use yii\helper\Security;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m201019_061204_create_users_table extends Migration
{
    private $tableName = '{{%users}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => 'SERIAL',
            'username' => $this->string(128)->notNull()->unique(),
            'password' => $this->string(128)->notNull(),
            'auth_key' => $this->string(),
            'access_token' => $this->string(),
            'password_reset_token' => $this->string(),

        ]);
        $this->addCommentOnTable( $this->tableName, 'Таблица пользователей');
        $this->addCommentOnColumn( $this->tableName, 'id', 'Идентификатор записи (неизменяемое)');
        $this->addCommentOnColumn( $this->tableName, 'username', 'Имя пользователя');
        $this->addCommentOnColumn( $this->tableName, 'password', 'Пароль');
        $this->addCommentOnColumn( $this->tableName, 'auth_key', 'Ключ авторизации');
        $this->addCommentOnColumn( $this->tableName, 'password_reset_token', 'Флаг сброса пароля');
        // Создание основного ключа
        $this->addPrimaryKey('pk-users-id', $this->tableName, 'id');

        // Добавление администратора
        $this->insert($this->tableName, [
            'username' => 'admin',
            'password' => Yii::$app->getSecurity()->generatePasswordHash('admin'),
            'access_token' => Yii::$app->getSecurity()->generateRandomString(),
            ]);

        // Добавление пользователя
        $this->insert($this->tableName, [
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
        // Удаление основного ключа
        $this->dropPrimaryKey('pk-users-id', $this->tableName );
        // Удаление таблицы
        $this->dropTable($this->tableName);
    }
}
