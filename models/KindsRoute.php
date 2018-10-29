<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kinds_route".
 *
 * @property int $id
 * @property int $kid
 * @property int $parent_id
 */
class KindsRoute extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kinds_route';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kid', 'parent_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kid' => 'Kid',
            'parent_id' => 'Parent ID',
        ];
    }
}
