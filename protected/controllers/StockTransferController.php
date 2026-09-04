<?php

class StockTransferController extends Controller {

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
                'actions' => array('admin', 'create', 'update', 'view', 'delete', 'add', 'remove', 'print',),
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

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $modelParent = $this->loadModelParent($id);

        //List of Issues items
        $model = new StockTransfer('searchTransfer');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['StockTransfer']))
            $model->attributes = $_GET['StockTransfer'];

        $this->render('view', array(
            'model' => $model,
            'modelParent' => $modelParent,
        ));
    }

    public function actionPrint($id) {
        $this->layout = '//layouts/report';
        $parent = $this->loadModelParent($id);

        //List of order items
        $model = new StockTransfer('searchTransfer');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['StockTransfer']))
            $model->attributes = $_GET['StockTransfer'];

        $this->render('print', array(
            'model' => $model,
            'parent' => $parent,
        ));
    }

    public function actionAdd() {
        $model = new StockTransfer;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['StockTransfer'])) {
            $model->attributes = $_POST['StockTransfer'];
            $model->rate = StockRequisition::genarateItemBuyRate($model->item, $model->batch);
            $model->total_amount = round(($model->quantity * $model->rate), 6);
            $model->created_by = Yii::app()->user->id;
            $model->created_on = date("Y-m-d G:i:s");
            if ($model->save()) {
                echo $model->id;
            } else {
                echo "false";
            }
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new StockTransfer;
        $modelParent = new StockTransferParent;
        $batch = new Batch;

        //List of added item
        $modelGrid = new StockTransfer('search');
        $modelGrid->unsetAttributes();  // clear any default values
        if (isset($_GET['StockTransfer']))
            $modelGrid->attributes = $_GET['StockTransfer'];

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['StockTransferParent'])) {
            $modelParent->attributes = $_POST['StockTransferParent'];
            $modelParent->transfer_date = date("Y-m-d G:i:s");
            $modelParent->transfer_number = StockTransferParent::generateStockTransferNumber();
            $modelParent->transfer_by = Yii::app()->user->id;
            $modelParent->status = 0;
            $modelParent->created_by = Yii::app()->user->id;
            $modelParent->created_on = date("Y-m-d G:i:s");

            if ($modelParent->save()) {
                StockTransfer::model()->updateAll(array('parent' => $modelParent->id), 'parent=0 AND created_by=' . (int) Yii::app()->user->id);

                Yii::app()->user->setFlash('success', 'Data was saved successfully');
                $this->redirect(array('admin'));
                //$this->redirect(array('update', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
            'modelGrid' => $modelGrid,
            'modelParent' => $modelParent,
            'batch' => $batch,
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

        if (isset($_POST['StockTransferParent'])) {
            $modelParent->attributes = $_POST['StockTransferParent'];

            if ($modelParent->save()) {
                //update stock summary when status approved
                if ($modelParent->status == 1) {
                    $criteria = new CDbCriteria;
                    $criteria->compare('parent', (int) $id);
                    $dataArray = StockTransfer::model()->findAll($criteria);
                    foreach ($dataArray as $key => $values) {
                        StockSummary::receiveStockSummary($values['store_to'], $values['item'], $values['batch'], $values['quantity']);
                        StockSummary::issueStockSummary($values['store_from'], $values['item'], $values['batch'], $values['quantity']);
                    }
                }

                Yii::app()->user->setFlash('success', 'Data was saved successfully');
                $this->redirect(array('admin'));
            }
        }

        //List of requisition items
        $model = new StockTransfer('searchTransfer');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['StockTransfer']))
            $model->attributes = $_GET['StockTransfer'];

        $this->render('update', array(
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
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    public function actionRemove($id) {
        StockTransferParent::model()->updateAll(array('status' => 2), 'id=' . (int) $id);
        Yii::app()->user->setFlash('success', 'Stock Transfer was deleted successfully.');
        $this->redirect(array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('StockTransfer');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new StockTransferParent('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['StockTransferParent']))
            $model->attributes = $_GET['StockTransferParent'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return StockTransfer the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = StockTransfer::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function loadModelParent($id) {
        $model = StockTransferParent::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param StockTransfer $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'stock-transfer-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
