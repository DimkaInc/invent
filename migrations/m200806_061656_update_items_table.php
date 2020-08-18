<?php

use yii\db\Migration;

/**
 * Class m200806_061656_update_items_table
 * Добавление поля "Комментарии" неограниченной длины
 */
class m200806_061656_update_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%items}}', 'comment', $this->string());
        $this->addCommentOnColumn('{{%items}}', 'comment', 'Дополнительная информация');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Так как в комментарии добавлена важная информация, то отмена этого поля приведёт к её потере.
        // в связи с этим отмена становится невозможной.
        echo "Миграция m200806_061656_update_items_table не может быть отменена.\n";
        
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200806_061656_update_items_table cannot be reverted.\n";

        return false;
    }
    */
}
