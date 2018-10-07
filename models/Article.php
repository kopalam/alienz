<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property int $dates
 * @property int $kind_id
 * @property int $status
 * @property string $cover
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;

    const SCENARIO_INSERT = 'insert';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_ABLE = 'able';

    public function scenarios()
    {
        return [
            self::SCENARIO_INSERT => ['title', 'content','dates','kind_id','cover','status'],
            self::SCENARIO_UPDATE => ['title', 'content','dates','kind_id','cover','status'],
            self::SCENARIO_ABLE => ['status'],
        ];
    }

    public static function tableName()
    {
        return 'article';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content', 'kind_id', 'status'], 'required'],
            [['content','cover'], 'string'],
            [['dates', 'kind_id', 'status'], 'integer'],
            [['title'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'dates' => 'Dates',
            'kind_id' => 'Kind ID',
            'status' => 'Status',
            'cover'=>'Cover',
        ];
    }
}
