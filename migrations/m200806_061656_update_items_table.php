<?php

use yii\db\Migration;

use app\models\Items;

/**
 * Class m200806_061656_update_items_table
 * Добавление поля 'Комментарии' неограниченной длины
 */
class m200806_061656_update_items_table extends Migration
{
    public $table = Items::tableName();
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = $this->table;
        $this->addColumn($table, 'comment', $this->string());
        $this->addCommentOnColumn($table, 'comment', 'Дополнительная информация');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Так как в комментарии добавлена важная информация, то отмена этого поля приведёт к её потере.
        // в связи с этим отмена становится невозможной.
        echo 'Миграция m200806_061656_update_items_table не может быть отменена.\n';
        
        return false;
    }

}
