<?php

namespace app\models\admin;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

class AdminUser extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%admin_user}}';
    }
}

