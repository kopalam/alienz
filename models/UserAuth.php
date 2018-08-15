<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

class UserAuth extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%user_auth}}';
    }
}

