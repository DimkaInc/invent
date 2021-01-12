<?php

use yii\db\Migration;

use app\models\Items;

/**
 * Class m210111_120758_modify_items_table
 */
class m210111_120758_modify_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = Items::tableName();
        $templateTable = 'mytemp';
        $this->createTable($templateTable, [
            'id' => $this->primaryKey(),
            'checked' => $this->boolean(),
        ]);
        $list = (new \yii\db\Query())
            ->select([ 'id', 'checked' ])
            ->from($table)
            ->all();
        $this->batchInsert($templateTable, [ 'id', 'checked' ], $list);
        $this->dropColumn($table, 'checked');
        $this->addColumn($table, 'checked', $this->integer());
        $list = (new \yii\db\Query())
            ->select('id')
            ->from($templateTable)
            ->where(['checked' =>  TRUE ])
            ->all();
        $this->update($table, [ 'checked' => 1 ], [ 'in', 'id', $list ]);
        $list = (new \yii\db\Query())
            ->select('id')
            ->from($templateTable)
            ->where([ 'checked' => FALSE ])
            ->all();
        $this->update($table, [ 'checked' => 2 ], [ 'in', 'id', $list ]);
        $this->dropTable($templateTable);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210111_120758_modify_items_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210111_120758_modify_items_table cannot be reverted.\n";

        return false;
    }
    */
}
