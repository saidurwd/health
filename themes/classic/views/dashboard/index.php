<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
    :root {
        --primary: #3b5998;
        --primary-light: #e8eaf6;
        --accent: #00bcd4;
        --success: #4caf50;
        --warning: #ff9800;
        --danger: #f44336;
        --dark: #2c3e50;
        --gray-50: #f8f9fa;
        --gray-100: #f1f3f5;
        --gray-200: #e9ecef;
        --gray-300: #dee2e6;
        --gray-400: #ced4da;
        --gray-500: #adb5bd;
        --gray-600: #6c757d;
        --gray-700: #495057;
        --gray-800: #343a40;
        --gray-900: #212529;
        --white: #ffffff;
        --shadow-sm: 0 1px 2px 0 rgba(0,0,0,0.05);
        --shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
        --radius: 12px;
        --radius-sm: 8px;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
        color: var(--gray-800);
        line-height: 1.6;
        min-height: 100vh;
    }

    .dashboard-header {
        background: var(--white);
        border-bottom: 1px solid var(--gray-200);
        padding: 20px 32px;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: var(--shadow-sm);
    }

    .header-content {
        max-width: 1600px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .header-title { display: flex; align-items: center; gap: 12px; }

    .header-title h1 {
        font-size: 24px;
        font-weight: 700;
        color: var(--dark);
        letter-spacing: -0.5px;
    }

    .header-title .icon {
        width: 40px; height: 40px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        color: var(--white); font-size: 18px;
    }

    .header-meta {
        display: flex; align-items: center; gap: 16px;
        color: var(--gray-600); font-size: 14px;
    }

    .header-meta .live-indicator {
        display: flex; align-items: center; gap: 6px;
        background: var(--gray-100); padding: 6px 12px;
        border-radius: 20px; font-weight: 500;
    }

    .header-meta .live-indicator::before {
        content: ''; width: 8px; height: 8px;
        background: var(--success); border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(1.1); }
    }

    .filter-bar {
        background: var(--white); margin: 24px 32px 0;
        padding: 20px 24px; border-radius: var(--radius);
        box-shadow: var(--shadow); display: flex;
        align-items: flex-end; gap: 16px; flex-wrap: wrap;
        border: 1px solid var(--gray-200);
    }

    .filter-group { display: flex; flex-direction: column; gap: 6px; }

    .filter-group label {
        font-size: 12px; font-weight: 600; text-transform: uppercase;
        letter-spacing: 0.5px; color: var(--gray-600);
    }

    .filter-group input, .filter-group select {
        padding: 10px 14px; border: 1px solid var(--gray-300);
        border-radius: var(--radius-sm); font-size: 14px;
        background: var(--white); color: var(--gray-800);
        min-width: 160px; transition: border-color 0.2s, box-shadow 0.2s;
        font-family: inherit;
    }

    .filter-group input:focus, .filter-group select:focus {
        outline: none; border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    .btn {
        padding: 10px 24px; border: none; border-radius: var(--radius-sm);
        font-size: 14px; font-weight: 600; cursor: pointer;
        transition: all 0.2s; display: inline-flex;
        align-items: center; gap: 8px; font-family: inherit;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), #4a69bd);
        color: var(--white); box-shadow: 0 4px 14px rgba(59, 89, 152, 0.3);
    }

    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(59, 89, 152, 0.4); }

    .btn-outline {
        background: var(--white); color: var(--primary);
        border: 1px solid var(--primary);
    }

    .btn-outline:hover { background: var(--primary-light); }

    .dashboard-container { max-width: 1600px; margin: 0 auto; padding: 24px 32px; }

    .kpi-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px; margin-bottom: 24px;
    }

    .kpi-card {
        background: var(--white); border-radius: var(--radius);
        padding: 24px; box-shadow: var(--shadow); border: 1px solid var(--gray-200);
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative; overflow: hidden;
    }

    .kpi-card::before {
        content: ''; position: absolute; top: 0; left: 0;
        width: 4px; height: 100%;
    }

    .kpi-card.primary::before { background: var(--primary); }
    .kpi-card.accent::before { background: var(--accent); }
    .kpi-card.success::before { background: var(--success); }
    .kpi-card.warning::before { background: var(--warning); }

    .kpi-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); }

    .kpi-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }

    .kpi-label {
        font-size: 13px; font-weight: 600; text-transform: uppercase;
        letter-spacing: 0.5px; color: var(--gray-600);
    }

    .kpi-icon {
        width: 40px; height: 40px; border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center; font-size: 18px;
    }

    .kpi-card.primary .kpi-icon { background: var(--primary-light); color: var(--primary); }
    .kpi-card.accent .kpi-icon { background: #e0f7fa; color: var(--accent); }
    .kpi-card.success .kpi-icon { background: #e8f5e9; color: var(--success); }
    .kpi-card.warning .kpi-icon { background: #fff3e0; color: var(--warning); }

    .kpi-value { font-size: 32px; font-weight: 700; color: var(--dark); line-height: 1.2; margin-bottom: 8px; }

    .kpi-trend { display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 500; }
    .kpi-trend.up { color: var(--success); }
    .kpi-trend.down { color: var(--danger); }

    .charts-grid { display: grid; grid-template-columns: repeat(12, 1fr); gap: 20px; margin-bottom: 24px; }

    .chart-card {
        background: var(--white); border-radius: var(--radius);
        padding: 24px; box-shadow: var(--shadow); border: 1px solid var(--gray-200);
    }

    .chart-card.full { grid-column: span 12; }
    .chart-card.half { grid-column: span 6; }
    .chart-card.third { grid-column: span 4; }
    .chart-card.two-third { grid-column: span 8; }

    .chart-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--gray-100);
    }

    .chart-title { font-size: 16px; font-weight: 700; color: var(--dark); }

    .chart-subtitle { font-size: 13px; color: var(--gray-600); margin-top: 2px; }

    .chart-actions { display: flex; gap: 8px; }

    .chart-actions button {
        padding: 4px 10px; border: 1px solid var(--gray-300);
        background: var(--white); border-radius: 6px; font-size: 12px;
        cursor: pointer; transition: all 0.2s; color: var(--gray-700); font-family: inherit;
    }

    .chart-actions button.active, .chart-actions button:hover {
        background: var(--primary); color: var(--white); border-color: var(--primary);
    }

    .chart-container { position: relative; height: 320px; }
    .chart-container.tall { height: 400px; }

    .data-table { width: 100%; border-collapse: collapse; margin-top: 8px; }

    .data-table th {
        text-align: left; padding: 12px 16px; font-size: 12px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.5px; color: var(--gray-600);
        border-bottom: 2px solid var(--gray-200); background: var(--gray-50);
    }

    .data-table td { padding: 14px 16px; font-size: 14px; color: var(--gray-700); border-bottom: 1px solid var(--gray-100); }
    .data-table tbody tr { transition: background 0.15s; }
    .data-table tbody tr:hover { background: var(--gray-50); }

    .status-badge {
        display: inline-block; padding: 4px 12px;
        border-radius: 20px; font-size: 12px; font-weight: 600;
    }

    .status-badge.paid { background: #e8f5e9; color: #2e7d32; }
    .status-badge.unpaid { background: #ffebee; color: #c62828; }
    .status-badge.pending { background: #fff3e0; color: #ef6c00; }

    .progress-bar {
        width: 100%; height: 8px; background: var(--gray-200);
        border-radius: 4px; overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%; border-radius: 4px; transition: width 0.6s ease;
    }

    .heatmap-container {
        display: grid; grid-template-columns: repeat(12, 1fr);
        gap: 4px; padding: 16px 0;
    }

    .heatmap-cell {
        aspect-ratio: 1; border-radius: 4px; position: relative;
        cursor: pointer; transition: transform 0.15s;
    }

    .heatmap-cell:hover { transform: scale(1.15); z-index: 2; }
    .heatmap-cell[data-level="0"] { background: #f1f3f5; }
    .heatmap-cell[data-level="1"] { background: #bbdefb; }
    .heatmap-cell[data-level="2"] { background: #64b5f6; }
    .heatmap-cell[data-level="3"] { background: #1976d2; }
    .heatmap-cell[data-level="4"] { background: #0d47a1; }

    .heatmap-labels {
        display: grid; grid-template-columns: 40px repeat(12, 1fr);
        gap: 4px; margin-bottom: 8px; font-size: 11px;
        color: var(--gray-600); font-weight: 500;
    }

    .heatmap-legend {
        display: flex; align-items: center; gap: 8px;
        font-size: 12px; color: var(--gray-600); margin-top: 12px;
    }

    .heatmap-legend-item { width: 16px; height: 16px; border-radius: 3px; }

    @media (max-width: 1024px) {
        .chart-card.half, .chart-card.third, .chart-card.two-third { grid-column: span 12; }
    }

    @media (max-width: 768px) {
        .dashboard-header, .filter-bar, .dashboard-container {
            padding-left: 16px; padding-right: 16px;
            margin-left: 16px; margin-right: 16px;
        }
        .kpi-grid { grid-template-columns: 1fr; }
        .header-content { flex-direction: column; align-items: flex-start; }
    }
</style>

<div class="dashboard-header">
    <div class="header-content">
        <div class="header-title">
            <div class="icon">&#x1F3E5;</div>
            <div>
                <h1>Health Management Dashboard</h1>
            </div>
        </div>
        <div class="header-meta">
            <span id="currentDateTime"><?php echo date('F j, Y g:i A'); ?></span>
            <span class="live-indicator">Live Data</span>
        </div>
    </div>
</div>

<div class="filter-bar">
    <div class="filter-group">
        <label>Start Date</label>
        <input type="date" id="startDate" value="<?php echo date('Y-m-01'); ?>">
    </div>
    <div class="filter-group">
        <label>End Date</label>
        <input type="date" id="endDate" value="<?php echo date('Y-m-t'); ?>">
    </div>
    <div class="filter-group">
        <label>Category</label>
        <select id="categoryFilter">
            <option value="all">All Categories</option>
            <?php
            $cats = Yii::app()->db->createCommand()
                ->select('id, title')
                ->from('{{patient_category_new}}')
                ->where('status="Active"')
                ->order('title')
                ->queryAll();
            foreach ($cats as $cat) {
                echo '<option value="' . $cat['id'] . '">' . CHtml::encode($cat['title']) . '</option>';
            }
            ?>
        </select>
    </div>
    <div class="filter-group">
        <label>Department</label>
        <select id="departmentFilter">
            <option value="all">All Departments</option>
            <?php
            $depts = Yii::app()->db->createCommand()
                ->select('id, title')
                ->from('{{department}}')
                ->order('title')
                ->queryAll();
            foreach ($depts as $dept) {
                echo '<option value="' . $dept['id'] . '">' . CHtml::encode($dept['title']) . '</option>';
            }
            ?>
        </select>
    </div>
    <button class="btn btn-primary" onclick="applyFilters()">Apply Filters</button>
    <form id="exportForm" method="get" action="/index.php?r=dashboard/export" style="display:none;"></form>
    <button class="btn btn-outline" onclick="document.getElementById('exportForm').submit();">Export</button>
</div>

<div class="dashboard-container">
    <div class="kpi-grid">
        <div class="kpi-card primary">
            <div class="kpi-header">
                <span class="kpi-label">Total Patients</span>
                <div class="kpi-icon">&#x1F464;</div>
            </div>
            <div class="kpi-value" id="kpiTotalPatients"><?php echo number_format($totalPatients); ?></div>
            <div class="kpi-trend <?php echo $patientTrend >= 0 ? 'up' : 'down'; ?>">
                <?php echo $patientTrend >= 0 ? '&#x2191;' : '&#x2193;'; ?> <?php echo abs($patientTrend); ?>% <span>vs last month</span>
            </div>
        </div>
        <div class="kpi-card accent">
            <div class="kpi-header">
                <span class="kpi-label">Monthly Revenue</span>
                <div class="kpi-icon">&#x1F4B0;</div>
            </div>
            <div class="kpi-value" id="kpiRevenue">৳<?php echo number_format($monthlyRevenue); ?></div>
            <div class="kpi-trend <?php echo $revenueTrend >= 0 ? 'up' : 'down'; ?>">
                <?php echo $revenueTrend >= 0 ? '&#x2191;' : '&#x2193;'; ?> <?php echo abs($revenueTrend); ?>% <span>vs last month</span>
            </div>
        </div>
        <div class="kpi-card success">
            <div class="kpi-header">
                <span class="kpi-label">Prescriptions Today</span>
                <div class="kpi-icon">&#x1F4EA;</div>
            </div>
            <div class="kpi-value" id="kpiPrescriptions"><?php echo number_format($prescriptionsToday); ?></div>
            <div class="kpi-trend <?php echo $prescriptionTrend >= 0 ? 'up' : 'down'; ?>">
                <?php echo $prescriptionTrend >= 0 ? '&#x2191;' : '&#x2193;'; ?> <?php echo abs($prescriptionTrend); ?>% <span>vs yesterday</span>
            </div>
        </div>
        <div class="kpi-card warning">
            <div class="kpi-header">
                <span class="kpi-label">Admissions</span>
                <div class="kpi-icon">&#x1F3E5;</div>
            </div>
            <div class="kpi-value" id="kpiAdmissions"><?php echo number_format($admissions); ?></div>
            <div class="kpi-trend <?php echo $admissionTrend >= 0 ? 'up' : 'down'; ?>">
                <?php echo $admissionTrend >= 0 ? '&#x2191;' : '&#x2193;'; ?> <?php echo abs($admissionTrend); ?>% <span>vs last week</span>
            </div>
        </div>
    </div>

    <div class="charts-grid">
        <div class="chart-card two-third">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Patient Attendance Trend</div>
                    <div class="chart-subtitle">Daily patient visits over time</div>
                </div>
                <div class="chart-actions">
                    <button class="active" onclick="updateTrendChart('week', this)">Week</button>
                    <button onclick="updateTrendChart('month', this)">Month</button>
                    <button onclick="updateTrendChart('year', this)">Year</button>
                </div>
            </div>
            <div class="chart-container tall">
                <canvas id="attendanceTrendChart"></canvas>
            </div>
        </div>
        <div class="chart-card third">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Patient Demographics</div>
                    <div class="chart-subtitle">Age & Sex distribution</div>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="demographicsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="charts-grid">
        <div class="chart-card half">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Service Revenue Breakdown</div>
                    <div class="chart-subtitle">Revenue by service type</div>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="serviceRevenueChart"></canvas>
            </div>
        </div>
        <div class="chart-card half">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Department Performance</div>
                    <div class="chart-subtitle">Patient count by department</div>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="departmentChart"></canvas>
            </div>
        </div>
    </div>

    <div class="charts-grid">
        <div class="chart-card third">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Disease Categories</div>
                    <div class="chart-subtitle">Common diagnoses</div>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="diseaseChart"></canvas>
            </div>
        </div>
        <div class="chart-card two-third">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Patient Volume Heatmap</div>
                    <div class="chart-subtitle">Hourly distribution for current week</div>
                </div>
            </div>
            <div id="heatmapContainer">
                <div class="heatmap-labels">
                    <span></span>
                    <?php for ($h = 8; $h <= 19; $h++): ?>
                        <span><?php echo $h; ?></span>
                    <?php endfor; ?>
                </div>
                <div class="heatmap-container" id="heatmapGrid"></div>
                <div class="heatmap-legend">
                    <span>Less</span>
                    <div class="heatmap-legend-item" style="background:#f1f3f5;"></div>
                    <div class="heatmap-legend-item" style="background:#bbdefb;"></div>
                    <div class="heatmap-legend-item" style="background:#64b5f6;"></div>
                    <div class="heatmap-legend-item" style="background:#1976d2;"></div>
                    <div class="heatmap-legend-item" style="background:#0d47a1;"></div>
                    <span>More</span>
                </div>
            </div>
        </div>
    </div>

    <div class="charts-grid">
        <div class="chart-card full">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Recent Patient Activity</div>
                    <div class="chart-subtitle">Latest registrations and invoices</div>
                </div>
                    <button class="btn btn-outline" onclick="window.location.href='<?php echo $this->createUrl('/patient/admin'); ?>'">View All</button>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Patient ID</th>
                        <th>Patient Name</th>
                        <th>Department</th>
                        <th>Date</th>
                        <th>Service</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody id="recentActivityTable">
                    <?php foreach ($recentActivity as $row): ?>
                        <tr>
                            <td><strong><?php echo CHtml::encode($row['id']); ?></strong></td>
                            <td><?php echo CHtml::encode($row['name']); ?></td>
                            <td><?php echo CHtml::encode($row['dept']); ?></td>
                            <td><?php echo CHtml::encode($row['date']); ?></td>
                            <td><?php echo CHtml::encode($row['service']); ?></td>
                            <td><strong>৳<?php echo number_format($row['amount'], 2); ?></strong></td>
                            <td><span class="status-badge <?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                            <td style="min-width: 120px;">
                                <div class="progress-bar">
                                    <div class="progress-bar-fill" style="width: <?php echo $row['progress']; ?>%; background: <?php echo $row['progress'] == 100 ? 'var(--success)' : ($row['progress'] > 50 ? 'var(--warning)' : 'var(--danger)'); ?>"></div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="charts-grid">
        <div class="chart-card third">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Referral Sources</div>
                    <div class="chart-subtitle">Patient origin analysis</div>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="referralChart"></canvas>
            </div>
        </div>
        <div class="chart-card third">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Geographic Distribution</div>
                    <div class="chart-subtitle">Patients by district</div>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="geographicChart"></canvas>
            </div>
        </div>
        <div class="chart-card third">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Staff Performance</div>
                    <div class="chart-subtitle">Revenue by invoice_by</div>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="staffChart"></canvas>
            </div>
        </div>
    </div>

    <div class="charts-grid">
        <div class="chart-card full">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Stock Alerts</div>
                    <div class="chart-subtitle">Low inventory items requiring attention</div>
                </div>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Store</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Rate (৳)</th>
                        <th>Value (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stockAlerts as $row): ?>
                        <tr>
                            <td><?php echo CHtml::encode($row['store']); ?></td>
                            <td><?php echo CHtml::encode($row['product']); ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td>৳<?php echo number_format($row['rate'], 2); ?></td>
                            <td><strong>৳<?php echo number_format($row['quantity'] * $row['rate'], 2); ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const sampleData = {
        trendWeek: {
            labels: <?php echo json_encode($trendWeek['labels']); ?>,
            patients: <?php echo json_encode($trendWeek['patients']); ?>,
            revenue: <?php echo json_encode($trendWeek['revenue']); ?>
        },
        trendMonth: {
            labels: <?php echo json_encode($trendMonth['labels']); ?>,
            patients: <?php echo json_encode($trendMonth['patients']); ?>,
            revenue: <?php echo json_encode($trendMonth['revenue']); ?>
        },
        trendYear: {
            labels: <?php echo json_encode($trendYear['labels']); ?>,
            patients: <?php echo json_encode($trendYear['patients']); ?>,
            revenue: <?php echo json_encode($trendYear['revenue']); ?>
        },
        demographics: {
            labels: <?php echo json_encode($demographics['labels']); ?>,
            values: <?php echo json_encode($demographics['values']); ?>
        },
        serviceRevenue: {
            labels: <?php echo json_encode($serviceRevenue['labels']); ?>,
            values: <?php echo json_encode($serviceRevenue['values']); ?>
        },
        departments: {
            labels: <?php echo json_encode($departments['labels']); ?>,
            values: <?php echo json_encode($departments['values']); ?>
        },
        diseases: {
            labels: <?php echo json_encode($diseases['labels']); ?>,
            values: <?php echo json_encode($diseases['values']); ?>
        },
        referralSources: {
            labels: <?php echo json_encode($referralSources['labels']); ?>,
            values: <?php echo json_encode($referralSources['values']); ?>
        },
        geographicData: {
            labels: <?php echo json_encode($geographicData['labels']); ?>,
            values: <?php echo json_encode($geographicData['values']); ?>
        },
        staffPerformance: {
            labels: <?php echo json_encode($staffPerformance['labels']); ?>,
            values: <?php echo json_encode($staffPerformance['values']); ?>
        },
        heatmap: <?php echo json_encode($heatmap); ?>
    };

    Chart.defaults.font.family = "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif";
    Chart.defaults.font.size = 13;
    Chart.defaults.color = '#6c757d';
    Chart.defaults.plugins.tooltip.backgroundColor = '#2c3e50';
    Chart.defaults.plugins.tooltip.padding = 12;
    Chart.defaults.plugins.tooltip.cornerRadius = 8;
    Chart.defaults.plugins.tooltip.titleFont = { weight: '600', size: 13 };
    Chart.defaults.plugins.tooltip.bodyFont = { size: 12 };

    const primaryColor = '#3b5998';
    const accentColor = '#00bcd4';
    const colors = ['#3b5998', '#00bcd4', '#4caf50', '#ff9800', '#f44336', '#9c27b0', '#795548', '#607d8b'];

    function initTrendChart(data) {
        const ctx = document.getElementById('attendanceTrendChart').getContext('2d');
        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: 'Patients', data: data.patients,
                        borderColor: primaryColor,
                        backgroundColor: (context) => {
                            const chart = context.chart;
                            const {ctx, chartArea} = chart;
                            if (!chartArea) return primaryColor + '40';
                            const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                            gradient.addColorStop(0, primaryColor + '40');
                            gradient.addColorStop(1, primaryColor + '00');
                            return gradient;
                        },
                        borderWidth: 3, fill: true, tension: 0.4,
                        pointRadius: 5, pointHoverRadius: 7,
                        pointBackgroundColor: primaryColor, pointBorderColor: '#fff', pointBorderWidth: 2,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Revenue (৳)', data: data.revenue,
                        borderColor: accentColor, backgroundColor: 'transparent',
                        borderWidth: 3, borderDash: [5, 5], tension: 0.4,
                        pointRadius: 5, pointHoverRadius: 7,
                        pointBackgroundColor: accentColor, pointBorderColor: '#fff', pointBorderWidth: 2,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { position: 'top', align: 'end', labels: { usePointStyle: true, pointStyle: 'circle', padding: 20 } } },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { weight: '500' } } },
                    y: {
                        type: 'linear', display: true, position: 'left',
                        grid: { color: '#f1f3f5' },
                        title: { display: true, text: 'Patients', color: primaryColor, font: { weight: '600' } },
                        ticks: { color: primaryColor }
                    },
                    y1: {
                        type: 'linear', display: true, position: 'right',
                        grid: { display: false },
                            title: { display: true, text: 'Revenue (৳)', color: accentColor, font: { weight: '600' } },
                            ticks: { color: accentColor, callback: v => '৳' + (v/1000) + 'k' }
                    }
                }
            }
        });
    }

    function updateTrendChart(period, btn) {
        const data = sampleData['trend' + period.charAt(0).toUpperCase() + period.slice(1)];
        window.trendChart.data.labels = data.labels;
        window.trendChart.data.datasets[0].data = data.patients;
        window.trendChart.data.datasets[1].data = data.revenue;
        window.trendChart.update('active');
        document.querySelectorAll('.chart-actions button').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }

    function initDemographicsChart() {
        const ctx = document.getElementById('demographicsChart').getContext('2d');
        return new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: sampleData.demographics.labels,
                datasets: [{ data: sampleData.demographics.values, backgroundColor: colors, borderWidth: 3, borderColor: '#fff', hoverOffset: 8 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '65%',
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle', padding: 12, boxWidth: 8 } } }
            }
        });
    }

    function initServiceRevenueChart() {
        const ctx = document.getElementById('serviceRevenueChart').getContext('2d');
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: sampleData.serviceRevenue.labels,
                datasets: [{
                    label: 'Revenue', data: sampleData.serviceRevenue.values,
                    backgroundColor: colors.map(c => c + 'cc'), borderColor: colors,
                    borderWidth: 2, borderRadius: 8, borderSkipped: false
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, indexAxis: 'y',
                plugins: { legend: { display: false } },
                        scales: { x: { grid: { color: '#f1f3f5' }, ticks: { callback: v => '৳' + (v/1000) + 'k' } }, y: { grid: { display: false } } }
            }
        });
    }

    function initDepartmentChart() {
        const ctx = document.getElementById('departmentChart').getContext('2d');
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: sampleData.departments.labels,
                datasets: [{
                    label: 'Patients', data: sampleData.departments.values,
                    backgroundColor: primaryColor + 'cc', borderColor: primaryColor,
                    borderWidth: 2, borderRadius: 8, borderSkipped: false
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { grid: { display: false } }, y: { grid: { color: '#f1f3f5' } } }
            }
        });
    }

    function initDiseaseChart() {
        const ctx = document.getElementById('diseaseChart').getContext('2d');
        return new Chart(ctx, {
            type: 'pie',
            data: {
                labels: sampleData.diseases.labels,
                datasets: [{ data: sampleData.diseases.values, backgroundColor: colors, borderWidth: 3, borderColor: '#fff', hoverOffset: 6 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle', padding: 10, boxWidth: 8, font: { size: 11 } } } }
            }
        });
    }

    function initReferralChart() {
        const ctx = document.getElementById('referralChart').getContext('2d');
        return new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: sampleData.referralSources.labels,
                datasets: [{ data: sampleData.referralSources.values, backgroundColor: colors, borderWidth: 3, borderColor: '#fff', hoverOffset: 8 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '65%',
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle', padding: 10, boxWidth: 8, font: { size: 10 } } } }
            }
        });
    }

    function initGeographicChart() {
        const ctx = document.getElementById('geographicChart').getContext('2d');
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: sampleData.geographicData.labels,
                datasets: [{
                    label: 'Patients', data: sampleData.geographicData.values,
                    backgroundColor: primaryColor + 'cc', borderColor: primaryColor,
                    borderWidth: 2, borderRadius: 8, borderSkipped: false
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { grid: { color: '#f1f3f5' } }, y: { grid: { display: false } } }
            }
        });
    }

    function initStaffChart() {
        const ctx = document.getElementById('staffChart').getContext('2d');
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: sampleData.staffPerformance.labels,
                datasets: [{
                    label: 'Revenue (৳)', data: sampleData.staffPerformance.values,
                    backgroundColor: accentColor + 'cc', borderColor: accentColor,
                    borderWidth: 2, borderRadius: 8, borderSkipped: false
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { grid: { color: '#f1f3f5' }, ticks: { callback: v => '৳' + (v/1000) + 'k' } }, y: { grid: { display: false } } }
            }
        });
    }

    function initHeatmap() {
        const container = document.getElementById('heatmapGrid');
        container.innerHTML = '';
        const heatmap = window.heatmapData || sampleData.heatmap;
        heatmap.forEach((level, i) => {
            const cell = document.createElement('div');
            cell.className = 'heatmap-cell';
            cell.setAttribute('data-level', Math.min(level, 4));
            const day = Math.floor(i / 12);
            const hour = (i % 12) + 8;
            cell.title = 'Day ' + (day + 1) + ', Hour ' + hour + ':00\nPatients: ' + (level * 3 + Math.floor(Math.random() * 5));
            cell.onclick = () => alert('Day ' + (day + 1) + ', Hour ' + hour + ':00\nPatients: ' + (level * 3 + Math.floor(Math.random() * 5)));
            container.appendChild(cell);
        });
    }

    function applyFilters() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const category = document.getElementById('categoryFilter').value;
        const department = document.getElementById('departmentFilter').value;

        const btn = document.querySelector('.btn-primary');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Loading...';
        btn.disabled = true;

        $.ajax({
            url: '<?php echo $this->createUrl('/dashboard/ajaxFilter'); ?>',
            type: 'POST',
            data: {
                start_date: startDate,
                end_date: endDate,
                category: category,
                department: department
            },
            dataType: 'json',
            success: function(data) {
                updateDashboard(data);
            },
            error: function() {
                alert('Failed to load filtered data. Please try again.');
            },
            complete: function() {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
    }

    function updateDashboard(data) {
        const primaryColor = '#3b5998';
        const accentColor = '#00bcd4';

        document.getElementById('kpiTotalPatients').textContent = Number(data.totalPatients).toLocaleString();
        document.getElementById('kpiRevenue').textContent = '৳' + Number(data.monthlyRevenue).toLocaleString();
        document.getElementById('kpiPrescriptions').textContent = Number(data.prescriptionsToday).toLocaleString();
        document.getElementById('kpiAdmissions').textContent = Number(data.admissions).toLocaleString();

        if (window.trendChart) {
            window.trendChart.data.labels = data.trendWeek.labels;
            window.trendChart.data.datasets[0].data = data.trendWeek.patients;
            window.trendChart.data.datasets[1].data = data.trendWeek.revenue;
            window.trendChart.update('active');
        }

        if (window.demographicsChart) {
            window.demographicsChart.data.labels = data.demographics.labels;
            window.demographicsChart.data.datasets[0].data = data.demographics.values;
            window.demographicsChart.update();
        }

        if (window.serviceRevenueChart) {
            window.serviceRevenueChart.data.labels = data.serviceRevenue.labels;
            window.serviceRevenueChart.data.datasets[0].data = data.serviceRevenue.values;
            window.serviceRevenueChart.update();
        }

        if (window.departmentChart) {
            window.departmentChart.data.labels = data.departments.labels;
            window.departmentChart.data.datasets[0].data = data.departments.values;
            window.departmentChart.update();
        }

        if (window.diseaseChart) {
            window.diseaseChart.data.labels = data.diseases.labels;
            window.diseaseChart.data.datasets[0].data = data.diseases.values;
            window.diseaseChart.update();
        }

        if (window.referralChart) {
            window.referralChart.data.labels = data.referralSources.labels;
            window.referralChart.data.datasets[0].data = data.referralSources.values;
            window.referralChart.update();
        }

        if (window.geographicChart) {
            window.geographicChart.data.labels = data.geographicData.labels;
            window.geographicChart.data.datasets[0].data = data.geographicData.values;
            window.geographicChart.update();
        }

        if (window.staffChart) {
            window.staffChart.data.labels = data.staffPerformance.labels;
            window.staffChart.data.datasets[0].data = data.staffPerformance.values;
            window.staffChart.update();
        }

        if (window.heatmapData) {
            window.heatmapData = data.heatmap;
            initHeatmap();
        }

        const tbody = document.getElementById('recentActivityTable');
        if (tbody && data.recentActivity) {
            tbody.innerHTML = data.recentActivity.map(row => {
                const amount = parseFloat(row.amount).toFixed(2);
                const progressColor = row.progress == 100 ? 'var(--success)' : (row.progress > 50 ? 'var(--warning)' : 'var(--danger)');
                return '<tr>' +
                    '<td><strong>' + escapeHtml(row.id) + '</strong></td>' +
                    '<td>' + escapeHtml(row.name) + '</td>' +
                    '<td>' + escapeHtml(row.dept) + '</td>' +
                    '<td>' + escapeHtml(row.date) + '</td>' +
                    '<td>' + escapeHtml(row.service) + '</td>' +
                    '<td><strong>৳' + Number(amount).toLocaleString() + '</strong></td>' +
                    '<td><span class="status-badge ' + row.status + '">' + row.status.charAt(0).toUpperCase() + row.status.slice(1) + '</span></td>' +
                    '<td style="min-width: 120px;"><div class="progress-bar"><div class="progress-bar-fill" style="width: ' + row.progress + '%; background: ' + progressColor + '"></div></div></td>' +
                '</tr>';
            }).join('');
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function exportDashboard() {
        window.location.href = '<?php echo $this->createUrl('/dashboard/export'); ?>';
    }

    document.getElementById('currentDateTime').textContent = new Date().toLocaleString('en-US', {
        year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'
    });

    window.addEventListener('DOMContentLoaded', () => {
        window.trendChart = initTrendChart(sampleData.trendWeek);
        window.demographicsChart = initDemographicsChart();
        window.serviceRevenueChart = initServiceRevenueChart();
        window.departmentChart = initDepartmentChart();
        window.diseaseChart = initDiseaseChart();
        window.referralChart = initReferralChart();
        window.geographicChart = initGeographicChart();
        window.staffChart = initStaffChart();
        window.heatmapData = sampleData.heatmap;
        initHeatmap();
    });
</script>
