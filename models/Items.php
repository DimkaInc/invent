<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table 'items'.
 *
 * @property int $id                    Идентификатор (неизменяемый)
// * @property int $type_id               Идентификатор типа оборудования
 * @property int $model_id              Идентификатор модели предмета/оборудования
 * @property string|null  $name         Сетевое имя оборудования
// * @property string|null  $model        Модель оборудования
 * @property string|null  $os           Операционная система
 * @property string|null  $mac          Сетевой MAC адрес
 * @property string|null  $serial       Серийный номер
// * @property string|null  $product      Код оборудования
// * @property string|null  $modelnumber  Номер модели
 * @property string|null  $invent       Инвентарный номер
 * @property boolean      $checked      Флаг прохождения инвентаризации
 * @property string|null  $statusName   Наименование состояния
 * @property string|null  $typeName     Наименование типа
 * @property string|null  $locationName Наименование места размещения
 * @property string|null  $regionName   Наименование региона/подразделения
 * @property string|null  $modelName    Наименование предмета/оборудования
 */
class Items extends \yii\db\ActiveRecord
{
    public $statusName;
    public $typeName;
    public $locationName;
    public $regionName;
    public $modelName;
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
            [[ 'name', 'os', 'serial', 'comment' ], 'string', 'max' => 255 ],
            [[ 'mac' ],    'string', 'max' => 20 ],
            [[ 'invent' ], 'string', 'max' => 50 ],
            [[ 'model_id' ], 'integer' ],
            [[ 'checked' ], 'boolean' ],
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
            'modelName'    => Yii::t('items', 'Model'),                   // Модель
            'model_id'     => Yii::t('items', 'Model identifier'),        // Идентификатор модели
            'typeName'     => Yii::t('items', 'Item type'),               // Название типа
            'os'           => Yii::t('items', 'Operating system'),        // Операционная система
            'mac'          => Yii::t('items', 'MAC address'),             // MAC адрес
            'serial'       => Yii::t('items', 'Serial number'),           // Серийный номер
            'invent'       => Yii::t('items', 'Inventory number'),        // Инвентарный номер
            'comment'      => Yii::t('items', 'Additional Information'),  // Дополнительная информация
            'checked'      => Yii::t('items', 'Location checked'),        // Проинвентаризировано
            'statusName'   => Yii::t('items', 'State'),                   // Название состояния
            'locationName' => Yii::t('items', 'Location'),                // Название местоположения
            'regionName'   => Yii::t('items', 'Region'),                  // Название подразделения

        ];
    }

    // Получение всех перемещений предмета/оборудования
    public function getMoving()
    {
        return $this->hasMany(Moving::className(), ['item_id' => 'id']);
    }

    // Получение модели предмета/оборудования
    public function getModels()
    {
        return $this->hasOne(Models::className(), ['id' => 'model_id']);
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

    // Получение региона/подразделения размещения предмета/оборудования
    public function getRegions()
    {
        return $this->getLocations()->select(Regions::tableName() .'.*')->joinWith('regions');
    }

    // Получение типа предмета/оборудования
    public function getTypes()
    {
        return $this->getModels()->select(Types::tableName() . '.*')->joinWith('types');
    }
}
