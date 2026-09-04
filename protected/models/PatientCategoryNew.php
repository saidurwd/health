<?php

/**
 * This is the model class for table "{{patient_category_new}}".
 *
 * The followings are the available columns in table '{{patient_category_new}}':
 * @property integer $id
 * @property string $title
 * @property string $status
 */
class PatientCategoryNew extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{patient_category_new}}';
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
            array('title, alias, path', 'length', 'max' => 250),
            array('status', 'length', 'max' => 8),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, parent, title, alias, path, status', 'safe', 'on' => 'search'),
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
            'parent' => 'Parent',
            'title' => 'Category',
            'alias' => 'Alias',
            'path' => 'Path',
            'status' => 'Status',
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
        $criteria->compare('path', $this->path, true);
        $criteria->compare('status', $this->status, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize'],
            ),
            'sort' => array('defaultOrder' => 'path ASC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PatientCategoryNew the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getData($id, $field) {
        $model = PatientCategoryNew::model()->findByAttributes(array('id' => $id));
        if (empty($model->$field)) {
            return null;
        } else {
            return $model->$field;
        }
    }

    public static function getPatientCategory($model, $field, $id) {
        $parent1 = PatientCategoryNew::model()->findAll(array('condition' => 'parent=0 OR parent IS NULL', 'order' => "title"));
        $option = '<select id="' . $model . '_' . $field . '" name="' . $model . '[' . $field . ']" class="select2">';
        $option .= '<option value="">Select a Category</option>';
        foreach ($parent1 as $key => $values1) {
            $option .= '<optgroup label="' . $values1["title"] . '">';
            $parent2 = PatientCategoryNew::model()->findAll(array('condition' => 'parent=' . (int) $values1["id"], 'order' => 'title'));
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

    public static function getPatientCategorySearch($id) {
        $parent1 = PatientCategoryNew::model()->findAll(array('condition' => 'parent=0 OR parent IS NULL', 'order' => 'title'));
        $option = '<select id="category_new" name="category_new" class="select2">';
        $option .= '<option value="">All Categories</option>';
        foreach ($parent1 as $key => $values1) {
            if ($id == $values1["id"]) {
                $option .= '<option selected="selected" value="' . $values1["id"] . '">' . $values1["title"] . '</option>';
            } else {
                $option .= '<option value="' . $values1["id"] . '">' . $values1["title"] . '</option>';
            }
            $parent2 = PatientCategoryNew::model()->findAll(array('condition' => 'parent=' . (int) $values1["id"], 'order' => 'title'));
            foreach ($parent2 as $key => $values2) {
                if ($id == $values2["id"]) {
                    $option .= '<option selected="selected" value="' . $values2["id"] . '" class="text-success">&nbsp;&nbsp;' . $values2["title"] . '</option>';
                } else {
                    $option .= '<option value="' . $values2["id"] . '" class="text-success">&nbsp;&nbsp;' . $values2["title"] . '</option>';
                }
            }
        }
        $option .= '</select>';

        return $option;
    }

    public static function getPatientCategoryForm($model, $field, $id) {
        $parent1 = PatientCategoryNew::model()->findAll(array('condition' => 'parent=0 OR parent IS NULL', "order" => "title"));
        $option = '<select id="' . $model . '_' . $field . '" name="' . $model . '[' . $field . ']" class="select2">';
        $option .= '<option value="">Select a Category</option>';
        foreach ($parent1 as $key => $values1) {
            if ($id == $values1["id"]) {
                $option .= '<option selected="selected" value="' . $values1["id"] . '">' . $values1["title"] . '</option>';
            } else {
                $option .= '<option value="' . $values1["id"] . '">' . $values1["title"] . '</option>';
            }
            $parent2 = PatientCategoryNew::model()->findAll(array('condition' => 'parent=' . (int) $values1["id"], 'order' => 'title'));
            foreach ($parent2 as $key => $values2) {
                if ($id == $values2["id"]) {
                    $option .= '<option selected="selected" value="' . $values2["id"] . '" class="text-success space-left-30">' . $values2["title"] . '</option>';
                } else {
                    $option .= '<option value="' . $values2["id"] . '" class="text-success space-left-30">' . $values2["title"] . '</option>';
                }
            }
        }
        $option .= '</select>';

        return $option;
    }

    public static function update_path($id) {
        $model = PatientCategoryNew::model()->findByPk($id);
        if ($model->parent == 0 || $model->parent === null) {
            $model->path = '0.' . $model->id;
            $model->save();
        } else {
            $parent = PatientCategoryNew::model()->findByAttributes(array('id' => $model->parent));
            $model->path = $parent->path . '.' . $model->id;
            $model->save();
        }
    }

    public static function update_alias($id) {
        $model = PatientCategoryNew::model()->findByPk($id);
        if ($model->parent == 0 || $model->parent === null) {
            $model->alias = $model->title;
            $model->save();
        } else {
            $parent = PatientCategoryNew::model()->findByAttributes(array('id' => $model->parent));
            $model->alias = $parent->alias . '/' . $model->title;
            $model->save();
        }
    }

    public static function get_full_path($id) {
        $model = PatientCategoryNew::model()->findByPk($id);
        $array = explode('.', @$model->path);
        $total = count($array);
        $data = null;
        $i = 1;
        foreach ($array as $key => $value) {
            if ($value > 0) {
                if ($total != $i) {
                    $data .= PatientCategoryNew::getData($value, 'title') . ' <i class="fa fa-angle-double-right text-info"></i> ';
                } else {
                    $data .= PatientCategoryNew::getData($value, 'title');
                }
            }
            $i++;
        }
        return $data;
    }

}
