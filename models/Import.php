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
class Import extends Model
{
    public $filecsv;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'filecsv' ], 'file', 'skipOnEmpty' => false, ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'filecsv' => Yii::t('items', 'CSV file'), // Файл csv
        ];
    }
    
    /**
     * 
     */
    public function upload()
    {
        if ($this->validate())
        {
            $this->filecsv->saveAs('upload/' . $this->filecsv->baseName . '.' . $this->filecsv->extension);
            return true;
        }
        else
        {
            if (isset($this->filecsv)) {
            $this->addError('filecsv', Yii::t('items', 'File upload is not valid'));
            } else {
            $this->addError('filecsv', Yii::t('items', 'Attribute filecsv is not set'));
            }
            return false;
        }
    }
}

