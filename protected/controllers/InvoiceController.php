<?php

class InvoiceController extends Controller {

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
            array(
                'allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('*'),
                'users' => array('*'),
            ),
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('admin', 'create', 'update', 'edit', 'view', 'delete', 'add', 'remove', 'rollback', 'adjustment', 'adjustmentEdit', 'print'),
                'users' => array('@'),
            ),
            array(
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array(
                'deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionPrint($id) {
        $this->layout = '//layouts/report';
        $parent = $this->loadModelParent($id);

        //List of order items
        $model = new Invoice('searchInvoice');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Invoice']))
            $model->attributes = $_GET['Invoice'];

        $this->render('print', array(
            'model' => $model,
            'parent' => $parent,
        ));
    }

    public function actionAdjustment($id, $adjustment, $type) {
        $model = Invoice::model()->findByPk(((int) $id));
        if ($type == "quantity") {
            $model->quantity = $adjustment;
            $model->total = ($model->quantity * $model->rate);
            if ($model->item > 0) {
                $model->discount = round(($model->total * ((int) Yii::app()->params['discountMedicine'] / 100)), 6);
            } else {
                $model->discountstatus = Service::getData($model->service, 'discount');
                if ($model->discountstatus == 'Yes') {
                    $model->discount = round(($model->total * ((int) Yii::app()->params['discountService'] / 100)), 6);
                } else {
                    $model->discount = 0;
                }
            }
            $model->amount = round(($model->total - $model->discount), 6);
            if ($model->save()) {
                echo $model->id;
            }
        }
    }

    public function actionAdjustmentEdit($id, $adjustment, $type) {
        $model = Invoice::model()->findByPk(((int) $id));
        $prevQty = $model->quantity;
        if ($type == "quantity") {
            $model->quantity = $adjustment;
            $model->total = ($model->quantity * $model->rate);
            if ($model->item > 0) {
                $model->discount = round(($model->total * ((int) Yii::app()->params['discountMedicine'] / 100)), 6);
            } else {
                $model->discountstatus = Service::getData($model->service, 'discount');
                if ($model->discountstatus == 'Yes') {
                    $model->discount = round(($model->total * ((int) Yii::app()->params['discountService'] / 100)), 6);
                } else {
                    $model->discount = 0;
                }
            }
            $model->amount = round(($model->total - $model->discount), 6);
            if ($model->save()) {
                //update Stock Summary 
                if ($model->item > 0) {
                    StockSummary::receiveStockSummary($model->store, $model->item, $model->batch, $prevQty);
                    StockSummary::issueStockSummary($model->store, $model->item, $model->batch, $model->quantity);
                }
            }
        }
    }

    public function actionAdd() {
        $model = new Invoice;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['Invoice'])) {
            $model->attributes = $_POST['Invoice'];
            $rate_status = Service::getData($model->service, 'rate_status');
            if ($model->servicetype == 'Medicine') {
                $model->service = NULL;
                $model->rate = StockRequisition::genarateItemRate($model->item, $model->store, $model->batch);
                $model->total = round(($model->quantity * $model->rate), 6);
                $model->discount = round(($model->total * ((int) Yii::app()->params['discountMedicine'] / 100)), 6);
                $model->amount = round(($model->total - $model->discount), 6);
            }
            if ($model->servicetype == 'Service') {
                //$model->discountstatus = Service::getData($model->service, 'discount');
                $model->item = NULL;
                $model->store = NULL;
                $model->batch = NULL;
                //check Auto /Manual rate entry
                if ($rate_status == 'Auto') {
                    $model->rate = Service::getData($model->service, 'rate');
                    $model->total = round(($model->quantity * $model->rate), 6);
                } else {
                    $model->total = round(($model->quantity * $model->rate), 6);
                }
                if (empty($model->discountamount)) {
                    $model->discountamount = 0;
                }
                if ($model->discounttype == 'Percentage') {
                    $model->discount = round(($model->total * ((int) $model->discountamount / 100)), 6);
                }
                if ($model->discounttype == 'Cash') {
                    $model->discount = round($model->discountamount, 6);
                }

                //                if ($model->discountstatus == 'Yes') {
                //                    $model->discount = round(($model->total * ((int) Yii::app()->params['discountService'] / 100)), 6);
                //                } else {
                //                    $model->discount = 0;
                //                }
                $model->amount = round(($model->total - $model->discount), 6);
            }
            $model->created_by = Yii::app()->user->id;
            $model->created_on = date("Y-m-d G:i:s");

            if ($model->validate()) {
                //                if (!$model->save()) {
                //                    print_r($model->getErrors());
                //                }
                if ($model->save()) {
                    echo $model->id;
                } else {
                    echo "false";
                }
            }
        }
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        //List of Invoice items
        $model = new Invoice('searchInvoice');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Invoice']))
            $model->attributes = $_GET['Invoice'];

        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Invoice;
        $modelParent = new InvoiceParent;

        //List of Invoice item
        $modelGrid = new Invoice('search');
        $modelGrid->unsetAttributes();  // clear any default values
        if (isset($_GET['Invoice']))
            $modelGrid->attributes = $_GET['Invoice'];

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['InvoiceParent'])) {
            $modelParent->attributes = $_POST['InvoiceParent'];
            $modelParent->invoice_date = date("Y-m-d G:i:s");
            $modelParent->invoice_number = InvoiceParent::generateInvoiceNumber();
            $modelParent->invoice_by = Yii::app()->user->id;
            $modelParent->status = 0;
            $modelParent->created_by = Yii::app()->user->id;
            $modelParent->created_on = date("Y-m-d G:i:s");

            if ($modelParent->save()) {
                Invoice::model()->updateAll(array('parent' => $modelParent->id), 'parent=0 AND created_by=' . (int) Yii::app()->user->id);
                //get total amount
                $total_amount = Invoice::getTotalAmount($modelParent->id);
                $patient_category_new = Patient::getData($modelParent->patient, 'category_new');
                $patient_category = Patient::getData($modelParent->patient, 'category');
                InvoiceParent::model()->updateAll(array('total_amount' => $total_amount, 'patient_category_new' => $patient_category_new, 'patient_category' => $patient_category), 'id=' . (int) $modelParent->id);

                Yii::app()->user->setFlash('success', 'Invoice was CREATED successfully.');
                $this->redirect(array('update', 'id' => $modelParent->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
            'modelGrid' => $modelGrid,
            'modelParent' => $modelParent,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {

        $modelParent = $this->loadModelParent($id);

        if ($modelParent->status == 1) {
            Yii::app()->user->setFlash('error', 'You are not authorized to perform this action!');
            $this->redirect(array('admin'));
        }

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['InvoiceParent'])) {
            $modelParent->attributes = $_POST['InvoiceParent'];
            $modelParent->total_amount = Invoice::getTotalAmount($modelParent->id);
            if ($modelParent->save()) {
                //update stock summary when status approved
                if ($modelParent->status == 1) {
                    $criteria = new CDbCriteria;
                    //$criteria->compare('parent', (int) $id);
                    $criteria->condition = 'parent = ' . (int) $id . ' AND item>0';
                    $dataArray = Invoice::model()->findAll($criteria);
                    foreach ($dataArray as $key => $values) {
                        //update stock summary
                        StockSummary::issueStockSummary($values['store'], $values['item'], $values['batch'], $values['quantity']);
                    }
                }

                Yii::app()->user->setFlash('success', 'Invoice was UPDATED successfully.');
                $this->redirect(array('admin'));
            }
        }

        //List of requisition items
        $model = new Invoice('searchInvoice');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Invoice']))
            $model->attributes = $_GET['Invoice'];

        $this->render('update', array(
            'model' => $model,
            'modelParent' => $modelParent,
        ));
    }

    public function actionRollback($id) {
        $modelParent = $this->loadModelParent($id);

        if ($modelParent->status == 1) {
            Yii::app()->user->setFlash('error', 'You are not authorized to perform this action!');
            $this->redirect(array('admin'));
        }

        //Only supper user can edit Received PR
        $isAdmin = User::get_reference_id(Yii::app()->user->id);
        if ($isAdmin != 1) {
            Yii::app()->user->setFlash('error', 'You are not authorized to perform this action!');
            $this->redirect(array('admin'));
        }

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['InvoiceParent'])) {
            $modelParent->attributes = $_POST['InvoiceParent'];
            $modelParent->total_amount = Invoice::getTotalAmount($modelParent->id);
            if ($modelParent->save()) {
                //update stock summary when status approved
                if ($modelParent->status == 1) {
                    $criteria = new CDbCriteria;
                    //$criteria->compare('parent', (int) $id);
                    $criteria->condition = 'parent = ' . (int) $id . ' AND item>0';
                    $dataArray = Invoice::model()->findAll($criteria);
                    foreach ($dataArray as $key => $values) {
                        //update stock summary
                        StockSummary::issueStockSummary($values['store'], $values['item'], $values['batch'], $values['quantity']);
                    }
                }

                Yii::app()->user->setFlash('success', 'Invoice was UPDATED successfully.');
                $this->redirect(array('admin'));
            }
        }

        //List of requisition items
        $model = new Invoice('searchInvoice');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Invoice']))
            $model->attributes = $_GET['Invoice'];

        $this->render('rollback', array(
            'model' => $model,
            'modelParent' => $modelParent,
        ));
    }

    public function actionEdit($id) {
        $modelParent = $this->loadModelParent($id);

        //Only Received PR permitted for this action
        if ($modelParent->status != 1) {
            Yii::app()->user->setFlash('error', 'You are not authorized to perform this action!');
            $this->redirect(array('admin'));
        }

        //Only supper user can edit Received PR
        $isAdmin = User::get_reference_id(Yii::app()->user->id);
        if ($isAdmin != 1) {
            Yii::app()->user->setFlash('error', 'You are not authorized to perform this action!');
            $this->redirect(array('admin'));
        }

        //List of invoice items
        $model = new Invoice('searchInvoice');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Invoice']))
            $model->attributes = $_GET['Invoice'];

        if (isset($_POST['InvoiceParent'])) {
            $modelParent->attributes = $_POST['InvoiceParent'];
            if ($modelParent->save()) {
                Yii::app()->user->setFlash('success', 'Invoice was UPDATED successfully.');
                $this->redirect(array('admin'));
            }
        }

        $this->render('edit', array(
            'model' => $model,
            'modelParent' => $modelParent,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        /*
         * when delete added item that was reference stock receive
         * reverse to the list that coming from LoadSR
         */
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $this->redirect(array('admin'));
        $dataProvider = new CActiveDataProvider('Invoice');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new InvoiceParent('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['InvoiceParent']))
            $model->attributes = $_GET['InvoiceParent'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionRemove($id) {
        InvoiceParent::model()->updateAll(array('status' => 2), 'id=' . (int) $id);
        Yii::app()->user->setFlash('success', 'Invoice was deleted successfully.');
        $this->redirect(array('admin'));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Invoice the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Invoice::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function loadModelParent($id) {
        $model = InvoiceParent::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Invoice $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'invoice-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
