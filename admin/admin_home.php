<?php
session_start();
include('config.php');

// 1. Authentication Check (Updated for your session logic)
if (!isset($_SESSION["username"]) || (isset($_SESSION["usertype"]) && $_SESSION["usertype"] !== "admin")) {
    header("Location: admin_login.php");
    exit;
}

// =======================================================================
// 2. Database Queries for Service Dashboard
// =======================================================================

$total_users = 0;
$total_services = 0;
$total_revenue = 0;
$pending_bookings = 0;
$confirmed_bookings = 0;
$total_bookings = 0;

$category_labels = [];
$category_data = [];
$status_labels = [];
$status_data = [];
$booking_trend_labels = [];
$booking_trend_data = [];

if (isset($conn)) {
    try {
        // --- Key Performance Indicators (KPIs) ---
        $total_users = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0] ?? 0;
        $total_services = $conn->query("SELECT COUNT(*) FROM services")->fetch_row()[0] ?? 0;
        $total_bookings = $conn->query("SELECT COUNT(*) FROM bookings")->fetch_row()[0] ?? 0;
        $total_revenue = $conn->query("SELECT SUM(total_amount) FROM bookings WHERE status = 'completed'")->fetch_row()[0] ?? 0;
        $pending_bookings = $conn->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'")->fetch_row()[0] ?? 0;
        $confirmed_bookings = $conn->query("SELECT COUNT(*) FROM bookings WHERE status = 'confirmed'")->fetch_row()[0] ?? 0;

        // --- Service Distribution by Category (Chart 1) ---
        $cat_counts = $conn->query("SELECT sc.category_name, COUNT(s.service_id) as count 
                                   FROM service_categories sc 
                                   LEFT JOIN services s ON sc.category_id = s.category_id 
                                   GROUP BY sc.category_id");
        while ($row = $cat_counts->fetch_assoc()) {
            $category_labels[] = $row['category_name'];
            $category_data[] = (int)$row['count'];
        }

        // --- Booking Status Distribution (Chart 2) ---
        $stat_counts = $conn->query("SELECT status, COUNT(*) as count FROM bookings GROUP BY status");
        while ($row = $stat_counts->fetch_assoc()) {
            $status_labels[] = ucfirst($row['status']);
            $status_data[] = (int)$row['count'];
        }

        // --- 7-Day Booking Trend (Chart 3) ---
        $trend_map = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $trend_map[$date] = 0;
        }
        $trend_res = $conn->query("SELECT DATE(created_at) as d, COUNT(*) as c FROM bookings 
                                  WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) GROUP BY d");
        while ($row = $trend_res->fetch_assoc()) {
            $trend_map[$row['d']] = (int)$row['c'];
        }
        $booking_trend_labels = array_map(fn($d) => date('M d', strtotime($d)), array_keys($trend_map));
        $booking_trend_data = array_values($trend_map);

        // --- Recent Data ---
        $recent_users = $conn->query("SELECT full_name as name, email, created_at FROM users ORDER BY created_at DESC LIMIT 5");
        
        $recent_bookings = $conn->query("SELECT b.booking_id, u.full_name, b.total_amount, b.status, b.created_at 
                                        FROM bookings b JOIN users u ON b.user_id = u.id 
                                        ORDER BY b.created_at DESC LIMIT 5");

    } catch (Exception $e) {
        die("Query Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <link rel="stylesheet" href="../css/style.css">

    <style>
        :root {
            --primary: #6f42c1; /* Purple theme */
            --accent: #e83e8c;
            --bg-light: #f8f9fc;
        }
        body { background-color: var(--bg-light); font-family: 'Inter', sans-serif; }
        .welcome-card {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white; border-radius: 15px; border: none;
        }
        .card-stats {
            border: none; border-radius: 12px; transition: 0.3s;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }
        .card-stats:hover { transform: translateY(-3px); }
        .icon-box {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: white;
        }
        .bg-purple { background: #6f42c1; }
        .bg-pink { background: #e83e8c; }
        .bg-orange { background: #fd7e14; }
        .bg-teal { background: #20c997; }
        .chart-card { border-radius: 15px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container-fluid mt-4 px-4">
    <div class="card welcome-card mb-4 shadow-sm">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="font-weight-bold text-white">Beautify Service Management Portal</h2>
                    <p class="mb-0">Hello <strong><?php echo $_SESSION["username"]; ?></strong>, here is what's happening with your business today.</p>
                </div>
                <div class="col-md-4 text-right d-none d-md-block">
                    <i class="fas fa-chart-line fa-4x opacity-2"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-stats h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Revenue</div>
                        <div class="h4 mb-0 font-weight-bold">Rs. <?php echo number_format($total_revenue); ?></div>
                    </div>
                    <div class="icon-box bg-teal"><i class="fas fa-dollar-sign"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-stats h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Pending Bookings</div>
                        <div class="h4 mb-0 font-weight-bold text-warning"><?php echo $pending_bookings; ?></div>
                    </div>
                    <div class="icon-box bg-orange"><i class="fas fa-clock"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-stats h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Users</div>
                        <div class="h4 mb-0 font-weight-bold"><?php echo $total_users; ?></div>
                    </div>
                    <div class="icon-box bg-purple"><i class="fas fa-users"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-stats h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Services</div>
                        <div class="h4 mb-0 font-weight-bold"><?php echo $total_services; ?></div>
                    </div>
                    <div class="icon-box bg-pink"><i class="fas fa-magic"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-4 mb-4">
            <div class="card chart-card h-100">
                <div class="card-header bg-white font-weight-bold">Services per Category</div>
                <div class="card-body"><canvas id="categoryChart"></canvas></div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card chart-card h-100">
                <div class="card-header bg-white font-weight-bold">Booking Status</div>
                <div class="card-body"><canvas id="statusChart"></canvas></div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card chart-card h-100">
                <div class="card-header bg-white font-weight-bold">7-Day Booking Trend</div>
                <div class="card-body"><canvas id="trendChart"></canvas></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Bookings</h6>
                    <a href="admin_bookings.php" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead class="bg-light">
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $recent_bookings->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $row['booking_id']; ?></td>
                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <td>Rs. <?php echo number_format($row['total_amount']); ?></td>
                                <td>
                                    <span class="badge badge-<?php 
                                        echo match($row['status']) {
                                            'pending' => 'warning',
                                            'confirmed' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                            default => 'secondary'
                                        };
                                    ?> px-2 py-1"><?php echo ucfirst($row['status']); ?></span>
                                </td>
                                <td><?php echo date('M d', strtotime($row['created_at'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white font-weight-bold">New Customers</div>
                <div class="list-group list-group-flush">
                    <?php while($user = $recent_users->fetch_assoc()): ?>
                        <div class="list-group-item d-flex align-items-center">
                            <div class="rounded-circle bg-light p-2 mr-3 text-primary"><i class="fas fa-user"></i></div>
                            <div>
                                <div class="small font-weight-bold"><?php echo htmlspecialchars($user['name']); ?></div>
                                <div class="text-xs text-muted"><?php echo $user['email']; ?></div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Data from PHP
    const catLabels = <?php echo json_encode($category_labels); ?>;
    const catData = <?php echo json_encode($category_data); ?>;
    const statLabels = <?php echo json_encode($status_labels); ?>;
    const statData = <?php echo json_encode($status_data); ?>;
    const trendLabels = <?php echo json_encode($booking_trend_labels); ?>;
    const trendData = <?php echo json_encode($booking_trend_data); ?>;

    // 1. Category Chart
    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: catLabels,
            datasets: [{ data: catData, backgroundColor: ['#6f42c1', '#e83e8c', '#fd7e14', '#20c997', '#ffc107'] }]
        },
        options: { plugins: { legend: { position: 'bottom' } }, maintainAspectRatio: false }
    });

    // 2. Status Chart
    new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: statLabels,
            datasets: [{ data: statData, backgroundColor: ['#ffc107', '#17a2b8', '#28a745', '#dc3545', '#6c757d'] }]
        },
        options: { plugins: { legend: { position: 'bottom' } }, maintainAspectRatio: false }
    });

    // 3. Trend Chart
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [{
                label: 'Bookings',
                data: trendData,
                borderColor: '#6f42c1',
                tension: 0.3,
                fill: true,
                backgroundColor: 'rgba(111, 66, 193, 0.1)'
            }]
        },
        options: { 
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
            maintainAspectRatio: false 
        }
    });
</script>
</body>
</html>