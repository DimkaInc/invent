<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%status}}".
 *
 * @property int $id Номер по порядку
 * @property string $name Состояние
 *
 * @property Items[] $items
 */
class Status extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%status}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 20],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Номер по порядку'),
            'name' => Yii::t('app', 'Состояние'),
        ];
    }

    /**
     * Gets query for [[Items]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Items::className(), ['state_id' => 'id']);
    }
}
