<?php

use yii\db\Migration;

use app\models\Items;

/**
 * Class m200907_045611_modify_items_table
 * Флаг прохождения инвентаризации
 */
class m200907_045611_modify_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = Items::tableName();
        $this->addColumn($table, 'checked', $this->boolean());
        $this->addCommentOnColumn($table, 'checked', 'Флаг прохождения инвентаризации');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = Items::tableName();
        $this->dropColumn(Items::tableName(), 'checked');
    }
}
