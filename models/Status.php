<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%status}}".
 *
 * @property int    $id   Номер по порядку
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
            [['name'], 'string', 'max' => 100],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'   => Yii::t('app', 'Identify'),
            'name' => Yii::t('status', 'Status name'),
        ];
    }

    /**
     * Получение данных из связанной тблицы оборудования.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMoving()
    {
        return $this->hasMany(Noving::className(), [ 'state_id' => 'id' ]);
    }
    public function getItems()
    {
        return $this->getMoving()->select(Items::tableName() . '.*')->joinWith('items');
    }
}
