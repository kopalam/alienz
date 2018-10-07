<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pay_order".
 *
 * @property int $id
 * @property int $uid
 * @property int $price
 * @property string $trade_sn
 * @property int $dates
 *   @property int $course_id
 */
class PayOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pay_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'price', 'dates','course_id'], 'integer'],
            [['trade_sn'], 'string', 'max' => 33],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'price' => 'Price',
            'trade_sn' => 'Trade Sn',
            'dates' => 'Dates',
            'course_id'=>'Course Id',
        ];
    }
}
