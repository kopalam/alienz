<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_rules".
 *
 * @property string $id
 * @property int $type
 * @property string $name
 * @property string $title
 * @property string $condition
 * @property int $status
 */
class AuthRules extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_rules';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status'], 'integer'],
            [['name'], 'string', 'max' => 80],
            [['title', 'condition'], 'string', 'max' => 33],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'name' => 'Name',
            'title' => 'Title',
            'condition' => 'Condition',
            'status' => 'Status',
        ];
    }
}
