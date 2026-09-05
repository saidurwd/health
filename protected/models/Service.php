<?php

/**
 * This is the model class for table "{{service}}".
 *
 * The followings are the available columns in table '{{service}}':
 * @property integer $id
 * @property string $title
 * @property string $status
 */
class Service extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{service}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, rate', 'required'),
            array('parent, service_grade, ordering', 'numerical', 'integerOnly' => true),
            array('title, alias, path, service_type, rate_status', 'length', 'max' => 250),
            array('rate', 'length', 'max' => 12),
            array('status, discount', 'length', 'max' => 8),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, parent, title, alias, path, rate, discount, service_type, rate_status, service_grade, ordering, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'parent' => 'Parent',
            'title' => 'Service',
            'alias' => 'Alias',
            'path' => 'Path',
            'rate' => 'Rate',
            'discount' => 'Discount',
            'service_type' => 'Service Type',
            'service_grade' => 'Grade',
            'ordering' => 'Ordering',
            'status' => 'Status',
            'rate_status' => 'Rate Status',
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
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('parent', $this->parent);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('alias', $this->alias, true);
        $criteria->compare('path', $this->path, true);
        $criteria->compare('rate', $this->rate, true);
        $criteria->compare('discount', $this->discount);
        $criteria->compare('service_type', $this->service_type);
        $criteria->compare('rate_status', $this->rate_status);
        $criteria->compare('service_grade', $this->service_grade);
        $criteria->compare('ordering', $this->ordering);
        $criteria->compare('status', $this->status, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize'],
            ),
            'sort' => array('defaultOrder' => 'path ASC')
        ));
    }

    public function search_print()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('parent', $this->parent);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('alias', $this->alias, true);
        $criteria->compare('path', $this->path, true);
        $criteria->compare('rate', $this->rate, true);
        $criteria->compare('discount', $this->discount);
        $criteria->compare('service_type', $this->service_type);
        $criteria->compare('rate_status', $this->rate_status);
        $criteria->compare('service_grade', $this->service_grade);
        $criteria->compare('ordering', $this->ordering);
        $criteria->compare('status', $this->status, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize1000'],
            ),
            'sort' => array('defaultOrder' => 'path ASC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Service the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function getData($id, $field)
    {
        $model = Service::model()->findByAttributes(array('id' => $id));
        if (empty($model->$field)) {
            return null;
        } else {
            return $model->$field;
        }
    }

    public static function getServiceCategory($model, $field, $id)
    {
        $cacheKey = 'ServiceCategory_parents';
        $parent1 = Yii::app()->cache->get($cacheKey);
        if ($parent1 === false) {
            $parent1 = Service::model()->findAll(array('condition' => 'parent=0 OR parent IS NULL', 'order' => "ordering ASC, path ASC"));
            Yii::app()->cache->set($cacheKey, $parent1, 600);
        }

        $option = '<select id="' . $model . '_' . $field . '" name="' . $model . '[' . $field . ']" class="select2">';
        $option .= '<option value="">Select a Service</option>';
        foreach ($parent1 as $key => $values1) {
            $option .= '<optgroup label="' . $values1["title"] . '">';
            $cacheKey2 = 'ServiceCategory_children_' . $values1["id"];
            $parent2 = Yii::app()->cache->get($cacheKey2);
            if ($parent2 === false) {
                $parent2 = Service::model()->findAll(array('condition' => 'parent=' . (int)$values1["id"], 'order' => 'ordering ASC, path ASC'));
                Yii::app()->cache->set($cacheKey2, $parent2, 600);
            }
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

    public static function getServiceCategorySearch($id)
    {
        $parent1 = Service::model()->findAll(array('condition' => 'parent=0 OR parent IS NULL', 'order' => 'ordering ASC, path ASC'));
        $option = '<select id="service" name="service" class="select2">';
        $option .= '<option value="">All Services</option>';
        foreach ($parent1 as $key => $values1) {
            if ($id == $values1["id"]) {
                $option .= '<option selected="selected" value="' . $values1["id"] . '">' . $values1["title"] . '</option>';
            } else {
                $option .= '<option value="' . $values1["id"] . '">' . $values1["title"] . '</option>';
            }
            $parent2 = Service::model()->findAll(array('condition' => 'parent=' . (int)$values1["id"], 'order' => 'ordering ASC, path ASC'));
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

    public static function getServiceCategoryReport($name = 'service', $id = 0)
    {
        $parent1 = Service::model()->findAll(array('condition' => 'parent=0 OR parent IS NULL', 'order' => "ordering ASC, path ASC"));
        $option = '<select id="' . $name . '" name="' . $name . '" class="select2">';
        $option .= '<option value="">Select a Service</option>';
        foreach ($parent1 as $key => $values1) {
            $option .= '<optgroup label="' . $values1["title"] . '">';
            $parent2 = Service::model()->findAll(array('condition' => 'parent=' . (int)$values1["id"], 'order' => 'ordering ASC, path ASC'));
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

    public static function update_path($id)
    {
        $model = Service::model()->findByPk($id);
        if ($model->parent == 0 || $model->parent === null) {
            $model->path = '0.' . $model->id;
            $model->save();
        } else {
            $parent = Service::model()->findByAttributes(array('id' => $model->parent));
            $model->path = $parent->path . '.' . $model->id;
            $model->save();
        }
    }

    public static function update_alias($id)
    {
        $model = Service::model()->findByPk($id);
        if ($model->parent == 0 || $model->parent === null) {
            $model->alias = $model->title;
            $model->save();
        } else {
            $parent = Service::model()->findByAttributes(array('id' => $model->parent));
            $model->alias = $parent->alias . '/' . $model->title;
            $model->save();
        }
    }

    public static function get_full_path($id)
    {
        $model = Service::model()->findByPk($id);
        $path = Service::getData($id, 'path');
        $array = explode('.', $path);
        $total = count($array);
        $data = null;
        $i = 1;
        foreach ($array as $key => $value) {
            if ($value > 0) {
                if ($total != $i) {
                    $data .= Service::getData($value, 'title') . ' <i class="fa fa-angle-double-right text-info"></i> ';
                } else {
                    $data .= Service::getData($value, 'title');
                }
            }
            $i++;
        }
        return $data;
    }

    public static function getRateStatus($data)
    {
        $array = Service::model()->findAll(array('condition' => 'rate_status="' . $data . '"', 'order' => 'id ASC'));
        $return = null;
        foreach ($array as $key => $values) {
            $return .= ' this.value == ' . $values["id"] . ' ||';
        }
        $return .= ' this.value == 999999';

        return $return;
    }

}
