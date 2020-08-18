<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%status}}`.
 * Добавление таблицы состояний оборудования, и свзянного с ним поля в таблицу оборудования
 */
class m200810_082546_create_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Создание таблицы состояний оборудования
        $this->createTable('{{%status}}', [
            "id" => $this->primaryKey(),
            "id" => "SERIAL",
            "name" => $this->string(20)->notNull()->unique(),
        ]);
        // Главный ключ
        $this->addPrimaryKey("id_status", '{{%status}}', "id");

        // Добавление описания таблице и колонкам
        $this->addCommentOnTable('{{%status}}', "Статусы состояния объектов");
        $this->addCommentOnColumn('{{%status}}', "id", "Номер по порядку");
        $this->addCommentOnColumn('{{%status}}', "name", "Состояние");
        
        // Создание в таблице оборудования колонки с состоянием
        $this->addColumn('{{%items}}', "state_id", $this->integer());
        $this->addCommentOnColumn('{{%items}}', "state_id", "Состояние");
        $this->createIndex("idx-items-state", '{{%items}}', "state_id");
        
        // Добавление состояний в табличу
        $this->insert('{{%status}}', ["name" => "Склад"]);
        $this->insert('{{%status}}', ["name" => "Работает"]);
        $ind = Yii::$app->db->getlastInsertID(); // Запомним идентификатор состояния "Работает"
        $this->insert('{{%status}}', ["name" => "Сломано"]);
        $this->insert('{{%status}}', ["name" => "Ремонт"]);
        $this->insert('{{%status}}', ["name" => "К списанию"]);
        $this->insert('{{%status}}', ["name" => "Списано"]);
        
        // Всем объектам назначим состояние "Работает"
        $this->update('{{%items}}', [ "state_id" => $ind ]);

        // Создадим связь между таблицами оборудования и состояния
        $this->addForeignKey("fk-items-status-id", '{{%items}}', "state_id", '{{%status}}', "id", "CASCADE");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "В связи с модификацией алгоритма работы программы от 17.08.2020, отмена миграции невозможна."
        return false;
        
        // Удаление связи таблиц items и status
        $this->dropForeignKey("fk-items-status-id", '{{%items}}');
        // Удаление индексации поля состояний в таблице оборудования
        $this->dropIndex("idx-items-state", '{{%items}}');
        // Удаление поля состояния в таблице оборудования
        $this->dropColumn('{{items}}', 'state_id');
        // Удаление основного ключа сортировки для таблицы состояний
        $this->dropPrimaryKey('id_status', '{{%status}}');
        // Удаление талицы состояний
        $this->dropTable('{{%status}}');
    }
}
