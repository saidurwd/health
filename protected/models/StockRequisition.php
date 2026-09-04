<?php

/**
 * This is the model class for table "{{stock_requisition}}".
 *
 * The followings are the available columns in table '{{stock_requisition}}':
 * @property integer $id
 * @property integer $parent
 * @property integer $reference
 * @property integer $item
 * @property string $quantity
 * @property string $rate
 * @property string $amount
 * @property integer $store
 * @property integer $batch
 *
 * The followings are the available model relations:
 * @property Item $item0
 */
class StockRequisition extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{stock_requisition}}';
    }

    public $parentRequisitionNumber;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('store, item, quantity', 'required'),
            array('parent, reference, item, store, batch, created_by, converted', 'numerical', 'integerOnly' => true),
            array('quantity, rate, amount', 'length', 'max' => 18),
            array('created_on, comments, parentRequisitionNumber', 'safe'),
            array('quantity', 'checkAvailability', 'on' => 'insert'),
            array('quantity', 'checkAvailabilityEdit', 'on' => 'update'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, parent, reference, item, quantity, rate, amount, store, batch, created_by, created_on, converted, comments, parentRequisitionNumber', 'safe', 'on' => 'search'),
        );
    }

    public function checkAvailability($attribute, $params) {
        $store = $this->store;
        $item = $this->item;
        $batch = $this->batch;
        $quantity = $this->quantity;
        $avlQty = StockSummary::availableQty($store, $item, $batch);

        if ($quantity > $avlQty) {
            $this->addError($attribute, 'Sorry! Request quantity not available!');
        }
    }

    public function checkAvailabilityEdit($attribute, $params) {
        $model = StockRequisition::model()->findByPk(((int) $this->id));
        $store = $this->store;
        $item = $this->item;
        $batch = $this->batch;
        $quantity = $this->quantity;
        $available = StockSummary::availableQty($store, $item, $batch);
        $avlQty = $available + $model->quantity;

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
            'parent0' => array(self::BELONGS_TO, 'StockRequisitionParent', 'parent'),
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
            'amount' => 'Amount',
            'store' => 'Store',
            'batch' => 'Batch',
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
        $criteria->compare('amount', $this->amount, true);
        $criteria->compare('store', $this->store);
        $criteria->compare('batch', $this->batch);
        $criteria->compare('converted', $this->converted);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('created_on', $this->created_on);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function searchRequisition($id) {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->condition = 'parent=' . (int) $id;

        $criteria->compare('id', $this->id);
        $criteria->compare('parent', $this->parent);
        $criteria->compare('reference', $this->reference);
        $criteria->compare('item', $this->item);
        $criteria->compare('quantity', $this->quantity, true);
        $criteria->compare('rate', $this->rate, true);
        $criteria->compare('amount', $this->amount, true);
        $criteria->compare('store', $this->store);
        $criteria->compare('batch', $this->batch);
        $criteria->compare('converted', $this->converted);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('created_on', $this->created_on);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function search_stock_requisition() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->alias = 't';
        $criteria->with = array('parent0');
        $criteria->together = true;
        $criteria->compare('parent0.status', (int) 1);
        $criteria->addSearchCondition('parent0.requisition_number', $this->parentRequisitionNumber, true);
        $criteria->compare('t.converted', 0);
        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.parent', $this->parent);
        $criteria->compare('t.reference', $this->reference);
        $criteria->compare('t.item', $this->item);
        $criteria->compare('t.quantity', $this->quantity, true);
        $criteria->compare('t.rate', $this->rate, true);
        $criteria->compare('t.amount', $this->amount, true);
        $criteria->compare('t.store', $this->store);
        $criteria->compare('t.batch', $this->batch);
        $criteria->compare('t.converted', $this->converted);
        $criteria->compare('t.created_by', $this->created_by);
        $criteria->compare('t.created_on', $this->created_on);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize20'],
            ),
            'sort' => array('defaultOrder' => 'parent0.requisition_date DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return StockRequisition the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /*
     * get total item in specific Stock Requisition
     */

    public static function getNumberOfItems($id) {
        $value = StockRequisition::model()->findAll(array('condition' => 'parent=' . (int) $id));
        return count($value);
    }

    /**
     * Generate Item Sale rate
     * @return type integer value
     */
    public static function genarateItemRate($itemid, $store, $batch) {
        if (Yii::app()->params['RATEMETHODE'] == 'ACTUAL') {
            $getRate = Yii::app()->db->createCommand()
                    ->select('ROUND((SUM(pr.total_amount)/SUM(pr.quantity)),6) AS avgrage')
                    ->from('{{purchase_receive_parent}} prp')
                    ->join('{{purchase_receive}} pr', 'pr.parent=prp.id')
                    ->where('prp.status=1 AND pr.item=' . (int) $itemid . ' AND pr.store=' . (int) $store . ' AND pr.batch=' . (int) $batch)
                    ->queryScalar();
        }
        if (Yii::app()->params['RATEMETHODE'] == 'LIFO') {
            $getRate = Yii::app()->db->createCommand()
                    ->select('rate')
                    ->from('{{purchase_receive_parent}} prp')
                    ->join('{{purchase_receive}} pr', 'pr.parent=prp.id')
                    ->where('prp.status=1 AND pr.item=' . (int) $itemid)
                    ->order('pr.id DESC')
                    ->limit('1')
                    ->queryScalar();
        }
        if (Yii::app()->params['RATEMETHODE'] == 'FIFO') {
            $getRate = Yii::app()->db->createCommand()
                    ->select('rate')
                    ->from('{{purchase_receive_parent}} prp')
                    ->join('{{purchase_receive}} pr', 'pr.parent=prp.id')
                    ->where('prp.status=1 AND pr.item=' . (int) $itemid)
                    ->order('pr.id ASC')
                    ->limit('1')
                    ->queryScalar();
        }
        if (Yii::app()->params['RATEMETHODE'] == 'AVERAGE') {
            $getRate = Yii::app()->db->createCommand()
                    ->select('ROUND((SUM(pr.total_amount)/SUM(pr.quantity)),6) AS avgrage')
                    ->from('{{purchase_receive_parent}} prp')
                    ->join('{{purchase_receive}} pr', 'pr.parent=prp.id')
                    ->where('prp.status=1 AND pr.item=' . (int) $itemid)
                    ->queryScalar();
        }
        return $getRate;
    }

    /**
     * Generate Item Buy rate
     * @return type integer value
     */
    public static function genarateItemBuyRate($itemid, $store, $batch) {
        if (Yii::app()->params['RATEMETHODE'] == 'ACTUAL') {
            $getRate = Yii::app()->db->createCommand()
                    ->select('ROUND((SUM(pr.buy_amount)/SUM(pr.quantity)),6) AS avgrage')
                    ->from('{{purchase_receive_parent}} prp')
                    ->join('{{purchase_receive}} pr', 'pr.parent=prp.id')
                    ->where('prp.status=1 AND pr.item=' . (int) $itemid . ' AND pr.store=' . (int) $store . ' AND pr.batch=' . (int) $batch)
                    ->queryScalar();
        }
        if (Yii::app()->params['RATEMETHODE'] == 'LIFO') {
            $getRate = Yii::app()->db->createCommand()
                    ->select('buy_rate')
                    ->from('{{purchase_receive_parent}} prp')
                    ->join('{{purchase_receive}} pr', 'pr.parent=prp.id')
                    ->where('prp.status=1 AND pr.item=' . (int) $itemid)
                    ->order('pr.id DESC')
                    ->limit('1')
                    ->queryScalar();
        }
        if (Yii::app()->params['RATEMETHODE'] == 'FIFO') {
            $getRate = Yii::app()->db->createCommand()
                    ->select('buy_rate')
                    ->from('{{purchase_receive_parent}} prp')
                    ->join('{{purchase_receive}} pr', 'pr.parent=prp.id')
                    ->where('prp.status=1 AND pr.item=' . (int) $itemid)
                    ->order('pr.id ASC')
                    ->limit('1')
                    ->queryScalar();
        }
        if (Yii::app()->params['RATEMETHODE'] == 'AVERAGE') {
            $getRate = Yii::app()->db->createCommand()
                    ->select('ROUND((SUM(pr.buy_amount)/SUM(pr.quantity)),6) AS avgrage')
                    ->from('{{purchase_receive_parent}} prp')
                    ->join('{{purchase_receive}} pr', 'pr.parent=prp.id')
                    ->where('prp.status=1 AND pr.item=' . (int) $itemid)
                    ->queryScalar();
        }
        return $getRate;
    }

    public static function getItemList($model, $field) {
        $array = Product::model()->findAll(array('condition' => '', 'order' => 'title'));
        $option = '<select id="' . $model . '_' . $field . '" name="' . $model . '[' . $field . ']" class="select2">';
        $option .= '<option value="">Select a Product</option>';
        foreach ($array as $key => $value) {
            //$option .='<option value="' . $value["id"] . '">' . $value['title'] . '</option>';
            $option .= '<option value="' . $value["id"] . '">' . $value['title'] . " [" . Product::getItemUOM($value["id"]) . "]" . '</option>';
        }
        $option .= '</select>';

        return $option;
    }

    public static function getQtyPendingStore($item, $store) {
        $connection = Yii::app()->db;
        $command = $connection->createCommand('SELECT IFNULL(SUM(quantity),0) AS total 
                                            FROM {{invoice}}  
                                            WHERE parent=0 AND item=' . $item . ' AND store=' . $store . ' GROUP BY item, store');
        $result = $command->queryScalar();

        $command2 = $connection->createCommand('SELECT IFNULL(SUM(inv.quantity),0) AS total 
                                            FROM {{invoice}} inv 
                                            LEFT OUTER JOIN {{invoice_parent}} invp ON inv.parent=invp.id 
                                            WHERE invp.status=0 AND inv.item=' . $item . ' AND inv.store=' . $store . ' GROUP BY inv.item, inv.store');
        $result2 = $command2->queryScalar();

        return $result + $result2;
    }

    public static function getStoreList($model, $field, $placeholder) {
        $array = StockSummary::model()->findAll(array('select' => 'store, item, SUM(quantity) AS quantity', 'condition' => '', 'group' => 'item, store'));
        $return = '<select id="' . $model . '_' . $field . '" name="' . $model . '[' . $field . ']" class="select2">';
        $return .= '<option value="">' . $placeholder . '</option>';
        foreach ($array as $key => $value) {
            $total_quantity = ($value["quantity"] - StockRequisition::getQtyPendingStore($value["item"], $value["store"]));
            $return .= '<option value="' . $value["store"] . '" class="' . $value["item"] . '">' . Store::getData($value["store"], "alias") . ' [' . Product::number_format($total_quantity, 2) . ']</option>';
        }
        $return .= '</select>';

        return $return;
    }

    public static function getQtyPendingBatch($item, $store, $batch) {
        if (empty($item))
            $item = 0;
        if (empty($store))
            $store = 0;
        if (empty($batch))
            $batch = 0;

        $connection = Yii::app()->db;
        $command = $connection->createCommand('SELECT IFNULL(SUM(quantity),0) AS total 
                                            FROM {{invoice}} 
                                            WHERE parent=0 AND item=' . $item . ' AND store=' . $store . ' AND batch=' . $batch . ' GROUP BY item, store, batch');
        $result = $command->queryScalar();

        $command2 = $connection->createCommand('SELECT IFNULL(SUM(inv.quantity),0) AS total 
                                            FROM {{invoice}} inv 
											LEFT OUTER JOIN {{invoice_parent}} invp ON inv.parent=invp.id 
                                            WHERE invp.status=0 AND inv.item=' . $item . ' AND inv.store=' . $store . ' GROUP BY inv.item, inv.store');
        $result2 = $command2->queryScalar();

        return $result + $result2;
    }

    public static function getBatchList($model, $field) {
        $array = StockSummary::model()->findAll(array('condition' => 'quantity>0'));
        $return = '<select id="' . $model . '_' . $field . '" name="' . $model . '[' . $field . ']" class="">'; //select2
        $return .= '<option value="">Select a Batch</option>';
        foreach ($array as $key => $value) {
            $total_quantity = ($value["quantity"] - StockRequisition::getQtyPendingBatch($value["item"], $value["store"], $value["batch"]));
            if ((time() - (60 * 60 * 24)) < strtotime(Batch::getExpiryDate($value["batch"]))) {
                $return .= '<option style="color:green;" value="' . $value["batch"] . '" class="' . $value["item"] . '\\' . $value["store"] . '">' . Batch::getBatch($value["batch"]) . ' [' . Batch::getExpiryDate($value["batch"]) . '] ' . ' [' . Product::number_format($total_quantity, 2) . Product::getItemUOM($value["item"]) . ']</option>';
            } else {
                $return .= '<option style="color:red;" value="' . $value["batch"] . '" class="' . $value["item"] . '\\' . $value["store"] . '">' . Batch::getBatch($value["batch"]) . ' [' . Batch::getExpiryDate($value["batch"]) . '] ' . ' [' . Product::number_format($total_quantity, 2) . Product::getItemUOM($value["item"]) . ']</option>';
            }
        }
        $return .= '</select>';

        return $return;
    }

    /*
     * get total Amount in specific Reqiosition 
     */

    public static function getTotalAmount($id) {
        $total = Yii::app()->db->createCommand()
                ->select('SUM(amount)')
                ->from('{{stock_requisition}}')
                ->where('parent = ' . (int) $id)
                ->queryScalar();
        return Product::number_format_currency($total, 2, Yii::app()->session->get('currency'));
    }

    /*
     * get Available Quantity by Req Number
     */

    public static function getAvailableQuantity($id) {
        $modelSR = StockRequisition::model()->findByPk($id);
        $modelSRH = StockRequisitionHistory::model()->find(array('select' => 'IFNULL(SUM(quantity),0) AS quantity', 'condition' => 'requisition_number=' . (int) $id));
        return ($modelSR->quantity - $modelSRH->quantity);
    }

}
