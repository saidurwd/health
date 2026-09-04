<?php

/**
 * This is the model class for table "{{stock_issue}}".
 *
 * The followings are the available columns in table '{{stock_issue}}':
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
 * @property Store $store0
 */
class StockIssue extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{stock_issue}}';
    }

    public $parentOrderNumber;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('item, quantity', 'required'),
            array('parent, reference, item, store, batch, created_by', 'numerical', 'integerOnly' => true),
            array('quantity, rate, amount', 'length', 'max' => 18),
            array('created_on, comments, parentOrderNumber', 'safe'),
            array('quantity', 'checkAvailability', 'on' => 'insert'),
            array('quantity', 'checkAvailabilityEdit', 'on' => 'update'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, parent, reference, item, quantity, rate, amount, store, batch, created_by, created_on, comments, parentOrderNumber', 'safe', 'on' => 'search'),
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
        $model = StockIssue::model()->findByPk(((int) $this->id));
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
            'item0' => array(self::BELONGS_TO, 'Product', 'item'),
            'store0' => array(self::BELONGS_TO, 'Store', 'store'),
            'parent0' => array(self::BELONGS_TO, 'StockIssueParent', 'parent'),
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
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('created_on', $this->created_on);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
			'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize'],
            ),
        ));
    }

    public function searchIssue($id) {
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
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('created_on', $this->created_on);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
			'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize'],
            ),
        ));
    }

    public function searchByProject($id) {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->alias = 't';
        $criteria->with = array('parent0');
        $criteria->together = true;
        $criteria->compare('parent0.status', (int) 1);
        $criteria->addSearchCondition('parent0.issue_number', $this->parentOrderNumber, true);
        $criteria->compare('id', $this->id);
        $criteria->compare('parent', $this->parent);
        $criteria->compare('reference', $this->reference);
        $criteria->compare('item', $this->item);
        $criteria->compare('quantity', $this->quantity, true);
        $criteria->compare('rate', $this->rate, true);
        $criteria->compare('amount', $this->amount, true);
        $criteria->compare('store', $this->store);
        $criteria->compare('batch', $this->batch);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('created_on', $this->created_on);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize20'],
            ),
            'sort' => array('defaultOrder' => 'parent0.issue_date DESC')
        ));
    }

    public function searchByAssignment($id) {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->alias = 't';
        $criteria->with = array('parent0');
        $criteria->together = true;
        $criteria->compare('parent0.status', (int) 1);
        $criteria->addSearchCondition('parent0.issue_number', $this->parentOrderNumber, true);
        $criteria->compare('id', $this->id);
        $criteria->compare('parent', $this->parent);
        $criteria->compare('reference', $this->reference);
        $criteria->compare('item', $this->item);
        $criteria->compare('quantity', $this->quantity, true);
        $criteria->compare('rate', $this->rate, true);
        $criteria->compare('amount', $this->amount, true);
        $criteria->compare('store', $this->store);
        $criteria->compare('batch', $this->batch);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('created_on', $this->created_on);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize20'],
            ),
            'sort' => array('defaultOrder' => 'parent0.issue_date DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return StockIssue the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /*
     * get total item in specific Stock Issue
     */

    public static function getNumberOfItems($id) {
        $value = StockIssue::model()->findAll(array('condition' => 'parent=' . (int) $id));
        return count($value);
    }

    public static function getTotalAmount($parent) {
        $getAmount = Yii::app()->db->createCommand()
                ->select('ROUND((SUM(amount)),6)')
                ->from('{{stock_issue}}')
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

    public static function getReferences($id) {
        $model = Yii::app()->db->createCommand("SELECT SR.parent FROM {{stock_requisition}} SR WHERE SR.id IN(SELECT SI.reference FROM {{stock_issue}} SI WHERE SI.parent=" . (int) $id . " GROUP BY SI.reference) GROUP BY SR.parent")->queryAll();
        $total = count($model);
        $data = null;
        $i = 1;
        foreach ($model as $key => $value) {
            if ($total != $i) {
                $data .= StockRequisitionParent::getData($value['parent'], 'requisition_number') . ', ';
            } else {
                $data .= StockRequisitionParent::getData($value['parent'], 'requisition_number');
            }
            $i++;
        }
        return $data;
    }

}
