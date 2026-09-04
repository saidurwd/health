<?php

/**
 * This is the model class for table "{{purchase_order_history}}".
 *
 * The followings are the available columns in table '{{purchase_order_history}}':
 * @property integer $id
 * @property integer $po_number
 * @property integer $pr_number
 * @property integer $item
 * @property string $quantity
 * @property integer $converted
 * @property integer $created_by
 * @property string $created_on
 */
class PurchaseOrderHistory extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{purchase_order_history}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('item, created_by, created_on', 'required'),
            array('po_number, pr_number, item, converted, created_by', 'numerical', 'integerOnly' => true),
            array('quantity', 'length', 'max' => 18),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, po_number, pr_number, item, quantity, converted, created_by, created_on', 'safe', 'on' => 'search'),
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
            'po_number' => 'Purchase Order #',
            'pr_number' => 'Purchase Receive #',
            'item' => 'Item',
            'quantity' => 'Quantity',
            'converted' => 'Converted',
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
        $criteria->compare('po_number', $this->po_number);
        $criteria->compare('pr_number', $this->pr_number);
        $criteria->compare('item', $this->item);
        $criteria->compare('quantity', $this->quantity, true);
        $criteria->compare('converted', $this->converted);
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
     * @return PurchaseOrderHistory the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /*
     * get Available Quantity by PO Number
     */

    public static function getAvailableQuantity($id) {
        $model = PurchaseOrderHistory::model()->find(array('select' => 'SUM(quantity) AS quantity', 'condition' => 'po_number=' . (int) $id));
        return $model->quantity;
    }

}
