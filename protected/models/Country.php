<?php

/**
 * This is the model class for table "{{country}}".
 *
 * The followings are the available columns in table '{{country}}':
 * @property integer $id
 * @property string $title
 * @property string $country_2_code
 * @property string $country_3_code
 * @property string $status
 */
class Country extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{country}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title', 'required'),
            array('title', 'length', 'max' => 255),
            array('country_2_code', 'length', 'max' => 2),
            array('country_3_code', 'length', 'max' => 3),
            array('status', 'length', 'max' => 8),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, country_2_code, country_3_code, status', 'safe', 'on' => 'search'),
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
            'title' => 'Country',
            'country_2_code' => 'Code 2',
            'country_3_code' => 'Code 3',
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('country_2_code', $this->country_2_code, true);
        $criteria->compare('country_3_code', $this->country_3_code, true);
        $criteria->compare('status', $this->status);

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
     * @return Country the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getData($id, $field) {
        $model = Country::model()->findByAttributes(array('id' => $id));
        if (empty($model->$field)) {
            return null;
        } else {
            return $model->$field;
        }
    }
}
