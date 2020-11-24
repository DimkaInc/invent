<?php

namespace app\models;

use Yii;

/**
 * Это класс модели типов.
 *
 * @property int         $id     Идентификатор типа (неизменяемое)
 * @property string|null $name   наименование типа оборудования
 * @property int         $icount Количество предметов/оборудования для конкретного типа
 * @property int         $ccount Количество проинвентаризированных предметов/оборудования для конкретного типа
 * @property string|null $tname  Наименование типа оборудования
 *
 * @property Items[]     $items
 */
class Types extends \yii\db\ActiveRecord
{
    public $icount;
    public $tname;
    public $ccount;
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
            [[ 'id' ], 'integer' ],
            [[ 'name' ], 'string', 'max' => 100, ],
            [[ 'name' ], 'required' ],
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
            'icount' => Yii::t('items', 'Total items count'),
            'ccount' => Yii::t('items', 'Total items checked')
        ];
    }

    /**
     * Gets query for [[Items]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModels()
    {
        return $this->hasMany(Models::className(), [ 'type_id' => 'id' ]);
    }

    public function getItems()
    {
        return $this->getModels()->select(Items::tableName() . '.*')->joinWith('items');
    }
}
