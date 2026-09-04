<?php

/**
 * This is the model class for table "{{purchase_receive_document}}".
 *
 * The followings are the available columns in table '{{purchase_receive_document}}':
 * @property integer $id
 * @property integer $receive_number
 * @property string $doc_title
 * @property string $doc_file
 * @property integer $created_by
 * @property string $created_on
 */
class PurchaseReceiveDocument extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{purchase_receive_document}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('receive_number', 'required'),
            array('receive_number, created_by', 'numerical', 'integerOnly' => true),
            array('doc_title', 'length', 'max' => 255),
            array('doc_file', 'length', 'max' => 400),
            array('created_on', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, receive_number, doc_title, doc_file, created_by, created_on', 'safe', 'on' => 'search'),
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
            'receive_number' => 'Receive Number',
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
    public function search($id) {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->condition = 'receive_number=' . (int) $id;

        $criteria->compare('id', $this->id);
        $criteria->compare('doc_title', $this->doc_title, true);
        $criteria->compare('doc_file', $this->doc_file, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('created_on', $this->created_on, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => false,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PurchaseReceiveDocument the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function loadModel($id) {
        $model = PurchaseReceiveDocument::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
