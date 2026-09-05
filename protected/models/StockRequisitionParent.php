<?php

/**
 * This is the model class for table "{{stock_requisition_parent}}".
 *
 * The followings are the available columns in table '{{stock_requisition_parent}}':
 * @property integer $id
 * @property string $requisition_date
 * @property string $requisition_number
 * @property integer $requisition_by
 * @property string $comments
 * @property integer $status
 * @property string $created_on
 * @property integer $created_by
 *
 * The followings are the available model relations:
 * @property TransectionStatus $status0
 * @property Users $createdBy
 */
class StockRequisitionParent extends CActiveRecord {
    
    public $error_message;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{stock_requisition_parent}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('requisition_date, requisition_number, requisition_by', 'required'),
            array('requisition_by, status, created_by', 'numerical', 'integerOnly' => true),
            array('requisition_number', 'length', 'max' => 100),
            array('comments, created_on', 'safe'),
            array('error_message', 'checkCoutItemsCreate', 'on' => 'insert'),
            array('error_message', 'checkCoutItemsUpdate', 'on' => 'update'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, requisition_date, requisition_number, requisition_by, comments, status, created_on, created_by', 'safe', 'on' => 'search'),
        );
    }
    
    public function checkCoutItemsCreate($attribute, $params) {
        $array = StockRequisition::model()->findAll(array('condition' => 'parent=0 AND created_by=' . (int) Yii::app()->user->id));
        $total = count($array);

        if ($total <= 0) {
            $this->addError($attribute, '<i class="fa fa-arrow-up"></i> Please add one or more items to the grid!');
        }
    }

    public function checkCoutItemsUpdate($attribute, $params) {
        $array = StockRequisition::model()->findAll(array('condition' => 'parent=' . (int) $this->id));
        $total = count($array);

        if ($total <= 0) {
            $this->addError($attribute, '<i class="fa fa-arrow-up"></i> Please add one or more items to the grid!');
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'stockRequisitions' => array(self::HAS_MANY, 'StockRequisition', 'parent'),
            'createdBy' => array(self::BELONGS_TO, 'User', 'created_by'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'requisition_date' => 'Date',
            'requisition_number' => 'Req#',
            'requisition_by' => 'Req By',
            'comments' => 'Comments',
            'status' => 'Status',
            'created_on' => 'Created On',
            'created_by' => 'Created By',
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
        $criteria->compare('requisition_date', $this->requisition_date, true);
        $criteria->compare('requisition_number', $this->requisition_number, true);
        $criteria->compare('requisition_by', $this->requisition_by);
        $criteria->compare('comments', $this->comments, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('created_on', $this->created_on, true);
        $criteria->compare('created_by', $this->created_by);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize20'],
            ),
            'sort' => array('defaultOrder' => 'requisition_date DESC, id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return StockRequisitionParent the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Generate Auto Requisition Number
     * @return type integer value
     */
    public static function generateRequisitionNumber() {
        $criteria = new CDbCriteria;
        $criteria->order = 'created_on DESC';
        $model = StockRequisitionParent::model()->find($criteria);
        if (empty($model->requisition_number)) {
            $autoValue = 1;
        } else {
            $ex = explode('-', $model->requisition_number);
            $max = $ex[2];
            if ($ex[1] == date('Y')) {
                $autoValue = ((int) $max + 1);
            } else {
                $autoValue = 1;
            }
        }

        $return = 'SR#' . strtoupper(Yii::app()->user->name) . '-' . date('Y') . '-' . $autoValue;
        return $return;
    }

    public static function visibleActions($id) {
        $model = StockRequisitionParent::model()->findByPk($id);
        if ($model->status == 1 || $model->status == 2) {
            return false;
        } else {
            return true;
        }
    }

    public static function getData($id, $field) {
        $value = StockRequisitionParent::model()->findByAttributes(array('id' => $id));
        if (empty($value->$field)) {
            return null;
        } else {
            return $value->$field;
        }
    }

}
