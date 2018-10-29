<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sign_up".
 *
 * @property int $id
 * @property int $course_id
 * @property int $uid
 * @property int $pay_dates
 * @property int $status
 * @property string $trade_sn
 * @property int $price
 */
class SignUp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sign_up';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_id', 'uid', 'pay_dates', 'status', 'trade_sn', 'price'], 'required'],
            [['course_id', 'uid', 'pay_dates', 'status', 'price'], 'integer'],
            [['trade_sn'], 'string', 'max' => 50],
        ];
    }

    public function getCourse_set()
    {
        return $this->hasMany(CourseSet::className(), ['course_id' => 'course_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'course_id' => 'Course ID',
            'uid' => 'Uid',
            'pay_dates' => 'Pay Dates',
            'status' => 'Status',
            'trade_sn' => 'Trade Sn',
            'price' => 'Price',
        ];
    }
}
