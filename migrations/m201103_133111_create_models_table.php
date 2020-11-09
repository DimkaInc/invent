<?php

use yii\db\Migration;
use app\models\Models;
use app\models\Types;
use app\models\Items;

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
        $table = Models::tableName();

        $this->createTable($table, [
            'id'          => 'SERIAL',
            'name'        => $this->string()->unique()->notNull()->comment('Наименование предмета/оборудования'),
            'type_id'     => $this->integer()->notNull()->comment('Идентификатор типа'),
            'modelnumber' => $this->string()->comment('Номер модели'),
            'product'     => $this->string()->comment('Код оборудования'),
        ]);

        $this->addCommentOnTable($table, 'Список наименований предметов/оборудования');
        $this->addPrimaryKey(
            'pk-models-id',
            $table,
            'id'
        );

        // Создания индекса для колонки `type_id`
        $this->createIndex(
            'idx-models-type_id',
            $table,
            'type_id'
        );

        // Добавление реляционной связи с таблицей типов `{{%type}}`
        $this->addForeignKey(
            'fk-models-type_id',
            $table,
            'type_id',
            Types::tableName(),
            'id',
            'CASCADE'
        );

        // Добавление поля в таблицу предметов/оборудования
        $itemsName = Items::tableName();
        
        $this->addColumn($itemsName, 'model_id', $this->integer()->comment('Идентификатор модели'));
        // Добавление индекса для колонки 'model_id'
        $this->createIndex(
            'idx-items-model-id',
            $itemsName,
            'model_id'
        );
        
        // Добавление реляционной связи с таблицей моделей '{{%models}}'
        $this->addForeignKey(
            'fk-items-model_id',
            $itemsName,
            'model_id',
            $table,
            'id',
            'CASCADE'
        );
        
        // Миграция записей моделей из таблицы предметов/оборудования в таблицу моделей
        $items = Items::find()->all();
        foreach($items as $row)
        {
            $model = Models::find()->where(['name' => $row->model])->all();
            if (count($model) > 0)
            {
                $this->update($itemsName, [ 'model_id' => $model[0]->id ], [ 'id' => $row->id ]);
            }
            else
            {
                $model = new Models();
                $model->name = $row->model;
                $model->modelnumber = $row->modelnumber;
                $model->product = $row->product;
                $model->type_id = $row->type_id;
                if ($model->validate() && $model->save())
                {
                    $this->update($itemsName, [ 'model_id' => $model->id ], [ 'id' => $row->id ]);
                }
                else
                {
                    echo Yii::t('models', 'Migration: error add model: ') . print_r($model->errors(), TRUE);
                    return FALSE;
                }
            }
        }
        $this->alterColumn($itemsName, 'model_id', $this->integer()->notNull()->comment('Идентификатор модели'));
        // Удаление ненужных колонок
        $this->dropColumn($itemsName, 'model');
        $this->dropColumn($itemsName, 'modelnumber');
        $this->dropColumn($itemsName, 'product');
        // Удаление связи таблиц оборудования и типов
        $this->dropForeignKey('fk-items-types-id', $itemsName);
        // Удаление индекса поля типов в таблице оборудования
        $this->dropIndex('idx-items-types', $itemsName);
        // Удаление поля типов в таблице оборудования
        $this->dropColumn($itemsName, 'type_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        echo Yii::t('app', 'Migration {migrate} can\'t reverted', ['migrate' => 'm201103_133111_create_models_table']);
        return FALSE;

        $table = Models::tableName();

        // drops foreign key for table `{{%type}}`
        $this->dropForeignKey(
            'fk-models-type_id',
            $table
        );

        // drops index for column `type_id`
        $this->dropIndex(
            'idx-models-type_id',
            $table
        );
        $this->dropPrimaryKey(
            'pk-models-id',
            $table
        );

        $this->dropTable($table);
    }
}
