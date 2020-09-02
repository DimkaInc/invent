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
            [['date'], 'checkValidDate'],
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
     * Проверка даты на следующие условия:
     * 1. Дата не больше текущей
     * 2. Если это первая запись о перемещении предмета/оборудования, то дата не меньше 1 января 1990 года
     * 3. Если есть более ранние перемещения, то дата не меньше самой поздей даты предыдущих перемещений
     * 4. Если есть более поздние перемещения, то дата не больше самой ранней даты последующих перемещений
     */
    public function checkValidDate()
    {
        if (!empty($this->date)) {
            $date = strtotime($this->date);
            if ($date > strtotime(date('d.m.Y'))) {
                $this->addError('date', Yii::t('moving', 'The date cannot be more than today'));
            } else {
                if ($date < strtotime('01.01.1990')) {
                    $this->addError('date', Yii::t('moving', 'Date cannot be less than 01.01.1990'));
                } else {
                    $item_id = $this->item_id;
                    
                    $query = Moving::find()
                        ->select('MAX(date) AS date')
                        ->where(['item_id' => $item_id]);
                    if (!empty($this->id)) {
                        $query = $query->andWhere(['<', 'id', $this->id]);
                    }
                    $query = $query->all();
                    if ((count($query) > 0) && ($date < strtotime($query[0]->date))) {
                        $this->addError('date', Yii::t('moving', 'The date cannot be less than {date}', ['date' => date('d.m.Y', strtotime($query[0]->date))]));
                    }
                    if (!empty($this->id)) {
                        $query = Moving::find()
                            ->select('MIN(date) AS date, id')
                            ->groupBy('id')
                            ->where(['item_id' => $item_id])
                            ->andWhere(['>', 'id', $this->id])
                            ->all();
                        if ((count($query) > 0) && ($date > strtotime($query[0]->date))) {
                            $this->addError('date', Yii::t('moving', 'The date cannot be more than {date}, {id}', ['date' => date('d.m.Y', strtotime($query[0]->date)), 'id' => $query[0]->id]));
                        }
                    }
                }
            }
        }
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
