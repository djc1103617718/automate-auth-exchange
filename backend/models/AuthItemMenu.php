<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_item_menu".
 *
 * @property string $request
 * @property string $description
 * @property string $auth_name
 * @property integer $menu_id
 *
 * @property Menu $menu
 * @property AuthItem $authName
 */
class AuthItemMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_item_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['request'], 'required'],
            [['description'], 'string'],
            [['menu_id'], 'integer'],
            [['request'], 'string', 'max' => 100],
            [['auth_name'], 'string', 'max' => 64],
            [['auth_name'], 'unique'],
            [['menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 'targetAttribute' => ['menu_id' => 'id']],
            [['auth_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['auth_name' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'request' => 'Request',
            'description' => 'Description',
            'auth_name' => 'Auth Name',
            'menu_id' => 'Menu ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthName()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'auth_name']);
    }
}
