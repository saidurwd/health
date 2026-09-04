<?php

/**
 * This is the model class for table "{{prescription_medicine}}".
 *
 * The followings are the available columns in table '{{prescription_medicine}}':
 * @property integer $id
 * @property integer $parent
 * @property string $servicetype
 * @property string $product
 * @property string $instruction
 * @property integer $no_of_days
 * @property integer $created_by
 * @property string $created_on
 */
class PrescriptionMedicine extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{prescription_medicine}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('parent, created_by, created_on', 'required'),
            array('parent, no_of_days, created_by', 'numerical', 'integerOnly' => true),
            array('servicetype', 'length', 'max' => 8),
            array('product, instruction', 'length', 'max' => 250),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, parent, servicetype, product, instruction, no_of_days, created_by, created_on', 'safe', 'on' => 'search'),
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
            'parent' => 'Parent',
            'servicetype' => 'Type',
            'product' => 'Product',
            'instruction' => 'Instruction',
            'no_of_days' => 'No of Days',
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
        if ($id == 0) {
            $criteria->condition = '(parent=0 OR parent IS NULL) AND created_by=' . (int) Yii::app()->user->id;
        } else {
            $criteria->condition = 'parent=' . $id;
        }

        $criteria->compare('id', $this->id);
        $criteria->compare('parent', $this->parent);
        $criteria->compare('servicetype', $this->servicetype, true);
        $criteria->compare('product', $this->product, true);
        $criteria->compare('instruction', $this->instruction, true);
        $criteria->compare('no_of_days', $this->no_of_days);
//        $criteria->compare('created_by', $this->created_by);
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
     * @return PrescriptionMedicine the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
