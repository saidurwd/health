<?php

class Report extends CActiveRecord
{

    public static function stockSummaryReport($cid, $itemid, $store)
    {
        $criteria = '';
        $criteriaPR = '';
        $criteriaSI = '';
        $criteriaINV = '';
        $criteriaSS = '';
        if (!empty(@$cid))
            $criteria = ' AND itm.category=' . @$cid;
        if (!empty(@$itemid))
            $criteria = ' AND itm.id=' . @$itemid;
        if (!empty(@$store)) {
            $criteriaPR .= ' AND pr.store=' . @$store;
            $criteriaSI .= ' AND si.store=' . @$store;
            $criteriaINV .= ' AND inv.store=' . @$store;
            $criteriaSS .= ' WHERE ss.store=' . @$store;
        }

        $connection = Yii::app()->db;
        $command = $connection->createCommand('SELECT cat.`title` AS category, itm.`title` AS item, itm.`minimum_storage_limit` AS minimum_quantity, IFNULL(AIN.quantity,0) AS in_quantity, IFNULL(AIN.amount,0.00) AS in_amount, (IFNULL(BOUT.quantity,0) + IFNULL(IOUT.quantity,0)) AS out_quantity, (IFNULL(BOUT.amount,0.00) + IFNULL(IOUT.amount,0.00)) AS out_amount, IFNULL(AVL.quantity,0) AS avl_quantity, IFNULL(AVL.amount,0.00) AS avl_amount
                                                FROM {{product}} itm 
                                                LEFT OUTER JOIN (
                                                        SELECT pr.item AS item, IFNULL(SUM(pr.quantity),0) AS quantity, IFNULL(SUM(pr.buy_amount),0.00) AS amount
                                                        FROM {{purchase_receive}} pr
                                                        LEFT OUTER JOIN {{purchase_receive_parent}} prp ON pr.parent=prp.id 
                                                        WHERE  prp.status = 1 ' . $criteriaPR . ' 
                                                        GROUP BY pr.item) AIN ON AIN.item=itm.id 
                                                LEFT OUTER JOIN (	
                                                        SELECT si.item AS item, IFNULL(SUM(si.quantity),0) AS quantity, IFNULL(SUM(si.amount),0.00) AS amount
                                                        FROM {{stock_issue}} si
                                                        LEFT OUTER JOIN {{stock_issue_parent}} sip ON si.parent=sip.id 
                                                        WHERE sip.status = 1 ' . $criteriaSI . ' 
                                                        GROUP BY si.item) BOUT ON BOUT.item=itm.id
                                                LEFT OUTER JOIN (	
                                                        SELECT inv.item AS item, IFNULL(SUM(inv.quantity),0) AS quantity, IFNULL(SUM(inv.amount),0.00) AS amount
                                                        FROM {{invoice}} inv
                                                        LEFT OUTER JOIN {{invoice_parent}} invp ON inv.parent=invp.id 
                                                        WHERE inv.servicetype="Medicine" AND invp.status = 1 ' . $criteriaINV . ' 
                                                        GROUP BY inv.item) IOUT ON IOUT.item=itm.id
                                                LEFT OUTER JOIN (	
                                                        SELECT ss.item AS item, IFNULL(SUM(ss.quantity),0) AS quantity, IFNULL(SUM(ss.amount),0.00) AS amount
                                                        FROM {{stock_summary}} ss  ' . $criteriaSS . ' 
                                                        GROUP BY ss.item) AVL ON AVL.item=itm.id
                                                LEFT OUTER JOIN {{product_category}} cat ON itm.category=cat.id 	
                                                WHERE itm.title IS NOT NULL' . $criteria);
        $grand_total = $command->queryAll();

        return $grand_total;
    }

    public static function stockReceiveReport($cid, $itemid, $start_date, $end_date)
    {
        $criteria = '';
        if (@$cid != 0)
            $criteria .= ' AND itm.category=' . @$cid;
        if (@$itemid != 0)
            $criteria .= ' AND itm.id=' . @$itemid;
        $connection = Yii::app()->db;
        $command = $connection->createCommand('SELECT prp.`receive_number` AS receive_number, prp.`receive_date` AS receive_date, sup.`title` AS supplier, pr.item AS itemid, itm.`title` AS item, pr.`quantity` AS quantity, pr.`buy_rate` AS rate, pr.`buy_amount` AS total, str.id AS storeid, str.title AS store, bat.title AS batch, bat.expiry AS expiry, pr.store AS storeid, pr.batch AS batchid  
                                                FROM {{purchase_receive}} pr
                                                LEFT OUTER JOIN {{purchase_receive_parent}} prp ON pr.parent=prp.id 
                                                LEFT OUTER JOIN {{vendor}} sup ON prp.supplier=sup.id 
                                                LEFT OUTER JOIN {{product}} itm ON pr.item=itm.id 
                                                LEFT OUTER JOIN {{store}} str ON pr.store=str.id 
                                                LEFT OUTER JOIN {{batch}} bat ON pr.batch=bat.id
                                                WHERE prp.status = 1 AND (prp.`receive_date` BETWEEN "' . $start_date . '" AND "' . $end_date . '")' . $criteria . ' ORDER BY item ASC ');
        $grand_total = $command->queryAll();

        return $grand_total;
    }

    public static function salesReport($start_date, $end_date, $type, $service, $product, $category, $status)
    {
        $criteria = '';
        if (@$category != NULL) {
            $criteria .= ' AND invp.patient_category=' . @$category;
        }
        if (@$status != NULL) {
            $criteria .= ' AND invp.payment_status="' . @$status . '"';
        }
        if (@$type != NULL) {
            if (@$type == 'Service' && @$service != NULL) {
                $criteria .= ' AND inv.service=' . @$service;
            } elseif ($type == "Medicine" && $product != NULL) {
                $criteria .= ' AND inv.item=' . @$product;
            } else {
                $criteria .= ' AND inv.servicetype="' . @$type . '"';
            }
        }

        $connection = Yii::app()->db;
        $command = $connection->createCommand('SELECT invp.invoice_number, invp.patient, inv.created_on, inv.servicetype, inv.service, inv.item, SUM(inv.quantity) AS sold, SUM(inv.amount) AS amount, invp.patient_category, invp.payment_status FROM {{invoice}} inv  
                                            LEFT OUTER JOIN {{invoice_parent}} invp ON inv.parent=invp.id 
                                            WHERE invp.status=1 AND DATE_FORMAT(inv.`created_on`, "%Y-%m-%d") >= DATE_FORMAT("' . $start_date . '", "%Y-%m-%d") AND DATE_FORMAT(inv.`created_on`, "%Y-%m-%d") <= DATE_FORMAT("' . $end_date . '", "%Y-%m-%d") ' . $criteria . '  
                                            GROUP BY invp.invoice_number, inv.item, inv.service ORDER BY inv.created_on ASC');
        $result = $command->queryAll();

        return $result;
    }

    public static function expirationReport($item, $store, $expiry)
    {
        $criteria = '';
        if (!empty(@$item))
            $criteria .= ' AND ss.item=' . @$item;
        if (!empty(@$store))
            $criteria .= ' AND ss.store=' . @$store;
        if (@$expiry == 0) {
            $criteria .= ' AND DATEDIFF(bt.expiry, CURDATE())<0';
        } else {
            $criteria .= ' AND DATEDIFF(bt.expiry, CURDATE())<=' . @$expiry . ' AND DATEDIFF(bt.expiry, CURDATE())>=0';
        }

        $connection = Yii::app()->db;
        $command = $connection->createCommand('SELECT st.id AS storeid, st.title AS store, it.title AS product, ss.batch, ss.quantity, ss.rate, ss.amount, um.formal_name AS unit, bt.`expiry` AS expiry
                                    FROM {{stock_summary}} ss 
                                    LEFT OUTER JOIN {{batch}} bt ON ss.batch=bt.id  
                                    LEFT OUTER JOIN {{product}} it ON ss.item=it.id
                                    LEFT OUTER JOIN {{store}} st ON ss.store=st.id
                                    LEFT OUTER join {{unit}} um ON it.unit=um.id
                                    WHERE ss.quantity>= 1 ' . $criteria . ' ORDER BY bt.`expiry` ASC');

        $grand_total = $command->queryAll();

        return $grand_total;
    }

    public static function patientRegisterReport($start_date, $end_date, $category, $category_new)
    {
        $criteria = '';
        if (!empty(@$category))
            $criteria .= ' AND pat.category=' . @$category;
        if (!empty(@$category_new))
            $criteria .= ' AND pat.category_new=' . @$category_new;

        $connection = Yii::app()->db;
        $command = $connection->createCommand('SELECT invp.invoice_date, pat.id AS id, pat.pat_id AS pid, pat.`category` AS category, pat.`category_new` AS category_new, pat.`ref_no`, pat.`name` AS pname, pat.`age`, pat.`sex`, CONCAT(pat.`emergency_name`,\'<br />\',pat.`emergency_contact`) AS emergency_person, pat.`emergency_relation`, pat.`address`, inv.amount, invp.`invoice_number`
                                                FROM {{invoice}} inv
                                                LEFT OUTER JOIN {{invoice_parent}} invp ON inv.parent=invp.id 
                                                LEFT OUTER JOIN {{patient}} pat ON pat.id=invp.patient 
                                                WHERE DATE_FORMAT(invp.`invoice_date`, "%Y-%m-%d") >= DATE_FORMAT("' . $start_date . '", "%Y-%m-%d") AND DATE_FORMAT(invp.`invoice_date`, "%Y-%m-%d") <= DATE_FORMAT("' . $end_date . '", "%Y-%m-%d") AND inv.service IN (16,201) AND invp.patient !=0 ' . $criteria . ' ORDER BY invp.`invoice_date` ASC');
        $result = $command->queryAll();

        return $result;
    }

    public static function diseaseReport($start_date, $end_date)
    {
        $connection = Yii::app()->db;
        $command = $connection->createCommand('SELECT TRIM(`diagnosis`) AS diagnosis, COUNT(*) AS total FROM {{patient_prescription}} 
                                                WHERE DATE_FORMAT(`created_on`, "%Y-%m-%d") >= DATE_FORMAT("' . $start_date . '", "%Y-%m-%d") AND DATE_FORMAT(`created_on`, "%Y-%m-%d") <= DATE_FORMAT("' . $end_date . '", "%Y-%m-%d") GROUP BY `diagnosis` ORDER BY `diagnosis`');
        $result = $command->queryAll();

        return $result;
    }

    public static function patientAttendanceAge($start_date, $end_date)
    {
        $connection = Yii::app()->db;
        $command = $connection->createCommand('SELECT p.sex, 
                                                SUM(IF(p.age <= 5,1,0)) as AGE_GROUP_1, 
                                                SUM(IF(p.age BETWEEN 6 and 14,1,0)) as AGE_GROUP_2, 
                                                SUM(IF(p.age BETWEEN 15 and 24,1,0)) as AGE_GROUP_3, 
                                                SUM(IF(p.age BETWEEN 25 and 200,1,0)) as AGE_GROUP_4, 
                                                COUNT(*) AS total 
                                                FROM {{patient_prescription}} pp
                                                INNER JOIN {{patient}} p ON p.id = pp.patient
                                                WHERE pp.created_on >= :start_date 
                                                AND pp.created_on <= :end_date 
                                                GROUP BY p.sex
                                                ORDER BY p.sex DESC');
        $start = $start_date . ' 00:00:00';
        $end = $end_date . ' 23:59:59';
        $command->bindParam(':start_date', $start);
        $command->bindParam(':end_date', $end);
        $result = $command->queryAll();

        return $result;
    }

    public static function patientAttendanceSex($start_date, $end_date)
    {
        $connection = Yii::app()->db;
        $command = $connection->createCommand('SELECT p.sex, COUNT(*) AS total
                                                FROM {{patient_prescription}} pp
                                                INNER JOIN {{patient}} p ON p.id = pp.patient
                                                WHERE pp.created_on >= :start_date 
                                                AND pp.created_on <= :end_date
                                                GROUP BY p.sex
                                                ORDER BY p.sex DESC');
        $start = $start_date . ' 00:00:00';
        $end = $end_date . ' 23:59:59';
        $command->bindParam(':start_date', $start);
        $command->bindParam(':end_date', $end);
        $result = $command->queryAll();

        return $result;
    }

    public static function medicineBillReport($start_date, $end_date, $category, $category_new, $service, $status)
    {
        $criteria = '';
        if (@$category != NULL)
            $criteria .= ' AND invp.patient_category=' . @$category;
        if (@$category_new != NULL)
            $criteria .= ' AND invp.patient_category_new=' . @$category_new;
        if (@$service != NULL)
            $criteria .= ' AND inv.service IN(SELECT s.id FROM {{service}} s WHERE s.parent=' . @$service . ' OR s.id=' . @$service . ')';
        if (@$status != NULL)
            $criteria .= ' AND invp.payment_status="' . @$status . '"';

        $connection = Yii::app()->db;
        $command = $connection->createCommand('SELECT A.created_on, A.`pat_id`, A.`ref_no`, A.`category_new`, A.`category`, A.`name`, A.amount_service, A.amount_medicine FROM 
                                            (
                                            SELECT inv.created_on, pat.`pat_id`, pat.`ref_no`, pat.`category`, pat.`category_new`, pat.`name`, (SELECT IFNULL(SUM(invv.amount),0) FROM {{invoice}} invv WHERE invv.`parent` = inv.`parent` AND invv.`servicetype`="Service" AND invv.`service` IN(16,201)) AS amount_service, (SELECT IFNULL(SUM(invvv.amount),0) FROM {{invoice}} invvv WHERE invvv.`parent` = inv.`parent` AND invvv.`servicetype`="Medicine") AS amount_medicine
                                            FROM {{invoice}} inv  
                                            LEFT OUTER JOIN {{invoice_parent}} invp ON inv.parent=invp.id 
                                            LEFT OUTER JOIN {{patient}} pat ON invp.patient=pat.id 
                                            WHERE invp.status=1 AND DATE_FORMAT(inv.`created_on`, "%Y-%m-%d") >= DATE_FORMAT("' . $start_date . '", "%Y-%m-%d") AND DATE_FORMAT(inv.`created_on`, "%Y-%m-%d") <= DATE_FORMAT("' . $end_date . '", "%Y-%m-%d") ' . $criteria . '  
                                            GROUP BY  inv.`parent` ORDER BY inv.created_on ASC
                                            ) A 
                                            WHERE A.amount_service>0 OR A.amount_medicine>0');
        $result = $command->queryAll();

        return $result;
    }

    public static function serviceBillReport($start_date, $end_date, $category, $category_new, $service, $status)
    {
        $criteria = '';
        $criteria1 = '';
        $criteria2 = '';
        if (@$category != NULL)
            $criteria .= ' AND invp.patient_category=' . @$category;
        if (@$category_new != NULL)
            $criteria .= ' AND invp.patient_category_new=' . @$category_new;
        if (@$service != NULL) {
            $criteria .= ' AND inv.service IN(SELECT s.id FROM {{service}} s WHERE s.parent=' . @$service . ' OR s.id=' . @$service . ')';
            $criteria1 .= ' AND (srvc.parent=' . @$service . ' OR srvc.id=' . @$service . ')';
            $criteria2 .= ' AND (srvs.parent=' . @$service . ' OR srvs.id=' . @$service . ')';
        }
        if (@$status != NULL)
            $criteria .= ' AND invp.payment_status="' . @$status . '"';

        $connection = Yii::app()->db;
        $command = $connection->createCommand('SELECT A.created_on, A.note, A.`pat_id`, A.`ref_no`, A.`category_new`, A.`category`, A.`name`, A.`patient_grade`, A.amount_consultation, A.amount_service FROM
                                            (
                                            SELECT inv.created_on, inv.note, pat.`pat_id`, pat.`ref_no`, pat.`category_new`, pat.`category`, pat.`patient_grade`, pat.`name`, 
                                            (SELECT IFNULL(SUM(invv.amount),0) FROM {{invoice}} invv WHERE invv.`parent` = inv.`parent` AND invv.`service` IN(SELECT srvc.id FROM {{service}} srvc WHERE srvc.`service_type` = "Consultation" ' . $criteria1 . ')) AS amount_consultation, 
                                            (SELECT IFNULL(SUM(invvv.amount),0) FROM {{invoice}} invvv WHERE invvv.`parent` = inv.`parent` AND invvv.`service` IN(SELECT srvs.id FROM {{service}} srvs WHERE srvs.`service_type` = "Service" ' . $criteria2 . ')) AS amount_service
                                            FROM {{invoice}} inv  
                                            LEFT OUTER JOIN {{invoice_parent}} invp ON inv.parent=invp.id 
                                            LEFT OUTER JOIN {{patient}} pat ON invp.patient=pat.id 
                                            WHERE invp.status=1 AND inv.`servicetype` = "Service" AND DATE_FORMAT(inv.`created_on`, "%Y-%m-%d") >= DATE_FORMAT("' . $start_date . '", "%Y-%m-%d") AND DATE_FORMAT(inv.`created_on`, "%Y-%m-%d") <= DATE_FORMAT("' . $end_date . '", "%Y-%m-%d") ' . $criteria . '  
                                            GROUP BY  inv.`parent` ORDER BY inv.created_on ASC
                                            ) A 
                                            WHERE A.amount_consultation>0 OR A.amount_service>0');
        $result = $command->queryAll();

        return $result;
    }

    public static function MedicineIncomeReport($start_date, $end_date, $product)
    {
        $criteria = '';
        if (@$product != NULL) {
            $criteria .= ' AND inv.item=' . @$product;
        }

        $connection = Yii::app()->db;
        $command = $connection->createCommand('SELECT inv.item, SUM(inv.quantity) AS quantity, SUM(inv.amount) AS sale_amount FROM {{invoice}} inv  
                                            LEFT OUTER JOIN {{invoice_parent}} invp ON inv.parent=invp.id 
                                            WHERE invp.status=1 AND servicetype="Medicine" AND DATE_FORMAT(inv.`created_on`, "%Y-%m-%d") >= DATE_FORMAT("' . $start_date . '", "%Y-%m-%d") AND DATE_FORMAT(inv.`created_on`, "%Y-%m-%d") <= DATE_FORMAT("' . $end_date . '", "%Y-%m-%d") ' . $criteria . '  
                                            GROUP BY inv.item');
        $result = $command->queryAll();

        return $result;
    }

    public static function periodWiseStockReport($cid, $itemid, $store, $start_date, $end_date)
    {
        $criteria = '';
        $criteriaPR = '';
        $criteriaSI = '';
        $criteriaINV = '';
        $criteriaSS = '';
        if (!empty(@$cid))
            $criteria = ' AND itm.category=' . @$cid;
        if (!empty(@$itemid))
            $criteria = ' AND itm.id=' . @$itemid;
        if (!empty(@$store)) {
            $criteriaPR .= ' AND pr.store=' . @$store;
            $criteriaSI .= ' AND si.store=' . @$store;
            $criteriaINV .= ' AND inv.store=' . @$store;
            $criteriaSS .= ' WHERE ss.store=' . @$store;
        }

        $connection = Yii::app()->db;
        $command = $connection->createCommand('SELECT cat.`title` AS category, itm.`title` AS item, 
                                                (IFNULL(OPAIN.quantity,0) - (IFNULL(OPBOUT.quantity,0)+IFNULL(OPIOUT.quantity,0))) AS opening_quantity,                                                 
                                                IFNULL(AIN.quantity,0) AS in_quantity, IFNULL(AIN.amount,0.00) AS in_amount, 
                                                (IFNULL(BOUT.quantity,0) + IFNULL(IOUT.quantity,0)) AS out_quantity, (IFNULL(BOUT.amount,0.00) + IFNULL(IOUT.amount,0.00)) AS out_amount, 
                                                IFNULL(AVL.quantity,0) AS avl_quantity, IFNULL(AVL.amount,0.00) AS avl_amount, 
                                                (IFNULL(CLAIN.quantity,0) - (IFNULL(CLBOUT.quantity,0)+IFNULL(CLIOUT.quantity,0))) AS closing_quantity 
                                                FROM {{product}} itm 
                                                
                                                LEFT OUTER JOIN (
                                                        SELECT pr.item AS item, IFNULL(SUM(pr.quantity),0) AS quantity, IFNULL(SUM(pr.buy_amount),0.00) AS amount
                                                        FROM {{purchase_receive}} pr
                                                        LEFT OUTER JOIN {{purchase_receive_parent}} prp ON pr.parent=prp.id 
                                                        WHERE  prp.status = 1 AND DATE_FORMAT(pr.`created_on`, "%Y-%m-%d") < DATE_FORMAT("' . $start_date . '", "%Y-%m-%d") ' . $criteriaPR . ' 
                                                        GROUP BY pr.item) OPAIN ON OPAIN.item=itm.id 
                                                LEFT OUTER JOIN (	
                                                        SELECT si.item AS item, IFNULL(SUM(si.quantity),0) AS quantity, IFNULL(SUM(si.amount),0.00) AS amount
                                                        FROM {{stock_issue}} si
                                                        LEFT OUTER JOIN {{stock_issue_parent}} sip ON si.parent=sip.id 
                                                        WHERE sip.status = 1 AND DATE_FORMAT(si.`created_on`, "%Y-%m-%d") < DATE_FORMAT("' . $start_date . '", "%Y-%m-%d") ' . $criteriaSI . ' 
                                                        GROUP BY si.item) OPBOUT ON OPBOUT.item=itm.id
                                                LEFT OUTER JOIN (	
                                                        SELECT inv.item AS item, IFNULL(SUM(inv.quantity),0) AS quantity, IFNULL(SUM(inv.amount),0.00) AS amount
                                                        FROM {{invoice}} inv
                                                        LEFT OUTER JOIN {{invoice_parent}} invp ON inv.parent=invp.id 
                                                        WHERE inv.servicetype="Medicine" AND invp.status = 1 AND DATE_FORMAT(inv.`created_on`, "%Y-%m-%d") < DATE_FORMAT("' . $start_date . '", "%Y-%m-%d") ' . $criteriaINV . ' 
                                                        GROUP BY inv.item) OPIOUT ON OPIOUT.item=itm.id
                                                        
                                                LEFT OUTER JOIN (
                                                        SELECT pr.item AS item, IFNULL(SUM(pr.quantity),0) AS quantity, IFNULL(SUM(pr.buy_amount),0.00) AS amount
                                                        FROM {{purchase_receive}} pr
                                                        LEFT OUTER JOIN {{purchase_receive_parent}} prp ON pr.parent=prp.id 
                                                        WHERE  prp.status = 1 AND DATE_FORMAT(pr.`created_on`, "%Y-%m-%d") >= DATE_FORMAT("' . $start_date . '", "%Y-%m-%d") AND DATE_FORMAT(pr.`created_on`, "%Y-%m-%d") <= DATE_FORMAT("' . $end_date . '", "%Y-%m-%d") ' . $criteriaPR . ' 
                                                        GROUP BY pr.item) AIN ON AIN.item=itm.id 
                                                LEFT OUTER JOIN (	
                                                        SELECT si.item AS item, IFNULL(SUM(si.quantity),0) AS quantity, IFNULL(SUM(si.amount),0.00) AS amount
                                                        FROM {{stock_issue}} si
                                                        LEFT OUTER JOIN {{stock_issue_parent}} sip ON si.parent=sip.id 
                                                        WHERE sip.status = 1 AND DATE_FORMAT(si.`created_on`, "%Y-%m-%d") >= DATE_FORMAT("' . $start_date . '", "%Y-%m-%d") AND DATE_FORMAT(si.`created_on`, "%Y-%m-%d") <= DATE_FORMAT("' . $end_date . '", "%Y-%m-%d") ' . $criteriaSI . ' 
                                                        GROUP BY si.item) BOUT ON BOUT.item=itm.id
                                                LEFT OUTER JOIN (	
                                                        SELECT inv.item AS item, IFNULL(SUM(inv.quantity),0) AS quantity, IFNULL(SUM(inv.amount),0.00) AS amount
                                                        FROM {{invoice}} inv
                                                        LEFT OUTER JOIN {{invoice_parent}} invp ON inv.parent=invp.id 
                                                        WHERE inv.servicetype="Medicine" AND invp.status = 1 AND DATE_FORMAT(inv.`created_on`, "%Y-%m-%d") >= DATE_FORMAT("' . $start_date . '", "%Y-%m-%d") AND DATE_FORMAT(inv.`created_on`, "%Y-%m-%d") <= DATE_FORMAT("' . $end_date . '", "%Y-%m-%d") ' . $criteriaINV . ' 
                                                        GROUP BY inv.item) IOUT ON IOUT.item=itm.id
                                                LEFT OUTER JOIN (	
                                                        SELECT ss.item AS item, IFNULL(SUM(ss.quantity),0) AS quantity, IFNULL(SUM(ss.amount),0.00) AS amount
                                                        FROM {{stock_summary}} ss  ' . $criteriaSS . ' 
                                                        GROUP BY ss.item) AVL ON AVL.item=itm.id
                                                        
                                                LEFT OUTER JOIN (
                                                        SELECT pr.item AS item, IFNULL(SUM(pr.quantity),0) AS quantity, IFNULL(SUM(pr.buy_amount),0.00) AS amount
                                                        FROM {{purchase_receive}} pr
                                                        LEFT OUTER JOIN {{purchase_receive_parent}} prp ON pr.parent=prp.id 
                                                        WHERE  prp.status = 1 AND DATE_FORMAT(pr.`created_on`, "%Y-%m-%d") <= DATE_FORMAT("' . $end_date . '", "%Y-%m-%d") ' . $criteriaPR . ' 
                                                        GROUP BY pr.item) CLAIN ON CLAIN.item=itm.id 
                                                LEFT OUTER JOIN (	
                                                        SELECT si.item AS item, IFNULL(SUM(si.quantity),0) AS quantity, IFNULL(SUM(si.amount),0.00) AS amount
                                                        FROM {{stock_issue}} si
                                                        LEFT OUTER JOIN {{stock_issue_parent}} sip ON si.parent=sip.id 
                                                        WHERE sip.status = 1 AND DATE_FORMAT(si.`created_on`, "%Y-%m-%d") <= DATE_FORMAT("' . $end_date . '", "%Y-%m-%d") ' . $criteriaSI . ' 
                                                        GROUP BY si.item) CLBOUT ON CLBOUT.item=itm.id
                                                LEFT OUTER JOIN (	
                                                        SELECT inv.item AS item, IFNULL(SUM(inv.quantity),0) AS quantity, IFNULL(SUM(inv.amount),0.00) AS amount
                                                        FROM {{invoice}} inv
                                                        LEFT OUTER JOIN {{invoice_parent}} invp ON inv.parent=invp.id 
                                                        WHERE inv.servicetype="Medicine" AND invp.status = 1 AND DATE_FORMAT(inv.`created_on`, "%Y-%m-%d") <= DATE_FORMAT("' . $end_date . '", "%Y-%m-%d") ' . $criteriaINV . ' 
                                                        GROUP BY inv.item) CLIOUT ON CLIOUT.item=itm.id
                                                        
                                                LEFT OUTER JOIN {{product_category}} cat ON itm.category=cat.id 	
                                                WHERE itm.title IS NOT NULL' . $criteria);
        $grand_total = $command->queryAll();

        return $grand_total;
    }

    public static function patientInvoiceReport($start_date, $end_date, $patient, $status)
    {
        $criteria = '';
        if (@$patient != NULL)
            $criteria .= ' AND invp.patient=' . @$patient;
        if (@$status != NULL)
            $criteria .= ' AND invp.payment_status="' . @$status . '"';

        $connection = Yii::app()->db;
        $command = $connection->createCommand('SELECT invp.invoice_number, invp.invoice_date, pat.id AS id, pat.pat_id AS pid, pat.`category` AS category, pat.`category_new` AS category_new, pat.`name` AS pname, invp.total_amount, invp.`payment_status`
                                                FROM {{invoice_parent}} invp
                                                LEFT OUTER JOIN {{patient}} pat ON pat.id=invp.patient 
                                                WHERE DATE_FORMAT(invp.`invoice_date`, "%Y-%m-%d") >= DATE_FORMAT("' . $start_date . '", "%Y-%m-%d") AND DATE_FORMAT(invp.`invoice_date`, "%Y-%m-%d") <= DATE_FORMAT("' . $end_date . '", "%Y-%m-%d") AND invp.patient !=0 ' . $criteria . ' ORDER BY invp.`invoice_date` DESC');
        $result = $command->queryAll();

        return $result;
    }

    public static function patientRegisterPhysioReport($start_date, $end_date, $category, $category_new)
    {
        $criteria = '';
        if (!empty(@$category))
            $criteria .= ' AND pat.category=' . @$category;
        if (!empty(@$category_new))
            $criteria .= ' AND pat.category_new=' . @$category_new;
        if (!empty(@$patient_type))
            $criteria .= ' AND pat.patient_type=' . @$patient_type;

        $connection = Yii::app()->db;
        $command = $connection->createCommand('SELECT invp.invoice_date, pat.id AS id, pat.pat_id AS pid, pat.`category` AS category, pat.`category_new` AS category_new, pat.`name` AS pname, pat.`patient_grade`, pat.`age`, pat.`sex`, pat.`ref_no`, pat.`mobile`, pat.`problem`, CONCAT(pat.`emergency_name`,\'<br />\',pat.`emergency_contact`) AS emergency_person, pat.`emergency_relation`, pat.`address`, inv.amount, invp.`invoice_number`
                                                FROM {{invoice}} inv
                                                LEFT OUTER JOIN {{invoice_parent}} invp ON inv.parent=invp.id 
                                                LEFT OUTER JOIN {{patient}} pat ON pat.id=invp.patient 
                                                WHERE DATE_FORMAT(invp.`invoice_date`, "%Y-%m-%d") >= DATE_FORMAT("' . $start_date . '", "%Y-%m-%d") AND DATE_FORMAT(invp.`invoice_date`, "%Y-%m-%d") <= DATE_FORMAT("' . $end_date . '", "%Y-%m-%d") AND inv.service IN(19,41,44) AND invp.patient !=0 ' . $criteria . ' ORDER BY invp.`invoice_date` ASC');
        $result = $command->queryAll();

        return $result;
    }

    public static function patientContactRegister($start_date, $end_date, $category, $category_new, $admission)
    {
        $criteria = '';
        if (!empty(@$category))
            $criteria .= ' AND pat.category=' . @$category;
        if (!empty(@$category_new))
//            $criteria .= ' AND pat.category_new=' . @$category_new;
            $criteria .= ' AND pat.category_new IN(SELECT s.id FROM {{patient_category_new}} s WHERE s.parent=' . @$category_new . ' OR s.id=' . @$category_new . ')';
        if (!empty(@$patient_type))
            $criteria .= ' AND pat.patient_type=' . @$patient_type;
        if (!empty(@$admission))
            $criteria .= ' AND pat.admission="' . @$admission . '"';

        $connection = Yii::app()->db;
        $command = $connection->createCommand('SELECT pat.`created_on`, pat.id AS id, pat.pat_id AS pid, pat.`category` AS category, pat.`category_new` AS category_new, pat.`name` AS pname, pat.`patient_grade`, pat.`age`, pat.`sex`, pat.`ref_no`, pat.`mobile`, pat.`referred`, pat.`problem`, CONCAT(pat.`emergency_name`,\'<br />\',pat.`emergency_contact`) AS emergency_person, pat.`emergency_relation`, pat.`address`
                                                FROM {{patient}} pat 
                                                WHERE DATE_FORMAT(pat.`created_on`, "%Y-%m-%d") >= DATE_FORMAT("' . $start_date . '", "%Y-%m-%d") AND DATE_FORMAT(pat.`created_on`, "%Y-%m-%d") <= DATE_FORMAT("' . $end_date . '", "%Y-%m-%d") ' . $criteria . '                                                
                                                ORDER BY pat.`created_on` ASC');
        $result = $command->queryAll();
//AND pat.category_new IN(5,6,7,8,9,10,11,12,13) //GROUP BY patp.`pre_number` 
//AND (SELECT COUNT(*) FROM {{patient_prescription}} patP WHERE patP.patient=pat.id AND DATE_FORMAT(patP.`created_on`, "%Y-%m-%d") >= DATE_FORMAT("' . $start_date . '", "%Y-%m-%d") AND DATE_FORMAT(patP.`created_on`, "%Y-%m-%d") <= DATE_FORMAT("' . $end_date . '", "%Y-%m-%d")) > 0
//AND (SELECT COUNT(*) FROM {{invoice_parent}} invP WHERE invP.patient=pat.id AND DATE_FORMAT(invP.`invoice_date`, "%Y-%m-%d") >= DATE_FORMAT("' . $start_date . '", "%Y-%m-%d") AND DATE_FORMAT(invP.`invoice_date`, "%Y-%m-%d") <= DATE_FORMAT("' . $end_date . '", "%Y-%m-%d")) > 0        

        return $result;
    }

    public static function InvoiceByPrescription($start_date, $end_date, $patient, $type)
    {
        $criteria = '';
        if (@$patient != NULL)
            $criteria .= ' AND invp.patient=' . @$patient;
        if (@$type == 1)
            $criteria .= ' AND invp.prescription IS NULL';

        $connection = Yii::app()->db;
        $command = $connection->createCommand('SELECT patp.pre_number, invp.invoice_number, patp.created_on as created_on, pat.id AS id, pat.pat_id AS pid, pat.`category` AS category, pat.`category_new` AS category_new, pat.`name` AS pname, invp.total_amount, invp.`payment_status`                                                
                                                FROM {{patient_prescription}} patp
                                                LEFT OUTER JOIN {{invoice_parent}} invp ON invp.prescription = patp.id
                                                LEFT OUTER JOIN {{patient}} pat ON pat.id = patp.patient 
                                                WHERE DATE_FORMAT(patp.`created_on`, "%Y-%m-%d") >= DATE_FORMAT("' . $start_date . '", "%Y-%m-%d") AND DATE_FORMAT(patp.`created_on`, "%Y-%m-%d") <= DATE_FORMAT("' . $end_date . '", "%Y-%m-%d") ' . $criteria . ' ORDER BY patp.`created_on` DESC');
        $result = $command->queryAll();

        return $result;
    }

}
