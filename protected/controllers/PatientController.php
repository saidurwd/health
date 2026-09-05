<?php

class PatientController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    protected function beforeAction($action) {
        $access = $this->checkAccess(Yii::app()->controller->id, Yii::app()->controller->action->id);
        if ($access == 1) {
            return true;
        } else {
            Yii::app()->user->setFlash('error', "You are not authorized to perform this action!");
            $this->redirect(array('/site/noaccess'));
        }
    }

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('*'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('admin', 'delete', 'create', 'update', 'view', 'card', 'newprescription', 'editprescription', 'remove', 'prescription', 'preblank', 'addmedicine', 'removemedicine', 'rehabilitation', 'registration', 'autocomplete'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionAddmedicine() {
        $model = new PrescriptionMedicine;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidationPrescription($model);

        if (isset($_POST['PrescriptionMedicine'])) {
            $model->attributes = $_POST['PrescriptionMedicine'];
            $model->product = Product::getData($model->product, 'title');
            //$model->parent = 0;
            $model->created_by = Yii::app()->user->id;
            $model->created_on = date("Y-m-d G:i:s");
            if ($model->validate()) {
                if ($model->save()) {
                    echo $model->id;
                } else {
                    echo "false";
                }
            }
        }
    }

    public function actionCard($id) {
        $this->layout = '//layouts/report';
        $this->render('card', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionRehabilitation($id) {
        $this->layout = '//layouts/report';
        $this->render('rehabilitation', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionRegistration($id) {
        $this->layout = '//layouts/report';
        $this->render('registration', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionPrescription($id) {
        $this->layout = '//layouts/report';
        $this->render('prescription', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionPreblank($id) {
        $this->layout = '//layouts/report';
        $this->render('preblank', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        //Related Invoice
        $model_invoice = new InvoiceParent('search_patient');
        $model_invoice->unsetAttributes();  // clear any default values
        if (isset($_GET['InvoiceParent']))
            $model_invoice->attributes = $_GET['InvoiceParent'];

        //Related Prescription
        $model_prescription = new PatientPrescription('search_patient');
        $model_prescription->unsetAttributes();  // clear any default values
        if (isset($_GET['PatientPrescription']))
            $model_prescription->attributes = $_GET['PatientPrescription'];

        $this->render('view', array(
            'model' => $this->loadModel($id),
            'model_invoice' => $model_invoice,
            'model_prescription' => $model_prescription,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Patient;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);               

        if (isset($_POST['Patient'])) {
            $model->attributes = $_POST['Patient'];
            $model->pat_id = Patient::autoPatientNumber();
            if (!empty($model->birth_date) || $model->birth_date != '0000-00-00') {
                $model->age = Patient::getAgeYear($model->birth_date);
            }
            if (empty($model->birth_date) || $model->birth_date == '0000-00-00') {
                $model->birth_date = Patient::getAgeToDate($model->age, $model->age_type);
            }
            $model->created_on = date("Y-m-d G:i:s");
            $model->created_by = Yii::app()->user->id;
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'Data was saved successfully');
                $this->redirect(array('admin'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionNewprescription($id) {
        $model = new PatientPrescription();
        $modelData = new PrescriptionMedicine();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PatientPrescription'])) {
            $model->attributes = $_POST['PatientPrescription'];
            $model->patient = $id;
            $model->pre_number = PatientPrescription::autoPrescriptionNumber();
            $model->created_on = date("Y-m-d G:i:s");
            $model->created_by = Yii::app()->user->id;
            if ($model->save()) {
                PrescriptionMedicine::model()->updateAll(array('parent' => $model->id), '(parent IS NULL OR parent=0) AND created_by=' . (int) Yii::app()->user->id);
                Yii::app()->user->setFlash('success', 'Data was saved successfully');
                $this->redirect(array('view', 'id' => $id));
            }
        }

        //List of item
        $modelGrid = new PrescriptionMedicine('search');
        $modelGrid->unsetAttributes();  // clear any default values
        if (isset($_GET['PrescriptionMedicine']))
            $modelGrid->attributes = $_GET['PrescriptionMedicine'];

        $this->render('newprescription', array(
            'model' => $model,
            'modelData' => $modelData,
            'modelGrid' => $modelGrid,
        ));
    }

    public function actionEditprescription($id) {
        $model = $this->loadModelPrescription($id);
        //$modelData = $this->loadModelPrescriptionMedicine($id);
        $modelData = new PrescriptionMedicine();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PatientPrescription'])) {
            $model->attributes = $_POST['PatientPrescription'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'Data was saved successfully');
                $this->clearPatientCache();
                $this->redirect(array('admin'));
            }
        }

        //List of item
        $modelGrid = new PrescriptionMedicine('search');
        $modelGrid->unsetAttributes();  // clear any default values
        if (isset($_GET['PrescriptionMedicine']))
            $modelGrid->attributes = $_GET['PrescriptionMedicine'];

        $this->render('editprescription', array(
            'model' => $model,
            'modelData' => $modelData,
            'modelGrid' => $modelGrid,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Patient'])) {
            $model->attributes = $_POST['Patient'];
            if ((!empty($model->birth_date) || $model->birth_date != '0000-00-00') && empty($model->age)) {
                $model->age = Patient::getAgeYear($model->birth_date);
            }
            if ((empty($model->birth_date) || $model->birth_date == '0000-00-00') && !empty($model->age)) {
                $model->birth_date = Patient::getAgeToDate($model->age, $model->age_type);
            }
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'Data was saved successfully');
                $this->clearPatientCache();
                $this->redirect(array('admin'));
            }
        }

        $this->render('update', array(
            'model' => $model,
        )    );
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();
        $this->clearPatientCache();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    public function actionRemove($id) {
        $this->loadModelPrescription($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    public function actionRemovemedicine($id) {
        $this->loadModelPrescriptionMedicine($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $this->redirect(array('admin'));
        $dataProvider = new CActiveDataProvider('Patient');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Patient('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Patient']))
            $model->attributes = $_GET['Patient'];

        $cacheKey = 'PatientAdmin_' . md5(serialize($model->attributes));
        $cached = Yii::app()->cache->get($cacheKey);
        if ($cached !== false && empty($model->attributes)) {
            $this->render('admin', $cached);
            return;
        }

        $data = array('model' => $model);
        if (empty($model->attributes)) {
            Yii::app()->cache->set($cacheKey, $data, 120);
        }

        $this->render('admin', $data);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Patient the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Patient::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function loadModelPrescription($id) {
        $model = PatientPrescription::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function loadModelPrescriptionMedicine($id) {
        $model = PrescriptionMedicine::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function clearPatientCache() {
        Yii::app()->cache->delete('PatientAdmin_' . md5(''));
        Yii::app()->cache->delete('PatientDropdowns');
    }

    /**
     * Performs the AJAX validation.
     * @param Patient $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'patient-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    protected function performAjaxValidationPrescription($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'prescription-medicine-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionAutocomplete() {
        if (isset($_GET['id'])) {
            $patient = Patient::model()->findByPk((int) $_GET['id']);
            if ($patient) {
                echo CJSON::encode(array(array(
                    'id' => $patient->id,
                    'text' => $patient->name . ' [' . $patient->pat_id . ']',
                )));
            } else {
                echo CJSON::encode(array());
            }
            Yii::app()->end();
        }

        if (!isset($_GET['q'])) {
            echo CJSON::encode(array());
            Yii::app()->end();
        }

        $term = trim($_GET['q']);
        $criteria = new CDbCriteria;
        $criteria->select = 'id, CONCAT(name, " [", pat_id, "]") AS text';
        $criteria->compare('name', $term, true, 'OR');
        $criteria->compare('pat_id', $term, true, 'OR');
        $criteria->order = 'name ASC';
        $criteria->limit = 20;

        $patients = Patient::model()->findAll($criteria);
        $results = array();
        foreach ($patients as $patient) {
            $results[] = array(
                'id' => $patient->id,
                'text' => $patient->name . ' [' . $patient->pat_id . ']',
            );
        }

        echo CJSON::encode($results);
        Yii::app()->end();
    }

}
