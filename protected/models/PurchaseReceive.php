<?php

/**
 * This is the model class for table "{{purchase_receive}}".
 *
 * The followings are the available columns in table '{{purchase_receive}}':
 * @property integer $id
 * @property integer $parent
 * @property integer $reference
 * @property integer $item
 * @property string $quantity
 * @property string $rate
 * @property string $total_amount
 * @property integer $batch
 *
 * The followings are the available model relations:
 * @property PurchaseReceiveParent $parent0
 * @property Item $item0
 */
class PurchaseReceive extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{purchase_receive}}';
    }

    public $uom;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('item, quantity, store', 'required'),
            array('parent, reference, item, store, batch, created_by', 'numerical', 'integerOnly' => true),
            array('quantity, rate, total_amount, buy_rate, buy_amount', 'length', 'max' => 18),
            array('created_on, uom', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, parent, reference, item, quantity, rate, total_amount, buy_rate, buy_amount, store, batch', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parent0' => array(self::BELONGS_TO, 'PurchaseReceiveParent', 'parent'),
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
            'item' => 'Product',
            'quantity' => 'Quantity',
            'rate' => 'Sale Rate',
            'total_amount' => 'Sale Amount',
            'buy_rate' => 'Buy Rate',
            'buy_amount' => 'Buy Amount',
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
        $criteria->alias = 't';
//        $criteria->with = array('batch0');
//        $criteria->together = true;
        $criteria->compare('t.parent', 0);
        $criteria->compare('t.created_by', (int) Yii::app()->user->id);
        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.parent', $this->parent);
        $criteria->compare('t.reference', $this->reference);
        $criteria->compare('t.item', $this->item);
        $criteria->compare('t.quantity', $this->quantity, true);
        $criteria->compare('t.rate', $this->rate, true);
        $criteria->compare('t.total_amount', $this->total_amount, true);
        $criteria->compare('t.buy_rate', $this->buy_rate, true);
        $criteria->compare('t.buy_amount', $this->buy_amount, true);
        $criteria->compare('t.store', $this->store);
        $criteria->compare('t.batch', $this->batch);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => false,
        ));
    }

    public function searchReceive($id) {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->condition = 'parent=' . (int) $id;

        $criteria->compare('id', $this->id);
        //$criteria->compare('parent', $this->parent);
        $criteria->compare('reference', $this->reference);
        $criteria->compare('item', $this->item);
        $criteria->compare('quantity', $this->quantity, true);
        $criteria->compare('rate', $this->rate, true);
        $criteria->compare('total_amount', $this->total_amount, true);
        $criteria->compare('t.buy_rate', $this->buy_rate, true);
        $criteria->compare('t.buy_amount', $this->buy_amount, true);
        $criteria->compare('store', $this->store);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => false,
        ));
    }

    public function searchReceivePrice() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('parent', $this->parent);
        $criteria->compare('reference', $this->reference);
        $criteria->compare('item', $this->item);
        $criteria->compare('quantity', $this->quantity, true);
        $criteria->compare('rate', $this->rate, true);
        $criteria->compare('total_amount', $this->total_amount, true);
        $criteria->compare('buy_rate', $this->buy_rate, true);
        $criteria->compare('buy_amount', $this->buy_amount, true);
        $criteria->compare('store', $this->store);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize50'],
            ),
            'sort' => array('defaultOrder' => 'created_on DESC, item ASC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PurchaseReceive the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /*
     * get total item in specific PR
     */

    public static function getNumberOfItems($id) {
        $value = PurchaseReceive::model()->findAll(array('condition' => 'parent=' . (int) $id));
        return count($value);
    }

    /*
     * get total Amount in specific PR
     */

    public static function getTotalAmount($id) {
        $total = Yii::app()->db->createCommand()
                ->select('SUM(total_amount)')
                ->from('{{purchase_receive}}')
                ->where('parent = ' . (int) $id)
                ->queryScalar();
        return Product::number_format_currency($total, 2, Yii::app()->session->get('currency'));
    }

    public static function getTotalAmountBuy($id) {
        $total = Yii::app()->db->createCommand()
                ->select('SUM(buy_amount)')
                ->from('{{purchase_receive}}')
                ->where('parent = ' . (int) $id)
                ->queryScalar();
        return Product::number_format_currency($total, 2, Yii::app()->session->get('currency'));
    }

    public static function getBatchData($id, $field) {
        $model = Batch::model()->findByPk($id);
        if ($model === null) {
            return null;
        } else {
            if ($field == 'expiry') {
                return User::get_date($model->$field);
            } else {
                return $model->$field;
            }
        }
    }

    /*
     * PurchaseReceive::remove_array_item($expression, '1'); // remove id called '1'
     */

    public static function remove_array_item($array, $item) {
        $index = array_search($item, $array);
        if ($index !== false) {
            unset($array[$index]);
        }

        return $array;
    }

    public static function getItemUom($model, $field) {
        $uom = Yii::app()->db->createCommand()
                ->select('uom')
                ->from('{{item}}')
                ->where('id = ' . $field)
                ->queryScalar();
        return Product::getItemUOM($uom);
    }

    public static function fileUpload($id) {
        $value = PurchaseReceiveDocument::model()->findAll(array('condition' => 'receive_number=' . (int) $id));
        $total = count($value);
        return CHtml::link('<span class="btn-label"><i class="fa fa-upload"></i></span> ' . $total . ' file(s)', 'javascript:void(0)', array('onclick' => 'renderFileUpload(' . (int) $id . ');', 'class' => 'btn btn-labeled btn-default btn-sm', 'title' => 'Upload'));
    }

    public static function fileDownload($id) {
        $value = PurchaseReceiveDocument::model()->findAll(array('condition' => 'receive_number=' . (int) $id));
        $total = count($value);
        if ($total > 0) {
            return CHtml::link('<span class="btn-label"><i class="fa fa-download"></i></span> ' . $total . ' file(s)', array('downloadall', 'id' => $id), array('class' => 'btn btn-labeled btn-default btn-sm', 'title' => 'Download'));
        } else {
            return CHtml::link('<span class="btn-label"><i class="fa fa-download"></i></span> ' . $total . ' file(s)', 'javascript:void(0);', array('class' => 'btn btn-labeled btn-default btn-sm disabled'));
        }
    }

    public static function getReferences($id) {
        $model = Yii::app()->db->createCommand("SELECT PO.parent FROM {{purchase_order}} PO WHERE PO.id IN(SELECT PR.reference FROM {{purchase_receive}} PR WHERE PR.parent=" . (int) $id . " GROUP BY PR.reference) GROUP BY PO.parent")->queryAll();
        $total = count($model);
        $data = null;
        $i = 1;
        foreach ($model as $key => $value) {
            if ($total != $i) {
                $data .= PurchaseOrderParent::getData($value['parent'], 'order_number') . ', ';
            } else {
                $data .= PurchaseOrderParent::getData($value['parent'], 'order_number');
            }
            $i++;
        }
        return $data;
    }

}
