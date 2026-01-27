<?php
session_start();
include('config.php');

// Timezone
date_default_timezone_set('Asia/Karachi');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_msg = "";
$error_msg = "";

// Handle Cancel Booking (within 5 minutes)
if (isset($_GET['cancel_booking'])) {
    $booking_id = intval($_GET['cancel_booking']);

    $sql = "SELECT created_at, status, cancellation_allowed_until 
            FROM bookings 
            WHERE booking_id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $booking_id, $user_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $booking = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if ($booking) {
        if ($booking['status'] !== 'pending') {
            $error_msg = "This booking cannot be cancelled anymore.";
        } elseif (strtotime($booking['cancellation_allowed_until']) < time()) {
            $error_msg = "Cancellation window has expired (5 minutes after booking).";
        } else {
            $update_sql = "UPDATE bookings SET status = 'cancelled', cancelled_at = NOW() WHERE booking_id = ?";
            $stmt_up = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param($stmt_up, "i", $booking_id);
            if (mysqli_stmt_execute($stmt_up)) {
                $success_msg = "Booking cancelled successfully.";
            } else {
                $error_msg = "Failed to cancel booking. Try again.";
            }
            mysqli_stmt_close($stmt_up);
        }
    } else {
        $error_msg = "Booking not found.";
    }
}

// Fetch User Profile
$sql_user = "SELECT full_name, email, phone FROM users WHERE id = ?";
$stmt_user = mysqli_prepare($conn, $sql_user);
mysqli_stmt_bind_param($stmt_user, "i", $user_id);
mysqli_stmt_execute($stmt_user);
$user = mysqli_stmt_get_result($stmt_user)->fetch_assoc();
mysqli_stmt_close($stmt_user);

// Fetch User Bookings
$bookings_sql = "SELECT b.*, 
                        GROUP_CONCAT(s.service_name SEPARATOR ', ') as service_names, 
                        GROUP_CONCAT(s.service_type SEPARATOR ', ') as service_types, 
                        MAX(s.image_url) as main_image 
                 FROM bookings b 
                 JOIN booking_services bs ON b.booking_id = bs.booking_id 
                 JOIN services s ON bs.service_id = s.service_id 
                 WHERE b.user_id = ? 
                 GROUP BY b.booking_id 
                 ORDER BY b.created_at DESC";
$stmt_book = mysqli_prepare($conn, $bookings_sql);
mysqli_stmt_bind_param($stmt_book, "i", $user_id);
mysqli_stmt_execute($stmt_book);
$bookings_res = mysqli_stmt_get_result($stmt_book);

// Fetch Services (with search)
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';
$services_sql = "SELECT * FROM services";
if ($search !== '') {
    $services_sql .= " WHERE service_name LIKE '%$search%'";
}
$services_sql .= " ORDER BY service_name ASC";
$services_res = mysqli_query($conn, $services_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard | Beautify</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        :root {
            --accent-gold: #c5a059;
            --soft-bg: #f9f9f9;
            --dark-text: #2c2c2c;
        }
        body { background: var(--soft-bg); font-family: 'Segoe UI', sans-serif; }
        .dashboard-header {
            background: linear-gradient(135deg, #fffcf5, #f9f5eb);
            padding: 60px 0 40px;
            text-align: center;
            margin-bottom: 40px;
        }
        .profile-avatar {
            width: 90px;
            height: 90px;
            background: var(--accent-gold);
            color: white;
            font-size: 2.5rem;
            font-weight: bold;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }
        .booking-card {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }
        .service-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            background: white;
        }
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        }
        .service-img {
            height: 220px;
            object-fit: cover;
        }
        .btn-book {
            background: var(--accent-gold);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 28px;
            font-weight: 600;
        }
        .btn-book:hover { background: #b38c47; }
        .status-badge {
            font-size: 0.9rem;
            padding: 6px 14px;
        }
        .countdown { font-weight: bold; color: #dc3545; }
        .no-data {
            padding: 60px 20px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            text-align: center;
        }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="dashboard-header">
    <div class="container">
        <h2 class="font-weight-bold mb-1">Welcome Back</h2>
        <p class="lead text-muted">Manage your bookings & explore services</p>
    </div>
</div>

<div class="container pb-5">

    <!-- Messages -->
    <?php if ($success_msg): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $success_msg ?>
            <button type="button" class="close" data-dismiss="alert">×</button>
        </div>
    <?php endif; ?>
    <?php if ($error_msg): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error_msg ?>
            <button type="button" class="close" data-dismiss="alert">×</button>
        </div>
    <?php endif; ?>

    <!-- Profile Header -->
    <div class="card shadow-sm mb-5">
        <div class="card-body d-flex align-items-center p-4">
            <div class="profile-avatar mr-4">
                <?= strtoupper(substr($user['full_name'] ?? 'U', 0, 1)) ?>
            </div>
            <div>
                <h4 class="mb-1"><?= htmlspecialchars($user['full_name'] ?? 'User') ?></h4>
                <p class="text-muted mb-1">
                    <i class="fas fa-envelope mr-2"></i><?= htmlspecialchars($user['email'] ?? '—') ?>
                </p>
                <p class="text-muted mb-0">
                    <i class="fas fa-phone mr-2"></i><?= htmlspecialchars($user['phone'] ?? '—') ?>
                </p>
            </div>
        </div>
    </div>

    <!-- My Bookings -->
    <div class="card booking-card mb-5">
        <div class="card-header bg-white border-0">
            <h4 class="mb-0">My Bookings</h4>
        </div>
        <div class="card-body p-0">
            <?php if (mysqli_num_rows($bookings_res) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Service</th>
                                <th>Scheduled</th>
                                <th>Status</th>
                                <th>Cancellation</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($b = mysqli_fetch_assoc($bookings_res)):
                                $allowed_until = strtotime($b['cancellation_allowed_until'] ?? $b['created_at'] . ' +5 minutes');
                                $rem_seconds = max(0, $allowed_until - time());
                            ?>
                            <tr>
                                <td>
                                    <?php if (!empty($b['main_image'])): ?>
                                        <img src="../images/<?= htmlspecialchars($b['main_image']) ?>" class="mr-3" style="width:60px;height:60px;object-fit:cover;border-radius:8px;" alt="Service">
                                    <?php endif; ?>
                                    <strong><?= htmlspecialchars($b['service_names']) ?></strong><br>
                                    <small class="text-muted">Rs <?= number_format($b['total_amount']) ?></small>
                                </td>
                                <td>
                                    <?= date('d M Y', strtotime($b['booking_date'])) ?><br>
                                    <small><?= date('h:i A', strtotime($b['start_time'])) ?> – <?= date('h:i A', strtotime($b['end_time'])) ?></small>
                                </td>
                                <td>
                                    <?php
                                    $status_map = [
                                        'pending'    => 'warning',
                                        'confirmed'  => 'success',
                                        'completed'  => 'info',
                                        'cancelled'  => 'danger',
                                        'no_show'    => 'secondary'
                                    ];
                                    $badge = $status_map[$b['status']] ?? 'secondary';
                                    ?>
                                    <span class="badge badge-<?= $badge ?> status-badge"><?= ucfirst($b['status']) ?></span>
                                </td>
                                <td>
                                    <?php if ($rem_seconds > 0 && $b['status'] === 'pending'): ?>
                                        <span class="countdown" data-seconds="<?= $rem_seconds ?>">Calculating...</span>
                                    <?php else: ?>
                                        <span class="text-muted">Closed</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($rem_seconds > 0 && $b['status'] === 'pending'): ?>
                                        <a href="?cancel_booking=<?= $b['booking_id'] ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Are you sure you want to cancel this booking?')">
                                            Cancel
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-data">
                    <i class="far fa-calendar-alt fa-4x text-muted mb-3"></i>
                    <h5>No bookings yet</h5>
                    <p class="text-muted">Start exploring services and book your first appointment today!</p>
                    <a href="services.php" class="btn btn-outline-dark mt-3">Browse Services</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Explore Services -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Explore Services</h4>
        <form class="form-inline" method="GET">
            <input class="form-control mr-2" type="search" name="search" 
                   placeholder="Search services..." value="<?= htmlspecialchars($search) ?>" style="width: 220px;">
            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <?php if (mysqli_num_rows($services_res) > 0): ?>
        <div class="row">
            <?php while ($s = mysqli_fetch_assoc($services_res)): ?>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="service-card h-100">
                        <?php if (!empty($s['image_url'])): ?>
                            <img src="../images/<?= htmlspecialchars($s['image_url']) ?>" class="service-img" alt="<?= htmlspecialchars($s['service_name']) ?>">
                        <?php else: ?>
                            <img src="../images/default_service.png" class="service-img" alt="Service">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($s['service_name']) ?></h5>
                            <p class="text-muted small mb-3"><?= htmlspecialchars(substr($s['description'] ?? '', 0, 80)) ?>...</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0 text-success">Rs <?= number_format($s['price'], 0) ?></h5>
                                    <small class="text-muted"><?= $s['duration_minutes'] ?> min</small>
                                </div>
                                <a href="book_service.php?service_id=<?= $s['service_id'] ?>" class="btn btn-book">Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="no-data">
            <i class="fas fa-search fa-4x text-muted mb-3"></i>
            <h5>No services found</h5>
            <p class="text-muted">Try a different search term or browse all categories.</p>
            <a href="services.php" class="btn btn-outline-dark mt-3">Clear Search</a>
        </div>
    <?php endif; ?>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.countdown').forEach(el => {
        let seconds = parseInt(el.dataset.seconds);
        if (isNaN(seconds) || seconds <= 0) {
            el.textContent = 'Expired';
            return;
        }

        const update = () => {
            if (seconds <= 0) {
                el.textContent = 'Expired';
                return;
            }
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            el.textContent = `${m}m ${s < 10 ? '0' : ''}${s}s left`;
            seconds--;
            setTimeout(update, 1000);
        };
        update();
    });
</script>
</body>
</html>