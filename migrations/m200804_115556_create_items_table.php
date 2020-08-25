<?php

use yii\db\Migration;

/**
 * Класс создания таблицы `{{%items}}`.
 * Учёт наименовний товаров
 */
class m200804_115556_create_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%items}}', [
            'id' => $this->primaryKey(),
            'id' => 'SERIAL',
            'date' => $this->date(),
            'invent' => $this->string(50),
            'serial' => $this->string(255),
            'model' => $this->string(255),
            'mac' => $this->string(20),
            'name' => $this->string(255),
            'os' => $this->string(255),
            'product' => $this->string(255),
            'modelnumber' => $this->string(255),
        ]);
        $this->addPrimaryKey('id_pk', '{{%items}}', 'id');

        $this->addCommentOnTable('{{%items}}', 'Список оборудования');
        $this->addCommentOnColumn('{{%items}}', 'id', 'Идентификатор (неизменяемый)');
        $this->addCommentOnColumn('{{%items}}', 'name', 'Сетевое имя оборудования');
        $this->addCommentOnColumn('{{%items}}', 'model', 'Модель оборудования');
        $this->addCommentOnColumn('{{%items}}', 'os', 'Операционная система');
        $this->addCommentOnColumn('{{%items}}', 'mac', 'Сетевой MAC адрес');
        $this->addCommentOnColumn('{{%items}}', 'serial', 'Серийный номер');
        $this->addCommentOnColumn('{{%items}}', 'product', 'Код оборудования');
        $this->addCommentOnColumn('{{%items}}', 'modelnumber', 'Номер модели');
        $this->addCommentOnColumn('{{%items}}', 'invent', 'Инвентарный номер');
        $this->addCommentOnColumn('{{%items}}', 'date', 'Дата внесения записи');
        $this->insert('{{%items}}', [
            'name' => 'MTV-WS-0001',
            'model' => 'HP ProOne 440 G5 23.8-in All-in-One',
            'os' => 'Windows 10 Pro',
            'mac' => '04:0e:3c:26:e3:2f',
            'serial' => '8CG0117YPJ',
            'product' => '7EM69EA#ACB',
            'modelnumber' => 'TPC-W056-23 440G5POTeA/59500T/1hq/8G54fL24 RUSS',
            'date' => '2020-07-31',
        ]);
        $this->insert('{{%items}}', [
            'name' => 'GOL-WS-0001',
            'model' => 'HP ProOne 440 G5 23.8-in All-in-One',
            'os' => 'Windows 10 Pro',
            'mac' => '04:0e:3c:26:59:5b',
            'serial' => '8CG0117XM1',
            'product' => '7EM69EA#ACB',
            'modelnumber' => 'TPC-W056-23 440G5POTeA/59500T/1hq/8G54fL24 RUSS',
            'date' => '2020-07-31',
        ]);
        $this->insert('{{%items}}', [
            'name' => 'GOL-WS-0002',
            'model' => 'HP ProOne 440 G5 23.8-in All-in-One',
            'os' => 'Windows 10 Pro',
            'mac' => '04:0e:3c:26:e3:03',
            'serial' => '8CG0117ZH1',
            'product' => '7EM69EA#ACB',
            'modelnumber' => 'TPC-W056-23 440G5POTeA/59500T/1hq/8G54fL24 RUSS',
            'date' => '2020-07-31',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('id_pk', '{{%items}}');
        $this->dropTable('{{%items}}');
    }
}
