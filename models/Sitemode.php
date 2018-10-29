<?php

namespace app\models;

use Yii;

class Sitemode extends \yii\db\ActiveRecord
{
    /*框架模式*/
    public static function tableName()
    {
        return '{{%site_mode}}';
    }
}

