<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Эта модель создана для формы items/check.
 *
 * @property string|null $qrcheck  QR-код инвентарного номера
 * @property int|null    $location Идентификатор места расположения
 * @property int|null    $region   Идентификатор региона/подразделения
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

