<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "course_set".
 *
 * @property int $id
 * @property int $status
 * @property int $course_id
 * @property int $stime
 * @property int $etime
 * @property int $remark
 * @property int $address
 * @property int $classes
 */
class CourseSet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'course_set';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['classes','status', 'course_id', 'stime', 'etime'], 'required'],
            [['classes','status', 'course_id', 'stime', 'etime'], 'integer'],
            [['remark','address'],'string'],
        ];
    }

    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'course_id' => 'Course ID',
            'stime' => 'Stime',
            'etime' => 'Etime',
            'remark'=>'Remark',
            'address'=>'Address',
            'classes'=>'Classes',
        ];
    }
}
