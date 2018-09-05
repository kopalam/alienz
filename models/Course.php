<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "course".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string $content
 * @property int $teacher_id
 * @property string $cover
 * @property int $start
 * @property int $price
 */
class Course extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'course';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'status', 'content', 'teacher_id','price', 'cover', 'start'], 'required'],
            [['status', 'teacher_id', 'start','price'], 'integer'],
            [['content', 'cover'], 'string'],
            [['name'], 'string', 'max' => 500],
        ];
    }

    public function getCset()
    {
        return $this->hasMany(CourseSet::className(), ['course_id' => 'id']);
    }

    public function getTeacher()
    {
        return $this->hasOne(Users::className(), ['id' => 'uid']);
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'status' => 'Status',
            'content' => 'Content',
            'teacher_id' => 'Teacher ID',
            'price' => 'Price',
            'cover' => 'Cover',
            'start' => 'Start',
        ];
    }
}
