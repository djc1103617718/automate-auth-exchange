<?php

namespace backend\models;

use Yii;
use common\models\User as UserCommon;

class User extends UserCommon
{
    public $lockMsg;

    /**
     * @return bool
     */
    public function lock()
    {
        if ($this->status === self::STATUS_DELETED) {
            $this->status = self::STATUS_ACTIVE;
            if (!$this->update()) {
                $this->lockMsg = '解冻用户失败';
                return false;
            }
            $this->lockMsg = '该用户已解冻';
        } elseif ($this->status === self::STATUS_ACTIVE) {
            $this->status = self::STATUS_DELETED;
            if (!$this->update()) {
                $this->lockMsg = '冻结用户失败';
                return false;
            }
            $this->lockMsg = '该用户已冻结';
        } else {
            $this->lockMsg = '操作有误';
            return false;
        }
        return true;
    }
}