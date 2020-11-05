<?php

use yii\db\Migration;
use app\models\Types;

/**
 * Handles the creation of table `{{%models}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%type}}`
 */
class m201103_133111_create_models_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = '{{%models}}';
        $this->createTable($table, [
            'id' => 'SERIAL',
            'name' => $this->string()->unique()->notNull()->comment('Наименование предмета/оборудования'),
            'type_id' => $this->integer()->notNull()->comment('Идентификатор типа'),
            'modelnum' => $this->string()->comment('Номер модели'),
            'product' => $this->string()->comment('Код оборудования'),
        ]);

        $this->addCommentOnTable($table, 'Список наименований предметов/оборудования');
        $this->addPrimaryKey(
            'pk-models-id',
            $table,
            'id'
        );

        // creates index for column `type_id`
        $this->createIndex(
            '{{%idx-models-type_id}}',
            $table,
            'type_id'
        );

        // add foreign key for table `{{%type}}`
        $this->addForeignKey(
            '{{%fk-models-type_id}}',
            $table,
            'type_id',
            Types::tableName(),
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = '{{%models}}';
        // drops foreign key for table `{{%type}}`
        $this->dropForeignKey(
            '{{%fk-models-type_id}}',
            $table
        );

        // drops index for column `type_id`
        $this->dropIndex(
            '{{%idx-models-type_id}}',
            $table
        );
        $this->dropPrimaryKey(
            'pk-models-id',
            $table
        );

        $this->dropTable($table);
    }
}
