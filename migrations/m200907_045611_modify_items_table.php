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
        $this->addColumn(Items::tableName(), 'checked', $this->boolean());
        $this->addCommentOnColumn(Items::tableName(), 'checked', 'Флаг прохождения инвентаризации');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Items::tableName(), 'checked');
    }
}
