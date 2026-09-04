<?php

/**
 * This is the model class for table "{{store}}".
 *
 * The followings are the available columns in table '{{store}}':
 * @property integer $id
 * @property integer $parent
 * @property string $title
 * @property string $alias
 * @property string $location
 * @property integer $incharge
 * @property string $description
 * @property string $path
 */
class Store extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{store}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title', 'required'),
            array('parent, incharge', 'numerical', 'integerOnly' => true),
            array('title, alias, location', 'length', 'max' => 255),
            array('path', 'length', 'max' => 252),
            array('description', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, parent, title, alias, location, incharge, description, path', 'safe', 'on' => 'search'),
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
            'title' => 'Store',
            'alias' => 'Alias',
            'location' => 'Location',
            'incharge' => 'Incharge',
            'description' => 'Details',
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
        $criteria->compare('location', $this->location, true);
        $criteria->compare('incharge', $this->incharge);
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
     * @return Store the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getData($id, $field) {
        $model = Store::model()->findByAttributes(array('id' => $id));
        if (empty($model->$field)) {
            return null;
        } else {
            return $model->$field;
        }
    }
    
    public static function get_store($id) {
        $title = Store::model()->findByAttributes(array('id' => $id));
        if (!empty($title->title)) {
            return $title->title;
        } else {
            return 'N/A';
        }
    }
    
    /*
     * Count total store in this company
     */

    public static function countTotalStore() {
        $value = Store::model()->findAll();
        return count($value);
    }

    public static function update_path($id) {
        $model = Store::model()->findByPk($id);
        if ($model->parent == 0 || $model->parent === null) {
            $model->path = '0.' . $model->id;
            $model->save();
        } else {
            $parent = Store::model()->findByAttributes(array('id' => $model->parent));
            $model->path = $parent->path . '.' . $model->id;
            $model->save();
        }
    }

    public static function update_alias($id) {
        $model = Store::model()->findByPk($id);
        if ($model->parent == 0 || $model->parent === null) {
            $model->alias = $model->title;
            $model->save();
        } else {
            $parent = Store::model()->findByAttributes(array('id' => $model->parent));
            $model->alias = $parent->alias . '/' . $model->title;
            $model->save();
        }
    }

    public static function get_full_path($id) {
        $model = Store::model()->findByPk($id);
        $array = explode('.', $model->path);
        $total = count($array);
        $data = null;
        $i = 1;
        foreach ($array as $key => $value) {
            if ($value > 0) {
                if ($total != $i) {
                    $data .= Store::getData($value, 'title') . ' <i class="fa fa-angle-double-right text-info"></i> ';
                } else {
                    $data .= Store::getData($value, 'title');
                }
            }
            $i++;
        }
        return $data;
    }

    public static function get_stores_grid($model, $field, $sid, $id) {
        $parent1 = Store::model()->findAll(array('condition' => 'parent=0 OR parent IS NULL', 'order' => 'path'));
        $option = '<select id="' . $model . '_' . $field . '" name="' . $model . '[' . $field . ']" class="select2 reload' . $id . '" onchange="saveadjustment(' . (int) $id . ', this.value, \'store\')">';
        $option .= '<option value="">Select a Store</option>';
        foreach ($parent1 as $key => $values1) {
            if ($sid == $values1["id"]) {
                $option .= '<option selected="selected" value="' . $values1["id"] . '">' . $values1["title"] . '</option>';
            } else {
                $option .= '<option value="' . $values1["id"] . '">' . $values1["title"] . '</option>';
            }
            $parent2 = Store::model()->findAll(array('condition' => 'parent=' . (int) $values1["id"], 'order' => 'path'));
            foreach ($parent2 as $key => $values2) {
                if ($sid == $values2["id"]) {
                    $option .= '<option selected="selected" value="' . $values2["id"] . '" class="text-success">&nbsp;&nbsp;' . $values2["title"] . '</option>';
                } else {
                    $option .= '<option value="' . $values2["id"] . '" class="text-success">&nbsp;&nbsp;' . $values2["title"] . '</option>';
                }
                $parent3 = Store::model()->findAll(array('condition' => 'parent=' . (int) $values2["id"], 'order' => 'path'));
                foreach ($parent3 as $key => $values3) {
                    if ($sid == $values3["id"]) {
                        $option .= '<option selected="selected" value="' . $values3["id"] . '" class="text-danger">&nbsp;&nbsp;&nbsp;&nbsp;' . $values3["title"] . '</option>';
                    } else {
                        $option .= '<option value="' . $values3["id"] . '" class="text-danger">&nbsp;&nbsp;&nbsp;&nbsp;' . $values3["title"] . '</option>';
                    }
                    $parent4 = Store::model()->findAll(array('condition' => 'parent=' . (int) $values3["id"], 'order' => 'path'));
                    foreach ($parent4 as $key => $values4) {
                        if ($sid == $values4["id"]) {
                            $option .= '<option selected="selected" value="' . $values4["id"] . '" class="text-warning">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $values4["title"] . '</option>';
                        } else {
                            $option .= '<option value="' . $values4["id"] . '" class="text-warning">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $values4["title"] . '</option>';
                        }
                        $parent5 = Store::model()->findAll(array('condition' => 'parent=' . (int) $values4["id"], 'order' => 'path'));
                        foreach ($parent5 as $key => $values5) {
                            if ($sid == $values5["id"]) {
                                $option .= '<option selected="selected" value="' . $values5["id"] . '" class="text-warning">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $values5["title"] . '</option>';
                            } else {
                                $option .= '<option value="' . $values5["id"] . '" class="text-warning">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $values5["title"] . '</option>';
                            }
                            $parent6 = Store::model()->findAll(array('condition' => 'parent=' . (int) $values5["id"], 'order' => 'path'));
                            foreach ($parent6 as $key => $values6) {
                                if ($sid == $values6["id"]) {
                                    $option .= '<option selected="selected" value="' . $values6["id"] . '" class="txt-color-magenta">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $values6["title"] . '</option>';
                                } else {
                                    $option .= '<option value="' . $values6["id"] . '" class="txt-color-magenta">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $values6["title"] . '</option>';
                                }
                                $parent7 = Store::model()->findAll(array('condition' => 'parent=' . (int) $values6["id"], 'order' => 'path'));
                                foreach ($parent7 as $key => $values7) {
                                    if ($sid == $values7["id"]) {
                                        $option .= '<option selected="selected" value="' . $values7["id"] . '" class="txt-color-pink">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $values7["title"] . '</option>';
                                    } else {
                                        $option .= '<option value="' . $values7["id"] . '" class="txt-color-pink">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $values7["title"] . '</option>';
                                    }
                                    $parent8 = Store::model()->findAll(array('condition' => 'parent=' . (int) $values7["id"], 'order' => 'path'));
                                    foreach ($parent8 as $key => $values8) {
                                        if ($sid == $values8["id"]) {
                                            $option .= '<option selected="selected" value="' . $values8["id"] . '" class="txt-color-orangeDark">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $values8["title"] . '</option>';
                                        } else {
                                            $option .= '<option value="' . $values8["id"] . '" class="txt-color-orangeDark">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $values8["title"] . '</option>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $option .= '</select>';

        return $option;
    }

    public static function get_parents($model, $field, $id) {
        $parent1 = Store::model()->findAll(array('condition' => 'parent=0 OR parent IS NULL', 'order' => 'path'));
        $option = '<select id="' . $model . '_' . $field . '" name="' . $model . '[' . $field . ']" class="select2">';
        $option .= '<option value="">Select a Store</option>';
        foreach ($parent1 as $key => $values1) {
            if ($id == $values1["id"]) {
                $option .= '<option selected="selected" value="' . $values1["id"] . '">' . $values1["title"] . '</option>';
            } else {
                $option .= '<option value="' . $values1["id"] . '">' . $values1["title"] . '</option>';
            }
            $parent2 = Store::model()->findAll(array('condition' => 'parent=' . (int) $values1["id"], 'order' => 'path'));
            foreach ($parent2 as $key => $values2) {
                if ($id == $values2["id"]) {
                    $option .= '<option selected="selected" value="' . $values2["id"] . '" class="text-success space-left-30">' . $values2["title"] . '</option>';
                } else {
                    $option .= '<option value="' . $values2["id"] . '" class="text-success space-left-30">' . $values2["title"] . '</option>';
                }
                $parent3 = Store::model()->findAll(array('condition' => 'parent=' . (int) $values2["id"], 'order' => 'path'));
                foreach ($parent3 as $key => $values3) {
                    if ($id == $values3["id"]) {
                        $option .= '<option selected="selected" value="' . $values3["id"] . '" class="text-danger space-left-60">' . $values3["title"] . '</option>';
                    } else {
                        $option .= '<option value="' . $values3["id"] . '" class="text-danger space-left-60">' . $values3["title"] . '</option>';
                    }
                    $parent4 = Store::model()->findAll(array('condition' => 'parent=' . (int) $values3["id"], 'order' => 'path'));
                    foreach ($parent4 as $key => $values4) {
                        if ($id == $values4["id"]) {
                            $option .= '<option selected="selected" value="' . $values4["id"] . '" class="text-warning space-left-90">' . $values4["title"] . '</option>';
                        } else {
                            $option .= '<option value="' . $values4["id"] . '" class="text-warning space-left-90">' . $values4["title"] . '</option>';
                        }
                        $parent5 = Store::model()->findAll(array('condition' => 'parent=' . (int) $values4["id"], 'order' => 'path'));
                        foreach ($parent5 as $key => $values5) {
                            if ($id == $values5["id"]) {
                                $option .= '<option selected="selected" value="' . $values5["id"] . '" class="text-warning space-left-120">' . $values5["title"] . '</option>';
                            } else {
                                $option .= '<option value="' . $values5["id"] . '" class="text-warning space-left-120">' . $values5["title"] . '</option>';
                            }
                            $parent6 = Store::model()->findAll(array('condition' => 'parent=' . (int) $values5["id"], 'order' => 'path'));
                            foreach ($parent6 as $key => $values6) {
                                if ($id == $values6["id"]) {
                                    $option .= '<option selected="selected" value="' . $values6["id"] . '" class="txt-color-magenta space-left-150">' . $values6["title"] . '</option>';
                                } else {
                                    $option .= '<option value="' . $values6["id"] . '" class="txt-color-magenta space-left-150">' . $values6["title"] . '</option>';
                                }
                                $parent7 = Store::model()->findAll(array('condition' => 'parent=' . (int) $values6["id"], 'order' => 'path'));
                                foreach ($parent7 as $key => $values7) {
                                    if ($id == $values7["id"]) {
                                        $option .= '<option selected="selected" value="' . $values7["id"] . '" class="txt-color-pink space-left-180">' . $values7["title"] . '</option>';
                                    } else {
                                        $option .= '<option value="' . $values7["id"] . '" class="txt-color-pink space-left-180">' . $values7["title"] . '</option>';
                                    }
                                    $parent8 = Store::model()->findAll(array('condition' => 'parent=' . (int) $values7["id"], 'order' => 'path'));
                                    foreach ($parent8 as $key => $values8) {
                                        if ($id == $values8["id"]) {
                                            $option .= '<option selected="selected" value="' . $values8["id"] . '" class="txt-color-orangeDark space-left-200">' . $values8["title"] . '</option>';
                                        } else {
                                            $option .= '<option value="' . $values8["id"] . '" class="txt-color-orangeDark space-left-200">' . $values8["title"] . '</option>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $option .= '</select>';

        return $option;
    }

    public static function get_child_stores($id) {
        $child = $id;
        $parent1 = Store::model()->findAll(array('condition' => 'parent =' . (int) $id, "order" => "path"));
        foreach ($parent1 as $key => $values1) {
            $child .= ',' . $values1["id"];
            $parent2 = Store::model()->findAll(array('condition' => 'parent=' . (int) $values1["id"], 'order' => 'path'));
            foreach ($parent2 as $key => $values2) {
                $child .= ',' . $values2["id"];
                $parent3 = Store::model()->findAll(array('condition' => 'parent=' . (int) $values2["id"], 'order' => 'path'));
                foreach ($parent3 as $key => $values3) {
                    $child .= ',' . $values3["id"];
                    $parent4 = Store::model()->findAll(array('condition' => 'parent=' . (int) $values3["id"], 'order' => 'path'));
                    foreach ($parent4 as $key => $values4) {
                        $child .= ',' . $values4["id"];
                        $parent5 = Store::model()->findAll(array('condition' => 'parent=' . (int) $values4["id"], 'order' => 'path'));
                        foreach ($parent5 as $key => $values5) {
                            $child .= ',' . $values5["id"];
                            $parent6 = Store::model()->findAll(array('condition' => 'parent=' . (int) $values5["id"], 'order' => 'path'));
                            foreach ($parent6 as $key => $values6) {
                                $child .= ',' . $values6["id"];
                                $parent7 = Store::model()->findAll(array('condition' => 'parent=' . (int) $values6["id"], 'order' => 'path'));
                                foreach ($parent7 as $key => $values7) {
                                    $child .= ',' . $values7["id"];
                                    $parent8 = Store::model()->findAll(array('condition' => 'parent=' . (int) $values7["id"], 'order' => 'path'));
                                    foreach ($parent8 as $key => $values8) {
                                        $child .= ',' . $values8["id"];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $child;
    }

}
