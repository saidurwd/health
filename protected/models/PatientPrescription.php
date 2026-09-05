<?php

/**
 * This is the model class for table "{{patient_prescription}}".
 *
 * The followings are the available columns in table '{{patient_prescription}}':
 * @property integer $id
 * @property integer $patient
 * @property string $pre_number
 * @property string $cc
 * @property string $oe
 * @property string $bp
 * @property string $pulse
 * @property string $temp
 * @property string $advice
 * @property string $rx
 * @property string $created_on
 * @property integer $created_by
 */
class PatientPrescription extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{patient_prescription}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('patient, diagnosis', 'required'),
            array('patient, diagnosis, created_by', 'numerical', 'integerOnly' => true),
            array('pre_number', 'length', 'max' => 20),
            array('cc, oe, bp, pulse, temp, advice', 'length', 'max' => 250),
            array('rx, admission, created_on', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, patient, pre_number, diagnosis, cc, oe, bp, pulse, temp, advice, rx, admission, created_on, created_by', 'safe', 'on' => 'search'),
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
            'patient' => 'Patient',
            'pre_number' => 'Pre. No.',
            'diagnosis' => 'Diagnosis',
            'cc' => 'C/C',
            'oe' => 'O/E',
            'bp' => 'B/P',
            'pulse' => 'Pulse',
            'temp' => 'Temp',
            'advice' => 'Advice',
            'rx' => 'Prescription',
            'admission' => 'Admission',
            'created_on' => 'Created Date',
            'created_by' => 'Created By',
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
        $criteria->compare('patient', $this->patient);
        $criteria->compare('pre_number', $this->pre_number, true);
        $criteria->compare('diagnosis', $this->diagnosis);
        $criteria->compare('cc', $this->cc, true);
        $criteria->compare('oe', $this->oe, true);
        $criteria->compare('bp', $this->bp, true);
        $criteria->compare('pulse', $this->pulse, true);
        $criteria->compare('temp', $this->temp, true);
        $criteria->compare('advice', $this->advice, true);
        $criteria->compare('rx', $this->rx, true);
        $criteria->compare('admission', $this->admission, true);
        $criteria->compare('created_on', $this->created_on, true);
        $criteria->compare('created_by', $this->created_by);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize20'],
            ),
            'sort' => array('defaultOrder' => 'created_on DESC, id DESC')
        ));
    }
    
    public function search_patient($patient) {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        //$criteria->compare('patient', $this->patient);
        $criteria->condition = 'patient=' . (int) $patient;
        $criteria->compare('pre_number', $this->pre_number, true);
        $criteria->compare('diagnosis', $this->diagnosis);
        $criteria->compare('cc', $this->cc, true);
        $criteria->compare('oe', $this->oe, true);
        $criteria->compare('bp', $this->bp, true);
        $criteria->compare('pulse', $this->pulse, true);
        $criteria->compare('temp', $this->temp, true);
        $criteria->compare('advice', $this->advice, true);
        $criteria->compare('rx', $this->rx, true);
        $criteria->compare('admission', $this->admission, true);
        $criteria->compare('created_on', $this->created_on, true);
        $criteria->compare('created_by', $this->created_by);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize20'],
            ),
            'sort' => array('defaultOrder' => 'created_on DESC, id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PatientPrescription the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getPrescriptionList($model, $field, $id, $placeholder) {
        $cacheKey = 'PatientPrescription_list';
        $array = Yii::app()->cache->get($cacheKey);
        if ($array === false) {
            $array = PatientPrescription::model()->findAll(array('condition' => '', 'order' => 'created_on DESC'));
            Yii::app()->cache->set($cacheKey, $array, 300);
        }

        $return = '<select id="' . $model . '_' . $field . '" name="' . $model . '[' . $field . ']" class="select2">';
        $return .= '<option value="">' . $placeholder . '</option>';
        foreach ($array as $key => $value) {
            if ($value["id"] == $id) {
                $return .= '<option selected="selected" value="' . $value["id"] . '" class="' . $value["patient"] . '">' . $value["pre_number"] . '</option>';
            } else {
                $return .= '<option value="' . $value["id"] . '" class="' . $value["patient"] . '">' . $value["pre_number"] . '</option>';
            }
        }
        $return .= '</select>';

        return $return;
    }

    public static function getData($id, $field) {
        $model = PatientPrescription::model()->findByAttributes(array('id' => $id));
        if (empty($model->$field)) {
            return null;
        } else {
            return $model->$field;
        }
    }
    
    public static function autoPrescriptionNumber() {
        $criteria = new CDbCriteria;
        $criteria->order = 'created_on DESC';
        $model = PatientPrescription::model()->find($criteria);
        if (empty($model->pre_number)) {
            $autoValue = 1;
        } else {
            $ex = explode('-', $model->pre_number);
            $max = $ex[2];
            if ($ex[1] == date('Y')) {
                $autoValue = ((int) $max + 1);
            } else {
                $autoValue = 1;
            }
        }

        $return = 'PRE#' . strtoupper(Yii::app()->user->name) . '-' . date('Y') . '-' . $autoValue;
        return $return;
    }

}
