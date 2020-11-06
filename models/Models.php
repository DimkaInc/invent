<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%models}}".
 *
 * @property int $id
 * @property string $name Наименование предмета/оборудования
 * @property int $type_id Идентификатор типа
 * @property string|null $modelnum Номер модели
 * @property string|null $product Код оборудования
 *
 * @property Types $type
 */
class Models extends \yii\db\ActiveRecord
{
    public $typeName;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%models}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type_id'], 'required'],
            [['type_id'], 'default', 'value' => null],
            [['type_id'], 'integer'],
            [['name', 'modelnumber', 'product'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Types::className(), 'targetAttribute' => ['type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Identifier'),
            'name' => Yii::t('models', 'Model name'),
            'type_id' => Yii::t('models', 'Type identify'),
            'modelnumber' => Yii::t('models', 'Model number'),
            'product' => Yii::t('models', 'Prodict number'),
            'typeName' => Yii::t('types', 'Type'),
        ];
    }

    /**
     * Gets query for [[Type]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTypes()
    {
        return $this->hasOne(Types::className(), ['id' => 'type_id']);
    }
}
