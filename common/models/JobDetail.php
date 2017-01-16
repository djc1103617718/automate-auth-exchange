<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%job_detail}}".
 *
 * @property integer $job_detail_id
 * @property integer $step_symbol
 * @property integer $job_id
 * @property string $arg1
 * @property string $arg2
 * @property string $arg3
 * @property string $arg4
 * @property string $arg5
 * @property string $arg6
 *
 * @property Job $job
 */
class JobDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%job_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['step_symbol', 'job_id'], 'required'],
            [['step_symbol'], 'integer'],
            [['arg1', 'arg2', 'arg3', 'arg4', 'arg5', 'arg6'], 'string', 'max' => 256],
            [['step_symbol', 'job_id'], 'unique', 'targetAttribute' => ['step_symbol', 'job_id'], 'message' => 'The combination of Step Symbol and Job ID has already been taken.'],
            [['job_id'], 'exist', 'skipOnError' => true, 'targetClass' => Job::className(), 'targetAttribute' => ['job_id' => 'job_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'job_detail_id' => Yii::t('app', 'Job Detail ID'),
            'step_symbol' => Yii::t('app', 'Step Symbol'),
            'job_id' => Yii::t('app', 'Job ID'),
            'arg1' => Yii::t('app', 'Arg1'),
            'arg2' => Yii::t('app', 'Arg2'),
            'arg3' => Yii::t('app', 'Arg3'),
            'arg4' => Yii::t('app', 'Arg4'),
            'arg5' => Yii::t('app', 'Arg5'),
            'arg6' => Yii::t('app', 'Arg6'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJob()
    {
        return $this->hasOne(Job::className(), ['job_id' => 'job_id']);
    }
}
