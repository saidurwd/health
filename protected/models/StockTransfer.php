<?php

/**
 * This is the model class for table "{{stock_transfer}}".
 *
 * The followings are the available columns in table '{{stock_transfer}}':
 * @property integer $id
 * @property integer $parent
 * @property integer $reference
 * @property integer $item
 * @property string $quantity
 * @property string $rate
 * @property string $total_amount
 * @property integer $store_from
 * @property integer $store_to
 * @property integer $batch
 * @property integer $created_by
 * @property string $created_on
 */
class StockTransfer extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{stock_transfer}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('item, quantity, store_from, store_to', 'required'),
            array('parent, reference, item, store_from, store_to, batch, created_by', 'numerical', 'integerOnly' => true),
            array('quantity, rate, total_amount', 'length', 'max' => 18),
            array('created_on', 'safe'),
            array('quantity', 'checkAvailability'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, parent, reference, item, quantity, rate, total_amount, store_from, store_to, batch, created_by, created_on', 'safe', 'on' => 'search'),
        );
    }
    
    public function checkAvailability($attribute, $params) {
        $store = $this->store_from;
        $item = $this->item;
        $batch = $this->batch;
        $quantity = $this->quantity;
        $avlQty = StockSummary::availableQty($store, $item, $batch);

        if ($quantity > $avlQty) {
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
            'parent0' => array(self::BELONGS_TO, 'StockTransferParent', 'parent'),
            'item0' => array(self::BELONGS_TO, 'Item', 'item'),
            'batch0' => array(self::BELONGS_TO, 'Batch', 'batch'),
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
            'item' => 'Item',
            'quantity' => 'Quantity',
            'rate' => 'Rate',
            'total_amount' => 'Amount',
            'store_from' => 'From Store',
            'store_to' => 'To Store',
            'batch' => 'Batch',
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
        $criteria->alias = 't';

        $criteria->compare('t.parent', 0);
        $criteria->compare('t.created_by', (int) Yii::app()->user->id);
        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.reference', $this->reference);
        $criteria->compare('t.item', $this->item);
        $criteria->compare('t.quantity', $this->quantity, true);
        $criteria->compare('t.rate', $this->rate, true);
        $criteria->compare('t.total_amount', $this->total_amount, true);
        $criteria->compare('t.store_from', $this->store_from);
        $criteria->compare('t.store_to', $this->store_to);
        $criteria->compare('t.batch', $this->batch);
        $criteria->compare('t.created_on', $this->created_on, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => false,
        ));
    }

    public function searchTransfer($id) {
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
        $criteria->compare('store_from', $this->store_from);
        $criteria->compare('store_to', $this->store_to);
        $criteria->compare('batch', $this->batch);
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
     * @return StockTransfer the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /*
     * get total item in specific PR
     */

    public static function getNumberOfItems($id) {
        $value = StockTransfer::model()->findAll(array('condition' => 'parent=' . (int) $id));
        return count($value);
    }

    /*
     * get total Amount in specific PR
     */

    public static function getTotalAmount($id) {
        $total = Yii::app()->db->createCommand()
                ->select('SUM(total_amount)')
                ->from('{{stock_transfer}}')
                ->where('parent = ' . (int) $id)
                ->queryScalar();
        return Product::number_format_currency($total, 2, Yii::app()->session->get('currency'));
    }

}
