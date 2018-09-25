<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "teacher_course".
 *
 * @property int $id
 * @property int $teacher_id
 * @property int $course_id
 * @property int $status
 */
class bakTeacherCourse extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teacher_course';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['teacher_id', 'course_id','kid', 'status'], 'required'],
            [['teacher_id', 'course_id','kid', 'status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'teacher_id' => 'Teacher ID',
            'course_id' => 'Course ID',
            'kid' => 'KID',
            'status' => 'Status',
        ];
    }
}
