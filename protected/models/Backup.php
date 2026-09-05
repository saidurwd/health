<?php

/**
 * This is the model class for table "{{backup}}".
 *
 * The followings are the available columns in table '{{backup}}':
 * @property integer $id
 * @property string $attachment
 * @property string $created_on
 * @property integer $created_by
 *
 * The followings are the available model relations:
 * @property User $createdBy
 */
class Backup extends CActiveRecord
{

    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const TYPE_SQL = 'sql';
    const TYPE_ZIP = 'zip';
    const TYPE_GZIP = 'gzip';

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{backup}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('created_on', 'required'),
            array('created_by', 'numerical', 'integerOnly' => true),
            array('attachment', 'length', 'max' => 250),
            array('id, attachment, created_on, created_by', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'createdBy' => array(self::BELONGS_TO, 'User', 'created_by'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'attachment' => 'Attachment',
            'created_on' => 'Created On',
            'created_by' => 'Created By',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('attachment', $this->attachment, true);
        $criteria->compare('created_on', $this->created_on, true);
        $criteria->compare('created_by', $this->created_by);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize'],
            ),
            'sort' => array('defaultOrder' => 'created_on DESC, id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Backup the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function getData($id, $field)
    {
        $model = Backup::model()->findByAttributes(array('id' => $id));
        if (empty($model->$field)) {
            return null;
        } else {
            return $model->$field;
        }
    }

    public static function formatBytes($bytes)
    {
        if ($bytes === null || $bytes < 0) {
            return '0 B';
        }
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    public static function cleanOldBackups($days = 30)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = 'created_on < DATE_SUB(NOW(), INTERVAL ' . (int) $days . ' DAY)';
        $oldBackups = Backup::model()->findAll($criteria);

        $deleted = 0;
        foreach ($oldBackups as $backup) {
            $filePath = Yii::app()->basePath . '/../uploads/backups/' . $backup->attachment;
            if (is_file($filePath) && file_exists($filePath)) {
                @unlink($filePath);
            }
            $backup->delete();
            $deleted++;
        }

        return $deleted;
    }
}
