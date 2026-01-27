<?php
session_start();
include('config.php');

// ==========================
// Admin Auth Guard
// ==========================
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Initialize
$report = [];
$report_summary = [];
$error_msg = '';
$success_msg = '';

// Handle CSV Download
if (isset($_POST['download_csv'])) {
    $csv_data = json_decode($_POST['csv_data'], true);
    $filename = 'report_' . date('Ymd_His') . '.csv';
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');

    if (!empty($csv_data)) {
        fputcsv($output, array_keys($csv_data[0])); // header
        foreach ($csv_data as $row) {
            fputcsv($output, $row);
        }
    }
    fclose($output);
    exit();
}

// Handle Report Form Submission
if (isset($_POST['generate_report'])) {
    $report_type = $_POST['report_type']; // sales / booking / profit
    $period_type = $_POST['period_type']; // daily / weekly / monthly / yearly / custom
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;

    // Validate period type and set default dates if needed
    $today = date('Y-m-d');
    switch ($period_type) {
        case 'daily':
            $start_date = $end_date = $today;
            break;
        case 'weekly':
            $start_date = date('Y-m-d', strtotime('monday this week'));
            $end_date = date('Y-m-d', strtotime('sunday this week'));
            break;
        case 'monthly':
            $start_date = date('Y-m-01');
            $end_date = date('Y-m-t');
            break;
        case 'yearly':
            $start_date = date('Y-01-01');
            $end_date = date('Y-12-31');
            break;
        case 'custom':
            if (!$start_date || !$end_date) {
                $error_msg = "Please select a valid start and end date for custom range.";
            }
            break;
        default:
            $error_msg = "Invalid period type.";
    }

    if (!$error_msg) {
        // Fetch all bookings in range
        $stmt = mysqli_prepare($conn, "SELECT b.booking_id, b.booking_date, b.start_time, b.total_amount, 
                                              b.status, b.service_address, b.contact_number, b.payment_method, b.created_at,
                                              u.full_name AS user_name, u.email AS user_email, u.phone AS user_phone,
                                              s.service_name, bs.price_at_booking
                                       FROM bookings b
                                       JOIN users u ON b.user_id = u.id
                                       JOIN booking_services bs ON b.booking_id = bs.booking_id
                                       JOIN services s ON bs.service_id = s.service_id
                                       WHERE b.booking_date BETWEEN ? AND ?
                                       ORDER BY b.booking_date ASC");
        mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $bid = $row['booking_id'];
            if (!isset($report[$bid])) {
                $report[$bid] = [
                    'Booking ID' => $bid,
                    'Booking Date' => $row['booking_date'],
                    'Start Time' => $row['start_time'],
                    'User' => $row['user_name'],
                    'Email' => $row['user_email'],
                    'Phone' => $row['user_phone'],
                    'Service Address' => $row['service_address'],
                    'Contact Number' => $row['contact_number'],
                    'Payment Method' => ucfirst(str_replace('_', ' ', $row['payment_method'])),
                    'Status' => ucfirst($row['status']),
                    'Services' => [],
                    'Total Amount' => 0,
                    'Profit' => 0
                ];
            }
            $report[$bid]['Services'][] = $row['service_name'] . ' (Rs ' . number_format($row['price_at_booking'], 2) . ')';
            $report[$bid]['Total Amount'] += $row['price_at_booking'];
            $report[$bid]['Profit'] += $row['price_at_booking'] * 0.3; // Example profit margin 30%
        }

        // Aggregate summary for table/chart
        $report_summary = [];
        foreach ($report as $b) {
            $date = $b['Booking Date'];
            if (!isset($report_summary[$date])) {
                $report_summary[$date] = ['Total Sales' => 0, 'Total Bookings' => 0, 'Total Profit' => 0];
            }
            $report_summary[$date]['Total Sales'] += $b['Total Amount'];
            $report_summary[$date]['Total Bookings'] += 1;
            $report_summary[$date]['Total Profit'] += $b['Profit'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Reports</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>Generate Reports</h2>

    <?php if (!empty($error_msg)): ?>
        <div class="alert alert-danger"><?= $error_msg ?></div>
    <?php endif; ?>

    <form method="post" class="mb-4">
        <div class="form-row align-items-end">
            <div class="col-md-3">
                <label>Report Type</label>
                <select name="report_type" class="form-control" required>
                    <option value="sales" <?= (isset($report_type) && $report_type=='sales')?'selected':'' ?>>Sales</option>
                    <option value="booking" <?= (isset($report_type) && $report_type=='booking')?'selected':'' ?>>Bookings</option>
                    <option value="profit" <?= (isset($report_type) && $report_type=='profit')?'selected':'' ?>>Profit</option>
                </select>
            </div>

            <div class="col-md-3">
                <label>Period Type</label>
                <select name="period_type" class="form-control" id="period_type" required>
                    <option value="daily" <?= (isset($period_type) && $period_type=='daily')?'selected':'' ?>>Daily</option>
                    <option value="weekly" <?= (isset($period_type) && $period_type=='weekly')?'selected':'' ?>>Weekly</option>
                    <option value="monthly" <?= (isset($period_type) && $period_type=='monthly')?'selected':'' ?>>Monthly</option>
                    <option value="yearly" <?= (isset($period_type) && $period_type=='yearly')?'selected':'' ?>>Yearly</option>
                    <option value="custom" <?= (isset($period_type) && $period_type=='custom')?'selected':'' ?>>Custom</option>
                </select>
            </div>

            <div class="col-md-2">
                <label>Start Date</label>
                <input type="date" name="start_date" class="form-control" value="<?= $start_date ?? '' ?>" id="start_date">
            </div>

            <div class="col-md-2">
                <label>End Date</label>
                <input type="date" name="end_date" class="form-control" value="<?= $end_date ?? '' ?>" id="end_date">
            </div>

            <div class="col-md-2">
                <button type="submit" name="generate_report" class="btn btn-primary btn-block">Generate</button>
            </div>
        </div>
    </form>

    <?php if (!empty($report)): ?>
        <div class="mb-3">
            <form method="post">
                <input type="hidden" name="csv_data" value='<?= json_encode(array_values($report)) ?>'>
                <button type="submit" name="download_csv" class="btn btn-success">Download CSV</button>
            </form>
        </div>

        <h4>Detailed Report</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th>Booking ID</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Service Address</th>
                        <th>Contact</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Services</th>
                        <th>Total Amount</th>
                        <th>Profit</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($report as $b): ?>
                    <tr>
                        <td><?= $b['Booking ID'] ?></td>
                        <td><?= $b['Booking Date'] ?></td>
                        <td><?= $b['Start Time'] ?></td>
                        <td><?= $b['User'] ?></td>
                        <td><?= $b['Email'] ?></td>
                        <td><?= $b['Phone'] ?></td>
                        <td><?= $b['Service Address'] ?></td>
                        <td><?= $b['Contact Number'] ?></td>
                        <td><?= $b['Payment Method'] ?></td>
                        <td><?= $b['Status'] ?></td>
                        <td><?= implode(", ", $b['Services']) ?></td>
                        <td>Rs <?= number_format($b['Total Amount'],2) ?></td>
                        <td>Rs <?= number_format($b['Profit'],2) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h4 class="mt-5">Summary by Date</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>Date</th>
                        <th>Total Sales (Rs)</th>
                        <th>Total Bookings</th>
                        <th>Total Profit (Rs)</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($report_summary as $date => $s): ?>
                    <tr>
                        <td><?= $date ?></td>
                        <td>Rs <?= number_format($s['Total Sales'],2) ?></td>
                        <td><?= $s['Total Bookings'] ?></td>
                        <td>Rs <?= number_format($s['Total Profit'],2) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>

<script>
    const periodSelect = document.getElementById('period_type');
    const startInput = document.getElementById('start_date');
    const endInput = document.getElementById('end_date');

    function toggleCustomDates() {
        if (periodSelect.value === 'custom') {
            startInput.disabled = false;
            endInput.disabled = false;
        } else {
            startInput.disabled = true;
            endInput.disabled = true;
        }
    }

    periodSelect.addEventListener('change', toggleCustomDates);
    toggleCustomDates();
</script>

</body>
</html>
