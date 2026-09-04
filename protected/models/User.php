<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property string $full_name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $register_date
 * @property string $lastvisit
 * @property string $activation
 * @property integer $group_id
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Comment[] $comments
 * @property Document[] $documents
 * @property Issue[] $issues
 * @property Issue[] $issues1
 * @property Issue[] $issues2
 * @property Issue[] $issues3
 * @property Issue[] $issues4
 * @property Project[] $projects
 * @property Project[] $projects1
 * @property Project[] $projects2
 * @property Project[] $projects3
 * @property StatusChangeHistory[] $statusChangeHistories
 * @property UserStatus $status0
 */
class User extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('full_name, username, email, password', 'required'),
            array('group_id, department, status', 'numerical', 'integerOnly' => true),
            array('full_name', 'length', 'max' => 150),
            array('username, email, password, activation', 'length', 'max' => 100),
            array('register_date, lastvisit, photo', 'safe'),
            array('username', 'unique'),
            array('email', 'unique'),
            array('email', 'email', 'checkMX' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, full_name, username, email, password, register_date, lastvisit, activation, group_id, department, status, picture', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'status0' => array(self::BELONGS_TO, 'UserStatus', 'status'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'full_name' => 'Name',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'register_date' => 'Register Date',
            'lastvisit' => 'Last Visit',
            'activation' => 'Activation',
            'group_id' => 'Group',
            'department' => 'Department',
            'status' => 'Status',
            'picture' => 'Picture',
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
        $criteria->compare('full_name', $this->full_name, true);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('register_date', $this->register_date, true);
        $criteria->compare('lastvisit', $this->lastvisit, true);
        $criteria->compare('activation', $this->activation, true);
        $criteria->compare('group_id', $this->group_id);
        $criteria->compare('department', $this->department);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize20'],
            ),
            'sort' => array('defaultOrder' => 'full_name ASC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function get_full_name($id) {
        $model = User::model()->findByPk($id);
        if (!empty($model->full_name)) {
            return $model->full_name;
        } else {
            return null;
        }
    }

    public static function get_email($id) {
        $model = User::model()->findByPk($id);
        if (!empty($model->email)) {
            return $model->email;
        } else {
            return null;
        }
    }

    public static function get_date_time($date) {
        if (empty($date) || $date == '0000-00-00' || $date == '0000-00-00 00:00:00') {
            return null;
        } else {
            return date("M j, Y, g:i:s A", strtotime($date));
        }
    }

    public static function get_date($date) {
        if (empty($date) || $date == '0000-00-00' || $date == '0000-00-00 00:00:00') {
            return null;
        } else {
            return date("M j, Y", strtotime($date));
        }
    }

    public static function get_date_ifexist($date) {
        if (empty($date) || $date == '0000-00-00' || $date == '0000-00-00 00:00:00') {
            return null;
        } else {
            return date("d-m-Y", strtotime($date));
        }
    }

    public static function get_alert_message() {
        if (Yii::app()->user->hasFlash('success')) {
            echo '<div class="alert alert-success fade in">';
            echo '<button class="close" data-dismiss="alert">×</button>';
            echo '<i class="fa-fw fa fa-check"></i>';
            echo Yii::app()->user->getFlash('success');
            echo '</div>';
        } elseif (Yii::app()->user->hasFlash('error')) {
            echo '<div class="alert alert-danger fade in">';
            echo '<button class="close" data-dismiss="alert">×</button>';
            echo '<i class="fa-fw fa fa-times"></i>';
            echo Yii::app()->user->getFlash('error');
            echo '</div>';
        }
    }
    
    /*
     * check supper user
     */

    public static function get_reference_id($id) {
        $model = User::model()->findByPk($id);
        if (!empty($model->group_id)) {
            return $model->group_id;
        } else {
            return null;
        }
    }

    /**
     * Send mail method
     */
    public static function sendMail($to, $subject, $message, $fromName, $fromMail) {
        $headers = "From: " . $fromName . "<" . $fromMail . "> \r\nX-Mailer: php\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";
        $message = wordwrap($message, 70);
        $message = str_replace("\n.", "\n..", $message);
        return mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $message, $headers);
    }

    /**
     * Send mail BCC method
     */
    public static function sendMailBCC($to, $subject, $message, $fromName, $fromMail, $bccList) {
        $headers = "From: " . $fromName . "<" . $fromMail . "> \r\nX-Mailer: php\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";
        $headers .= "Bcc: $bccList\r\n";
        $to = $fromMail;
        $message = wordwrap($message, 70);
        $message = str_replace("\n.", "\n..", $message);
        return mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $message, $headers);
    }

    public static function get_profile_picture($id) {
        $value = User::model()->findByAttributes(array('id' => $id));
        $filePath = Yii::app()->basePath . '/../uploads/user/thumb/' . $value->photo;
        if ((is_file($filePath)) && (file_exists($filePath))) {
            return CHtml::image(Yii::app()->baseUrl . '/uploads/user/thumb/' . $value->photo, 'Picture', array('alt' => 'Picture', 'class' => 'online', 'title' => '', 'style' => ''));
        } else {
            return CHtml::image(Yii::app()->baseUrl . '/uploads/user/thumb/male.png', 'Picture', array('alt' => 'Picture', 'class' => 'online', 'title' => '', 'style' => ''));
        }
    }

}
