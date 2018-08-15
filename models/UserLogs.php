<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

class UserLogs extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%user_logs}}';
    }
}

