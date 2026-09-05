<?php

/**
 * This is the model class for table "{{stock_issue_parent}}".
 *
 * The followings are the available columns in table '{{stock_issue_parent}}':
 * @property integer $id
 * @property string $issue_date
 * @property string $issue_number
 * @property integer $issue_by
 * @property string $total_amount
 * @property string $comments
 * @property integer $status
 * @property string $created_on
 * @property integer $created_by
 *
 * The followings are the available model relations:
 * @property TransectionStatus $status0
 * @property Users $createdBy
 */
class StockIssueParent extends CActiveRecord {

    public $error_message;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{stock_issue_parent}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('issue_date, issue_number, issue_by', 'required'),
            array('issue_by, status, created_by', 'numerical', 'integerOnly' => true),
            array('issue_number', 'length', 'max' => 100),
            array('total_amount', 'length', 'max' => 18),
            array('comments, created_on', 'safe'),            
            array('error_message', 'checkCoutItemsCreate', 'on' => 'insert'),
            array('error_message', 'checkCoutItemsUpdate', 'on' => 'update'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, issue_date, issue_number, issue_by, total_amount, comments, status, created_on, created_by', 'safe', 'on' => 'search'),
        );
    }

    public function checkCoutItemsCreate($attribute, $params) {
        $array = StockIssue::model()->findAll(array('condition' => 'parent=0 AND created_by=' . (int) Yii::app()->user->id));
        $total = count($array);

        if ($total <= 0) {
            $this->addError($attribute, '<i class="fa fa-arrow-up"></i> Please add one or more items to the grid!');
        }
    }

    public function checkCoutItemsUpdate($attribute, $params) {
        $array = StockIssue::model()->findAll(array('condition' => 'parent=' . (int) $this->id));
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
            'stockIssues' => array(self::HAS_MANY, 'StockIssue', 'parent'),
            'status0' => array(self::BELONGS_TO, 'TransectionStatus', 'status'),
            'createdBy' => array(self::BELONGS_TO, 'User', 'created_by'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'issue_date' => 'Date',
            'issue_number' => 'Issue#',
            'issue_by' => 'Issue By',
            'total_amount' => 'Amount',
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
        $criteria->compare('issue_date', $this->issue_date, true);
        $criteria->compare('issue_number', $this->issue_number, true);
        $criteria->compare('issue_by', $this->issue_by);
        $criteria->compare('total_amount', $this->total_amount, true);
        $criteria->compare('comments', $this->comments, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('created_on', $this->created_on, true);
        $criteria->compare('created_by', $this->created_by);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize20'],
            ),
            'sort' => array('defaultOrder' => 'issue_date DESC, id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return StockIssueParent the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Generate Auto Issue Number
     * @return type integer value
     */
    public static function generateIssueNumber() {
        $criteria = new CDbCriteria;
        $criteria->order = 'created_on DESC';
        $model = StockIssueParent::model()->find($criteria);
        if (empty($model->issue_number)) {
            $autoValue = 1;
        } else {
            $ex = explode('-', $model->issue_number);
            $max = $ex[2];
            if ($ex[1] == date('Y')) {
                $autoValue = ((int) $max + 1);
            } else {
                $autoValue = 1;
            }
        }

        $return = 'SI#' . strtoupper(Yii::app()->user->name) . '-' . date('Y') . '-' . $autoValue;
        return $return;
    }

    public static function visibleActions($id) {
        $model = StockIssueParent::model()->findByPk($id);
        if ($model->status == 1 || $model->status == 2) {
            return false;
        } else {
            return true;
        }
    }

    public static function visibleActionEdit($id) {
        $model = StockIssueParent::model()->findByPk($id);
        if ($model->status == 1) {
            return true;
        } else {
            return false;
        }
    }

    public static function getData($id, $field) {
        $value = StockIssueParent::model()->findByAttributes(array('id' => $id));
        if (empty($value->$field)) {
            return null;
        } else {
            return $value->$field;
        }
    }

    public static function getReferenceRequisitionNo($id) {
        if ($id > 0) {
            $model = StockRequisition::model()->findByPk($id);
            $modelParent = StockRequisitionParent::model()->findByPk($model->parent);
            return $modelParent->requisition_number;
        } else {
            return null;
        }
    }

}
