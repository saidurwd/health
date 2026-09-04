<?php

/**
 * This is the model class for table "{{product_category}}".
 *
 * The followings are the available columns in table '{{product_category}}':
 * @property integer $id
 * @property integer $parent
 * @property string $title
 * @property string $alias
 * @property string $description
 * @property string $path
 *
 * The followings are the available model relations:
 * @property Product[] $products
 */
class ProductCategory extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{product_category}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title', 'required'),
            array('parent', 'numerical', 'integerOnly' => true),
            array('title, alias', 'length', 'max' => 250),
            array('path', 'length', 'max' => 150),
            array('description', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, parent, title, alias, description, path', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'products' => array(self::HAS_MANY, 'Product', 'category'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'parent' => 'Parent',
            'title' => 'Category',
            'alias' => 'Alias',
            'description' => 'Description',
            'path' => 'Path',
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
        $criteria->compare('parent', $this->parent);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('alias', $this->alias, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('path', $this->path, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize'],
            ),
            'sort' => array('defaultOrder' => 'path')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ProductCategory the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getData($id, $field) {
        $model = ProductCategory::model()->findByAttributes(array('id' => $id));
        if (empty($model->$field)) {
            return null;
        } else {
            return $model->$field;
        }
    }

    public static function update_path($id) {
        $model = ProductCategory::model()->findByPk($id);
        if ($model->parent == 0 || $model->parent === null) {
            $model->path = '0.' . $model->id;
            $model->save();
        } else {
            $parent = ProductCategory::model()->findByAttributes(array('id' => $model->parent));
            $model->path = $parent->path . '.' . $model->id;
            $model->save();
        }
    }

    public static function update_alias($id) {
        $model = ProductCategory::model()->findByPk($id);
        if ($model->parent == 0 || $model->parent === null) {
            $model->alias = $model->title;
            $model->save();
        } else {
            $parent = ProductCategory::model()->findByAttributes(array('id' => $model->parent));
            $model->alias = $parent->alias . '/' . $model->title;
            $model->save();
        }
    }

    public static function get_full_path($id) {
        $model = ProductCategory::model()->findByPk($id);
        $array = explode('.', $model->path);
        $total = count($array);
        $data = null;
        $i = 1;
        foreach ($array as $key => $value) {
            if ($value > 0) {
                if ($total != $i) {
                    $data .= ProductCategory::getData($value, 'title') . ' <i class="fa fa-angle-double-right text-info"></i> ';
                } else {
                    $data .= ProductCategory::getData($value, 'title');
                }
            }
            $i++;
        }
        return $data;
    }

    public static function getProductCategorySearch($id) {
        $parent1 = ProductCategory::model()->findAll(array('condition' => 'parent=0 OR parent IS NULL', 'order' => 'path'));
        $option = '<select id="categoryid" name="categoryid" class="select2">';
        $option .= '<option value="">All Categories</option>';
        foreach ($parent1 as $key => $values1) {
            if ($id == $values1["id"]) {
                $option .= '<option selected="selected" value="' . $values1["id"] . '">' . $values1["title"] . '</option>';
            } else {
                $option .= '<option value="' . $values1["id"] . '">' . $values1["title"] . '</option>';
            }
            $parent2 = ProductCategory::model()->findAll(array('condition' => 'parent=' . (int) $values1["id"], 'order' => 'path'));
            foreach ($parent2 as $key => $values2) {
                if ($id == $values2["id"]) {
                    $option .= '<option selected="selected" value="' . $values2["id"] . '" class="text-success">&nbsp;&nbsp;' . $values2["title"] . '</option>';
                } else {
                    $option .= '<option value="' . $values2["id"] . '" class="text-success">&nbsp;&nbsp;' . $values2["title"] . '</option>';
                }
                $parent3 = ProductCategory::model()->findAll(array('condition' => 'parent=' . (int) $values2["id"], 'order' => 'path'));
                foreach ($parent3 as $key => $values3) {
                    if ($id == $values3["id"]) {
                        $option .= '<option selected="selected" value="' . $values3["id"] . '" class="text-danger">&nbsp;&nbsp;&nbsp;&nbsp;' . $values3["title"] . '</option>';
                    } else {
                        $option .= '<option value="' . $values3["id"] . '" class="text-danger">&nbsp;&nbsp;&nbsp;&nbsp;' . $values3["title"] . '</option>';
                    }
                    $parent4 = ProductCategory::model()->findAll(array('condition' => 'parent=' . (int) $values3["id"], 'order' => 'path'));
                    foreach ($parent4 as $key => $values4) {
                        if ($id == $values4["id"]) {
                            $option .= '<option selected="selected" value="' . $values4["id"] . '" class="text-warning">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $values4["title"] . '</option>';
                        } else {
                            $option .= '<option value="' . $values4["id"] . '" class="text-warning">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $values4["title"] . '</option>';
                        }
                    }
                }
            }
        }
        $option .= '</select>';

        return $option;
    }

    public static function getProductCategory($model, $field, $id) {
        $parent1 = ProductCategory::model()->findAll(array('condition' => 'parent=0 OR parent IS NULL', "order" => "path"));
        $option = '<select id="' . $model . '_' . $field . '" name="' . $model . '[' . $field . ']" class="select2">';
        $option .= '<option value="">Select a Category</option>';
        foreach ($parent1 as $key => $values1) {
            if ($id == $values1["id"]) {
                $option .= '<option selected="selected" value="' . $values1["id"] . '">' . $values1["title"] . '</option>';
            } else {
                $option .= '<option value="' . $values1["id"] . '">' . $values1["title"] . '</option>';
            }
            $parent2 = ProductCategory::model()->findAll(array('condition' => 'parent=' . (int) $values1["id"], 'order' => 'path'));
            foreach ($parent2 as $key => $values2) {
                if ($id == $values2["id"]) {
                    $option .= '<option selected="selected" value="' . $values2["id"] . '" class="text-success space-left-30">' . $values2["title"] . '</option>';
                } else {
                    $option .= '<option value="' . $values2["id"] . '" class="text-success space-left-30">' . $values2["title"] . '</option>';
                }
                $parent3 = ProductCategory::model()->findAll(array('condition' => 'parent=' . (int) $values2["id"], 'order' => 'path'));
                foreach ($parent3 as $key => $values3) {
                    if ($id == $values3["id"]) {
                        $option .= '<option selected="selected" value="' . $values3["id"] . '" class="text-danger space-left-60">' . $values3["title"] . '</option>';
                    } else {
                        $option .= '<option value="' . $values3["id"] . '" class="text-danger space-left-60">' . $values3["title"] . '</option>';
                    }
                    $parent4 = ProductCategory::model()->findAll(array('condition' => 'parent=' . (int) $values3["id"], 'order' => 'path'));
                    foreach ($parent4 as $key => $values4) {
                        if ($id == $values4["id"]) {
                            $option .= '<option selected="selected" value="' . $values4["id"] . '" class="text-warning space-left-90">' . $values4["title"] . '</option>';
                        } else {
                            $option .= '<option value="' . $values4["id"] . '" class="text-warning space-left-90">' . $values4["title"] . '</option>';
                        }
                    }
                }
            }
        }
        $option .= '</select>';

        return $option;
    }

}
