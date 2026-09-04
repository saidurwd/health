<?php

class PurchaseReceiveController extends Controller {

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
                    'admin',
                    'create',
                    'update',
                    'edit',
                    'view',
                    'delete',
                    'add',
                    'remove',
                    'addpo',
                    'addpoupdate',
                    'addselectedpo',
                    'addselectedpoupdate',
                    'adjustment',
                    'adjustmentEdit',
                    'print',
                    'download',
                    'downloadall',
                    'upload',
                    'docupload',
                    'downloadfile',
                    'deletefile',
                    'price'),
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

    public function actionPrice() {
        $model = new PurchaseReceive('searchReceivePrice');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PurchaseReceive']))
            $model->attributes = $_GET['PurchaseReceive'];

        $this->render('price', array(
            'model' => $model,
        ));
    }

    public function actionUpload($id) {
        $this->layout = false;

        $modelDocument = new PurchaseReceiveDocument();

        $model = $this->loadModel($id);

        //List of documents for this PR
        $modelList = new PurchaseReceiveDocument('search');
        $modelList->unsetAttributes();  // clear any default values
        if (isset($_GET['PurchaseReceiveDocument']))
            $modelList->attributes = $_GET['PurchaseReceiveDocument'];

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('_file', array(
                'model' => $model,
                'modelDocument' => $modelDocument,
                'modelList' => $modelList,
            ));
            return;
        }
    }

    public function actionDocupload() {
        $model = new PurchaseReceiveDocument;
        $this->performAjaxValidationDocument($model, "purchase-receive-document-form");
        $path = Yii::app()->basePath . '/../uploads/store';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        if (isset($_POST['PurchaseReceiveDocument'])) {
            $model->attributes = $_POST['PurchaseReceiveDocument'];
            $model->created_by = Yii::app()->user->id;
            $model->created_on = date("Y-m-d G:i:s");
            if ($model->validate()) {
                //Picture upload script
                if (@!empty($_FILES['PurchaseReceiveDocument']['name']['doc_file'])) {
                    $model->doc_file = $_POST['PurchaseReceiveDocument']['doc_file'];

                    if ($model->validate(array('PurchaseReceiveDocument'))) {
                        $model->doc_file = CUploadedFile::getInstance($model, 'doc_file');
                    } else {
                        $model->doc_file = null;
                    }
                    $model->doc_file->saveAs($path . '/' . time() . '_' . str_replace(' ', '_', strtolower($model->doc_file)));
                    $model->doc_file = time() . '_' . str_replace(' ', '_', strtolower($model->doc_file));
                }
                if ($model->save()) {
                    echo $model->id;
                } else {
                    echo "false";
                }
            }
        }
    }

    public function actionDownloadfile($id) {
        $this->render('downloadfile', array(
            'model' => PurchaseReceiveDocument::loadModel($id),
        ));
    }

    public function actionDeletefile($id) {
        /*
         * when delete added item that was reference purchase order
         * reverse to the list that coming from LoadPO
         */
        $model = $this->loadModelDocument($id);
        $myFile = Yii::app()->basePath . '/../uploads/store/' . $model->doc_file;
        if ((is_file($myFile)) && (file_exists($myFile))) {
            unlink($myFile);
        }
        $this->loadModelDocument($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('create'));
    }

    /**
     * Download a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionDownload($id) {
        $this->render('download', array(
            'model' => StoreDocument::loadModel($id),
        ));
    }

    public function actionDownloadall($id) {
        $this->render('downloadall', array(
            'id' => $id,
        ));
    }

    public function actionPrint($id) {
        $this->layout = '//layouts/report';
        $parent = $this->loadModelReceive($id);

        //List of receive items
        $model = new PurchaseReceive('searchReceive');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PurchaseReceive']))
            $model->attributes = $_GET['PurchaseReceive'];

        $this->render('print', array(
            'model' => $model,
            'parent' => $parent,
        ));
    }

    public function actionAdjustment($id, $adjustment, $type) {
        $model = PurchaseReceive::model()->findByPk(((int) $id));
        if ($type == "store") {
            $model->store = $adjustment;
            $model->save();
        }
        if ($type == "quantity") {
            $model->quantity = $adjustment;
            $model->total_amount = ($model->quantity * $model->rate);
            if ($model->save()) {
                if ($model->reference) {
                    PurchaseOrderHistory::model()->updateAll(array('quantity' => $model->quantity), 'po_number=' . (int) $model->reference . ' AND pr_number=' . (int) $model->id);
                    //check available Quantity
                    $totalOrdered = PurchaseOrderHistory::getAvailableQuantity($model->reference);
                    $modelPO = PurchaseOrder::model()->findByPk(((int) $model->reference));
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
        if ($type == "rate") {
            $model->rate = $adjustment;
            $model->total_amount = ($model->quantity * $model->rate);
            $model->save();
        }
    }

    public function actionAdjustmentEdit($id, $adjustment, $type) {
        $model = PurchaseReceive::model()->findByPk(((int) $id));
        $prevQty = $model->quantity;
        if ($type == "quantity") {
            $model->quantity = $adjustment;
            $model->total_amount = ($model->quantity * $model->rate);
            if ($model->save()) {
                if ($model->reference) {
                    PurchaseOrderHistory::model()->updateAll(array('quantity' => $model->quantity), 'po_number=' . (int) $model->reference . ' AND pr_number=' . (int) $model->id);
                    //check available Quantity
                    $totalOrdered = PurchaseOrderHistory::getAvailableQuantity($model->reference);
                    $modelPO = PurchaseOrder::model()->findByPk(((int) $model->reference));
                    if ($modelPO->quantity <= $totalOrdered) {
                        $modelPO->converted = 1;
                        $modelPO->save();
                    } else {
                        $modelPO->converted = 0;
                        $modelPO->save();
                    }
                }
                //update Stock Summary         
                StockSummary::issueStockSummary($model->store, $model->item, $model->batch, $prevQty);
                StockSummary::receiveStockSummary($model->store, $model->item, $model->batch, $model->quantity);
            }
        }
        if ($type == "rate") {
            $model->rate = $adjustment;
            $model->total_amount = ($model->quantity * $model->rate);
            $model->save();
        }
    }

    public function actionAddselectedpo($selectedIDs) {
        //explode selected items 
        $expression = explode(',', $selectedIDs);
        $totalAccess = count($expression);

        //add data to PR
        for ($e = 0; $e < $totalAccess; $e++) {
            if ($expression[$e] != 1) {
                $model = new PurchaseReceive;
                $modelPO = PurchaseOrder::model()->findByPk(((int) $expression[$e]));
                $model->parent = 0;
                $model->reference = $modelPO->id;
                $model->item = $modelPO->item;
                //$model->quantity = $modelPO->quantity;
                $model->quantity = PurchaseOrder::getAvailableQuantity($modelPO->id);
                $model->rate = 0;
                $model->store = 0;
                $model->batch = 0;
                $model->created_by = Yii::app()->user->id;
                $model->created_on = date("Y-m-d G:i:s");
                if ($model->save()) {
                    //Purchase Order History [keep converted/non converted history data]
                    $modelHistory = new PurchaseOrderHistory;
                    $modelHistory->po_number = $modelPO->id;
                    $modelHistory->pr_number = $model->id;
                    $modelHistory->item = $modelPO->item;
                    //$modelHistory->quantity = $modelPO->quantity;
                    $modelHistory->quantity = PurchaseOrder::getAvailableQuantity($modelPO->id);
                    $modelHistory->converted = 0;
                    $modelHistory->created_by = Yii::app()->user->id;
                    $modelHistory->created_on = date("Y-m-d G:i:s");
                    $modelHistory->save();

                    //check available Quantity
                    $totalOrdered = PurchaseOrderHistory::getAvailableQuantity($modelPO->id);
                    if ($modelPO->quantity <= $totalOrdered) {
                        $modelPO->converted = 1;
                        $modelPO->save();
                    }

                    echo $model->id;
                }
            }
        }
    }

    public function actionAddselectedpoupdate($selectedIDs, $prp) {
        //explode selected items 
        $expression = explode(',', $selectedIDs);
        $totalAccess = count($expression);

        //add data to PR
        for ($e = 0; $e < $totalAccess; $e++) {
            if ($expression[$e] != 1) {
                $model = new PurchaseReceive;
                $modelPO = PurchaseOrder::model()->findByPk(((int) $expression[$e]));
                $model->parent = $prp;
                $model->reference = $modelPO->id;
                $model->item = $modelPO->item;
                $model->quantity = PurchaseOrder::getAvailableQuantity($modelPO->id);
                $model->rate = 0;
                $model->store = 0;
                $model->batch = 0;
                $model->created_by = Yii::app()->user->id;
                $model->created_on = date("Y-m-d G:i:s");
                if ($model->save()) {
                    //Purchase Order History [keep converted/non converted history data]
                    $modelHistory = new PurchaseOrderHistory;
                    $modelHistory->po_number = $modelPO->id;
                    $modelHistory->pr_number = $model->id;
                    $modelHistory->item = $modelPO->item;
                    $modelHistory->quantity = PurchaseOrder::getAvailableQuantity($modelPO->id);
                    $modelHistory->converted = 0;
                    $modelHistory->created_by = Yii::app()->user->id;
                    $modelHistory->created_on = date("Y-m-d G:i:s");
                    $modelHistory->save();

                    //check available Quantity
                    $totalOrdered = PurchaseOrderHistory::getAvailableQuantity($modelPO->id);
                    if ($modelPO->quantity <= $totalOrdered) {
                        $modelPO->converted = 1;
                        $modelPO->save();
                    }

                    echo $model->id;
                }
            }
        }
    }

    public function actionAddpo() {
        $model = new PurchaseReceive;
        $modelPO = PurchaseOrder::model()->findByPk(((int) $_REQUEST['id']));
        if (Yii::app()->request->isAjaxRequest) {
            $model->batch = 0;
            $model->parent = 0;
            $model->reference = $modelPO->id;
            $model->item = $modelPO->item;
            $model->quantity = PurchaseOrder::getAvailableQuantity($modelPO->id);
            $model->rate = 0;
            $model->store = 0;
            $model->created_by = Yii::app()->user->id;
            $model->created_on = date("Y-m-d G:i:s");
            if ($model->save()) {
                //Purchase Order History [keep converted/non converted history data]
                $modelHistory = new PurchaseOrderHistory;
                $modelHistory->po_number = $modelPO->id;
                $modelHistory->pr_number = $model->id;
                $modelHistory->item = $modelPO->item;
                //$modelHistory->quantity = $modelPO->quantity;
                $modelHistory->quantity = PurchaseOrder::getAvailableQuantity($modelPO->id);
                $modelHistory->converted = 0;
                $modelHistory->created_by = Yii::app()->user->id;
                $modelHistory->created_on = date("Y-m-d G:i:s");
                $modelHistory->save();

                //check available Quantity
                $totalOrdered = PurchaseOrderHistory::getAvailableQuantity($modelPO->id);
                if ($modelPO->quantity <= $totalOrdered) {
                    $modelPO->converted = 1;
                    $modelPO->save();
                }

                echo $model->id;
            } else {
                echo "false";
            }
            return;
        }
    }

    public function actionAddpoupdate() {
        $model = new PurchaseReceive;
        $modelPO = PurchaseOrder::model()->findByPk(((int) $_REQUEST['id']));
        if (Yii::app()->request->isAjaxRequest) {
            $model->batch = 0;
            $model->parent = $_REQUEST['prp'];
            $model->reference = $modelPO->id;
            $model->item = $modelPO->item;
            $model->quantity = PurchaseOrder::getAvailableQuantity($modelPO->id);
            $model->rate = 0;
            $model->store = 0;
            $model->created_by = Yii::app()->user->id;
            $model->created_on = date("Y-m-d G:i:s");
            if ($model->save()) {
                //Purchase Order History [keep converted/non converted history data]
                $modelHistory = new PurchaseOrderHistory;
                $modelHistory->po_number = $modelPO->id;
                $modelHistory->pr_number = $model->id;
                $modelHistory->item = $modelPO->item;
                //$modelHistory->quantity = $modelPO->quantity;
                $modelHistory->quantity = PurchaseOrder::getAvailableQuantity($modelPO->id);
                $modelHistory->converted = 0;
                $modelHistory->created_by = Yii::app()->user->id;
                $modelHistory->created_on = date("Y-m-d G:i:s");
                $modelHistory->save();

                //check available Quantity
                $totalOrdered = PurchaseOrderHistory::getAvailableQuantity($modelPO->id);
                if ($modelPO->quantity <= $totalOrdered) {
                    $modelPO->converted = 1;
                    $modelPO->save();
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
        $parent = $this->loadModelReceive($id);
        //List of receive items
        $model = new PurchaseReceive('searchReceive');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PurchaseReceive']))
            $model->attributes = $_GET['PurchaseReceive'];

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
        $model = new PurchaseReceive;
        $batch = new Batch;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);
        $this->performAjaxValidation($batch);

        if (isset($_POST['PurchaseReceive'])) {
            $model->attributes = $_POST['PurchaseReceive'];
            $batch->attributes = $_POST['Batch'];
            //Save batch 
            $getBatchID = Batch::countBatchLotNumber($batch->expiry);
            $batch->title = str_replace("-", "", $batch->expiry);
            if ($getBatchID == 0) {
                $batch->save();
                //get batch id from saved batch
                $model->batch = $batch->id;
            } else {
                //get batch id from old batch
                $model->batch = $getBatchID;
            }
            $model->total_amount = round(($model->quantity * $model->rate), 6);
            $model->buy_amount = round(($model->quantity * $model->buy_rate), 6);
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
        $model = new PurchaseReceive;
        $modelParent = new PurchaseReceiveParent;
        $batch = new Batch;
        $document = new StoreDocument();

        //Set item document absolut path
        $path = Yii::app()->basePath . '/../uploads/store';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        //List of added item
        $modelGrid = new PurchaseReceive('search');
        $modelGrid->unsetAttributes();  // clear any default values
        if (isset($_GET['PurchaseReceive']))
            $modelGrid->attributes = $_GET['PurchaseReceive'];

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PurchaseReceiveParent'])) {
            //empty store/batch check
            $emptyStore = PurchaseReceive::model()->findAll(array('condition' => '(batch=0 OR batch IS NULL OR store=0 OR store IS NULL) AND parent=0 AND created_by=' . (int) Yii::app()->user->id));
            $emptyStoreCount = count($emptyStore);
            if ($emptyStoreCount > 0) {
                Yii::app()->user->setFlash('error', 'Store/Batch cannot be blank!');
                $this->redirect(array('create'));
            }

            $modelParent->attributes = $_POST['PurchaseReceiveParent'];
            $modelParent->receive_date = date("Y-m-d G:i:s");
            $modelParent->receive_number = PurchaseReceiveParent::generatePurchaseReceiveNumber();
            $modelParent->receive_by = Yii::app()->user->id;
            $modelParent->status = 0;
            $modelParent->created_by = Yii::app()->user->id;
            $modelParent->created_on = date("Y-m-d G:i:s");
            if ($modelParent->save()) {
                PurchaseReceive::model()->updateAll(array('parent' => $modelParent->id), 'parent=0 AND created_by=' . (int) Yii::app()->user->id);

                //Document file upload
                $photos = CUploadedFile::getInstancesByName('StoreDocument[doc_file]');
                // proceed if the images have been set
                if (isset($photos) && count($photos) > 0) {
                    // go through each uploaded image
                    foreach ($photos as $image => $pic) {
                        $rndval1 = time() . '1';
                        if ($pic->saveAs($path . '/' . $rndval1 . '_' . str_replace(' ', '_', strtolower($pic->name)))) {
                            // add it to the main model now
                            $img_add = new StoreDocument();
                            $img_add->transection_type = 2;
                            $img_add->transection_id = $modelParent->id;
                            $img_add->doc_title = $pic->name;
                            $img_add->created_by = Yii::app()->user->id;
                            $img_add->created_on = date("Y-m-d G:i:s");
                            $img_add->doc_file = $rndval1 . '_' . str_replace(' ', '_', strtolower($pic->name));
                            $img_add->save();
                            StoreDocument::model()->updateAll(array('doc_file' => $rndval1 . '_' . str_replace(' ', '_', strtolower($pic->name))), 'id=' . (int) $img_add->id);
                        } else {
                            print_r($img_add->getErrors());
                            exit;
                        }
                    }
                }

                Yii::app()->user->setFlash('success', 'Data was saved successfully');
                $this->redirect(array('admin'));
            }
        }

        //Load data from purchase order
        $loadpo = new PurchaseOrder('search_purchase_receive');
        $loadpo->unsetAttributes();  // clear any default values
        if (isset($_GET['PurchaseOrder'])) {
            $loadpo->attributes = $_GET['PurchaseOrder'];
        }

        $this->render('create', array(
            'model' => $model,
            'modelGrid' => $modelGrid,
            'modelParent' => $modelParent,
            'batch' => $batch,
            'loadpo' => $loadpo,
            'document' => $document,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $modelParent = $this->loadModelReceive($id);
        $batch = new Batch;
        $document = new StoreDocument();

        if ($modelParent->status == 1) {
            Yii::app()->user->setFlash('error', 'You are not authorized to perform this action!');
            $this->redirect(array('admin'));
        }

        //Set item document absolut path
        $path = Yii::app()->basePath . '/../uploads/store';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PurchaseReceiveParent'])) {
            //empty store/batch check
            $emptyStore = PurchaseReceive::model()->findAll(array('condition' => '(batch=0 OR batch IS NULL OR store=0 OR store IS NULL) AND parent=' . (int) $id));
            $emptyStoreCount = count($emptyStore);
            if ($emptyStoreCount > 0) {
                Yii::app()->user->setFlash('error', 'Store/Batch cannot be blank!');
                $this->redirect(array('update', 'id' => $id));
            }

            $modelParent->attributes = $_POST['PurchaseReceiveParent'];

            if ($modelParent->save()) {
                //update stock summary when status approved
                if ($modelParent->status == 1) {
                    $criteria = new CDbCriteria;
                    $criteria->compare('parent', (int) $id);
                    $dataArray = PurchaseReceive::model()->findAll($criteria);
                    foreach ($dataArray as $key => $values) {
                        //update stock summary
                        StockSummary::receiveStockSummary($values['store'], $values['item'], $values['batch'], $values['quantity']);
                        //update Purchase Order History
                        if ($values['reference']) {
                            PurchaseOrderHistory::model()->updateAll(array('converted' => 1), 'po_number=' . (int) $values['reference'] . ' AND pr_number=' . (int) $values['id']);
                        }
                    }
                }

                //Document file upload
                $photos = CUploadedFile::getInstancesByName('StoreDocument[doc_file]');
                // proceed if the images have been set
                if (isset($photos) && count($photos) > 0) {
                    // go through each uploaded image
                    foreach ($photos as $image => $pic) {
                        $rndval1 = time() . '1';
                        if ($pic->saveAs($path . '/' . $rndval1 . '_' . str_replace(' ', '_', strtolower($pic->name)))) {
                            // add it to the main model now
                            $img_add = new StoreDocument();
                            $img_add->transection_type = 2;
                            $img_add->transection_id = $modelParent->id;
                            $img_add->doc_title = $pic->name;
                            $img_add->created_by = Yii::app()->user->id;
                            $img_add->created_on = date("Y-m-d G:i:s");
                            $img_add->doc_file = $rndval1 . '_' . str_replace(' ', '_', strtolower($pic->name));
                            $img_add->save();
                            StoreDocument::model()->updateAll(array('doc_file' => $rndval1 . '_' . str_replace(' ', '_', strtolower($pic->name))), 'id=' . (int) $img_add->id);
                        } else {
                            print_r($img_add->getErrors());
                            exit;
                        }
                    }
                }

                Yii::app()->user->setFlash('success', 'Data was saved successfully');
                $this->redirect(array('admin'));
            }
        }

        //List of order items
        $model = new PurchaseReceive('searchReceive');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PurchaseReceive']))
            $model->attributes = $_GET['PurchaseReceive'];

        //Load data from purchase order
        $loadpo = new PurchaseOrder('search_purchase_receive');
        $loadpo->unsetAttributes();  // clear any default values
        if (isset($_GET['PurchaseOrder'])) {
            $loadpo->attributes = $_GET['PurchaseOrder'];
        }

        $this->render('update', array(
            'model' => $model,
            'modelParent' => $modelParent,
            'batch' => $batch,
            'loadpo' => $loadpo,
            'document' => $document,
        ));
    }

    public function actionEdit($id) {
        $modelParent = $this->loadModelReceive($id);

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


        //List of order items
        $model = new PurchaseReceive('searchReceive');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PurchaseReceive']))
            $model->attributes = $_GET['PurchaseReceive'];

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
         * when delete added item that was reference purchase order
         * reverse to the list that coming from LoadPO
         */
        $model = $this->loadModel($id);
        if ($model->reference > 0) {
            //delete from Purchase Order History
            PurchaseOrderHistory::model()->deleteAll('po_number=' . (int) $model->reference . ' AND pr_number=' . (int) $id);

            //reload Purchase Order Quantity
            //check available Quantity
            $totalOrdered = PurchaseOrderHistory::getAvailableQuantity($model->reference);
            $modelPO = PurchaseOrder::model()->findByPk(((int) $model->reference));
            if ($modelPO->quantity <= $totalOrdered) {
                $modelPO->converted = 1;
                $modelPO->save();
            } else {
                $modelPO->converted = 0;
                $modelPO->save();
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
        $dataProvider = new CActiveDataProvider('PurchaseReceive');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new PurchaseReceiveParent('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PurchaseReceiveParent']))
            $model->attributes = $_GET['PurchaseReceiveParent'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionRemove($id) {
        PurchaseReceiveParent::model()->updateAll(array('status' => 2), 'id=' . (int) $id);
        Yii::app()->user->setFlash('success', 'Purchase Receive was deleted successfully.');
        $this->redirect(array('admin'));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return PurchaseReceive the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = PurchaseReceive::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function loadModelReceive($id) {
        $model = PurchaseReceiveParent::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function loadModelBatch($id) {
        $model = Batch::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function loadModelDocument($id) {
        $model = PurchaseReceiveDocument::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param PurchaseReceive $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'purchase-receive-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    protected function performAjaxValidationDocument($document) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'purchase-receive-document-form') {
            echo CActiveForm::validate($document);
            Yii::app()->end();
        }
    }

}
