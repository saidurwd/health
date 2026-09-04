<?php

/**
 * This is the model class for table "{{vendor}}".
 *
 * The followings are the available columns in table '{{vendor}}':
 * @property integer $id
 * @property string $title
 * @property string $email
 * @property string $phone
 * @property string $mobile
 * @property string $address
 */
class Vendor extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{vendor}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title', 'required'),
            array('title, address', 'length', 'max' => 255),
            array('email', 'length', 'max' => 150),
            array('phone, mobile', 'length', 'max' => 100),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, email, phone, mobile, address', 'safe', 'on' => 'search'),
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
            'title' => 'Vendor',
            'email' => 'Email',
            'phone' => 'Phone',
            'mobile' => 'Mobile',
            'address' => 'Address',
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('address', $this->address, true);

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
     * @return Vendor the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    /*
     * Count total vendor in this company
     */

    public static function countTotalVendors() {
        $value = Vendor::model()->findAll();
        return count($value);
    }

    /*
     * get vendor address details
     */

    public static function get_address_details($id) {
        $value = Vendor::model()->findByAttributes(array('id' => $id));
        $data = null;
        if (!empty($value->title)) {
            $data .= '<h4>' . $value->title . '</h4>';
        }
        $data .= '<address>';
        if (!empty($value->address)) {
            $data .= $value->address . '<br />';
        }
        if (!empty($value->email)) {
            $data .= '<abbr title="Email">E: </abbr>' . $value->email . '<br />';
        }
        if (!empty($value->phone)) {
            $data .= '<abbr title="Phone">P: </abbr>' . $value->phone . '<br />';
        }
        if (!empty($value->mobile)) {
            $data .= '<abbr title="Mobile">M: </abbr>' . $value->mobile . '<br />';
        }
        $data .= '</address>';

        return $data;
    }

    public static function getData($id, $field) {
        $model = Vendor::model()->findByAttributes(array('id' => $id));
        if (empty($model->$field)) {
            return null;
        } else {
            return $model->$field;
        }
    }
    
    public static function get_vendor($id) {
        $title = Vendor::model()->findByAttributes(array('id' => $id));
        if (!empty($title->title)) {
            return $title->title;
        } else {
            return null;
        }
    }

}
