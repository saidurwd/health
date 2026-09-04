<?php

/**
 * This is the model class for table "{{store_document}}".
 *
 * The followings are the available columns in table '{{store_document}}':
 * @property integer $id
 * @property integer $transection_type
 * @property integer $transection_id
 * @property string $doc_title
 * @property string $doc_file
 * @property integer $created_by
 * @property string $created_on
 */
class StoreDocument extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{store_document}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('transection_type', 'required'),
            array('transection_type, transection_id, created_by', 'numerical', 'integerOnly' => true),
            array('doc_title', 'length', 'max' => 255),
            array('doc_file', 'length', 'max' => 400),
            array('created_on', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, transection_type, transection_id, doc_title, doc_file, created_by, created_on', 'safe', 'on' => 'search'),
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
            'transection_type' => 'Type',
            'transection_id' => 'Transection ID',
            'doc_title' => 'Title',
            'doc_file' => 'Document',
            'created_by' => 'Created By',
            'created_on' => 'Created On',
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
        $criteria->compare('transection_type', $this->transection_type);
        $criteria->compare('transection_id', $this->transection_id);
        $criteria->compare('doc_title', $this->doc_title, true);
        $criteria->compare('doc_file', $this->doc_file, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('created_on', $this->created_on, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return StoreDocument the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function loadModel($id) {
        $model = StoreDocument::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
