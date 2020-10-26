<?php

use yii\db\Migration;

use app\models\Items;
use app\models\Locations;
use app\models\Regions;

/**
 * Handles the creation of table `{{%locations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%items}}`
 * - `{{%regions}}`
 */
class m200818_123015_create_locations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $regions   = Regions::tableName();
        $locations = Locations::tableName();
        $items     = Items::tableName();
        // Создание таблицы регионов
        $this->createTable($regions, [
            'id' => 'SERIAL',
            'name' => $this->string(120)->notNull()->unique()->comment('Наименование региона (подразделения)'),
        ]);

        // Добавлнение комментариев в таблицу и поля
        $this->addCommentOnTable( $regions, 'Регионы (подразделения)');
        $this->addCommentOnColumn($regions, 'id', 'Идентификатор региона (неизменяемое)');

        // Создание основного ключа регионов
        $this->addPrimaryKey('pk-regions-id', $regions, 'id');

        // Создание таблицы расположений
        $this->createTable($locations, [
            'id'        => 'SERIAL',
            'region_id' => $this->integer()->notNull()->comment('Идентификатор региона (подразделения)'),
            'name'      => $this->string(120)->notNull()->comment('Наименование маста размещения'),
        ]);

        // Добавлнение комментариев в таблицу и поля
        $this->addCommentOnTable($locations,  'Места размещения оборудования');
        $this->addCommentOnColumn($locations, 'id', 'Идентификатор места (неизменяемое)');

        // Создание основного ключа
        $this->addPrimaryKey('pk-locations-id', $locations, 'id');

        // Создание индексирования для указателя региона
        $this->createIndex('idx-locations-region_id', $locations, 'region_id');

        // Создание указателя места размещения в таблице оборудования
        $this->addColumn($items, 'location_id', $this->integer());

        // Добавление комментария для поля места  размещения
        $this->addCommentOnColumn($items, 'location_id', 'Идентификатор места размещения');

        // Создание индексирования для указателя места размещения
        $this->createIndex('idx-items-location_id', $items, 'location_id');

        // Создание связи между таблицами оборудования и размещений
        $this->addForeignKey('fk-items-locations-id', $items, 'location_id', $locations, 'id', 'CASCADE');

        // Создание связи `{{%regions}}`
        $this->addForeignKey('fk-locations-regions-id', $locations, 'region_id', $regions, 'id', 'CASCADE');
        $this->insert($regions, [ 'name' => 'Одинцовская ветеринарная станция' ]);
        $regionId = Yii::$app->db->getlastInsertID();
        $this->insert($locations, [ 'region_id' => $regionId, 'name' => 'Матвейково' ]);
        $locationId = Yii::$app->db->getlastInsertID();
        $this->update($items, [ 'location_id' => $locationId ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $regions   = Regions::tableName();
        $locations = Locations::tableName();
        $items     = Items::tableName();
        // Удаление связи между таблицами locations и regions
        $this->dropForeignKey('fk-locations-regions-id', $locations);

        // Удаление связи таблиц оборудования и мест размещения
        $this->dropForeignKey('fk-items-locations-id', $items);

        // Удвление индексации для идентификатора мест размещения в таблице оборудования
        $this->dropIndex('idx-items-location_id', $items);

        // Удаление идентификатора местра размещения из таблицы оборудования
        $this->dropColumn($items, 'location_id');

        // удаление индексации для поля регионов
        $this->dropIndex('idx-locations-region_id', $locations);

        // удаление основного ключа для таблицы мест размещения
        $this->dropPrimaryKey('pk-locations-id', $locations);

        // Удаление таблицы размещений
        $this->dropTable($locations);

        // удаление основного ключа для таблицы регионов
        $this->dropPrimaryKey('pk-regions-id', $regions);

        // Удаление таблицы регионов
        $this->dropTable($regions);
    }
}
