<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "my_course".
 *
 * @property int $id
 * @property int $uid
 * @property int $course_id
 */
class MyCourse extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'my_course';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'course_id'], 'integer'],
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
            'course_id' => 'Course ID',
        ];
    }
}
