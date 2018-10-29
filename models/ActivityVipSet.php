<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "activity_vip_set".
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property int $price
 * @property int $dates
 * @property int $vip_id
 * @property int $status
 * @property int $pay
 * @property int $type
 * @property string $vip_about
 * @property int $people_limit
 * @property string $rules
 */
class ActivityVipSet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity_vip_set';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'title', 'price', 'dates', 'vip_id', 'status', 'pay', 'vip_about', 'people_limit', 'rules'], 'required'],
            [['price', 'dates', 'vip_id', 'status', 'pay', 'type', 'people_limit'], 'integer'],
            [['name'], 'string', 'max' => 200],
            [['title'], 'string', 'max' => 32],
            [['vip_about'], 'string', 'max' => 600],
            [['rules'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'title' => 'Title',
            'price' => 'Price',
            'dates' => 'Dates',
            'vip_id' => 'Vip ID',
            'status' => 'Status',
            'pay' => 'Pay',
            'type' => 'Type',
            'vip_about' => 'Vip About',
            'people_limit' => 'People Limit',
            'rules' => 'Rules',
        ];
    }
}
