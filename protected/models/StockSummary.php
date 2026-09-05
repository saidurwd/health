<?php

/**
 * This is the model class for table "{{stock_summary}}".
 *
 * The followings are the available columns in table '{{stock_summary}}':
 * @property integer $id
 * @property integer $store
 * @property integer $item
 * @property integer $batch
 * @property string $quantity
 *
 * The followings are the available model relations:
 * @property Store $store0
 * @property Item $item0
 */
class StockSummary extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{stock_summary}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('store,item', 'required'),
            array('store, item, batch', 'numerical', 'integerOnly' => true),
            array('quantity,rate,amount', 'length', 'max' => 18),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, store, item, batch, quantity,rate,amount', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'store0' => array(self::BELONGS_TO, 'Store', 'store'),
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
            'store' => 'Store',
            'item' => 'Item',
            'batch' => 'Batch',
            'quantity' => 'Quantity',
            'rate' => 'Rate',
            'amount' => 'Amount',
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
        $criteria->compare('store', $this->store);
        $criteria->compare('item', $this->item);
        $criteria->compare('batch', $this->batch);
        $criteria->compare('quantity', $this->quantity, true);
        $criteria->compare('rate', $this->rate, true);
        $criteria->compare('amount', $this->amount, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return StockSummary the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /*
     * update Stock Summary 
     * when purchase receeive approved
     */

    public static function receiveStockSummary($store, $item, $batch, $quantity) {
        $rate = StockRequisition::genarateItemRate($item, $store, $batch);
        if (($model = StockSummary::model()->find(array('condition' => 'store=' . (int) $store . ' AND batch=' . (int) $batch . ' AND item=' . (int) $item))) === null) {
            $model = new StockSummary;
            $model->store = (int) $store;
            $model->item = (int) $item;
            $model->batch = (int) $batch;
            $model->quantity = $quantity;
            $model->rate = $rate;
            $model->amount = round(($model->quantity * $rate), 2);
            $model->save();
        } else {
            $model->quantity = $model->quantity + $quantity;
            $model->rate = $rate;
            $model->amount = round(($model->quantity * $rate), 2);
            $model->save();
        }

        Yii::app()->cache->delete('StockRequisition_StoreList');
        Yii::app()->cache->delete('StockRequisition_BatchList');
    }

    /*
     * update Stock Summary 
     * when stock issue approved
     */

    public static function issueStockSummary($store, $item, $batch, $quantity) {
        $rate = StockRequisition::genarateItemRate($item, $store, $batch);
        if (($model = StockSummary::model()->find(array('condition' => 'store=' . (int) $store . ' AND batch=' . (int) $batch . ' AND item=' . (int) $item))) === null) {
            $model = new StockSummary;
            $model->store = (int) $store;
            $model->item = (int) $item;
            $model->batch = (int) $batch;
            $model->quantity = $quantity;
            $model->rate = $rate;
            $model->amount = round(($model->quantity * $rate), 2);
            $model->save();
        } else {
            $model->quantity = $model->quantity - $quantity;
            $model->rate = $rate;
            $model->amount = round(($model->quantity * $model->rate), 2);
            $model->save();
        }

        Yii::app()->cache->delete('StockRequisition_StoreList');
        Yii::app()->cache->delete('StockRequisition_BatchList');
    }

    public static function getAvailableItem($store, $item) {
        $model = StockSummary::model()->find(array('select' => 'SUM(quantity) AS quantity', 'condition' => 'store=' . (int) $store . ' AND item=' . (int) $item));
        return Product::number_format($model->quantity, 2) . Product::getItemUOM($item);
    }

    public static function getAvailableItemBatch($store, $item, $batch) {
        $model = StockSummary::model()->find(array('condition' => 'store=' . (int) $store . ' AND item=' . (int) $item . ' AND batch=' . (int) $batch));
        return Product::number_format($model->quantity, 2) . Product::getItemUOM($item);
    }

    public static function getExpireAmount($days) {
        $oDbConnection = Yii::app()->db;
        $oCommand = $oDbConnection->createCommand('SELECT ROUND(IFNULL(SUM(ss.`amount`),0),2)
                                                    FROM {{stock_summary}} ss 
                                                    LEFT JOIN {{batch}} bt ON ss.batch=bt.id   
                                                    WHERE DATEDIFF(bt.end_date, CURDATE())<=' . (int) $days . ' AND DATEDIFF(bt.end_date, CURDATE())>=0');

        return $oCommand->queryScalar();
    }

    public static function availableQty($store, $item, $batch) {
        $model = StockSummary::model()->find(array('condition' => 'store=' . (int) $store . ' AND batch=' . (int) $batch . ' AND item=' . (int) $item));
        if (empty($model->quantity)) {
            return 0;
        } else {
            return $model->quantity;
        }
    }

    public static function getInventoryStatus() {
        $model = StockSummary::model()->find(array('select' => 'IFNULL(ROUND(SUM(amount),2),0) AS amount', 'condition' => ''));
        return $model->amount;
    }

    public static function getMaterialreceived() {
        $connection = Yii::app()->db;
        $command = $connection->createCommand('SELECT IFNULL(ROUND(SUM(pr.total_amount),2),0) AS total_amount
                                                FROM {{purchase_receive}} pr
                                                LEFT OUTER JOIN {{purchase_receive_parent}} prp ON pr.parent=prp.id 
                                                WHERE prp.status = 1');
        $grand_total = $command->queryScalar();

        return $grand_total;
    }

    public static function getMaterialOrdered() {
        $connection = Yii::app()->db;
        $command = $connection->createCommand('SELECT IFNULL(ROUND(SUM(po.total_amount),2),0) AS total_amount
                                                FROM {{purchase_order}} po
                                                LEFT OUTER JOIN {{purchase_order_parent}} pop ON po.parent=pop.id 
                                                WHERE pop.status = 1');
        $grand_total = $command->queryScalar();

        return $grand_total;
    }

}
