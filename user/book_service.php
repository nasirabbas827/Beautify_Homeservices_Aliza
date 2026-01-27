<?php
session_start();
include('config.php');

// Auth Guard
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Get service_id
$service_id = intval($_GET['service_id'] ?? 0);
if ($service_id <= 0) {
    echo "<script>alert('Invalid service selected.'); window.location='services.php';</script>";
    exit();
}

// Fetch service details
$sql = "SELECT s.*, c.category_name 
        FROM services s 
        JOIN service_categories c ON s.category_id = c.category_id 
        WHERE s.service_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $service_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$service = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$service) {
    echo "<script>alert('Service not found.'); window.location='services.php';</script>";
    exit();
}

// Handle form submission
$errors = [];
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_date   = trim($_POST['booking_date'] ?? '');
    $start_time     = trim($_POST['start_time'] ?? '');
    $service_address = trim($_POST['service_address'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');
    $payment_method = $_POST['payment_method'] ?? '';

    // Basic server-side validation
    if (empty($booking_date))   $errors[] = "Booking date is required.";
    if (empty($start_time))     $errors[] = "Start time is required.";
    if (empty($service_address)) $errors[] = "Service address is required.";
    if (empty($contact_number)) $errors[] = "Contact number is required.";
    if (empty($payment_method)) $errors[] = "Please select a payment method.";

    // Simple date/time check (can be enhanced later)
    $today = date('Y-m-d');
    if ($booking_date < $today) {
        $errors[] = "Booking date cannot be in the past.";
    }

    if (empty($errors)) {
        // Calculate end time
        $start = new DateTime("$booking_date $start_time");
        $start->modify("+{$service['duration_minutes']} minutes");
        $end_time = $start->format('H:i:s');

        // Insert booking
        $total_amount = $service['price'];
        $sql = "INSERT INTO bookings (user_id, booking_date, start_time, end_time, service_address, contact_number, payment_method, total_amount, status, created_at, cancellation_allowed_until)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW(), DATE_ADD(NOW(), INTERVAL 5 MINUTE))";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "issssssd", 
            $_SESSION['user_id'], $booking_date, $start_time, $end_time, 
            $service_address, $contact_number, $payment_method, $total_amount
        );

        if (mysqli_stmt_execute($stmt)) {
            $booking_id = mysqli_insert_id($conn);

            // Link service to booking
            $sql2 = "INSERT INTO booking_services (booking_id, service_id, quantity, price_at_booking) 
                     VALUES (?, ?, 1, ?)";
            $stmt2 = mysqli_prepare($conn, $sql2);
            mysqli_stmt_bind_param($stmt2, "iid", $booking_id, $service_id, $service['price']);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);

            $success_msg = "Booking confirmed! Your appointment is scheduled.";
            // Optional: redirect after 3 seconds
            // header("refresh:3;url=my_bookings.php");
        } else {
            $errors[] = "Failed to create booking. Please try again.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book <?= htmlspecialchars($service['service_name']) ?> | Beautify</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        :root {
            --accent-gold: #c5a059;
            --soft-gray: #f9f9f9;
            --dark-text: #2c2c2c;
        }
        body { background: var(--soft-gray); font-family: 'Segoe UI', sans-serif; }
        .booking-header {
            background: linear-gradient(135deg, #fffcf5, #f9f5eb);
            padding: 60px 0 40px;
            text-align: center;
        }
        .service-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .service-img {
            height: 370px;
            width: 500px;
            object-fit: cover;
        }
        .form-card {
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            background: white;
        }
        .btn-book {
            background: var(--accent-gold);
            color: white;
            border: none;
            padding: 14px 40px;
            font-size: 1.1rem;
            border-radius: 50px;
            transition: all 0.3s;
        }
        .btn-book:hover {
            background: #b38c47;
            transform: translateY(-2px);
        }
        .input-group-text { background: #f1f1f1; }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="booking-header">
    <div class="container">
        <h2 class="font-weight-bold mb-2">Book Your Service</h2>
        <p class="lead text-muted">You're about to enjoy professional beauty care at home</p>
    </div>
</div>

<div class="container mt-5 mb-5">

    <!-- Service Details -->
    <div class="service-card mb-5">
        <div class="row no-gutters">
            <div class="col-md-5">
                <?php if (!empty($service['image_url'])): ?>
                    <img src="../images/<?= htmlspecialchars($service['image_url']) ?>" class="service-img" alt="<?= htmlspecialchars($service['service_name']) ?>">
                <?php else: ?>
                    <img src="../images/default_service.png" class="service-img" alt="Service Image">
                <?php endif; ?>
            </div>
            <div class="col-md-7">
                <div class="card-body p-5">
                    <h3 class="card-title mb-3"><?= htmlspecialchars($service['service_name']) ?></h3>
                    <p class="text-muted mb-2"><strong>Category:</strong> <?= htmlspecialchars($service['category_name']) ?></p>
                    <p class="mb-2"><strong>Duration:</strong> <?= $service['duration_minutes'] ?> minutes</p>
                    <h5 class="text-success mb-3">Rs <?= number_format($service['price'], 0) ?></h5>
                    <p class="text-muted"><?= nl2br(htmlspecialchars($service['description'] ?? 'No description available.')) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Form -->
    <div class="form-card mx-auto p-5" style="max-width: 720px;">
        <h4 class="text-center mb-4">Appointment Details</h4>

        <?php if ($success_msg): ?>
            <div class="alert alert-success text-center">
                <strong>Success!</strong> <?= $success_msg ?><br>
                <small>You will be redirected to your bookings soon...</small>
            </div>
            <div class="text-center mt-4">
                <a href="my_bookings.php" class="btn btn-outline-dark">View My Bookings</a>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $err): ?>
                    <div><i class="fas fa-exclamation-circle mr-2"></i><?= $err ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!$success_msg): ?>
        <form method="POST" id="bookingForm">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label><i class="far fa-calendar-alt mr-2"></i>Booking Date</label>
                    <input type="date" name="booking_date" class="form-control" 
                           min="<?= date('Y-m-d') ?>" 
                           value="<?= htmlspecialchars($_POST['booking_date'] ?? '') ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label><i class="far fa-clock mr-2"></i>Start Time</label>
                    <input type="time" name="start_time" class="form-control" 
                           value="<?= htmlspecialchars($_POST['start_time'] ?? '') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-map-marker-alt mr-2"></i>Service Address</label>
                <textarea class="form-control" name="service_address" rows="3" 
                          placeholder="Full address with landmark" required><?= htmlspecialchars($_POST['service_address'] ?? '') ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label><i class="fas fa-phone mr-2"></i>Contact Number</label>
                    <input type="tel" name="contact_number" class="form-control" 
                           pattern="[0-9]{10,13}" placeholder="03XX XXXXXXX" 
                           value="<?= htmlspecialchars($_POST['contact_number'] ?? '') ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label><i class="fas fa-wallet mr-2"></i>Payment Method</label>
                    <select name="payment_method" class="form-control" required>
                        <option value="">Choose Payment Method</option>
                        <option value="cash_on_delivery" <?= ($_POST['payment_method'] ?? '') == 'cash_on_delivery' ? 'selected' : '' ?>>Cash on Delivery</option>
                        <option value="card" <?= ($_POST['payment_method'] ?? '') == 'card' ? 'selected' : '' ?>>Debit / Credit Card</option>
                        <option value="easypaisa" <?= ($_POST['payment_method'] ?? '') == 'easypaisa' ? 'selected' : '' ?>>EasyPaisa</option>
                        <option value="jazzcash" <?= ($_POST['payment_method'] ?? '') == 'jazzcash' ? 'selected' : '' ?>>JazzCash</option>
                        <option value="other" <?= ($_POST['payment_method'] ?? '') == 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
            </div>

            <div class="text-center mt-5">
                <button type="submit" class="btn btn-book btn-lg">Confirm Booking</button>
            </div>
        </form>
        <?php endif; ?>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>