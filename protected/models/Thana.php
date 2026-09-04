<?php

/**
 * This is the model class for table "{{thana}}".
 *
 * The followings are the available columns in table '{{thana}}':
 * @property integer $id
 * @property integer $country
 * @property integer $state
 * @property integer $city
 * @property integer $district
 * @property string $title
 * @property string $status
 */
class Thana extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{thana}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('state, city, district, title', 'required'),
            array('country, state, city, district', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 100),
            array('status', 'length', 'max' => 8),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, country, state, city, district, title, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'country0' => array(self::BELONGS_TO, 'Country', 'country'),
            'state0' => array(self::BELONGS_TO, 'State', 'state'),
            'city0' => array(self::BELONGS_TO, 'City', 'city'),
            'district0' => array(self::BELONGS_TO, 'District', 'district'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'country' => 'Country',
            'state' => 'State',
            'city' => 'City',
            'district' => 'District',
            'title' => 'Thana',
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
        $criteria->compare('country', $this->country);
        $criteria->compare('state', $this->state);
        $criteria->compare('city', $this->city);
        $criteria->compare('district', $this->district);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('status', $this->status, true);

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
     * @return Thana the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getData($id, $field) {
        $model = Thana::model()->findByAttributes(array('id' => $id));
        if (empty($model->$field)) {
            return null;
        } else {
            return $model->$field;
        }
    }
    
    public static function getRelatedThanaDistrict($model, $field, $id, $class = 'form-control') {
        $array = Thana::model()->findAll(array('condition' => 'status="Active"', 'order' => 'title'));
        $option = '<select id="' . $model . '_' . $field . '" name="' . $model . '[' . $field . ']" class="' . $class . '">';
        $option .= '<option value="">Select a Thana</option>';
        foreach ($array as $key => $values) {
            if ($values["id"] == $id) {
                $option .= '<option selected="selected" value="' . $values["id"] . '" class="' . $values["district"] . '">' . $values["title"] . '</option>';
            } else {
                $option .= '<option value="' . $values["id"] . '" class="' . $values["district"] . '">' . $values["title"] . '</option>';
            }
        }
        $option .= '</select>';

        return $option;
    }
}
