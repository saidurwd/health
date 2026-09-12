<?php

/**
 * This is the model class for table "{{invoice}}".
 *
 * The followings are the available columns in table '{{invoice}}':
 * @property integer $id
 * @property integer $parent
 * @property integer $service
 * @property integer $item
 * @property string $quantity
 * @property string $rate
 * @property string $amount
 * @property integer $store
 * @property integer $batch
 *
 * The followings are the available model relations:
 * @property Item $item0
 * @property Store $store0
 */
class Invoice extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{invoice}}';
    }

    public $parentOrderNumber;
    public $service;
    public $total;
//    public $discountstatus;
    public $discounttype;
    public $discountamount;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('quantity', 'required'),
            array('parent, service, item, store, batch, created_by, discountamount', 'numerical', 'integerOnly' => true),
            array('quantity, rate, discount, amount', 'length', 'max' => 18),
            array('servicetype', 'length', 'max' => 100),
            array('note', 'length', 'max' => 400),
            array('created_on, comments, parentOrderNumber, service, discounttype', 'safe'),
            array('quantity', 'checkAvailability', 'on' => 'insert'),
            array('quantity', 'checkAvailabilityEdit', 'on' => 'update'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, parent, servicetype, service, item, quantity, rate, discount, amount, store, batch, note, created_by, created_on, comments, parentOrderNumber, discountamount', 'safe', 'on' => 'search'),
        );
    }

    public function checkAvailability($attribute, $params) {
        $store = $this->store;
        $item = $this->item;
        $batch = $this->batch;
        $quantity = $this->quantity;
        $invoice_pending = StockRequisition::getQtyPendingBatch($item, $store, $batch);
        $avlQty = StockSummary::availableQty($store, $item, $batch) - $invoice_pending;

        if ($quantity > $avlQty && $this->servicetype == 'Medicine') {
            $this->addError($attribute, 'Sorry! Request quantity not available!');
        }
    }

    public function checkAvailabilityEdit($attribute, $params) {
        $model = Invoice::model()->findByPk(((int) $this->id));
        $store = $this->store;
        $item = $this->item;
        $batch = $this->batch;
        $quantity = $this->quantity;
        $invoice_pending = StockRequisition::getQtyPendingBatch($item, $store, $batch);
        $available = StockSummary::availableQty($store, $item, $batch) - $invoice_pending;
        $avlQty = $available + $model->quantity;

        if ($quantity > $avlQty && $this->servicetype == 'Medicine') {
            $this->addError($attribute, 'Sorry! Request quantity not available!');
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'item0' => array(self::BELONGS_TO, 'Product', 'item'),
            'store0' => array(self::BELONGS_TO, 'Store', 'store'),
            'parent0' => array(self::BELONGS_TO, 'InvoiceParent', 'parent'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'parent' => 'Parent',
            'service' => 'Service',
            'item' => 'Product',
            'quantity' => 'Quantity',
            'rate' => 'Rate',
            'discount' => 'Discount',
            'amount' => 'Amount',
            'store' => 'Store',
            'batch' => 'Batch',
            'note' => 'Note',
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
        $criteria->condition = 'parent=0 AND created_by=' . (int) Yii::app()->user->id;

        $criteria->compare('id', $this->id);
        $criteria->compare('parent', $this->parent);
        $criteria->compare('servicetype', $this->servicetype);
        $criteria->compare('service', $this->service);
        $criteria->compare('item', $this->item);
        $criteria->compare('quantity', $this->quantity, true);
        $criteria->compare('rate', $this->rate, true);
        $criteria->compare('discount', $this->discount, true);
        $criteria->compare('amount', $this->amount, true);
        $criteria->compare('store', $this->store);
        $criteria->compare('batch', $this->batch);
        $criteria->compare('note', $this->note, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('created_on', $this->created_on);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize100'],
            ),
        ));
    }

    public function searchInvoice($id) {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->condition = 'parent=' . (int) $id;

        $criteria->compare('id', $this->id);
        $criteria->compare('parent', $this->parent);
        $criteria->compare('servicetype', $this->servicetype);
        $criteria->compare('service', $this->service);
        $criteria->compare('item', $this->item);
        $criteria->compare('quantity', $this->quantity, true);
        $criteria->compare('rate', $this->rate, true);
        $criteria->compare('discount', $this->discount, true);
        $criteria->compare('amount', $this->amount, true);
        $criteria->compare('store', $this->store);
        $criteria->compare('batch', $this->batch);
        $criteria->compare('note', $this->note, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('created_on', $this->created_on);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize100'],
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Invoice the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /*
     * get total item in specific Invoice
     */

    public static function getNumberOfItems($id) {
        $value = Invoice::model()->findAll(array('condition' => 'parent=' . (int) $id));
        return count($value);
    }

    public static function getTotalAmount($parent) {
        $getAmount = Yii::app()->db->createCommand()
                ->select('ROUND((SUM(amount)),6)')
                ->from('{{invoice}}')
                ->where('parent=' . (int) $parent)
                ->queryScalar();
        return $getAmount;
    }

    public static function getTotalDiscount($parent) {
        $getAmount = Yii::app()->db->createCommand()
                ->select('ROUND((SUM(discount)),6)')
                ->from('{{invoice}}')
                ->where('parent=' . (int) $parent)
                ->queryScalar();
        return $getAmount;
    }

    public function getTotalFooter($records, $colName) {
        $total = 0.0;
        if (count($records) > 0) {
            foreach ($records as $record) {
                $total += $record->$colName;
            }
        }
        //return number_format($total, 2);
        return Product::number_format_currency($total, 2, Yii::app()->session->get('currency'));
    }

    public static function getData($id, $field) {
        $model = Invoice::model()->findByAttributes(array('id' => $id));
        if (empty($model->$field)) {
            return null;
        } else {
            return $model->$field;
        }
    }

}
