<?php

class UserController extends Controller {

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
                'actions' => array('admin', 'delete', 'create', 'update', 'view', 'edit'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete', 'create', 'update', 'view', 'edit'),
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
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $this->layout = false;
        $model = new User;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['User'])) {
                $model->attributes = $_POST['User'];
                if ($model->validate()) {
                    $model->password = SHA1($model->password);
                    $model->register_date = date("Y-m-d G:i:s");
                    $model->activation = md5(microtime());
                    if ($model->save()) {
                        Yii::app()->user->setFlash('success', 'User was saved successfully');
                        Yii::app()->end();
                    }
                }
            }
            echo $this->renderPartial('create', array('model' => $model), true, true);
            Yii::app()->end();
        }

        if (isset($_POST['User'])) {

            $path = Yii::app()->basePath . '/../uploads/user';
            $path_thumb = Yii::app()->basePath . '/../uploads/user/thumb';
            $model->attributes = $_POST['User'];

            if (@!empty($_FILES['User']['name']['photo']) && $model->validate()) {
                $model->photo = $_POST['User']['photo'];
                $model->photo = CUploadedFile::getInstance($model, 'photo');
                $model->photo->saveAs($path . '/' . time() . '_' . str_replace(' ', '_', strtolower($model->photo)));
                $model->photo = time() . '_' . str_replace(' ', '_', strtolower($model->photo));
                $image = Yii::app()->image->load($path . '/' . $model->photo);
                $image->resize(400, 100);
                $image->save($path_thumb . '/' . $model->photo);
            } else {
                
            }
            if ($model->validate()) {
                $model->password = SHA1($model->password);
                $model->register_date = date("Y-m-d G:i:s");
                $model->activation = md5(microtime());
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', 'User was saved successfully');
                    $this->redirect(array('admin'));
                }
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $this->layout = false;
        $model = $this->loadModel($id);
        $previuosFileName = $model->photo;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['User'])) {
                $model->attributes = $_POST['User'];
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', 'User was saved successfully');
                    Yii::app()->end();
                }
            }
            echo $this->renderPartial('update', array('model' => $model), true, true);
            Yii::app()->end();
        }

        if (isset($_POST['User'])) {

            $path = Yii::app()->basePath . '/../uploads/user';
            $path_thumb = Yii::app()->basePath . '/../uploads/user/thumb';
            $model->attributes = $_POST['User'];

            if (@!empty($_FILES['User']['name']['photo']) && $model->validate()) {
                $model->photo = $_POST['User']['photo'];


                $filePath = $path . '/' . $previuosFileName;
                $filePath_thumb = $path_thumb . '/' . $previuosFileName;
                if ((is_file($filePath)) && (file_exists($filePath))) {
                    unlink($filePath);
                }
                if ((is_file($filePath_thumb)) && (file_exists($filePath_thumb))) {
                    unlink($filePath_thumb);
                }
                $model->photo = CUploadedFile::getInstance($model, 'photo');
                $model->photo->saveAs($path . '/' . time() . '_' . str_replace(' ', '_', strtolower($model->photo)));
                $model->photo = time() . '_' . str_replace(' ', '_', strtolower($model->photo));
                $image = Yii::app()->image->load($path . '/' . $model->photo);
                $image->resize(400, 100);
                $image->save($path_thumb . '/' . $model->photo);
            } else {
                $model->photo = $previuosFileName;
            }


            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'User was saved successfully');
                $this->redirect(array('admin'));
            }
        }
        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionEdit($id) {
        $this->layout = false;
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['User'])) {
                $model->attributes = $_POST['User'];
                $model->password = SHA1($model->password);
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', 'Password was changed successfully');
                    Yii::app()->end();
                }
            }
            echo $this->renderPartial('edit', array('model' => $model), true, true);
            Yii::app()->end();
        }

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            $model->password = SHA1($model->password);
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'Password was changed successfully');
                $this->redirect(array('admin'));
            }
        }
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
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('User');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return User the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param User $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
