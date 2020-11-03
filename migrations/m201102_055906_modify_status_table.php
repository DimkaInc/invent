<?php

use yii\db\Migration;
use app\models\Status;

/**
 * Class m201102_055906_modify_status_table
 */
class m201102_055906_modify_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(Status::tableName(), 'name', $this->string(100)->notNull()->unique()->comment('Состояние'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201102_055906_modify_status_table revert don't need.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201102_055906_modify_status_table cannot be reverted.\n";

        return false;
    }
    */
}
