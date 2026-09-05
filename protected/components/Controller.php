<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';
    public $bodyClass;

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    public function checkAccess($controller, $action) {
        $val = Acl::model()->findByAttributes(array('controller' => $controller, 'actions' => $action, 'group_id' => Yii::app()->user->group));
        if (!isset($val->access)) {
            $val = 1;
        } else {
            $val = $val->access;
        }
        return $val;
    }

    public function init() {
        $this->bodyClass = '';
        $this->statistics();
    }

    public function statistics() {
        $criteria = new CDbCriteria;
        $criteria->condition = 'server_time < DATE_SUB(NOW(), INTERVAL 7 DAY)';
        Visitor::model()->deleteAll($criteria);

        $model = new Visitor;
        $model->user_id = Yii::app()->user->id;
        $model->user_name = Yii::app()->user->name;
        $model->server_time = new CDbExpression('NOW()');
        $model->page_title = $this->pageTitle;
        $model->page_link = Yii::app()->request->url;
        //$model->browser = Yii::app()->browser->getBrowser();
        $model->browser = '';
        $model->visitor_ip = $_SERVER['REMOTE_ADDR'];
        $model->save();
    }

}

function checkstatus($issueid, $statusid, $title) {
    $stid = Yii::app()->db->createCommand()
            ->select('status')
            ->from('{{issue}}')
            ->where("id=$issueid")
            ->queryScalar();
    $accid = Yii::app()->db->createCommand()
            ->select('access')
            ->from('{{status}}')
            ->where("id=$stid")
            ->queryScalar();
    $accessstatus = "no";
    if ($accid != "") {
        $accarray = explode(",", $accid);
        if (in_array($statusid, $accarray)) {
            $accessstatus = "yes";
        }
    }
    $sp_unresolved = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{specification}}')
            ->where("(dev_status IS NULL OR dev_status=0) AND issue=" . (int) $issueid)
            ->queryAll();
    if (count($sp_unresolved) > 0 && $statusid == 5) {
        $accessstatus = "no";
    }
    if ($accessstatus == "yes" || $stid == $statusid) {
        if (!($stid == $statusid))
            print "<a href=\"javascript:void(0);\" onclick=\"onstatus(" . $issueid . "," . $statusid . ");\" class=\"btn btn-default btn-sm btn-block\">$title</a>";
        else
            print "<a href=\"javascript:void(0);\"  class=\"btn btn-primary btn-sm btn-block\">$title</a>";
    }
    else {
        print "<a href=\"javascript:void(0);\" class=\"btn btn-default btn-sm disabled btn-block\">$title</a>";
    }
}
