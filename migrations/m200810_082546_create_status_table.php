<?php

use yii\db\Migration;

use app\models\Items;

/**
 * Handles the creation of table `{{%status}}`.
 * Добавление таблицы состояний оборудования, и свзянного с ним поля в таблицу оборудования
 */
class m200810_082546_create_status_table extends Migration
{
    public $status = '{{%status}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Создание таблицы состояний оборудования
        $this->createTable($status, [
            'id' => 'SERIAL',
            'name' => $this->string(20)->notNull()->unique()->comment('Состояние'),
        ]);
        // Главный ключ
        $this->addPrimaryKey('id_status', $status, 'id');

        // Добавление описания таблице и колонкам
        $this->addCommentOnTable( $status, 'Статусы состояния объектов');
        $this->addCommentOnColumn($status, 'id', 'Номер по порядку');
        
        // Создание в таблице оборудования колонки с состоянием
        $this->addColumn(Items::tableName(), 'state_id', $this->integer());
        $this->addCommentOnColumn(Items::tableName(), 'state_id', 'Состояние');
        $this->createIndex('idx-items-state', Items::tableName(), 'state_id');
        
        // Добавление состояний в табличу
        $this->insert($status, ['name' => 'Склад']);
        $this->insert($status, ['name' => 'Работает']);
        $ind = Yii::$app->db->getlastInsertID(); // Запомним идентификатор состояния 'Работает'
        $this->insert($status, ['name' => 'Сломано']);
        $this->insert($status, ['name' => 'Ремонт']);
        $this->insert($status, ['name' => 'К списанию']);
        $this->insert($status, ['name' => 'Списано']);
        
        // Всем объектам назначим состояние 'Работает'
        $this->update(Items::tableName(), [ 'state_id' => $ind ]);

        // Создадим связь между таблицами оборудования и состояния
        $this->addForeignKey('fk-items-status-id', Items::tableName(), 'state_id', $status, 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo 'В связи с модификацией алгоритма работы программы от 17.08.2020, отмена миграции невозможна.'
        return false;
        
        // Удаление связи таблиц items и status
        $this->dropForeignKey('fk-items-status-id', Items::tableName());
        // Удаление индексации поля состояний в таблице оборудования
        $this->dropIndex('idx-items-state', Items::tableName());
        // Удаление поля состояния в таблице оборудования
        $this->dropColumn(Items::tableName(), 'state_id');
        // Удаление основного ключа сортировки для таблицы состояний
        $this->dropPrimaryKey('id_status', $status);
        // Удаление талицы состояний
        $this->dropTable($status);
    }
}
