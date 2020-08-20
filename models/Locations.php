<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%locations}}".
 *
 * @property int $id Идентификатор места (неизменяемое)
 * @property int $region_id Идентификатор региона (подразделения)
 * @property string $name Нименование маста размещения
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
            'id' => Yii::t('regions', 'Идентификатор места (неизменяемое)'),
            'region_id' => Yii::t('regions', 'Идентификатор региона (подразделения)'),
            'name' => Yii::t('regions', 'Нименование маста размещения'),
            'regionName' => Yii::t('regions', 'Region Name'),
        ];
    }

    /**
     * Gets query for [[Items]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Items::className(), ['location_id' => 'id']);
    }

    /**
     * Gets query for [[Region]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRegions()
    {
        return $this->hasOne(Regions::className(), ['id' => 'region_id']);
    }
}
