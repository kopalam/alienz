<?php

namespace app\models\admin;

use Yii;

/**
 * This is the model class for table "admin_user".
 *
 * @property int $id
 * @property string $name
 * @property string $telephone
 * @property string $passwd
 * @property string $cauth_iden
 * @property string $content
 * @property string $avatar
 * @property string $nickname
 * @property int $status
 */
class AdminUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['passwd'], 'required'],
            [['content'], 'string'],
            [['status'], 'integer'],
            [['name', 'cauth_iden', 'nickname'], 'string', 'max' => 33],
            [['telephone'], 'string', 'max' => 32],
            [['passwd'], 'string', 'max' => 132],
            [['avatar'], 'string', 'max' => 120],
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
            'telephone' => 'Telephone',
            'passwd' => 'Passwd',
            'cauth_iden' => 'Cauth Iden',
            'content' => 'Content',
            'avatar' => 'Avatar',
            'nickname' => 'Nickname',
            'status' => 'Status',
        ];
    }
}
