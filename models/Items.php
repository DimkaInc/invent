<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table 'items'.
 *
 * @property int $id                   Идентификатор (неизменяемый)
 * @property int $type_id              Идентификатор типа оборудования
 * @property string|null  $name         Сетевое имя оборудования
 * @property string|null  $model        Модель оборудования
 * @property string|null  $os           Операционная система
 * @property string|null  $mac          Сетевой MAC адрес
 * @property string|null  $serial       Серийный номер
 * @property string|null  $product      Код оборудования
 * @property string|null  $modelnumber  Номер модели
 * @property string|null  $invent       Инвентарный номер
 * @property boolean      $checked      Флаг прохождения инвентаризации
 * @property string|null  $statusName   Наименование состояния
 * @property string|null  $typeName     Наименование типа
 * @property string|null  $locationName Наименование места размещения
 * @property string|null  $regionName   Наименование региона/подразделения
 */
class Items extends \yii\db\ActiveRecord
{
    public $statusName;
    public $typeName;
    public $locationName;
    public $regionName;
    public $myMessage;
     /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'model', 'os', 'serial', 'product', 'modelnumber', 'comment' ], 'string', 'max' => 255],
            [['mac'],    'string', 'max' => 20],
            [['invent'], 'string', 'max' => 50],
            [['type_id'], 'integer'],
            [['checked'], 'boolean'],
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
            'comment'      => Yii::t('items', 'Additional Information'),  // Дополнительная информация
            'checked'      => Yii::t('items', 'Location checked'),        // Проинвентаризировано
            'statusName'   => Yii::t('items', 'State'),                   // Название состояния
            'type_id'      => Yii::t('items', 'Item type'),               // Идентификатор типа
            'typeName'     => Yii::t('items', 'Item type'),               // Название типа
            'locationName' => Yii::t('items', 'Location'),                // Название местоположения
            'regionName'   => Yii::t('items', 'Region'),                  // Название подразделения

        ];
    }

    // Получение типа предмета/оборудования
    public function getTypes()
    {
        return $this->hasOne(Types::className(), ['id' => 'type_id']);
    }

    // Получение всех перемещений предмета/оборудования
    public function getMoving()
    {
        return $this->hasMany(Moving::className(), ['item_id' => 'id']);
    }

    // Получение статусов предмета/оборудования
    public function getStatus()
    {
        return $this->getMoving()->select(Status::tableName() . '.*')->joinWith('status');
    }

    // Получение места размещения предмета/оборудования
    public function getLocations()
    {
        return $this->getMoving()->select(Locations::tableName() . '.*')->joinWith('locations');
    }

    // Получение Получение региона/подразделения размещения предмета/оборудования
    public function getRegions()
    {
        return $this->getLocations()->select(Regions::tableName() .'.*')->joinWith('regions');
    }

}
