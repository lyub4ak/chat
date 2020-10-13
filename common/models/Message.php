<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property string $text
 * @property int $is_banned
 * @property int $created_by_id
 * @property int $updated_by_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * Message sending.
     */
    const SCENARIO_SEND = 'send';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }


    public function behaviors()
    {
        return [
            'blameableBehavior' => [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by_id',
                'updatedByAttribute' => 'updated_by_id',
            ],
            'timestampBehavior' => [
                'class' => TimestampBehavior::class,
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SEND] = ['text', 'created_by_id', 'updated_by_id', 'created_at', 'updated_at'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text'], 'required'],
            [['text'], 'string'],
            [['is_banned', 'created_by_id', 'updated_by_id', 'created_at', 'updated_at'], 'integer'],
            [['is_banned'], 'default', 'value' => 0],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
            'is_banned' => 'Is Banned',
            'created_by_id' => 'Created By ID',
            'updated_by_id' => 'Updated By ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'created_by_id']);
    }

    /**
     * Name of user.
     *
     * @return string
     */
    public function getUsername () {
        return $this->user->username;
    }

    /**
     * Finds all not banned messages.
     *
     * @return static[]
     */
    public static function findAllNotBanned()
    {
        return static::find()->where([
            'is_banned' => false,
        ])
            ->with('user')
            ->orderBy(['created_at' => SORT_ASC])
            ->all();
    }
}
