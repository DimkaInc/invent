<?php

use yii\db\Migration;

use app\models\Items;
use app\models\Locations;
use app\models\Status;
use app\models\Moving;

/**
 * Создание таблицы перемещения оборудования `{{%moving}}`.
 * Использует связанные таблицы:
 *
 * - `{{%items}}` - Оборудование
 * - `{{%locations}}` - Места размещения
 * - `{{%status}}` - Состояние
 */
class m200826_081002_create_moving_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table    = Moving::tableName();
        $items     = Items::tableName();
        $locations = Locations::tableName();
        $status    = Status::tableName();
        $this->createTable( $table, [
            'id'          => 'SERIAL',
            'date'        => $this->date()->notNull()->comment('Дата перемещения'),
            'item_id'     => $this->integer()->notNull()->comment('Идентификатор предмета/оборудования'),
            'location_id' => $this->integer()->notNull()->comment('Идентификатор места размещения'),
            'state_id'    => $this->integer()->notNull()->comment('Идентификатор состояния'),
            'comment'     => $this->text()->comment('Комментарии'),
        ]);

        $this->addCommentOnTable( $table, 'Таблица перемещений предмета/оборудования');
        $this->addCommentOnColumn( $table, 'id', 'Идентификатор записи (неизменяемое)');
        // Создание основного ключа
        $this->addPrimaryKey('pk-moving-id', $table, 'id');

        // Создание ключа сортировки по дате
        $this->createIndex('idx-moving-date', $table, 'date');

        // Создание ключа сортировки по коду оборудования
        $this->createIndex('idx-moving-item_id', $table, 'item_id');

        // Создание связи между перемещениями и оборудованием
        $this->addForeignKey('fk-moving-item_id', $table, 'item_id', $items, 'id', 'CASCADE');

        // Создание ключа сортировки по коду места размещения
        $this->createIndex('idx-moving-location_id', $table, 'location_id');

        // Создание связи между перемещениями и местами размещения
        $this->addForeignKey('fk-moving-location_id', $table, 'location_id', $locations, 'id', 'CASCADE');

        // Создание ключа сортировки по коду состояния
        $this->createIndex('idx-moving-state_id', $table, 'state_id');

        // Создание связи между перемещениями и состоянием оборудования
        $this->addForeignKey('fk-moving-state_id', $table, 'state_id', $status, 'id', 'CASCADE');

        // Внесение первых движений из последнего состояния оборудования
        foreach (Items::find()->all() as $val) {
            $this->insert( $table,
                [
                    'date'        => $val->date,
                    'item_id'     => $val->id,
                    'location_id' => $val->location_id,
                    'state_id'    => $val->state_id,
                    'comment'     => $val->comment,
                ]
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table    = Moving::tableName();
        // Удаление связи между перемещениями и оборудованием
        $this->dropForeignKey('fk-moving-item_id', $table);

        // Удаление ключа сортировки по коду оборудования
        $this->dropIndex('idx-moving-item_id', $table);

        // Удаление связи между перемещениями и местами расположения
        $this->dropForeignKey('fk-moving-location_id', $table);

        // Удаление ключа сортировки по коду местра размещения
        $this->dropIndex('idx-moving-location_id', $table);

        // Удаление связи между перемещениями и состояниями оборудования
        $this->dropForeignKey('fk-moving-state_id', $table);

        // Удаление ключа сортировки по коду состояния оборудования
        $this->dropIndex('idx-moving-state_id', $table);

        // Удаление ключа сортировки по дате
        $this->dropIndex('idx-moving-date', $table);

        // Удаление основного ключа
        $this->dropPrimaryKey('pk-moving-id', $table);

        // Удаление таблицы перемещений
        $this->dropTable($table);
    }
}
