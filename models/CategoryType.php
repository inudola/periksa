<?php

namespace reward\models;

use Yii;

/**
 * This is the model class for table "category_type".
 *
 * @property int $id
 * @property string $name
 * @property string $icon
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Category[] $categories
 */
class CategoryType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['icon'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxSize'=>1024 * 1024 * 1],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Category Type',
            'icon' => 'Icon',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['type_category_id' => 'id']);
    }


    public function beforeSave($insert)
    {
        $this->name = strtoupper($this->name);

        if($this->isNewRecord)
            $this->created_at = date("Y-m-d H:i:s");
        else
            $this->updated_at = date("Y-m-d H:i:s");

        return parent::beforeSave($insert);
    }
}
