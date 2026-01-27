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

// Handle booking update (status, date, start_time)
if (isset($_POST['update_booking'])) {
    $booking_id = intval($_POST['booking_id']);
    $new_status = $_POST['status'];
    $new_date = $_POST['booking_date'];
    $new_start_time = $_POST['start_time'];

    // Fetch service duration to calculate end_time
    $sql = "SELECT s.duration_minutes 
            FROM booking_services bs 
            JOIN services s ON bs.service_id = s.service_id
            WHERE bs.booking_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $booking_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $duration_minutes);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    $end_time = date("H:i:s", strtotime("$new_start_time +$duration_minutes minutes"));

    $allowed_status = ['pending','confirmed','completed','cancelled','no_show'];
    if (in_array($new_status, $allowed_status)) {
        $stmt = mysqli_prepare($conn, "UPDATE bookings SET status=?, booking_date=?, start_time=?, end_time=? WHERE booking_id=?");
        mysqli_stmt_bind_param($stmt, "ssssi", $new_status, $new_date, $new_start_time, $end_time, $booking_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $success_msg = "Booking updated successfully.";
    }
}

// Fetch all bookings with services and user info
$sql = "SELECT 
            b.booking_id, b.booking_date, b.start_time, b.end_time, b.total_amount, b.status, 
            b.service_address, b.contact_number, b.payment_method, b.created_at,
            u.full_name AS user_name, u.email AS user_email, u.phone AS user_phone,
            s.service_name, s.duration_minutes, bs.price_at_booking
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN booking_services bs ON b.booking_id = bs.booking_id
        JOIN services s ON bs.service_id = s.service_id
        ORDER BY b.created_at DESC";

$result = mysqli_query($conn, $sql);

$bookings = [];
while ($row = mysqli_fetch_assoc($result)) {
    $bid = $row['booking_id'];
    if (!isset($bookings[$bid])) {
        $bookings[$bid] = [
            'booking_id' => $bid,
            'booking_date' => $row['booking_date'],
            'start_time' => $row['start_time'],
            'end_time' => $row['end_time'],
            'total_amount' => $row['total_amount'],
            'status' => $row['status'],
            'service_address' => $row['service_address'],
            'contact_number' => $row['contact_number'],
            'payment_method' => $row['payment_method'],
            'created_at' => $row['created_at'],
            'user_name' => $row['user_name'],
            'user_email' => $row['user_email'],
            'user_phone' => $row['user_phone'],
            'services' => []
        ];
    }
    $bookings[$bid]['services'][] = [
        'name' => $row['service_name'], 
        'price' => $row['price_at_booking'],
        'duration_minutes' => $row['duration_minutes']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Bookings</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>
<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2 class="mb-4">All Bookings</h2>

    <?php if (!empty($success_msg)): ?>
        <div class="alert alert-success"><?= $success_msg ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-sm table-striped">
            <thead class="thead-dark">
            <tr>
                <th>Booking ID</th>
                <th>User</th>
                <th>Date & Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($bookings as $b): ?>
                <tr>
                    <td><?= $b['booking_id'] ?></td>
                    <td><?= htmlspecialchars($b['user_name']) ?><br><small><?= htmlspecialchars($b['user_email']) ?></small></td>
                    <td><?= $b['booking_date'] ?> <?= $b['start_time'] ?> - <?= $b['end_time'] ?></td>
                    <td><?= ucfirst($b['status']) ?></td>
                    <td>
                        <!-- Modal Trigger -->
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#bookingModal<?= $b['booking_id'] ?>">Edit</button>
                    </td>
                </tr>

                <!-- Modal -->
                <div class="modal fade" id="bookingModal<?= $b['booking_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel<?= $b['booking_id'] ?>" aria-hidden="true">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Booking #<?= $b['booking_id'] ?> Details</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                      </div>
                      <div class="modal-body">
                          <p><strong>User:</strong> <?= htmlspecialchars($b['user_name']) ?> (<?= htmlspecialchars($b['user_email']) ?>)</p>
                          <p><strong>Contact:</strong> <?= htmlspecialchars($b['contact_number']) ?></p>
                          <p><strong>Service Address:</strong> <?= htmlspecialchars($b['service_address']) ?></p>
                          <p><strong>Payment Method:</strong> <?= ucfirst(str_replace('_',' ',$b['payment_method'])) ?></p>
                          <p><strong>Services:</strong></p>
                          <ul>
                            <?php foreach ($b['services'] as $s): ?>
                                <li><?= htmlspecialchars($s['name']) ?> - Rs <?= number_format($s['price'],2) ?> (<?= $s['duration_minutes'] ?> min)</li>
                            <?php endforeach; ?>
                          </ul>
                          <p><strong>Total Amount:</strong> Rs <?= number_format($b['total_amount'],2) ?></p>

                          <!-- Update Form -->
                          <form method="post" class="mt-2">
                              <input type="hidden" name="booking_id" value="<?= $b['booking_id'] ?>">
                              <div class="form-row">
                                  <div class="form-group col-md-4">
                                      <label>Booking Date</label>
                                      <input type="date" name="booking_date" class="form-control form-control-sm" value="<?= $b['booking_date'] ?>">
                                  </div>
                                  <div class="form-group col-md-4">
                                      <label>Start Time</label>
                                      <input type="time" name="start_time" class="form-control form-control-sm" value="<?= $b['start_time'] ?>">
                                  </div>
                                  <div class="form-group col-md-4">
                                      <label>Status</label>
                                      <select name="status" class="form-control form-control-sm">
                                          <?php 
                                          $statuses = ['pending','confirmed','completed','cancelled','no_show'];
                                          foreach ($statuses as $status) {
                                              $selected = ($status==$b['status']) ? 'selected' : '';
                                              echo "<option value='$status' $selected>".ucfirst($status)."</option>";
                                          }
                                          ?>
                                      </select>
                                  </div>
                              </div>
                              <button type="submit" name="update_booking" class="btn btn-primary btn-sm">Update Booking</button>
                          </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
