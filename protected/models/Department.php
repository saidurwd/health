<?php

/**
 * This is the model class for table "{{department}}".
 *
 * The followings are the available columns in table '{{department}}':
 * @property integer $id
 * @property integer $parent
 * @property string $code
 * @property string $title
 * @property string $alias
 * @property string $description
 * @property string $path
 */
class Department extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{department}}';
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
            array('code', 'length', 'max' => 4),
            array('title', 'length', 'max' => 150),
            array('alias, path', 'length', 'max' => 250),
            array('description', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, parent, code, title, alias, description, path', 'safe', 'on' => 'search'),
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
            'code' => 'Code',
            'title' => 'Department',
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
        $criteria->compare('code', $this->code, true);
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
     * @return Department the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getData($id, $field) {
        $model = Department::model()->findByAttributes(array('id' => $id));
        if (empty($model->$field)) {
            return null;
        } else {
            return $model->$field;
        }
    }

    public static function update_path($id) {
        $model = Department::model()->findByPk($id);
        if ($model->parent == 0 || $model->parent === null) {
            $model->path = '0.' . $model->id;
            $model->save();
        } else {
            $parent = Department::model()->findByAttributes(array('id' => $model->parent));
            $model->path = $parent->path . '.' . $model->id;
            $model->save();
        }
    }

    public static function update_alias($id) {
        $model = Department::model()->findByPk($id);
        if ($model->parent == 0 || $model->parent === null) {
            $model->alias = $model->title;
            $model->save();
        } else {
            $parent = Department::model()->findByAttributes(array('id' => $model->parent));
            $model->alias = $parent->alias . '/' . $model->title;
            $model->save();
        }
    }

    public static function get_full_path($id) {
        $model = Department::model()->findByPk($id);
        $array = explode('.', $model->path);
        $total = count($array);
        $data = null;
        $i = 1;
        foreach ($array as $key => $value) {
            if ($value > 0) {
                if ($total != $i) {
                    $data .= Department::getData($value, 'title') . ' <i class="fa fa-angle-double-right text-info"></i> ';
                } else {
                    $data .= Department::getData($value, 'title');
                }
            }
            $i++;
        }
        return $data;
    }

}
