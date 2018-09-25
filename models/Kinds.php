<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kinds".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 */
class Kinds extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */


//    const STATUS_ACTIVE = 0;
//    const STATUS_INACTIVE = 1;
//
//    const SCENARIO_INSERT = 'insert';
//    const SCENARIO_UPDATE = 'update';
//    const SCENARIO_ABLE = 'able';
//
//    public function scenarios()
//    {
//        return [
//            self::SCENARIO_INSERT => ['name', 'status'],
//            self::SCENARIO_UPDATE => ['name', 'status'],
//            self::SCENARIO_ABLE => ['status'],
//        ];
//    }

    public static function tableName()
    {
        return 'kinds';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'status'], 'required'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'status' => 'Status',
        ];
    }
}
