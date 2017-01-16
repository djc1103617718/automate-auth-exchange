<?php

namespace backend\models;

use yii\helpers\ArrayHelper;

class NoticeCategory extends \common\models\NoticeCategory
{
    /**
     * @return array
     */
    public static function categoryIdToName()
    {
        $category = self::find()
            ->select(['category_name', 'category_id'])
            ->where(['status' => self::STATUS_NORMAL])
            ->asArray()
            ->all();
        if (!$category) {
            $categoryList = [0 => '不设父级'];
        } else {
            $categoryList = ArrayHelper::merge([0 => '不设父级'], ArrayHelper::map($category, 'category_id', 'category_name'));
        }
        return $categoryList;
    }

    public function fakeDelete()
    {
        $this->status = self::STATUS_DELETE;
        if ($this->update()){
            return true;
        } else {
            return false;
        }
    }
}