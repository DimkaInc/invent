<?php

namespace app\models;

use Yii;

/**
 * Это класс модели типов.
 *
 * @property int         $id     Идентификатор типа (неизменяемое)
 * @property string|null $name   наименование типа оборудования
 * @property int         $icount Количество оборудования для конкретного типа
 * @property string|null $tname  Наименование типа оборудования
 *
 * @property Items[]     $items
 */
class Types extends \yii\db\ActiveRecord
{
    public $icount;
    public $tname;
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
            'id'     => Yii::t('app',   'Identifire'),
            'name'   => Yii::t('types', 'Type'),
            'tname'  => Yii::t('types', 'Types'),
            'icount' => Yii::t('types', 'Count of items'),
        ];
    }

    /**
     * Gets query for [[Items]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Items::className(), [ 'type_id' => 'id' ]);
    }
}
