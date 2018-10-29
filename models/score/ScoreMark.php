<?php

namespace app\models\score;

use Yii;

/**
 * This is the model class for table "score_mark".
 *
 * @property int $id
 * @property int $uid
 * @property int $last_sign_time
 * @property int $total_day
 */
class ScoreMark extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'score_mark';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'last_sign_time', 'total_day'], 'integer'],
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
            'last_sign_time' => 'Last Sign Time',
            'total_day' => 'Total Day',
        ];
    }
}
