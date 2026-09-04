<?php

/**
 * This is the model class for table "{{backup}}".
 *
 * The followings are the available columns in table '{{backup}}':
 * @property integer $id
 * @property string $attachment
 * @property string $created_on
 * @property integer $created_by
 *
 * The followings are the available model relations:
 * @property User $createdBy
 */
class Backup extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{backup}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('created_on', 'required'),
            array('created_by', 'numerical', 'integerOnly' => true),
            array('attachment', 'length', 'max' => 250),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, attachment, created_on, created_by', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'createdBy' => array(self::BELONGS_TO, 'User', 'created_by'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'attachment' => 'Attachment',
            'created_on' => 'Created On',
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
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('attachment', $this->attachment, true);
        $criteria->compare('created_on', $this->created_on, true);
        $criteria->compare('created_by', $this->created_by);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize'],
            ),
            'sort' => array('defaultOrder' => 'created_on DESC, id DESC')
        )
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Backup the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function getData($id, $field)
    {
        $model = Backup::model()->findByAttributes(array('id' => $id));
        if (empty($model->$field)) {
            return null;
        } else {
            return $model->$field;
        }
    }

}
