<?php

class StockIssueController extends Controller {

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
                'actions' => array('admin', 'create', 'update', 'edit', 'view', 'delete', 'add', 'remove', 'addsr', 'adjustment', 'adjustmentEdit', 'print'),
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
        $parent = $this->loadModelParent($id);

        //List of order items
        $model = new StockIssue('searchIssue');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['StockIssue']))
            $model->attributes = $_GET['StockIssue'];

        $this->render('print', array(
            'model' => $model,
            'parent' => $parent,
        ));
    }

    public function actionAdjustment($id, $adjustment, $type) {
        $model = StockIssue::model()->findByPk(((int) $id));
        if ($type == "quantity") {
            $model->quantity = $adjustment;
            $model->amount = ($model->quantity * $model->rate);
            if ($model->save()) {
                if ($model->reference) {
                    StockRequisitionHistory::model()->updateAll(array('quantity' => $model->quantity), 'requisition_number=' . (int) $model->reference . ' AND issue_number=' . (int) $model->id);
                    //check available Quantity
                    $totalOrdered = StockRequisitionHistory::getAvailableQuantity($model->reference);
                    $modelPO = StockRequisition::model()->findByPk(((int) $model->reference));
                    if ($modelPO->quantity <= $totalOrdered) {
                        $modelPO->converted = 1;
                        $modelPO->save();
                    } else {
                        $modelPO->converted = 0;
                        $modelPO->save();
                    }
                }
            }
        }
    }

    public function actionAdjustmentEdit($id, $adjustment, $type) {
        $model = StockIssue::model()->findByPk(((int) $id));
        $prevQty = $model->quantity;
        if ($type == "quantity") {
            $model->quantity = $adjustment;
            $model->amount = ($model->quantity * $model->rate);
            if ($model->save()) {
                if ($model->reference) {
                    StockRequisitionHistory::model()->updateAll(array('quantity' => $model->quantity), 'requisition_number=' . (int) $model->reference . ' AND issue_number=' . (int) $model->id);
                    //check available Quantity
                    $totalOrdered = StockRequisitionHistory::getAvailableQuantity($model->reference);
                    $modelPO = StockRequisition::model()->findByPk(((int) $model->reference));
                    if ($modelPO->quantity <= $totalOrdered) {
                        $modelPO->converted = 1;
                        $modelPO->save();
                    } else {
                        $modelPO->converted = 0;
                        $modelPO->save();
                    }
                }
                //update Stock Summary 
                StockSummary::receiveStockSummary($model->store, $model->item, $model->batch, $prevQty);
                StockSummary::issueStockSummary($model->store, $model->item, $model->batch, $model->quantity);
            }
        }
    }

    public function actionAdd() {
        $model = new StockIssue;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['StockIssue'])) {
            $model->attributes = $_POST['StockIssue'];
            $model->rate = StockRequisition::genarateItemBuyRate($model->item, $model->store, $model->batch);
            $model->amount = round(($model->quantity * $model->rate), 6);
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

    public function actionAddsr() {
        $model = new StockIssue;
        $modelSR = StockRequisition::model()->findByPk(((int) $_REQUEST['id']));
        if (Yii::app()->request->isAjaxRequest) {
            $model->parent = 0;
            $model->reference = $modelSR->id;
            $model->item = $modelSR->item;
            $model->quantity = StockRequisition::getAvailableQuantity($modelSR->id);
            $model->rate = $modelSR->rate;
            $model->amount = $modelSR->amount;
            $model->store = $modelSR->store;
            $model->batch = $modelSR->batch;
            $model->created_by = Yii::app()->user->id;
            $model->created_on = date("Y-m-d G:i:s");
            if ($model->save()) {
                //Stock Requisition History [keep converted/non converted history data]
                $modelHistory = new StockRequisitionHistory;
                $modelHistory->requisition_number = $modelSR->id;
                $modelHistory->issue_number = $model->id;
                $modelHistory->item = $modelSR->item;
                $modelHistory->quantity = StockRequisition::getAvailableQuantity($modelSR->id);
                $modelHistory->converted = 0;
                $modelHistory->created_by = Yii::app()->user->id;
                $modelHistory->created_on = date("Y-m-d G:i:s");
                $modelHistory->save();

                //check available Quantity
                $totalOrdered = StockRequisitionHistory::getAvailableQuantity($modelSR->id);
                if ($modelSR->quantity <= $totalOrdered) {
                    $modelSR->converted = 1;
                    $modelSR->save();
                }

                echo $model->id;
            } else {
                echo "false";
            }
            return;
        }
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        //List of Issues items
        $model = new StockIssue('searchIssue');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['StockIssue']))
            $model->attributes = $_GET['StockIssue'];

        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new StockIssue;
        $modelParent = new StockIssueParent;

        //List of issues item
        $modelGrid = new StockIssue('search');
        $modelGrid->unsetAttributes();  // clear any default values
        if (isset($_GET['StockIssue']))
            $modelGrid->attributes = $_GET['StockIssue'];

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['StockIssueParent'])) {
            $modelParent->attributes = $_POST['StockIssueParent'];
            $modelParent->issue_date = date("Y-m-d G:i:s");
            $modelParent->issue_number = StockIssueParent::generateIssueNumber();
            $modelParent->issue_by = Yii::app()->user->id;
            $modelParent->status = 0;
            $modelParent->created_by = Yii::app()->user->id;
            $modelParent->created_on = date("Y-m-d G:i:s");

            if ($modelParent->save()) {
                StockIssue::model()->updateAll(array('parent' => $modelParent->id), 'parent=0 AND created_by=' . (int) Yii::app()->user->id);
                //get total amount
                $total_amount = StockIssue::getTotalAmount($modelParent->id);
                StockIssueParent::model()->updateAll(array('total_amount' => $total_amount), 'id=' . (int) $modelParent->id);

                Yii::app()->user->setFlash('success', 'Data was saved successfully');
                $this->redirect(array('admin'));
            }
        }

        //Load data from Stock Requisition
        $loadsr = new StockRequisition('search_stock_requisition');
        $loadsr->unsetAttributes();  // clear any default values
        if (isset($_GET['StockRequisition'])) {
            $loadsr->attributes = $_GET['StockRequisition'];
        }

        $this->render('create', array(
            'model' => $model,
            'modelGrid' => $modelGrid,
            'modelParent' => $modelParent,
            'loadsr' => $loadsr,
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

        if (isset($_POST['StockIssueParent'])) {
            $modelParent->attributes = $_POST['StockIssueParent'];

            if ($modelParent->save()) {
                //update stock summary when status approved
                if ($modelParent->status == 1) {
                    $criteria = new CDbCriteria;
                    $criteria->compare('parent', (int) $id);
                    $dataArray = StockIssue::model()->findAll($criteria);
                    foreach ($dataArray as $key => $values) {
                        //update stock summary
                        StockSummary::issueStockSummary($values['store'], $values['item'], $values['batch'], $values['quantity']);
                        //update Requisition History
                        if ($values['reference']) {
                            StockRequisitionHistory::model()->updateAll(array('converted' => 1), 'requisition_number=' . (int) $values['reference'] . ' AND issue_number=' . (int) $values['id']);
                        }
                    }
                }

                Yii::app()->user->setFlash('success', 'Data was saved successfully');
                $this->redirect(array('admin'));
            }
        }

        //List of requisition items
        $model = new StockIssue('searchIssue');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['StockIssue']))
            $model->attributes = $_GET['StockIssue'];

        $this->render('update', array(
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

        //List of requisition items
        $model = new StockIssue('searchIssue');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['StockIssue']))
            $model->attributes = $_GET['StockIssue'];

        $this->render('edit', array(
            'model' => $model,
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
        $model = $this->loadModel($id);
        if ($model->reference > 0) {
            //delete from Purchase Order History
            StockRequisitionHistory::model()->deleteAll('requisition_number=' . (int) $model->reference . ' AND issue_number=' . (int) $id);
            //reload Purchase Order Quantity
            //check available Quantity
            $totalOrdered = StockRequisitionHistory::getAvailableQuantity($model->reference);
            $modelSR = StockRequisition::model()->findByPk(((int) $model->reference));
            if (count($modelSR) > 0) {
                if ($modelSR->quantity <= $totalOrdered) {
                    $modelSR->converted = 1;
                    $modelSR->save();
                } else {
                    $modelSR->converted = 0;
                    $modelSR->save();
                }
            }
        }
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
        $dataProvider = new CActiveDataProvider('StockIssue');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new StockIssueParent('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['StockIssueParent']))
            $model->attributes = $_GET['StockIssueParent'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionRemove($id) {
        StockIssueParent::model()->updateAll(array('status' => 2), 'id=' . (int) $id);
        Yii::app()->user->setFlash('success', 'Stock Issue was deleted successfully.');
        $this->redirect(array('admin'));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return StockIssue the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = StockIssue::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function loadModelIssue($id) {
        $model = StockIssueParent::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function loadModelParent($id) {
        $model = StockIssueParent::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param StockIssue $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'stock-issue-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
