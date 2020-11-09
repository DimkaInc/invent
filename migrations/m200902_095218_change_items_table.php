<?php

use yii\db\Migration;

use app\models\Items;

/**
 * Class m200902_095218_change_items_table
 */
class m200902_095218_change_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        echo 'Удаление неиспользуемых полей в таблице предметов/оборудования. Так же удаление уже не нужных связей. Отменить невозможно.';
        $table = Items::tableName();
        $this->dropForeignKey('fk-items-locations-id', $table);
        $this->dropForeignKey('fk-items-status-id', $table);
        $this->dropIndex('idx-items-location_id', $table);
        $this->dropIndex('idx-items-state', $table);
        $this->dropColumn($table, 'date');
        $this->dropColumn($table, 'state_id');
        $this->dropColumn($table, 'location_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "Миграция m200902_095218_change_items_table не может быть отменена.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200902_095218_change_items_table cannot be reverted.\n";

        return false;
    }
    */
}
