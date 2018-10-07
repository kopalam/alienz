<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sign_set".
 *
 * @property int $id
 * @property int $firstscore
 * @property int $addscore
 */
class SignSet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sign_set';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['firstscore', 'addscore'], 'required'],
            [['firstscore', 'addscore'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstscore' => 'Firstscore',
            'addscore' => 'Addscore',
        ];
    }
}
