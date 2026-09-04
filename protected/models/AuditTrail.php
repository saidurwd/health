<?php

/**
 * This is the model class for table "{{audit_trail}}".
 *
 * The followings are the available columns in table '{{audit_trail}}':
 * @property string $id
 * @property integer $user_id
 * @property string $login_time
 * @property string $logout_time
 */
class AuditTrail extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{audit_trail}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id', 'required'),
            array('user_id', 'numerical', 'integerOnly' => true),
            array('login_time, logout_time', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_id, login_time, logout_time', 'safe', 'on' => 'search'),
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
            'user_id' => 'User',
            'login_time' => 'Login Time',
            'logout_time' => 'Logout Time',
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('login_time', $this->login_time, true);
        $criteria->compare('logout_time', $this->logout_time, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize'],
            ),
            'sort' => array('defaultOrder' => 't.login_time DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AuditTrail the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function returnInterval($dateOne, $dateTwo) {
        $datetime1 = new DateTime($dateOne);
        $datetime2 = new DateTime($dateTwo);
        $diff = $datetime1->diff($datetime2);
        $string = '';
        $pass = '';

        if ($diff->y) {
            $string .= ($diff->y == 1) ? $diff->y . " year" : $diff->y . " years";
            $pass = ', ';
        }
        if ($diff->m) {
            $string .= $pass;
            $string .= ($diff->m == 1) ? $diff->m . " month" : $diff->m . " months";
            $pass = ', ';
        }
        if ($diff->d) {
            $string .= $pass;
            $string .= ($diff->d == 1) ? $diff->d . " day" : $diff->d . " days";
            $pass = ', ';
        }
        if ($diff->h) {
            $string .= $pass;
            $string .= ($diff->h == 1) ? $diff->h . " hour" : $diff->h . " hours";
            $pass = ', ';
        }
        if ($diff->i) {
            $string .= $pass;
            $string .= ($diff->i == 1) ? $diff->i . " minute" : $diff->i . " minutes";
            $pass = ', ';
        }
        if ($diff->s) {
            $string .= $pass;
            $string .= ($diff->s == 1) ? $diff->s . " second" : $diff->s . " seconds";
        }
        $pos = strrpos($string, ',');
        $string = substr_replace($string, ' and ', $pos, 2);
        return $string;
    }

}
