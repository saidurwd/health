<?php

class PurchaseOrderController extends Controller {

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
                'actions' => array('admin', 'create', 'update', 'view', 'delete', 'add', 'remove', 'print'),
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

    public function actionPrint($id) {
        $this->layout = '//layouts/report';

        $model = new PurchaseOrder('searchOrder');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PurchaseOrder']))
            $model->attributes = $_GET['PurchaseOrder'];

        $parent = $this->loadModelParent($id);

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
        //List of order items
        $model = new PurchaseOrder('searchOrder');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PurchaseOrder']))
            $model->attributes = $_GET['PurchaseOrder'];

        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionAdd() {
        $model = new PurchaseOrder;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['PurchaseOrder'])) {
            $model->attributes = $_POST['PurchaseOrder'];
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

    public function actionCreate() {
        $model = new PurchaseOrder;
        $modelParent = new PurchaseOrderParent;

        //List of added item
        $modelGrid = new PurchaseOrder('search');
        $modelGrid->unsetAttributes();  // clear any default values
        if (isset($_GET['PurchaseOrder']))
            $modelGrid->attributes = $_GET['PurchaseOrder'];

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PurchaseOrderParent'])) {
            $modelParent->attributes = $_POST['PurchaseOrderParent'];
            $modelParent->order_date = date("Y-m-d G:i:s");
            $modelParent->order_number = PurchaseOrderParent::generatePurchaseOrderNumber();
            $modelParent->order_by = Yii::app()->user->id;
            $modelParent->status = 0;
            $modelParent->created_by = Yii::app()->user->id;
            $modelParent->created_on = date("Y-m-d G:i:s");
            if ($modelParent->save()) {
                PurchaseOrder::model()->updateAll(array('parent' => $modelParent->id), 'parent=0 AND created_by=' . (int) Yii::app()->user->id);
                Yii::app()->user->setFlash('success', 'Data was saved successfully');
                $this->redirect(array('admin'));
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

        if (isset($_POST['PurchaseOrderParent'])) {
            $modelParent->attributes = $_POST['PurchaseOrderParent'];
            if ($modelParent->save()) {
                Yii::app()->user->setFlash('success', 'Data was saved successfully');
                $this->redirect(array('admin'));
            }
        }

        //List of order items
        $model = new PurchaseOrder('searchOrder');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PurchaseOrder']))
            $model->attributes = $_GET['PurchaseOrder'];

        $this->render('update', array(
            'model' => $model,
            'modelParent' => $modelParent,
        ));
    }

    public function actionRemove($id) {
        PurchaseOrderParent::model()->updateAll(array('status' => 2), 'id=' . (int) $id);
        Yii::app()->user->setFlash('success', 'Purchase Order was deleted successfully.');
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
        $dataProvider = new CActiveDataProvider('PurchaseOrder');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new PurchaseOrderParent('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PurchaseOrderParent']))
            $model->attributes = $_GET['PurchaseOrderParent'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return PurchaseOrder the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = PurchaseOrder::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function loadModelParent($id) {
        $model = PurchaseOrderParent::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param PurchaseOrder $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'purchase-order-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
