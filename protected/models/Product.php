<?php

/**
 * This is the model class for table "{{product}}".
 *
 * The followings are the available columns in table '{{product}}':
 * @property integer $id
 * @property integer $category
 * @property string $title
 * @property string $product_code
 * @property string $description
 * @property integer $unit
 * @property string $threshold_value
 * @property string $minimum_storage_limit
 * @property integer $created_by
 * @property string $created_on
 *
 * The followings are the available model relations:
 * @property ProductCategory $category0
 * @property Unit $unit0
 */
class Product extends CActiveRecord {

    public $title;
    public $formal_name;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{product}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('category, title, unit', 'required'),
            array('category, unit, created_by', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            array('product_code', 'length', 'max' => 100),
            array('threshold_value, minimum_storage_limit', 'length', 'max' => 18),
            array('description, title, formal_name, created_on', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, category, title, product_code, description, unit, threshold_value, minimum_storage_limit, created_by, created_on, title, formal_name', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'category0' => array(self::BELONGS_TO, 'ProductCategory', 'category'),
            'unit0' => array(self::BELONGS_TO, 'Unit', 'unit'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'category' => 'Category',
            'title' => 'Product',
            'product_code' => 'Code',
            'description' => 'Description',
            'unit' => 'Unit',
            'threshold_value' => 'Threshold Value',
            'minimum_storage_limit' => 'Min Storage Limit',
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
        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.title', $this->title, true);
        $criteria->compare('t.product_code', $this->product_code, true);
        $criteria->compare('t.description', $this->description, true);
        $criteria->compare('t.threshold_value', $this->threshold_value, true);
        $criteria->compare('t.minimum_storage_limit', $this->minimum_storage_limit, true);
        $criteria->compare('t.created_by', $this->created_by);
        $criteria->compare('t.created_on', $this->created_on, true);
        $criteria->with = array('category0', 'unit0');
        $criteria->compare('category0.title', $this->category, true);
        $criteria->compare('unit0.formal_name', $this->unit, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize'],
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Product the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /*
     * Format example (22,222.00) for negative numbers
     * For positive 22,222.00
     */

    public static function number_format($value, $decimal_place) {
        if (is_numeric($value)) {
            if ($value < 0) {
                return "(" . number_format(abs($value), $decimal_place, '.', ',') . ")";
            } else {
                return number_format($value, $decimal_place, '.', ',');
            }
        } else {
            return $value;
        }
    }

    /*
     * Format example ($22,222.00) for negative numbers 
     * For positive $22,222.00 
     */

    public static function number_format_currency($value, $decimal_place, $currency) {
        if (is_numeric($value)) {
            if ($value < 0) {
                return "(" . $currency . number_format(abs($value), $decimal_place, '.', ',') . ")";
            } else {
                return $currency . number_format($value, $decimal_place, '.', ',');
            }
        } else {
            return $value;
        }
    }

    public static function number_format_currency_round($value, $decimal_place, $currency) {
        if (is_numeric($value)) {
            $value = round($value);
            if ($value < 0) {
                return "(" . $currency . number_format(abs($value), $decimal_place, '.', ',') . ")";
            } else {
                return $currency . number_format($value, $decimal_place, '.', ',');
            }
        } else {
            return $value;
        }
    }

    public static function getItemName($id) {
        $model = Product::model()->findByPk($id);
        if (!empty($model->title)) {
            return $model->title;
        } else {
            return null;
        }
    }

    /*
     * Count total item
     */

    public static function countTotalItems() {
        $value = Product::model()->findAll();
        return count($value);
    }

    public static function getItemUOM($itemID) {
        $oDbConnection = Yii::app()->db;
        $oCommand = $oDbConnection->createCommand('SELECT u.`formal_name`  FROM {{product}} i LEFT JOIN {{unit}} u ON i.unit=u.id WHERE i.id=' . (int) $itemID);
        if (!empty($oCommand->queryScalar())) {
            return $oCommand->queryScalar();
        } else {
            return 'N/A';
        }
        //return $oCommand->queryScalar();
    }

    public static function getItemList($model, $field) {
        $array = Product::model()->findAll(array('condition' => '', 'order' => 'title'));
        $option = '<select id="' . $model . '_' . $field . '" name="' . $model . '[' . $field . ']" class="select2">';
        $option .= '<option value="">Select a Product</option>';
        foreach ($array as $key => $value) {
            $option .= '<option value="' . $value["id"] . '">' . $value['title'] . " - " . $value['product_code'] . " (" . Product::getItemUOM($value["id"]) . ")" . '</option>';
        }
        $option .= '</select>';

        return $option;
    }

    public static function get_related_item_search($field, $id = 0) {
        $array = Product::model()->findAll(array('condition' => '', 'order' => 'title'));
        $option = '<select id="' . $field . '" name="' . $field . '" class="select2">';
        $option .= '<option value="">All Products</option>';
        foreach ($array as $key => $values) {
            if ($id == $values["id"]) {
                $option .= '<option selected="selected" value="' . $values["id"] . '" class="' . $values["category"] . '">' . $values["title"] . '</option>';
            } else {
                $option .= '<option value="' . $values["id"] . '" class="' . $values["category"] . '">' . $values["title"] . '</option>';
            }
        }
        $option .= '</select>';

        return $option;
    }

    public static function getData($id, $field) {
        $model = Product::model()->findByAttributes(array('id' => $id));
        if (empty($model->$field)) {
            return null;
        } else {
            return $model->$field;
        }
    }

    public static function getValues($id, $field) {
        $model = Product::model()->findByAttributes(array('id' => $id));
        if (empty($model->$field)) {
            return null;
        } else {
            return $model->$field;
        }
    }

    public static function getProductCategoryReport($name = 'product', $id=0) {
        $parent1 = ProductCategory::model()->findAll(array('condition' => 'parent=0 OR parent IS NULL', 'order' => "path"));
        $option = '<select id="' . $name . '" name="' . $name . '" class="select2">';
        $option .= '<option value="">Select a Product</option>';
        foreach ($parent1 as $key => $values1) {
            $option .= '<optgroup label="' . $values1["title"] . '">';
            $parent2 = Product::model()->findAll(array('condition' => 'category=' . (int) $values1["id"], 'order' => 'title'));
            foreach ($parent2 as $key => $values2) {
                if ($id == $values2["id"]) {
                    $option .= '<option selected="selected" value="' . $values2["id"] . '">' . $values2["title"] . '</option>';
                } else {
                    $option .= '<option value="' . $values2["id"] . '">' . $values2["title"] . '</option>';
                }
            }
            $option .= '</optgroup>';
        }
        $option .= '</select>';

        return $option;
    }

}
