<?php

namespace app\models\score;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

class ScoreSet extends ActiveRecord
{

    public static function tableName(){
        return '{{%user_score}}';
    }


}

