<?php

class UserGroupController extends Controller {

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
                'actions' => array('admin', 'delete', 'create', 'update', 'view', 'access', 'turnon', 'turnoff', 'accessall', 'accessallc'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete', 'create', 'update', 'view'),
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
        $model = new UserGroup;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model, "user-group-form");
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['UserGroup'])) {
                $model->attributes = $_POST['UserGroup'];
                if ($model->save()) {
                    echo $model->id;
                } else {
                    echo "false";
                }
                return;
            }
        } else {
            if (isset($_POST['UserGroup'])) {
                $model->attributes = $_POST['UserGroup'];
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', 'Group was saved successfully');
                    Yii::app()->end();
                }
            }

            $this->render('create', array(
                'model' => $model,
            ));
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate() {
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $_REQUEST["UserGroup"]["id"];
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model, "user-group-form");
        if (Yii::app()->request->isAjaxRequest) {

            if (isset($_POST['UserGroup'])) {
                $model->attributes = $_POST['UserGroup'];
                if ($model->save()) {
                    echo $model->id;
                } else {
                    echo "false";
                }
                return;
            }
            $this->renderPartial('_form_update', array(
                'model' => $model,
            ));
            return;
        }
        if (isset($_POST['UserGroup'])) {
            $model->attributes = $_POST['UserGroup'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'Group was saved successfully');
                Yii::app()->end();
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        //$this->loadModel($id)->delete();
        try {
            $this->loadModel($id)->delete();
            if (!isset($_GET['ajax'])) {
                Yii::app()->user->setFlash('success', 'Data was deleted successfully');
                return;
            } else {
                echo "<div class='alert alert-success fade in'>Data was deleted successfully</div>"; //for ajax
				$model = new UserGroup('search');
				$this->renderPartial('admin', array(
				'model' => $model,
				));
				
            }
        } catch (CDbException $e) {
            if (!isset($_GET['ajax'])) {
                Yii::app()->user->setFlash('error', 'Data was not deleted successfully');
            } else {
                echo "<div class='alert alert-danger fade in'>Data was not deleted successfully</div>"; //for ajax
            }
        }

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('UserGroup');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new UserGroup('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['UserGroup']))
            $model->attributes = $_GET['UserGroup'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }
    
    public function actionAccess() {
        if (isset($_POST['updateaccess'])) {
            $usergroup = $_POST['usergroup'];
            Acl::model()->updateAll(array('access' => 0), 'group_id =' . $usergroup);
            foreach ($_POST as $key => $values) {
                $acval = explode('||', $key);
                if (isset($acval[0]) && isset($acval[1])) {
                    Acl::model()->updateAll(array('access' => $values), 'group_id ="' . $usergroup . '" AND controller = "' . $acval[0] . '" AND actions="' . $acval[1] . '"');
                }
            }
            Yii::app()->user->setFlash('success', 'Access was saved successfully');
            $this->redirect(array('edit', 'id' => $usergroup));
        }

        $getGroup = $_GET['id'];
        $rValue = Yii::app()->db->createCommand()
                ->select('*')
                ->from('{{acl_action}}')
                ->queryAll();
        foreach ($rValue as $key => $values) {
            $model = new Acl();
            $model->group_id = $getGroup;
            $model->controller = AclController::get_controller($values["controller_id"]);
            $model->actions = $values["action"];
            $model->action_title = $values["title"];
            $model->access = 0;
            $val = Acl::checkExist($model->group_id, $model->controller, $model->actions);
            if ($val <= 0) {
                $model->save();
            } else {
                Acl::model()->updateAll(array('action_title' => $model->action_title), 'group_id ="' . (int) $getGroup . '" AND controller = "' . $model->controller . '" AND actions="' . $model->actions . '"');
            }
        }

        $this->render('access');
    }

    public function actionturnon($id) {
        Yii::app()->db->createCommand('update `{{acl}}` set access=1 where id=' . (int) $id)->execute();
        print "ok";
    }

    public function actionturnoff($id) {
        Yii::app()->db->createCommand('update `{{acl}}` set access=0 where id=' . (int) $id)->execute();
        print "ok";
    }

    public function actionAccessall($id, $group_id) {
        if ($id == 2)
            Yii::app()->db->createCommand('update `{{acl}}` set access=1 where group_id=' . (int) $group_id)->execute();
        else
            Yii::app()->db->createCommand('update `{{acl}}` set access=0 where group_id=' . (int) $group_id)->execute();
        print "ok";
    }

    public function actionAccessallc($id, $group_id, $cntrl) {
        if ($id == 2)
            Yii::app()->db->createCommand('update `{{acl}}` set access=1 where (group_id=' . (int) $group_id . " and controller='" . $cntrl . "')")->execute();
        else
            Yii::app()->db->createCommand('update `{{acl}}` set access=0 where (group_id=' . (int) $group_id . " and controller='" . $cntrl . "')")->execute();

        print "$id, $group_id, $cntrl";
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return UserGroup the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = UserGroup::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param UserGroup $model the model to be validated
     */
    protected function performAjaxValidation($model, $form_id) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === $form_id) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

//    protected function performAjaxValidation($model) {
//        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-group-form') {
//            echo CActiveForm::validate($model);
//            Yii::app()->end();
//        }
//    }
}
