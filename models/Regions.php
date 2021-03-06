<?php

namespace app\models;

use Yii;

/**
 * Это класс модели для таблицы регионов '{{%regions}}'.
 *
 * @property int    $id     Идентификатор региона (неизменяемое)
 * @property string $name   Наименование региона (подразделения)
 * @property string $lname  Наименование места/размещения
 * @property int    $icount Количество предметов/оборудования в регионе/подразделении
 * @property int    $icount Количество проинвентаризарованных предметов/оборудования в регионе/подразделении
 *
 * @property Locations[] $locations Места/размежения
 * @property Items[]     $items     Предметы/оборудование
 */
class Regions extends \yii\db\ActiveRecord
{
    public $lname;
    public $icount;
    public $ccount;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%regions}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 120],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'     => Yii::t('app',     'Identifier'),
            'name'   => Yii::t('regions', 'Region'),
            'icount' => Yii::t('items',   'Total items count'),
            'ccount' => Yii::t('items',   'Total items checked'),
        ];
    }

    /**
     * Выполнение запроса для [[Locations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Locations::className(), [ 'region_id' => 'id' ]);
    }

    /**
     * Выполнение запроса для [[Items]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMoving()
    {
        return $this->getLocations()->select(Moving::tableName() . '.*')->joinWith('moving');
    }
    public function getItems()
    {
        return $this->getMoving()->select(Items::tableName() . '.*')->joinWith('items');
    }
}
