<?php

/**
 * This is the model class for table "{{transection_status}}".
 *
 * The followings are the available columns in table '{{transection_status}}':
 * @property integer $id
 * @property integer $status_id
 * @property string $status_title
 * @property integer $user_view
 * @property integer $transection_type
 */
class TransectionStatus extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TransectionStatus the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{transection_status}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('status_id, user_view', 'numerical', 'integerOnly' => true),
            array('status_title', 'length', 'max' => 20),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, status_id, status_title, user_view, transection_type', 'safe', 'on' => 'search'),
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
            'id' => Yii::t('TransectionStatus', 'id'),
            'status_id' => Yii::t('TransectionStatus', 'status_id'),
            'status_title' => Yii::t('TransectionStatus', 'status_title'),
            'user_view' => Yii::t('TransectionStatus', 'user_view'),
            'transection_type' => Yii::t('TransectionStatus', 'transection_type'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('status_id', $this->status_id);
        $criteria->compare('status_title', $this->status_title, true);
        $criteria->compare('user_view', $this->user_view);
        $criteria->compare('transection_type', $this->transection_type);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize20'],
            ),
        ));
    }

    public static function getStatus($id, $type) {
        $value = TransectionStatus::model()->findByAttributes(array('status_id' => $id, 'transection_type' => $type));
        if (!empty($value->status_title)) {
            if ($value->status_id == 0) {
                return '<span class="label label-primary">' . $value->status_title . '</span>';
            } elseif ($value->status_id == 1) {
                return '<span class="label label-success">' . $value->status_title . '</span>';
            } else {
                return '<span class="label label-danger">' . $value->status_title . '</span>';
            }
        } else {
            return null;
        }
    }

}
