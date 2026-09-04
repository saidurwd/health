<?php

/**
 * This is the model class for table "{{batch}}".
 *
 * The followings are the available columns in table '{{batch}}':
 * @property integer $id
 * @property string $title
 * @property string $manufacturing
 * @property string $expiry
 */
class Batch extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{batch}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('expiry', 'required'),
            array('title', 'length', 'max' => 100),
            array('manufacturing, expiry', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, manufacturing, expiry', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => 'Batch',
            'manufacturing' => 'Manufacturing',
            'expiry' => 'Expiry',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('manufacturing', $this->manufacturing, true);
        $criteria->compare('expiry', $this->expiry, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize'],
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Batch the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    /*
     * check batch exist 
     * using expiry date
     */

    public static function countBatchExpiry($expiry) {
        $model = Batch::model()->findByAttributes(array('end_date' => $expiry));
        if (empty($model->id)) {
            return 0;
        } else {
            return $model->id;
        }
    }

    /*
     * check batch exist 
     * using Lot Number
     */

    public static function countBatchLotNumber($expiry) {
        $model = Batch::model()->findByAttributes(array('expiry' => $expiry));
        if (empty($model->id)) {
            return 0;
        } else {
            return $model->id;
        }
    }
    
    public static function getData($id, $field) {
        $model = Batch::model()->findByAttributes(array('id' => $id));
        if (empty($model->$field)) {
            return 'N/A';
        } else {
            return $model->$field;
        }
    }
    
    public static function getExpiryDate($id) {
        $model = Batch::model()->findByAttributes(array('id' => $id));
        if (empty($model->expiry)) {
            return null;
        } else {
            return $model->expiry;
        }
    }
    
    public static function getBatch($id) {
        $model = Batch::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model->title;
    }

}
