<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table 'items'.
 *
 * @property int $id                   Идентификатор (неизменяемый)
 * @property int $state_id             Идентификатор состояния
 * @property int $type_id              Идентификатор типа оборудования
 * @property int $location_id          Идентификатор места размещения
 * @property string|null $name         Сетевое имя оборудования
 * @property string|null $model        Модель оборудования
 * @property string|null $os           Операционная система
 * @property string|null $mac          Сетевой MAC адрес
 * @property string|null $serial       Серийный номер
 * @property string|null $product      Код оборудования
 * @property string|null $modelnumber  Номер модели
 * @property string|null $invent       Инвентарный номер
 * @property string|null $date         Дата внесения записи
 * @property string|null $statusName   Наименование состояния
 * @property string|null $typeName     Наименование типа
 * @property string|null $locationName Наименование места размещения
 * @property string|null $regionName   Наименование региона/подразделения
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
            [['mac'],    'string', 'max' => 20],
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
            'id'           => Yii::t('app',   'Identifier'),              // Идентификатор
            'name'         => Yii::t('items', 'Item network name'),       // Сетевое имя оборудования
            'model'        => Yii::t('items', 'Model'),                   // Модель
            'os'           => Yii::t('items', 'Operating system'),        // Операционная система
            'mac'          => Yii::t('items', 'MAC address'),             // MAC адрес
            'serial'       => Yii::t('items', 'Serial number'),           // Серийный номер
            'product'      => Yii::t('items', 'Product number'),          // Номер продукции
            'modelnumber'  => Yii::t('items', 'Model number'),            // Номер модели
            'invent'       => Yii::t('items', 'Inventory number'),        // Инвентарный номер
            'date'         => Yii::t('items', 'Date of entry'),           // Дата записи
            'comment'      => Yii::t('items', 'Additional Information'),  // Дополнительная информация
            'state_id'     => Yii::t('items', 'State'),                   // Идентификатор состояния
            'statusName'   => Yii::t('items', 'State'),                   // Название состояния
            'type_id'      => Yii::t('items', 'Item type'),               // Идентификатор типа
            'typeName'     => Yii::t('items', 'Item type'),               // Название типа
            'location_id'  => Yii::t('items', 'Location'),                // Идентификатор метоположения
            'locationName' => Yii::t('items', 'Location'),                // Название местоположения
            'regionName'   => Yii::t('items', 'Region'),                  // Название подразделения

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

    // Получение Получение региона/подразделения размещения оборудования
    public function getRegions()
    {
        return $this->getLocations()->joinWith('regions');
    }
}
