<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $openId
 * @property string $nickName
 * @property int $gender
 * @property string $language
 * @property string $city
 * @property string $province
 * @property string $country
 * @property string $avatarUrl
 * @property int $reg_time
 * @property string $unionId
 * @property string $telephone
 * @property string $cauth_iden
 * @property int $status
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['openId', 'nickName'], 'required'],
            [['gender', 'reg_time', 'status'], 'integer'],
            [['openId', 'nickName'], 'string', 'max' => 33],
            [['language', 'cauth_iden'], 'string', 'max' => 32],
            [['city', 'province', 'country', 'telephone'], 'string', 'max' => 20],
            [['avatarUrl'], 'string', 'max' => 355],
            [['unionId'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openId' => 'Open ID',
            'nickName' => 'Nick Name',
            'gender' => 'Gender',
            'language' => 'Language',
            'city' => 'City',
            'province' => 'Province',
            'country' => 'Country',
            'avatarUrl' => 'Avatar Url',
            'reg_time' => 'Reg Time',
            'unionId' => 'Union ID',
            'telephone' => 'Telephone',
            'cauth_iden' => 'Cauth Iden',
            'status' => 'Status',
        ];
    }
}
