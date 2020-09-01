<?php

namespace app\models;

use Yii;

/**
 * Это класс модели таблицы перемещений оборудования.
 *
 * @property int         $id           Идентификатор записи (неизменяемое)
 * @property string      $date         Дата перемещения
 * @property int         $item_id      Идентификатор предмета/оборудования
 * @property int         $location_id  Идентификатор места размещения
 * @property int         $state_id     Идентификатор состояния
 * @property string|null $comment      Комментарии
 * @property string|null $itemModel    Название модели предмета/оборудования
 * @property string|null $locationName Наименование места размещения
 * @property string|null $statusName   Наименование состояния предмета/оборудования
 * @property string|null $regionName   Наименование региона/подразделения
 *
 * @property Items       $item         Предметы/оборудование
 * @property Locations   $location     Места размещения
 * @property Status      $state        Состояния
 * @property Regions     $region       Регион/подразделение
 */
class Moving extends \yii\db\ActiveRecord
{

    public $itemModel;
    public $locationName;
    public $statusName;
    public $regionName;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%moving}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'item_id', 'location_id', 'state_id'], 'required'],
            [['date'],        'safe'],
            [['date'],        'date', 'format' => 'dd.MM.yyyy' ],
            [['item_id', 'location_id', 'state_id'], 'default', 'value' => null],
            [['id', 'item_id', 'location_id', 'state_id'], 'integer'],
            [['comment'],     'string'],
            [['item_id'],     'exist', 'skipOnError' => true, 'targetClass' => Items::className(),     'targetAttribute' => ['item_id' => 'id']],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Locations::className(), 'targetAttribute' => ['location_id' => 'id']],
            [['state_id'],    'exist', 'skipOnError' => true, 'targetClass' => Status::className(),    'targetAttribute' => ['state_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     * Подписи в заголовке таблицы
     */
    public function attributeLabels()
    {
        return [
            'comment'      => Yii::t('moving',    'Comment'),
            'date'         => Yii::t('moving',    'Moving date'),
            'id'           => Yii::t('app',       'Identifier'),
            'item_id'      => Yii::t('moving',    'Item ID'),
            'itemModel'    => Yii::t('items',     'Model'),
            'location_id'  => Yii::t('moving',    'Location ID'),
            'locationName' => Yii::t('locations', 'Locations'),
            'regionName'   => Yii::t('regions',   'Region'),
            'state_id'     => Yii::t('moving',    'Status ID'),
            'statusName'   => Yii::t('status',    'Status'),
        ];
    }

    /**
     * Получение связанной таблицы предметов/оборудования.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasOne(Items::className(), ['id' => 'item_id']);
    }

    /**
     * Получение связанной таблицы места размещения.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasOne(Locations::className(), ['id' => 'location_id']);
    }

    /**
     * Получение связанной таблицы региона/подразделения.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRegions()
    {
        return $this->getLocations()->select(Regions::tableName() . '.*')->joinWith('regions');
    }

    /**
     * Получение связанной таблицы состояния.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'state_id']);
    }
}
