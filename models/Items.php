<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "items".
 *
 * @property int $id Идентификатор (неизменяемый)
 * @property string|null $name Сетевое имя оборудования
 * @property string|null $model Модель оборудования
 * @property string|null $os Операционная система
 * @property string|null $mac Сетевой MAC адрес
 * @property string|null $serial Серийный номер
 * @property string|null $product Код оборудования
 * @property string|null $modelnumber Номер модели
 * @property string|null $invent Инвентарный номер
 * @property string|null $date Дата внесения записи
 */
class Items extends \yii\db\ActiveRecord
{
    public $statusName;
    public $typeName;
    public $locationName;
    public $regionName;
     /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['name', 'model', 'os', 'serial', 'product', 'modelnumber', 'comment' ], 'string', 'max' => 255],
            [['mac'], 'string', 'max' => 20],
            [['invent'], 'string', 'max' => 50],
            [['state_id', 'type_id', 'location_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('items', "Идентификатор (неизменяемый)"),
            'name' => Yii::t('items', "Сетевое имя оборудования"),
            'model' => Yii::t('items', "Модель оборудования"),
            'os' => Yii::t('items', "Операционная система"),
            'mac' => Yii::t('items', "Сетевой MAC адрес"),
            'serial' => Yii::t('items', "Серийный номер"),
            'product' => Yii::t('items', "Код оборудования"),
            'modelnumber' => Yii::t('items', "Номер модели"),
            'invent' => Yii::t('items', "Инвентарный номер"),
            'date' => Yii::t('items', "Дата внесения записи"),
            'comment' => Yii::t('items', "Дополнительная информация"),
            'state_id' => Yii::t('items', "Состояние"),
            'statusName' => Yii::t('items', "State"),
            'type_id' => Yii::t('items', "Тип оборудования"),
            'typeName' => Yii::t('items', "Type of item"),
            'location_id' => Yii::t('items', "Место размещения"),
            'locationName' => Yii::t('items', "Location on region"),
            'regionName' => Yii::t('items', "Region")

        ];
    }

    // Получение статуса оборудования
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'state_id']);
    }

    // Получение типа оборудования
    public function getTypes()
    {
        return $this->hasOne(Types::className(), ['id' => 'type_id']);
    }

    // Получение места размещения оборудования
    public function getLocations()
    {
        return $this->hasOne(Locations::className(), ['id' => 'location_id']);
    }
}
