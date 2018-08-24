<?php

namespace app\models\score;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

class UserScore extends ActiveRecord
{

    public static function tableName(){
        return '{{%user_score}}';
    }


}

