<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%regions}}".
 *
 * @property int $id Идентификатор региона (неизменяемое)
 * @property string $name Наименование региона (подразделения)
 *
 * @property Locations[] $locations
 */
class Regions extends \yii\db\ActiveRecord
{
    public $lname;
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
            'id' => Yii::t('regions', 'Идентификатор региона (неизменяемое)'),
            'name' => Yii::t('regions', 'Наименование региона (подразделения)'),
        ];
    }

    /**
     * Gets query for [[Locations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Locations::className(), ['region_id' => 'id']);
    }
}
