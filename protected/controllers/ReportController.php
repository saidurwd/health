<?php

class ReportController extends Controller {

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
                'actions' => array(
                    'stocksummary',
                    'stocksummaryprint',
                    'stockreceive',
                    'stockreceiveprint',
                    'invoice', 'sales',
                    'salesprint', 'expiration',
                    'expirationprint',
                    'register',
                    'registerprint',
                    'disease',
                    'diseaseprint',
                    'category',
                    'categoryprint',
                    'medicine',
                    'medicineprint',
                    'service',
                    'serviceprint',
                    'mincome',
                    'mincomeprint',
                    'periodstock',
                    'periodstockprint',
                    'patinvoice',
                    'patinvoiceprint',
                    'registerphysio',
                    'registerphysioprint',
                    'prescription',
                    'prescriptionprint',
                    'contactregister',
                    'contactregisterprint',
                    'allserviceprint'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionAllserviceprint() {
        set_time_limit(0);
        $this->layout = '//layouts/report';
        $model = new Service('search_print');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Service']))
            $model->attributes = $_GET['Service'];

        $this->render('allserviceprint', array(
            'model' => $model,
        ));
    }

    public function actionStocksummary() {
        set_time_limit(0);
        $this->render('stocksummary');
    }

    public function actionStocksummaryprint() {
        set_time_limit(0);
        $this->layout = '//layouts/report';
        $this->render('stocksummaryprint');
    }

    public function actionStockreceive() {
        set_time_limit(0);
        $this->render('stockreceive');
    }

    public function actionStockreceiveprint() {
        set_time_limit(0);
        $this->layout = '//layouts/report';
        $this->render('stockreceiveprint');
    }

    public function actionSales() {
        set_time_limit(0);
        $this->render('sales');
    }

    public function actionSalesprint() {
        set_time_limit(0);
        $this->layout = '//layouts/report';
        $this->render('salesprint');
    }

    public function actionExpiration() {
        set_time_limit(0);
        $this->render('expiration');
    }

    public function actionExpirationprint() {
        set_time_limit(0);
        $this->layout = '//layouts/report';
        $this->render('expirationprint');
    }

    public function actionRegister() {
        set_time_limit(0);
        $this->render('register');
    }

    public function actionRegisterprint() {
        set_time_limit(0);
        $this->layout = '//layouts/report';
        $this->render('registerprint');
    }

    public function actionDisease() {
        set_time_limit(0);
        $this->render('disease');
    }

    public function actionDiseaseprint() {
        set_time_limit(0);
        $this->layout = '//layouts/report';
        $this->render('diseaseprint');
    }

    public function actionCategory() {
        set_time_limit(0);
        $this->render('category');
    }

    public function actionCategoryprint() {
        set_time_limit(0);
        $this->layout = '//layouts/report';
        $this->render('categoryprint');
    }

    public function actionMedicine() {
        set_time_limit(0);
        $this->render('medicine');
    }

    public function actionMedicineprint() {
        set_time_limit(0);
        $this->layout = '//layouts/report';
        $this->render('medicineprint');
    }

    public function actionService() {
        set_time_limit(0);
        $this->render('service');
    }

    public function actionServiceprint() {
        set_time_limit(0);
        $this->layout = '//layouts/report';
        $this->render('serviceprint');
    }

    public function actionMincome() {
        set_time_limit(0);
        $this->render('mincome');
    }

    public function actionMincomeprint() {
        set_time_limit(0);
        $this->layout = '//layouts/report';
        $this->render('mincomeprint');
    }

    public function actionPeriodstock() {
        set_time_limit(0);
        $this->render('periodstock');
    }

    public function actionPeriodstockprint() {
        set_time_limit(0);
        $this->layout = '//layouts/report';
        $this->render('periodstockprint');
    }

    public function actionPatinvoice() {
        set_time_limit(0);
        $this->render('patinvoice');
    }

    public function actionPatinvoiceprint() {
        set_time_limit(0);
        $this->layout = '//layouts/report';
        $this->render('patinvoiceprint');
    }

    public function actionRegisterphysio() {
        set_time_limit(0);
        $this->render('registerphysio');
    }

    public function actionRegisterphysioprint() {
        set_time_limit(0);
        $this->layout = '//layouts/report';
        $this->render('registerphysioprint');
    }

    public function actionPrescription() {
        set_time_limit(0);
        $this->render('prescription');
    }

    public function actionPrescriptionprint() {
        set_time_limit(0);
        $this->layout = '//layouts/report';
        $this->render('prescriptionprint');
    }

    public function actionContactregister() {
        set_time_limit(0);
        $this->render('contactregister');
    }

    public function actionContactregisterprint() {
        set_time_limit(0);
        $this->layout = '//layouts/report';
        $this->render('contactregisterprint');
    }

}
