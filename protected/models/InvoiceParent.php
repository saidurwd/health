<?php

/**
 * This is the model class for table "{{invoice_parent}}".
 *
 * The followings are the available columns in table '{{invoice_parent}}':
 * @property integer $id
 * @property string $patient
 * @property string $invoice_date
 * @property string $invoice_number
 * @property integer $invoice_by
 * @property string $total_amount
 * @property string $comments
 * @property integer $status
 * @property string $created_on
 * @property integer $created_by
 *
 * The followings are the available model relations:
 * @property TransectionStatus $status0
 * @property User $createdBy
 */
class InvoiceParent extends CActiveRecord
{

    public $error_message;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{invoice_parent}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('invoice_date, invoice_number, invoice_by', 'required'),
            array('patient, prescription, invoice_by, patient_category_new, patient_category, status, created_by', 'numerical', 'integerOnly' => true),
            array('invoice_number, payment_status', 'length', 'max' => 100),
            array('total_amount', 'length', 'max' => 18),
            array('comments, created_on', 'safe'),
            array('error_message', 'checkCoutItemsCreate', 'on' => 'insert'),
            array('error_message', 'checkCoutItemsUpdate', 'on' => 'update'),
            //array('patient_category', 'required', 'on' => 'update'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, patient, prescription, invoice_date, invoice_number, invoice_by, total_amount, patient_category_new, patient_category, comments, status, payment_status, created_on, created_by', 'safe', 'on' => 'search'),
        );
    }

    public function checkCoutItemsCreate($attribute, $params)
    {
        $total = Yii::app()->db->createCommand()
            ->select('COUNT(*)')
            ->from('{{invoice}}')
            ->where('parent=0 AND created_by=' . (int) Yii::app()->user->id)
            ->queryScalar();

        if ($total <= 0) {
            $this->addError($attribute, '<i class="fa fa-arrow-up"></i> Please add one or more items to the grid!');
        }
    }

    public function checkCoutItemsUpdate($attribute, $params)
    {
        $total = Yii::app()->db->createCommand()
            ->select('COUNT(*)')
            ->from('{{invoice}}')
            ->where('parent=' . (int) $this->id)
            ->queryScalar();

        if ($total <= 0) {
            $this->addError($attribute, '<i class="fa fa-arrow-up"></i> Please add one or more items to the grid!');
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'invoices' => array(self::HAS_MANY, 'Invoice', 'parent'),
            'status0' => array(self::BELONGS_TO, 'TransectionStatus', 'status'),
            'createdBy' => array(self::BELONGS_TO, 'User', 'created_by'),
            'patient0' => array(self::BELONGS_TO, 'Patient', 'patient'),
            'invoiceBy' => array(self::BELONGS_TO, 'User', 'invoice_by'),
            'itemCount' => array(self::STAT, 'Invoice', 'parent'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'patient' => 'Patient',
            'prescription' => 'Prescription',
            'invoice_date' => 'Date',
            'invoice_number' => 'Invoice#',
            'invoice_by' => 'Invoice By',
            'total_amount' => 'Amount',
            'patient_category' => 'Sub Category',
            'patient_category_new'=> 'Category',
            'comments' => 'Comments',
            'status' => 'Status',
            'payment_status' => 'Payment Status',
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
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->with = array('patient0', 'invoiceBy', 'status0');

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.patient', $this->patient);
        $criteria->compare('t.prescription', $this->prescription);
        $criteria->compare('t.invoice_date', $this->invoice_date, true);
        $criteria->compare('t.invoice_number', $this->invoice_number, true);
        $criteria->compare('t.invoice_by', $this->invoice_by);
        $criteria->compare('t.total_amount', $this->total_amount, true);
        $criteria->compare('t.patient_category_new', $this->patient_category_new);
        $criteria->compare('t.patient_category', $this->patient_category);
        $criteria->compare('t.comments', $this->comments, true);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('t.payment_status', $this->payment_status);
        $criteria->compare('t.created_on', $this->created_on, true);
        $criteria->compare('t.created_by', $this->created_by);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize20'],
            ),
            'sort' => array('defaultOrder' => 't.invoice_date DESC, t.id DESC')
        ));
    }

    public function search_patient($patient)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->condition = 'patient=' . (int) $patient;
        $criteria->compare('id', $this->id);
        //$criteria->compare('patient', $this->patient);
        $criteria->compare('prescription', $this->prescription);
        $criteria->compare('invoice_date', $this->invoice_date, true);
        $criteria->compare('invoice_number', $this->invoice_number, true);
        $criteria->compare('invoice_by', $this->invoice_by);
        $criteria->compare('total_amount', $this->total_amount, true);
        $criteria->compare('patient_category_new', $this->patient_category_new);
        $criteria->compare('patient_category', $this->patient_category);
        $criteria->compare('comments', $this->comments, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('payment_status', $this->payment_status);
        $criteria->compare('created_on', $this->created_on, true);
        $criteria->compare('created_by', $this->created_by);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize20'],
            ),
            'sort' => array('defaultOrder' => 'invoice_date DESC, id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return InvoiceParent the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Generate Auto Invoice Number
     * @return type integer value
     */
    public static function generateInvoiceNumber()
    {
        $year = date('Y');
        $maxId = Yii::app()->db->createCommand()
            ->select('MAX(id)')
            ->from('{{invoice_parent}}')
            ->queryScalar();
        $autoValue = $maxId ? ((int) $maxId + 1) : 1;

        $return = 'INV#' . strtoupper(Yii::app()->user->name) . '-' . $year . '-' . $autoValue;
        return $return;
    }

    public static function visibleActions($id)
    {
        $model = InvoiceParent::model()->findByPk($id);
        if ($model->status == 1 || $model->status == 2) {
            return false;
        } else {
            return true;
        }
    }

    public static function visibleRollbackActions($id)
    {
        $model = InvoiceParent::model()->findByPk($id);
        if ($model->status == 1 || $model->status == 0) {
            return false;
        } else {
            return true;
        }
    }

    public static function visibleActionEdit($id)
    {
        $model = InvoiceParent::model()->findByPk($id);
        if ($model->status == 1) {
            return true;
        } else {
            return false;
        }
    }

    public static function getData($id, $field)
    {
        $value = InvoiceParent::model()->findByAttributes(array('id' => $id));
        if (empty($value->$field)) {
            return null;
        } else {
            return $value->$field;
        }
    }
}
