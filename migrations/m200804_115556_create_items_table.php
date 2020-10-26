<?php

use yii\db\Migration;

/**
 * Класс создания таблицы `{{%items}}`.
 * Учёт наименовний товаров
 */
class m200804_115556_create_items_table extends Migration
{
    public $table = '{{%items}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id'          => 'SERIAL',
            'date'        => $this->date()->comment('Дата внесения записи'),
            'invent'      => $this->string(50)->comment('Инвентарный номер'),
            'serial'      => $this->string(255)->comment('Серийный номер'),
            'model'       => $this->string(255)->comment('Модель оборудования'),
            'mac'         => $this->string(20)->comment('Сетевой MAC адрес'),
            'name'        => $this->string(255)->comment('Сетевое имя оборудования'),
            'os'          => $this->string(255)->comment('Операционная система'),
            'product'     => $this->string(255)->comment('Код оборудования'),
            'modelnumber' => $this->string(255)->comment('Номер модели'),
        ]);
        $this->addPrimaryKey('id_pk', $this->table, 'id');

        $this->addCommentOnTable( $this->table, 'Список оборудования');
        $this->addCommentOnColumn($this->table, 'id', 'Идентификатор (неизменяемый)');
        $this->insert($table, [
            'name'        => 'MTV-WS-0001',
            'model'       => 'HP ProOne 440 G5 23.8-in All-in-One',
            'os'          => 'Windows 10 Pro',
            'mac'         => '04:0e:3c:26:e3:2f',
            'serial'      => '8CG0117YPJ',
            'product'     => '7EM69EA#ACB',
            'modelnumber' => 'TPC-W056-23 440G5POTeA/59500T/1hq/8G54fL24 RUSS',
            'date'        => '2020-07-31',
        ]);
        $this->insert($this->table, [
            'name'        => 'GOL-WS-0001',
            'model'       => 'HP ProOne 440 G5 23.8-in All-in-One',
            'os'          => 'Windows 10 Pro',
            'mac'         => '04:0e:3c:26:59:5b',
            'serial'      => '8CG0117XM1',
            'product'     => '7EM69EA#ACB',
            'modelnumber' => 'TPC-W056-23 440G5POTeA/59500T/1hq/8G54fL24 RUSS',
            'date'        => '2020-07-31',
        ]);
        $this->insert($this->table, [
            'name'        => 'GOL-WS-0002',
            'model'       => 'HP ProOne 440 G5 23.8-in All-in-One',
            'os'          => 'Windows 10 Pro',
            'mac'         => '04:0e:3c:26:e3:03',
            'serial'      => '8CG0117ZH1',
            'product'     => '7EM69EA#ACB',
            'modelnumber' => 'TPC-W056-23 440G5POTeA/59500T/1hq/8G54fL24 RUSS',
            'date'        => '2020-07-31',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('id_pk', $this->table);
        $this->dropTable($this->table);
    }
}
