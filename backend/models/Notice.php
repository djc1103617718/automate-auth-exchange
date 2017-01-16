<?php

namespace backend\models;
use backend\models\NoticeCategory;

class Notice extends \common\models\Notice
{
    public static function remarkAlready($noticeIds)
    {
        Notice::updateAll(['status' => self::STATUS_ALREADY_READ],['notice_id' => $noticeIds]);
    }

    /**
     * 获取所有服务端消息的分类
     *
     * @param null $parentId
     * @return array
     */
    public static function getServerNoticeCategory($parentId = null)
    {
        $serverCategoryNames = [];
        // 由于乱码问题,暂时直接用ID; 服务端消息ID=1;
        $severCategoryId = NoticeCategory::findOne(['category_name' => self::CATEGORY_NAME_SERVER_NOTICE])->category_id;
        if (!$parentId) {
            $parentId = $severCategoryId;
            $serverCategoryNames[] = self::CATEGORY_NAME_SERVER_NOTICE;
        }
        $model = NoticeCategory::find()
            ->select(['category_id', 'category_name'])
            ->where(['status' => NoticeCategory::STATUS_NORMAL, 'pid' => $parentId])
            ->asArray()
            ->all();
        if (!empty($model)) {
            foreach ($model as $item) {
                $serverCategoryNames[] = $item['category_name'];
                $serverCategoryNames = array_merge($serverCategoryNames, self::getServerNoticeCategory($item['category_id']));
            }
        }
        //var_dump($serverCategoryNames);die;
        return $serverCategoryNames;
    }
}