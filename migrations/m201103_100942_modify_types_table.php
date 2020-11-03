<?php

use yii\db\Migration;
use app\models\Types;
/**
 * Class m201103_100942_modify_types_table
 */
class m201103_100942_modify_types_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(Types::tableName(), 'name', $this->string(100)->unique()->notnull()->comment('Тип оборудования'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "Reverse don\'t need\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201103_100942_modify_types_table cannot be reverted.\n";

        return false;
    }
    */
}
