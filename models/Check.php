<?php

namespace app\models;

use Yii;
use yii\base\Model;

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
class Check extends Model
{
    public $qrcheck;
    public $location;
    public $region;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['qrcheck', ], 'string', 'max' => 255],
//            [['qrcheck', ], 'required' ],
            [['location', 'region'],  'integer' ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'qrcheck'      => Yii::t('items',     'Inventory QR-code'), // QR-код инвентарного номера
            'location'     => Yii::t('locations', 'Location'),          // Местоположение/размещение (идентификатор)
            'region'       => Yii::t('regions',   'Region'),            // Регион/подразделение (идентификатор)
        ];
    }
}

