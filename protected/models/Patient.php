<?php

/**
 * This is the model class for table "{{patient}}".
 *
 * The followings are the available columns in table '{{patient}}':
 * @property integer $id
 * @property integer $category
 * @property string $name
 * @property integer $age
 * @property string $sex
 * @property string $birth_date
 * @property string $blood_groop
 * @property string $marital_status
 * @property string $email
 * @property string $national_id
 * @property string $spouse
 * @property string $occupation
 * @property string $religion
 * @property string $address
 * @property integer $thana
 * @property integer $district
 * @property integer $country
 * @property string $mobile
 * @property string $emergency_name
 * @property string $emergency_relation
 * @property string $emergency_contact
 * @property string $created_on
 * @property integer $created_by
 *
 * The followings are the available model relations:
 * @property PatientCategory $category0
 */
class Patient extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{patient}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('category_new, category, name, admission', 'required'),
            array('category_new, category, age, thana, district, country, created_by, patient_type, patient_grade', 'numerical', 'integerOnly' => true),
            array('name, email, spouse, occupation, religion, mobile, emergency_name, emergency_relation, emergency_contact, village, post', 'length', 'max' => 150),
            array('sex', 'length', 'max' => 6),
            array('blood_groop', 'length', 'max' => 5),
            array('marital_status', 'length', 'max' => 9),
            array('national_id, pat_id, age_type, ref_no, no_of_family_member, earning_member, admission', 'length', 'max' => 50),
            array('address, referred, guardian_occupation, earning_source', 'length', 'max' => 250),
            array('problem', 'length', 'max' => 400),
//            array('ref_no', 'checkCateroty'),
            array('age,birth_date', 'checkAgeEmpty'),
            array('birth_date, created_on', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, category_new, category, pat_id, ref_no, name, age, age_type, sex, birth_date, blood_groop, marital_status, email, national_id, spouse, occupation, religion, address, village, post, thana, district, country, mobile, emergency_name, emergency_relation, emergency_contact, created_on, created_by, patient_type, patient_grade, problem, admission', 'safe', 'on' => 'search'),
        );
    }

    public function checkCateroty($attribute, $params) {
        $category = $this->category;
        if ($category != 3 && empty($category)) {
            $this->addError($attribute, 'Sorry! Reference No cannot be blank.');
        }
    }

    public function checkAgeEmpty($attribute, $params) {
        $age = $this->age;
        $birth = $this->birth_date;
        if (empty($age) && $birth == "0000-00-00") {
            $this->addError($attribute, 'Sorry! Age/Date of Birth cannot be blank!');
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'category0' => array(self::BELONGS_TO, 'PatientCategory', 'category'),
            'category_new0' => array(self::BELONGS_TO, 'PatientCategoryNew', 'category_new'),
            'thana0' => array(self::BELONGS_TO, 'Thana', 'thana'),
            'district0' => array(self::BELONGS_TO, 'District', 'district'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'category' => 'Sub Category',
            'category_new' => 'Category',
            'pat_id' => 'Patient ID',
            'ref_no' => 'Ref. No',
            'name' => 'Name',
            'age' => 'Age',
            'age_type' => 'Age Type',
            'sex' => 'Sex',
            'birth_date' => 'Date of Birth',
            'blood_groop' => 'Blood Group',
            'marital_status' => 'Marital Status',
            'email' => 'Email',
            'national_id' => 'National ID',
            'spouse' => 'Spouse',
            'occupation' => 'Occupation',
            'religion' => 'Religion',
            'address' => 'Address',
            'village' => 'Village',
            'post' => 'Post',
            'thana' => 'Thana',
            'district' => 'District',
            'country' => 'Country',
            'mobile' => 'Mobile',
            'emergency_name' => 'Guardian Name',
            'emergency_relation' => 'Relation',
            'emergency_contact' => 'Contact',
            'patient_type' => 'Patient Type',
            'patient_grade' => 'Patient Grade',
            'problem' => 'Problem',
            'referred' => 'Referred',
            'no_of_family_member' => 'Nr. of family member',
            'earning_member' => 'Earning Member',
            'guardian_occupation' => 'Guardian Occupation',
            'earning_source' => 'Earning Source',
            'admission' => 'Admission',
            'created_on' => 'Registration Date',
            'created_by' => 'Registration By',
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
        //$criteria->compare('category', $this->category);
        $criteria->compare('pat_id', $this->pat_id, true);
        $criteria->compare('ref_no', $this->ref_no, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('age', $this->age);
        $criteria->compare('age_type', $this->age_type, true);
        $criteria->compare('sex', $this->sex, true);
        $criteria->compare('birth_date', $this->birth_date, true);
        $criteria->compare('blood_groop', $this->blood_groop, true);
        $criteria->compare('marital_status', $this->marital_status, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('national_id', $this->national_id, true);
        $criteria->compare('spouse', $this->spouse, true);
        $criteria->compare('occupation', $this->occupation, true);
        $criteria->compare('religion', $this->religion, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('village', $this->village);
        $criteria->compare('post', $this->post);
        $criteria->compare('thana', $this->thana);
        $criteria->compare('district', $this->district);
        $criteria->compare('country', $this->country);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('emergency_name', $this->emergency_name, true);
        $criteria->compare('emergency_relation', $this->emergency_relation, true);
        $criteria->compare('emergency_contact', $this->emergency_contact, true);
        $criteria->compare('created_on', $this->created_on, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('patient_type', $this->patient_type);
        $criteria->compare('patient_grade', $this->patient_grade);
        $criteria->compare('problem', $this->problem, true);
        $criteria->compare('referred', $this->referred, true);
        $criteria->compare('guardian_occupation', $this->guardian_occupation, true);
        $criteria->compare('no_of_family_member', $this->no_of_family_member, true);
        $criteria->compare('earning_member', $this->earning_member, true);
        $criteria->compare('earning_source', $this->earning_source, true);
        $criteria->compare('admission', $this->admission, true);
        $criteria->with = array('category0', 'category_new0', 'thana0', 'district0');
        $criteria->compare('category0.title', $this->category, true);
        $criteria->compare('category_new0.title', $this->category_new, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize'],
            ),
            'sort' => array('defaultOrder' => 't.id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Patient the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getData($id, $field) {
        $model = Patient::model()->findByAttributes(array('id' => $id));
        if (empty($model->$field)) {
            return null;
        } else {
            return $model->$field;
        }
    }

    public static function getAgeYear($date) {
        if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
            $dob = new DateTime($date);
            $now = new DateTime();
            return $now->diff($dob)->y;
        }
        $difference = time() - strtotime($date);
        return floor($difference / 31556926);
    }

    public static function getAge($date) {
        if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
            $dob = new DateTime($date);
            $now = new DateTime();
            return $now->diff($dob)->y . ' Years ' . $now->diff($dob)->m . ' Months ' . $now->diff($dob)->d . ' Days';
        }
        $difference = time() - strtotime($date);
        return floor($difference / 31556926) . ' Years';
    }

    public static function getPatiantAge($id) {
        $model = Patient::model()->findByAttributes(array('id' => $id));
        if (!empty(@$model)) {
            $data = NULL;
            if (empty($model->birth_date) || $model->birth_date == '0000-00-00' || $model->birth_date == '0000-00-00 00:00:00') {
                $data .= $model->age . ' ' . $model->age_type;
            } else {
                $data .= Patient::getAge($model->birth_date);
            }
            return $data;
        } else {
            return NULL;
        }
    }

    public static function getPatiantAddress($id) {
        $model = Patient::model()->findByAttributes(array('id' => $id));
        if (!empty(@$model)) {
            $data = NULL;
            if (isset($model->thana))
                $data .= ', ' . Thana::getData($model->thana, 'title');
            if (isset($model->district))
                $data .= ', ' . District::getData($model->district, 'title');
            return $model->address . $data;
        } else {
            return NULL;
        }
    }

    public static function autoPatientNumber() { //PAT#2021-SEP-386
        $maxId = Yii::app()->db->createCommand()
            ->select('MAX(id)')
            ->from('{{patient}}')
            ->queryScalar();
        $autoValue = $maxId ? ((int) $maxId + 1) : 1;
        return 'PAT#' . date('Y') . '-' . strtoupper(date('M')) . '-' . $autoValue;
    }

    public static function getAgeToDate($age, $type) {
        if ($age > 0) {
            if ($type == "Year") {
                return date('Y-m-d', strtotime('-' . $age . ' years'));
            } else {
                return date('Y-m-d', strtotime('-' . $age . ' months'));
            }
        } else {
            return NULL;
        }
    }

}
