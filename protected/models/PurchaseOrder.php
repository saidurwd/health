<?php

/**
 * This is the model class for table "{{purchase_order}}".
 *
 * The followings are the available columns in table '{{purchase_order}}':
 * @property integer $id
 * @property integer $parent
 * @property integer $reference
 * @property integer $item
 * @property string $quantity
 * @property string $rate
 * @property string $total_amount
 *
 * The followings are the available model relations:
 * @property PurchaseOrderParent $parent0
 * @property Item $item0
 */
class PurchaseOrder extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{purchase_order}}';
    }

    public $parentOrderNumber;
    public $parentSupplier;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('item, quantity', 'required'),
            array('parent, reference, item, created_by, converted', 'numerical', 'integerOnly' => true),
            array('quantity, rate, total_amount', 'length', 'max' => 18),
            array('created_on, parentOrderNumber, parentSupplier', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, parent, reference, item, quantity, rate, total_amount, converted, created_by, created_on, parentOrderNumber, parentSupplier', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parent0' => array(self::BELONGS_TO, 'PurchaseOrderParent', 'parent'),
            'item0' => array(self::BELONGS_TO, 'Product', 'item'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'parent' => 'Parent',
            'reference' => 'Reference',
            'item' => 'Product',
            'quantity' => 'Quantity',
            'rate' => 'Rate',
            'total_amount' => 'Amount',
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
        $criteria->condition = 'parent=0 AND created_by=' . (int) Yii::app()->user->id;

        $criteria->compare('id', $this->id);
        $criteria->compare('parent', $this->parent);
        $criteria->compare('reference', $this->reference);
        $criteria->compare('item', $this->item);
        $criteria->compare('quantity', $this->quantity, true);
        $criteria->compare('rate', $this->rate, true);
        $criteria->compare('total_amount', $this->total_amount, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('created_on', $this->created_on);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => false,
        ));
    }

    public function searchOrder($id) {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->condition = 'parent=' . (int) $id;

        $criteria->compare('id', $this->id);
        $criteria->compare('parent', $this->parent);
        $criteria->compare('reference', $this->reference);
        $criteria->compare('item', $this->item);
        $criteria->compare('quantity', $this->quantity, true);
        $criteria->compare('rate', $this->rate, true);
        $criteria->compare('total_amount', $this->total_amount, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('created_on', $this->created_on);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => false,
        ));
    }

    /*
     * Search ordered item for purchase receive
     */

    public function search_purchase_receive() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->alias = 't';
        $criteria->with = array('parent0');
        $criteria->together = true;
        $criteria->compare('parent0.status', (int) 1);
        $criteria->addSearchCondition('parent0.order_number', $this->parentOrderNumber, true);
        $criteria->addSearchCondition('parent0.supplier', $this->parentSupplier, true);
        $criteria->compare('t.converted', 0);
        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.parent', $this->parent);
        $criteria->compare('t.reference', $this->reference);
        $criteria->compare('t.item', $this->item);
        $criteria->compare('t.quantity', $this->quantity, true);
        $criteria->compare('t.rate', $this->rate, true);
        $criteria->compare('t.total_amount', $this->total_amount, true);
        $criteria->compare('t.created_by', $this->created_by);
        $criteria->compare('t.created_on', $this->created_on, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => false,
//            'pagination' => array(
//                'pageSize' => Yii::app()->params['pageSize20'],
//            ),
            'sort' => array('defaultOrder' => 'parent0.order_date DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PurchaseOrder the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /*
     * get total item in specific PO
     */

    public static function getNumberOfItems($id) {
        $value = PurchaseOrder::model()->findAll(array('condition' => 'parent=' . (int) $id));
        return count($value);
    }

    /*
     * get Available Quantity by PO Number
     */

    public static function getAvailableQuantity($id) {
        $modelPO = PurchaseOrder::model()->findByPk($id);
        $modelPOH = PurchaseOrderHistory::model()->find(array('select' => 'IFNULL(SUM(quantity),0) AS quantity', 'condition' => 'po_number=' . (int) $id));
        return ($modelPO->quantity - $modelPOH->quantity);
    }

}
