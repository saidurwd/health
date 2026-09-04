<?php

class StockRequisitionController extends Controller {

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
                'actions' => array('admin', 'create', 'update', 'view', 'delete', 'add', 'remove', 'print', 'convertissue'),
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

    public function actionConvertissue($id) {
        $modelParent = new StockIssueParentSearch;
        $modelParent->issue_date = date("Y-m-d G:i:s");
        $modelParent->issue_number = StockIssueParent::generateIssueNumber();
        $modelParent->issue_by = Yii::app()->user->id;
        $modelParent->status = 0;
        $modelParent->created_by = Yii::app()->user->id;
        $modelParent->created_on = date("Y-m-d G:i:s");
        //$modelParent->save();
        if (!$modelParent->save()) {
            print_r($modelParent->getErrors()); exit;
        } else {
            //get child data
            $child = StockRequisition::model()->findAll(array('condition' => 'parent=' . (int) $id . ' AND converted = 0'));
            foreach ($child as $key => $values) {
                $model = new StockIssue;
                $model->parent = $modelParent->id;
                $model->reference = $values['id'];
                $model->item = $values['item'];
                $model->quantity = $values['quantity'];
                $model->rate = $values['rate'];
                $model->amount = $values['amount'];
                $model->store = $values['store'];
                $model->batch = $values['batch'];
                $model->created_by = Yii::app()->user->id;
                $model->created_on = date("Y-m-d G:i:s");
                $model->save();
            }
            //get total amount
            $total_amount = StockIssue::getTotalAmount($modelParent->id);
            StockIssueParent::model()->updateAll(array('total_amount' => $total_amount), 'id=' . (int) $modelParent->id);

            //set as converted when issued
            StockRequisition::model()->updateAll(array('converted' => 1), 'parent=' . (int) $id);

            //redirection 
            Yii::app()->user->setFlash('success', 'Data was saved successfully');
            $this->redirect(array('stockIssue/update', 'id' => $modelParent->id));
        }
    }

    public function actionPrint($id) {
        $this->layout = '//layouts/report';
        $parent = $this->loadModelParent($id);

        //List of order items
        $model = new StockRequisition('searchRequisition');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['StockRequisition']))
            $model->attributes = $_GET['StockRequisition'];

        $this->render('print', array(
            'model' => $model,
            'parent' => $parent,
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        //get Requisition Parent
        $parent = $this->loadModelParent($id);

        //List of order items
        $model = new StockRequisition('searchRequisition');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['StockRequisition']))
            $model->attributes = $_GET['StockRequisition'];

        $this->render('view', array(
            'model' => $model,
            'parent' => $parent,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionAdd() {
        $model = new StockRequisition;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['StockRequisition'])) {
            $model->attributes = $_POST['StockRequisition'];
            $model->rate = StockRequisition::genarateItemBuyRate($model->item, $model->store, $model->batch);
            $model->amount = round(($model->quantity * $model->rate), 6);
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

    public function actionCreate() {
        $model = new StockRequisition;
        $modelParent = new StockRequisitionParent;

        //List of added item
        $modelGrid = new StockRequisition('search');
        $modelGrid->unsetAttributes();  // clear any default values
        if (isset($_GET['StockRequisition']))
            $modelGrid->attributes = $_GET['StockRequisition'];

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['StockRequisitionParent'])) {
            $modelParent->attributes = $_POST['StockRequisitionParent'];
            $modelParent->requisition_date = date("Y-m-d G:i:s");
            $modelParent->requisition_by = Yii::app()->user->id;
            $modelParent->requisition_number = StockRequisitionParent::generateRequisitionNumber();
            $modelParent->status = 0;
            $modelParent->created_by = Yii::app()->user->id;
            $modelParent->created_on = date("Y-m-d G:i:s");
            if ($modelParent->save()) {
                StockRequisition::model()->updateAll(array('parent' => $modelParent->id), 'parent=0 AND created_by=' . (int) Yii::app()->user->id);
                Yii::app()->user->setFlash('success', 'Data was saved successfully');
                //$this->redirect(array('admin'));
                $this->redirect(array('view', 'id' => $modelParent->id));
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

        if (isset($_POST['StockRequisitionParent'])) {
            $modelParent->attributes = $_POST['StockRequisitionParent'];
            if ($modelParent->save()) {
                Yii::app()->user->setFlash('success', 'Data was saved successfully');
                //$this->redirect(array('admin'));
                $this->redirect(array('view', 'id' => $modelParent->id));
            }
        }

        //List of requisition items
        $model = new StockRequisition('searchRequisition');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['StockRequisition']))
            $model->attributes = $_GET['StockRequisition'];

        $this->render('update', array(
            'model' => $model,
            'modelParent' => $modelParent,
        ));
    }

    public function actionRemove($id) {
        StockRequisitionParent::model()->updateAll(array('status' => 2), 'id=' . (int) $id);
        Yii::app()->user->setFlash('success', 'Stock Requisition was deleted successfully.');
        $this->redirect(array('admin'));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
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
        $dataProvider = new CActiveDataProvider('StockRequisition');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new StockRequisitionParent('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['StockRequisitionParent']))
            $model->attributes = $_GET['StockRequisitionParent'];
        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return StockRequisition the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = StockRequisition::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function loadModelParent($id) {
        $model = StockRequisitionParent::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param StockRequisition $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'stock-requisition-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
