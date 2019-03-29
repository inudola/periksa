<?php

namespace reward\models;

use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Integer;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Import Excel form
 */
class UploadXlsxForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $userFile;

    /**
     * @var Integer
     */
    public $foreignKey;

    /**
     * @var String
     */
    private $_finalName = '';

    /**
     * @var Boolean
     */
    public $overwrite;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['foreignKey'], 'integer'],
            [['userFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xlsx'],
            [['overwrite'], 'safe'],
            [['overwrite'], 'default', 'value' => false],
            [['foreignKey'], 'default', 'value' => 0],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->_finalName = uniqid() . $this->userFile->baseName . '.' . $this->userFile->extension;
            $this->userFile->saveAs('uploads/' . $this->_finalName);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get final uploaded file name
     *
     * @return String
     */
    public function getFinalName()
    {
        return $this->_finalName;
    }

    public function isOverwrite() {
        if ($this->overwrite) {
            return true;
        } else {
            return false;
        }
    }
}