<?php

namespace app\models\score;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

class ScoreLogs extends ActiveRecord
{
    public static function tableName(){
        return '{{%score_logs}}';
    }
}

