<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table '{{%locations}}'.
 *
 * @property int $id Идентификатор места (неизменяемое)
 * @property int $region_id Идентификатор региона (подразделения)
 * @property string $name Нименование маста размещения
 * @property string $regionName Нименование региона/подразделения
 *
 * @property Items[] $items
 * @property Regions $region
 */
class Locations extends \yii\db\ActiveRecord
{

    public $regionName;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%locations}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['region_id', 'name'], 'required'],
            [['region_id'], 'default', 'value' => null],
            [['id', 'region_id'], 'integer'],
            [['name'], 'string', 'max' => 120],
            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => Regions::className(), 'targetAttribute' => ['region_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('app',       'Identifier'),
            'region_id'  => Yii::t('locations', 'Region ID'),
            'name'       => Yii::t('locations', 'Location name'),
            'regionName' => Yii::t('regions',   'Region Name'),
        ];
    }

    // Получение связанных с конкретным местом перемещений предметов/оборудования 
    public function getMoving()
    {
        return $this->hasMany(Moving::className(), ['location_id' => 'id']);
    }

    /**
     * Получение связанного оборудования с конкретным местом
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->getMoving()->select(Items::tableName() . '.*')->joinWith('items');
    }

    /**
     * Получение подразделения для конкретного места
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRegions()
    {
        return $this->hasOne(Regions::className(), ['id' => 'region_id']);
    }
}
