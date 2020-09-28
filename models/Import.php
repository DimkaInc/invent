<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Этот класс модели создан для формы items/import
 *
 * @property file $filecsv Файл, содержащий данные
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

