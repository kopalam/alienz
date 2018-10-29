<?php

namespace app\models\score;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

class ScoreSet extends ActiveRecord
{
    public $inScore;
    public $inPrice;
    public $outScore;
    public $outPrice;
    public static function tableName(){
        return '{{%score_set}}';
    }


}

