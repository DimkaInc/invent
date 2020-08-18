<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%types}}".
 *
 * @property int $id Идентификатор типа (неизменяемое)
 * @property string|null $name Тип оборудования
 *
 * @property Items[] $items
 */
class Types extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%types}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Идентификатор типа (неизменяемое)'),
            'name' => Yii::t('app', 'Тип оборудования'),
        ];
    }

    /**
     * Gets query for [[Items]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Items::className(), ['type_id' => 'id']);
    }
}
