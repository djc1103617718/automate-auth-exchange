<?php

namespace common\exceptions;

class MailSendException extends \yii\base\Exception
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Mail Send';
    }
}