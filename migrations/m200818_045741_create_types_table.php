<?php

use yii\db\Migration;

use app\models\Items;
/**
 * Handles the creation of table `{{%types}}`.
 * Добавление таблицы типов оборудования и свзянного с нею поля в таблице оборудования
 */
class m200818_045741_create_types_table extends Migration
{
    public $types = '{{%types}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Создание таблицы типов
        $this->createTable($types, [
            'id'   => 'SERIAL',
            'name' => $this->string(20)->comment('Тип оборудования'),
        ]);
        // Добавление основного ключа
        $this->addPrimaryKey('id-types', $types, 'id');
        
        // Добавление комментария для описания таблицы
        $this->addCommentOnTable( $types, 'Типы оборудования');
        $this->addCommentOnColumn($types, 'id', 'Идентификатор типа (неизменяемое)');
        
        // Добавление поля типа оборудования в таблицу оборудования
        $this->addColumn(Items::tableName(), 'type_id', $this->integer());
        $this->addCommentOnColumn(Items::tableName(), 'type_id', 'Тип оборудования');
        $this->createIndex('idx-items-types', Items::tableName(), 'type_id');
        
        // Добавление связи полей таблиц types и items
        $this->addForeignKey('fk-items-types-id', Items::tableName(), 'type_id', $types, 'id', 'CASCADE');
        
        // Добавление базовых типов
        $this->insert($types, ['name' => 'Компьютер']);
        $this->insert($types, ['name' => 'Принтер']);
        $this->insert($types, ['name' => 'МФУ']);
        $this->insert($types, ['name' => 'Сканер']);
        $this->insert($types, ['name' => 'ИБП']);
        $this->insert($types, ['name' => 'Свич/коммутатор']);
        $this->insert($types, ['name' => 'Модем']);
        $this->insert($types, ['name' => 'Монитор']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo 'Отменить миграцию невозможно из-за внесённых данных';
        return false;
        // Удаление связи таблиц оборудования и типов
        $this->dropForeignKey('fk-items-types-id', Items::tableName());
        // Удаление индекса поля типов в таблице оборудования
        $this->dropIndex('idx-items-types', Items::tableName());
        // Удаление поля типов в таблице оборудования
        $this->dropColumn(Items::tableName(), 'type_id');
        // Удаление основного ключа в таблице типов
        $this->dropPrimaryKey('id-types', $types);
        // Удаление таблицы типов
        $this->dropTable($types);
    }
}
