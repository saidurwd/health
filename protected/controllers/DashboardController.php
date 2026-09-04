<?php

class DashboardController extends Controller {

    public $layout = '//layouts/column2';

    public function filters() {
        return array(
            'accessControl',
            'postOnly + delete',
        );
    }

    protected function beforeAction($action) {
        return true;
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('index', 'ajaxFilter', 'export'),
                'users' => array('@'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex() {
        $db = Yii::app()->db;
        $cacheKey = 'DashboardIndexData';
        $cached = Yii::app()->cache->get($cacheKey);

        if ($cached !== false) {
            $this->render('index', $cached);
            return;
        }

        $totalPatients = $db->createCommand()
            ->select('COUNT(*)')
            ->from('{{patient}}')
            ->queryScalar();

        $monthlyRevenue = $db->createCommand()
            ->select('COALESCE(SUM(total_amount),0)')
            ->from('{{invoice_parent}}')
            ->where('MONTH(invoice_date)=MONTH(NOW()) AND YEAR(invoice_date)=YEAR(NOW())')
            ->queryScalar();

        $prescriptionsToday = $db->createCommand()
            ->select('COUNT(*)')
            ->from('{{patient_prescription}}')
            ->where('DATE(created_on)=CURDATE()')
            ->queryScalar();

        $admissions = $db->createCommand()
            ->select('COUNT(*)')
            ->from('{{patient}}')
            ->where('admission="Yes"')
            ->queryScalar();

        $lastMonthPatients = $db->createCommand()
            ->select('COUNT(*)')
            ->from('{{patient}}')
            ->where('created_on >= DATE_SUB(NOW(), INTERVAL 2 MONTH) AND created_on < DATE_SUB(NOW(), INTERVAL 1 MONTH)')
            ->queryScalar();
        $patientTrend = $lastMonthPatients > 0 ? round((($totalPatients - $lastMonthPatients) / $lastMonthPatients) * 100, 1) : 0;

        $lastMonthRevenue = $db->createCommand()
            ->select('COALESCE(SUM(total_amount),0)')
            ->from('{{invoice_parent}}')
            ->where('MONTH(invoice_date)=MONTH(DATE_SUB(NOW(), INTERVAL 1 MONTH)) AND YEAR(invoice_date)=YEAR(DATE_SUB(NOW(), INTERVAL 1 MONTH))')
            ->queryScalar();
        $revenueTrend = $lastMonthRevenue > 0 ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) : 0;

        $yesterdayPrescriptions = $db->createCommand()
            ->select('COUNT(*)')
            ->from('{{patient_prescription}}')
            ->where('DATE(created_on)=DATE_SUB(CURDATE(), INTERVAL 1 DAY)')
            ->queryScalar();
        $prescriptionTrend = $yesterdayPrescriptions > 0 ? round((($prescriptionsToday - $yesterdayPrescriptions) / $yesterdayPrescriptions) * 100, 1) : 0;

        $lastWeekAdmissions = $db->createCommand()
            ->select('COUNT(*)')
            ->from('{{patient}}')
            ->where('admission="Yes" AND created_on >= DATE_SUB(NOW(), INTERVAL 14 DAY) AND created_on < DATE_SUB(NOW(), INTERVAL 7 DAY)')
            ->queryScalar();
        $admissionTrend = $lastWeekAdmissions > 0 ? round((($admissions - $lastWeekAdmissions) / $lastWeekAdmissions) * 100, 1) : 0;

        $trendWeek = array(
            'labels' => array(),
            'patients' => array(),
            'revenue' => array(),
        );
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $trendWeek['labels'][] = date('D', strtotime($date));

            $patients = $db->createCommand()
                ->select('COUNT(*)')
                ->from('{{patient}}')
                ->where('DATE(created_on)="' . $date . '"')
                ->queryScalar();
            $trendWeek['patients'][] = (int) $patients;

            $revenue = $db->createCommand()
                ->select('COALESCE(SUM(total_amount),0)')
                ->from('{{invoice_parent}}')
                ->where('DATE(invoice_date)="' . $date . '"')
                ->queryScalar();
            $trendWeek['revenue'][] = (float) $revenue;
        }

        $trendMonth = array(
            'labels' => array(),
            'patients' => array(),
            'revenue' => array(),
        );
        for ($i = 3; $i >= 1; $i--) {
            $start = date('Y-m-d', strtotime("-" . (($i - 1) * 7) . " days"));
            $end = date('Y-m-d', strtotime("-" . (($i - 1) * 7 + 6) . " days"));
            $trendMonth['labels'][] = "Week " . (5 - $i);

            $patients = $db->createCommand()
                ->select('COUNT(*)')
                ->from('{{patient}}')
                ->where('DATE(created_on)>="' . $end . '" AND DATE(created_on)<="' . $start . '"')
                ->queryScalar();
            $trendMonth['patients'][] = (int) $patients;

            $revenue = $db->createCommand()
                ->select('COALESCE(SUM(total_amount),0)')
                ->from('{{invoice_parent}}')
                ->where('DATE(invoice_date)>="' . $end . '" AND DATE(invoice_date)<="' . $start . '"')
                ->queryScalar();
            $trendMonth['revenue'][] = (float) $revenue;
        }

        $trendYear = array(
            'labels' => array(),
            'patients' => array(),
            'revenue' => array(),
        );
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $trendYear['labels'][] = date('M', strtotime($month));

            $patients = $db->createCommand()
                ->select('COUNT(*)')
                ->from('{{patient}}')
                ->where('DATE_FORMAT(created_on,"%Y-%m")="' . $month . '"')
                ->queryScalar();
            $trendYear['patients'][] = (int) $patients;

            $revenue = $db->createCommand()
                ->select('COALESCE(SUM(total_amount),0)')
                ->from('{{invoice_parent}}')
                ->where('DATE_FORMAT(invoice_date,"%Y-%m")="' . $month . '"')
                ->queryScalar();
            $trendYear['revenue'][] = (float) $revenue;
        }

        $demographics = array('labels' => array(), 'values' => array());
        $ageGroups = array('0-5', '6-14', '15-24', '25+');
        foreach ($ageGroups as $group) {
            if (strpos($group, '+') !== false) {
                $min = rtrim($group, '+');
                $cond = 'age >= ' . (int) $min;
            } else {
                list($min, $max) = explode('-', $group);
                $cond = 'age >= ' . (int) $min . ' AND age <= ' . (int) $max;
            }
            foreach (array('Male', 'Female') as $sex) {
                $cnt = $db->createCommand()
                    ->select('COUNT(*)')
                    ->from('{{patient}}')
                    ->where($cond . ' AND sex="' . $sex . '"')
                    ->queryScalar();
                $demographics['labels'][] = $sex . ' ' . $group;
                $demographics['values'][] = (int) $cnt;
            }
        }

        $serviceRevenue = array('labels' => array(), 'values' => array());
        $services = $db->createCommand()
            ->select('s.title, SUM(i.amount) as rev')
            ->from('{{invoice}} i')
            ->join('{{service}} s', 'i.service = s.id')
            ->where('i.service > 0')
            ->group('i.service')
            ->order('rev DESC')
            ->limit(6)
            ->queryAll();
        foreach ($services as $svc) {
            $serviceRevenue['labels'][] = $svc['title'];
            $serviceRevenue['values'][] = (float) $svc['rev'];
        }

        $departments = array('labels' => array(), 'values' => array());
        $depts = $db->createCommand()
            ->select('pcn.title, COUNT(*) as cnt')
            ->from('{{patient}} p')
            ->join('{{patient_category_new}} pcn', 'p.category_new = pcn.id')
            ->where('pcn.parent=0 OR pcn.parent IS NULL')
            ->group('p.category_new')
            ->order('cnt DESC')
            ->queryAll();
        foreach ($depts as $dept) {
            $departments['labels'][] = $dept['title'];
            $departments['values'][] = (int) $dept['cnt'];
        }

        $diseases = array('labels' => array(), 'values' => array());
        $dis = $db->createCommand()
            ->select('d.title, COUNT(*) as cnt')
            ->from('{{patient_prescription}} pp')
            ->join('{{disease}} d', 'pp.diagnosis = d.id')
            ->group('pp.diagnosis')
            ->order('cnt DESC')
            ->limit(7)
            ->queryAll();
        foreach ($dis as $d) {
            $diseases['labels'][] = $d['title'];
            $diseases['values'][] = (int) $d['cnt'];
        }

        $referralSources = array('labels' => array(), 'values' => array());
        $refs = $db->createCommand()
            ->select('referred, COUNT(*) as cnt')
            ->from('{{patient}}')
            ->where('referred IS NOT NULL AND referred != ""')
            ->group('referred')
            ->order('cnt DESC')
            ->limit(8)
            ->queryAll();
        foreach ($refs as $ref) {
            $referralSources['labels'][] = $ref['referred'];
            $referralSources['values'][] = (int) $ref['cnt'];
        }

        $geographicData = array('labels' => array(), 'values' => array());
        $geo = $db->createCommand()
            ->select('d.title as district, COUNT(*) as cnt')
            ->from('{{patient}} p')
            ->join('{{district}} d', 'p.district = d.id')
            ->group('p.district')
            ->order('cnt DESC')
            ->limit(8)
            ->queryAll();
        foreach ($geo as $g) {
            $geographicData['labels'][] = $g['district'];
            $geographicData['values'][] = (int) $g['cnt'];
        }

        $staffPerformance = array('labels' => array(), 'values' => array());
        $staff = $db->createCommand()
            ->select('u.full_name, COUNT(*) as invoices, SUM(ip.total_amount) as revenue')
            ->from('{{invoice_parent}} ip')
            ->join('{{user}} u', 'ip.invoice_by = u.id')
            ->group('ip.invoice_by')
            ->order('revenue DESC')
            ->limit(8)
            ->queryAll();
        foreach ($staff as $s) {
            $staffPerformance['labels'][] = $s['full_name'];
            $staffPerformance['values'][] = (float) $s['revenue'];
        }

        $stockAlerts = array();
        $stock = $db->createCommand()
            ->select('st.title as store, p.title as product, ss.quantity, ss.rate')
            ->from('{{stock_summary}} ss')
            ->join('{{product}} p', 'ss.item = p.id')
            ->join('{{store}} st', 'ss.store = st.id')
            ->where('ss.quantity > 0')
            ->order('ss.quantity ASC')
            ->limit(10)
            ->queryAll();
        foreach ($stock as $row) {
            $stockAlerts[] = array(
                'store' => $row['store'],
                'product' => $row['product'],
                'quantity' => (float) $row['quantity'],
                'rate' => (float) $row['rate'],
            );
        }

        $heatmap = array();
        for ($day = 0; $day < 7; $day++) {
            for ($hour = 8; $hour <= 19; $hour++) {
                $cnt = $db->createCommand()
                    ->select('COUNT(*)')
                    ->from('{{invoice_parent}}')
                    ->where('DAYOFWEEK(created_on)=' . ($day + 1) . ' AND HOUR(created_on)=' . $hour)
                    ->queryScalar();
                $heatmap[] = (int) $cnt;
            }
        }

        $recentActivity = array();
        $recent = $db->createCommand()
            ->select('ip.id, p.pat_id, p.name, pcn.title as dept, DATE(ip.invoice_date) as dt, ip.invoice_date, ip.total_amount, ip.payment_status')
            ->from('{{invoice_parent}} ip')
            ->join('{{patient}} p', 'ip.patient = p.id')
            ->leftJoin('{{patient_category_new}} pcn', 'p.category_new = pcn.id')
            ->order('ip.id DESC')
            ->limit(8)
            ->queryAll();
        foreach ($recent as $row) {
            $status = strtolower($row['payment_status']);
            if ($status == '') $status = 'pending';
            $amount = (float) $row['total_amount'];
            $service = $row['dept'] ? $row['dept'] : 'Consultation';
            $recentActivity[] = array(
                'id' => $row['pat_id'],
                'name' => $row['name'],
                'dept' => $row['dept'] ? $row['dept'] : 'General',
                'date' => $row['dt'] ? $row['dt'] : date('Y-m-d', strtotime($row['invoice_date'])),
                'service' => $service,
                'amount' => $amount,
                'status' => $status,
                'progress' => $status == 'paid' ? 100 : ($status == 'pending' ? 60 : 20),
            );
        }

        $data = array(
            'totalPatients' => $totalPatients,
            'monthlyRevenue' => $monthlyRevenue,
            'prescriptionsToday' => $prescriptionsToday,
            'admissions' => $admissions,
            'patientTrend' => $patientTrend,
            'revenueTrend' => $revenueTrend,
            'prescriptionTrend' => $prescriptionTrend,
            'admissionTrend' => $admissionTrend,
            'trendWeek' => $trendWeek,
            'trendMonth' => $trendMonth,
            'trendYear' => $trendYear,
            'demographics' => $demographics,
            'serviceRevenue' => $serviceRevenue,
            'departments' => $departments,
            'diseases' => $diseases,
            'referralSources' => $referralSources,
            'geographicData' => $geographicData,
            'staffPerformance' => $staffPerformance,
            'stockAlerts' => $stockAlerts,
            'heatmap' => $heatmap,
            'recentActivity' => $recentActivity,
        );

        Yii::app()->cache->set($cacheKey, $data, 300);
        $this->render('index', $data);
    }

    public function actionAjaxFilter() {
        $db = Yii::app()->db;
        $startDate = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-01');
        $endDate = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-t');
        $category = isset($_POST['category']) ? $_POST['category'] : 'all';
        $department = isset($_POST['department']) ? $_POST['department'] : 'all';

        $cacheKey = 'DashboardFilter_' . md5($startDate . $endDate . $category . $department);
        $cached = Yii::app()->cache->get($cacheKey);
        if ($cached !== false) {
            echo $cached;
            Yii::app()->end();
        }

        $patientCondition = '1=1';
        if ($category != 'all') {
            $patientCondition .= ' AND category_new=' . (int) $category;
        }
        if ($department != 'all') {
            $patientCondition .= ' AND category_new IN (SELECT id FROM {{patient_category_new}} WHERE parent=' . (int) $department . ' OR id=' . (int) $department . ')';
        }

        $totalPatients = $db->createCommand()
            ->select('COUNT(*)')
            ->from('{{patient}}')
            ->where($patientCondition)
            ->queryScalar();

        $monthlyRevenue = $db->createCommand()
            ->select('COALESCE(SUM(total_amount),0)')
            ->from('{{invoice_parent}}')
            ->where('DATE(invoice_date)>="' . $startDate . '" AND DATE(invoice_date)<="' . $endDate . '"')
            ->queryScalar();

        $prescriptionsToday = $db->createCommand()
            ->select('COUNT(*)')
            ->from('{{patient_prescription}}')
            ->where('DATE(created_on)=CURDATE()')
            ->queryScalar();

        $admissions = $db->createCommand()
            ->select('COUNT(*)')
            ->from('{{patient}}')
            ->where('admission="Yes" AND ' . $patientCondition)
            ->queryScalar();

        $trendWeek = array('labels' => array(), 'patients' => array(), 'revenue' => array());
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $trendWeek['labels'][] = date('D', strtotime($date));
            $patients = $db->createCommand()
                ->select('COUNT(*)')
                ->from('{{patient}}')
                ->where('DATE(created_on)="' . $date . '" AND ' . $patientCondition)
                ->queryScalar();
            $trendWeek['patients'][] = (int) $patients;
            $revenue = $db->createCommand()
                ->select('COALESCE(SUM(total_amount),0)')
                ->from('{{invoice_parent}}')
                ->where('DATE(invoice_date)="' . $date . '"')
                ->queryScalar();
            $trendWeek['revenue'][] = (float) $revenue;
        }

        $trendMonth = array('labels' => array(), 'patients' => array(), 'revenue' => array());
        for ($i = 3; $i >= 1; $i--) {
            $start = date('Y-m-d', strtotime("-" . (($i - 1) * 7) . " days"));
            $end = date('Y-m-d', strtotime("-" . (($i - 1) * 7 + 6) . " days"));
            $trendMonth['labels'][] = "Week " . (5 - $i);
            $patients = $db->createCommand()
                ->select('COUNT(*)')
                ->from('{{patient}}')
                ->where('DATE(created_on)>="' . $end . '" AND DATE(created_on)<="' . $start . '" AND ' . $patientCondition)
                ->queryScalar();
            $trendMonth['patients'][] = (int) $patients;
            $revenue = $db->createCommand()
                ->select('COALESCE(SUM(total_amount),0)')
                ->from('{{invoice_parent}}')
                ->where('DATE(invoice_date)>="' . $end . '" AND DATE(invoice_date)<="' . $start . '"')
                ->queryScalar();
            $trendMonth['revenue'][] = (float) $revenue;
        }

        $trendYear = array('labels' => array(), 'patients' => array(), 'revenue' => array());
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $trendYear['labels'][] = date('M', strtotime($month));
            $patients = $db->createCommand()
                ->select('COUNT(*)')
                ->from('{{patient}}')
                ->where('DATE_FORMAT(created_on,"%Y-%m")="' . $month . '" AND ' . $patientCondition)
                ->queryScalar();
            $trendYear['patients'][] = (int) $patients;
            $revenue = $db->createCommand()
                ->select('COALESCE(SUM(total_amount),0)')
                ->from('{{invoice_parent}}')
                ->where('DATE_FORMAT(invoice_date,"%Y-%m")="' . $month . '"')
                ->queryScalar();
            $trendYear['revenue'][] = (float) $revenue;
        }

        $demographics = array('labels' => array(), 'values' => array());
        $ageGroups = array('0-5', '6-14', '15-24', '25+');
        foreach ($ageGroups as $group) {
            if (strpos($group, '+') !== false) {
                $min = rtrim($group, '+');
                $cond = 'age >= ' . (int) $min;
            } else {
                list($min, $max) = explode('-', $group);
                $cond = 'age >= ' . (int) $min . ' AND age <= ' . (int) $max;
            }
            foreach (array('Male', 'Female') as $sex) {
                $cnt = $db->createCommand()
                    ->select('COUNT(*)')
                    ->from('{{patient}}')
                    ->where($cond . ' AND sex="' . $sex . '" AND ' . $patientCondition)
                    ->queryScalar();
                $demographics['labels'][] = $sex . ' ' . $group;
                $demographics['values'][] = (int) $cnt;
            }
        }

        $serviceRevenue = array('labels' => array(), 'values' => array());
        $services = $db->createCommand()
            ->select('s.title, SUM(i.amount) as rev')
            ->from('{{invoice}} i')
            ->join('{{service}} s', 'i.service = s.id')
            ->where('i.service > 0')
            ->group('i.service')
            ->order('rev DESC')
            ->limit(6)
            ->queryAll();
        foreach ($services as $svc) {
            $serviceRevenue['labels'][] = $svc['title'];
            $serviceRevenue['values'][] = (float) $svc['rev'];
        }

        $departments = array('labels' => array(), 'values' => array());
        $deptCondition = '1=1';
        if ($department != 'all') {
            $deptCondition = 'pcn.id=' . (int) $department . ' OR pcn.parent=' . (int) $department;
        }
        $depts = $db->createCommand()
            ->select('pcn.title, COUNT(*) as cnt')
            ->from('{{patient}} p')
            ->join('{{patient_category_new}} pcn', 'p.category_new = pcn.id')
            ->where('(' . $deptCondition . ')')
            ->group('p.category_new')
            ->order('cnt DESC')
            ->queryAll();
        foreach ($depts as $dept) {
            $departments['labels'][] = $dept['title'];
            $departments['values'][] = (int) $dept['cnt'];
        }

        $diseases = array('labels' => array(), 'values' => array());
        $diseaseCondition = '1=1';
        if ($category != 'all') {
            $diseaseCondition = 'p.category_new=' . (int) $category;
        }
        $dis = $db->createCommand()
            ->select('d.title, COUNT(*) as cnt')
            ->from('{{patient_prescription}} pp')
            ->join('{{patient}} p', 'pp.patient = p.id')
            ->join('{{disease}} d', 'pp.diagnosis = d.id')
            ->where($diseaseCondition)
            ->group('pp.diagnosis')
            ->order('cnt DESC')
            ->limit(7)
            ->queryAll();
        foreach ($dis as $d) {
            $diseases['labels'][] = $d['title'];
            $diseases['values'][] = (int) $d['cnt'];
        }

        $referralSources = array('labels' => array(), 'values' => array());
        $refs = $db->createCommand()
            ->select('referred, COUNT(*) as cnt')
            ->from('{{patient}}')
            ->where('referred IS NOT NULL AND referred != ""')
            ->group('referred')
            ->order('cnt DESC')
            ->limit(8)
            ->queryAll();
        foreach ($refs as $ref) {
            $referralSources['labels'][] = $ref['referred'];
            $referralSources['values'][] = (int) $ref['cnt'];
        }

        $geographicData = array('labels' => array(), 'values' => array());
        $geo = $db->createCommand()
            ->select('d.title as district, COUNT(*) as cnt')
            ->from('{{patient}} p')
            ->join('{{district}} d', 'p.district = d.id')
            ->group('p.district')
            ->order('cnt DESC')
            ->limit(8)
            ->queryAll();
        foreach ($geo as $g) {
            $geographicData['labels'][] = $g['district'];
            $geographicData['values'][] = (int) $g['cnt'];
        }

        $staffPerformance = array('labels' => array(), 'values' => array());
        $staff = $db->createCommand()
            ->select('u.full_name, COUNT(*) as invoices, SUM(ip.total_amount) as revenue')
            ->from('{{invoice_parent}} ip')
            ->join('{{user}} u', 'ip.invoice_by = u.id')
            ->group('ip.invoice_by')
            ->order('revenue DESC')
            ->limit(8)
            ->queryAll();
        foreach ($staff as $s) {
            $staffPerformance['labels'][] = $s['full_name'];
            $staffPerformance['values'][] = (float) $s['revenue'];
        }

        $stockAlerts = array();
        $stock = $db->createCommand()
            ->select('st.title as store, p.title as product, ss.quantity, ss.rate')
            ->from('{{stock_summary}} ss')
            ->join('{{product}} p', 'ss.item = p.id')
            ->join('{{store}} st', 'ss.store = st.id')
            ->where('ss.quantity > 0')
            ->order('ss.quantity ASC')
            ->limit(10)
            ->queryAll();
        foreach ($stock as $row) {
            $stockAlerts[] = array(
                'store' => $row['store'],
                'product' => $row['product'],
                'quantity' => (float) $row['quantity'],
                'rate' => (float) $row['rate'],
            );
        }

        $heatmap = array();
        for ($day = 0; $day < 7; $day++) {
            for ($hour = 8; $hour <= 19; $hour++) {
                $cnt = $db->createCommand()
                    ->select('COUNT(*)')
                    ->from('{{invoice_parent}}')
                    ->where('DAYOFWEEK(created_on)=' . ($day + 1) . ' AND HOUR(created_on)=' . $hour)
                    ->queryScalar();
                $heatmap[] = (int) $cnt;
            }
        }

        $recentActivity = array();
        $recent = $db->createCommand()
            ->select('ip.id, p.pat_id, p.name, pcn.title as dept, DATE(ip.invoice_date) as dt, ip.invoice_date, ip.total_amount, ip.payment_status')
            ->from('{{invoice_parent}} ip')
            ->join('{{patient}} p', 'ip.patient = p.id')
            ->leftJoin('{{patient_category_new}} pcn', 'p.category_new = pcn.id')
            ->order('ip.id DESC')
            ->limit(8)
            ->queryAll();
        foreach ($recent as $row) {
            $status = strtolower($row['payment_status']);
            if ($status == '') $status = 'pending';
            $amount = (float) $row['total_amount'];
            $service = $row['dept'] ? $row['dept'] : 'Consultation';
            $recentActivity[] = array(
                'id' => $row['pat_id'],
                'name' => $row['name'],
                'dept' => $row['dept'] ? $row['dept'] : 'General',
                'date' => $row['dt'] ? $row['dt'] : date('Y-m-d', strtotime($row['invoice_date'])),
                'service' => $service,
                'amount' => $amount,
                'status' => $status,
                'progress' => $status == 'paid' ? 100 : ($status == 'pending' ? 60 : 20),
            );
        }

        $output = CJSON::encode(array(
            'totalPatients' => $totalPatients,
            'monthlyRevenue' => $monthlyRevenue,
            'prescriptionsToday' => $prescriptionsToday,
            'admissions' => $admissions,
            'trendWeek' => $trendWeek,
            'trendMonth' => $trendMonth,
            'trendYear' => $trendYear,
            'demographics' => $demographics,
            'serviceRevenue' => $serviceRevenue,
            'departments' => $departments,
            'diseases' => $diseases,
            'referralSources' => $referralSources,
            'geographicData' => $geographicData,
            'staffPerformance' => $staffPerformance,
            'stockAlerts' => $stockAlerts,
            'heatmap' => $heatmap,
            'recentActivity' => $recentActivity,
        ));

        Yii::app()->cache->set($cacheKey, $output, 300);
        echo $output;
        Yii::app()->end();
    }

    public function actionExport() {
        $this->layout = false;
        $db = Yii::app()->db;

        $totalPatients = $db->createCommand()
            ->select('COUNT(*)')
            ->from('{{patient}}')
            ->queryScalar();

        $monthlyRevenue = $db->createCommand()
            ->select('COALESCE(SUM(total_amount),0)')
            ->from('{{invoice_parent}}')
            ->where('MONTH(invoice_date)=MONTH(NOW()) AND YEAR(invoice_date)=YEAR(NOW())')
            ->queryScalar();

        $prescriptionsToday = $db->createCommand()
            ->select('COUNT(*)')
            ->from('{{patient_prescription}}')
            ->where('DATE(created_on)=CURDATE()')
            ->queryScalar();

        $admissions = $db->createCommand()
            ->select('COUNT(*)')
            ->from('{{patient}}')
            ->where('admission="Yes"')
            ->queryScalar();

        $recent = $db->createCommand()
            ->select('p.pat_id, p.name, pcn.title as dept, DATE(ip.invoice_date) as dt, ip.total_amount, ip.payment_status')
            ->from('{{invoice_parent}} ip')
            ->join('{{patient}} p', 'ip.patient = p.id')
            ->leftJoin('{{patient_category_new}} pcn', 'p.category_new = pcn.id')
            ->order('ip.id DESC')
            ->limit(100)
            ->queryAll();

        $fileName = 'dashboard_export_' . date('Y-m-d_H-i-s') . '.csv';
        $csv = "Dashboard Export\n";
        $csv .= "Generated On," . date('Y-m-d H:i:s') . "\n\n";
        $csv .= "KPIs\n";
        $csv .= "Total Patients," . $totalPatients . "\n";
        $csv .= "Monthly Revenue," . $monthlyRevenue . "\n";
        $csv .= "Prescriptions Today," . $prescriptionsToday . "\n";
        $csv .= "Admissions," . $admissions . "\n\n";
        $csv .= "Recent Patient Activity\n";
        $csv .= "Patient ID,Patient Name,Department,Date,Amount,Status\n";
        foreach ($recent as $row) {
            $csv .= $row['pat_id'] . ",";
            $csv .= "\"" . str_replace("\"", "\"\"", $row['name']) . "\",";
            $csv .= "\"" . str_replace("\"", "\"\"", $row['dept'] ? $row['dept'] : 'General') . "\",";
            $csv .= ($row['dt'] ? $row['dt'] : date('Y-m-d', strtotime($row['invoice_date']))) . ",";
            $csv .= $row['total_amount'] . ",";
            $csv .= ucfirst($row['payment_status']) . "\n";
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $fileName);
        header('Content-Length: ' . strlen($csv));
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        echo $csv;
        Yii::app()->end();
    }
}
