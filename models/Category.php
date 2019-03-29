<?php

namespace reward\models;

/**
 * This is the model class for table "category".
 *
 * @property string $id
 * @property string $category_code
 * @property string $category_name
 * @property string $status
 * @property string $icon
 * @property string $title
 * @property string $note
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Reward[] $rewards
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $nama_reward;

    const ACTIVE = 1;
    const INACTIVE = 0;

    const THP = 'Take Home Pay';

    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_name'], 'required'],
            [['status'], 'required'],
            [['description'], 'string'],
            [['icon'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxSize'=>1024 * 1024 * 1],
            [['created_at', 'updated_at'], 'safe'],
            [['category_name', 'title', 'note'], 'string', 'max' => 50],
            [['category_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoryType::className(), 'targetAttribute' => ['category_type_id' => 'id']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_name' => 'Category Name',
            'status' => 'Status',
            'icon' => 'Icon',
            'title' => 'Title',
            'note' => 'Note',
            'category_type_id' => 'Category Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function beforeSave($insert)
    {
        if($this->isNewRecord)
            $this->created_at = date("Y-m-d H:i:s");
            
        $this->updated_at = date("Y-m-d H:i:s");

        return parent::beforeSave($insert);
    }

    public function getCategoryType()
    {
        return $this->hasOne(CategoryType::className(), ['id' => 'category_type_id']);
    }

}
