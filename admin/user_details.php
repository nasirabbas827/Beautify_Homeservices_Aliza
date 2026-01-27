<?php
include('config.php');
session_start();

// Only admin access
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Validate user ID
$user_id = intval($_GET['id'] ?? 0);
if ($user_id <= 0) {
    echo "<script>alert('Invalid User ID'); window.location='view_users.php';</script>";
    exit;
}

// Fetch user details
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($query);

if (!$user) {
    echo "<script>alert('User not found'); window.location='view_users.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">

    <div class="row mb-4">
        <div class="col">
            <h3>User Details</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">

            <div class="card shadow">
                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Full Name</div>
                        <div class="col-md-8"><?= htmlspecialchars($user['full_name']) ?></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Date of Birth</div>
                        <div class="col-md-8"><?= $user['dob'] ?></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">CNIC</div>
                        <div class="col-md-8"><?= htmlspecialchars($user['cnic']) ?></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Phone</div>
                        <div class="col-md-8"><?= htmlspecialchars($user['phone']) ?></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Email</div>
                        <div class="col-md-8"><?= htmlspecialchars($user['email']) ?></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Username</div>
                        <div class="col-md-8"><?= htmlspecialchars($user['username']) ?></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Address</div>
                        <div class="col-md-8"><?= nl2br(htmlspecialchars($user['address'])) ?></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Created At</div>
                        <div class="col-md-8"><?= $user['created_at'] ?></div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 font-weight-bold">Last Updated</div>
                        <div class="col-md-8"><?= $user['updated_at'] ?></div>
                    </div>

                    <div class="text-center">
                        <a href="view_users.php" class="btn btn-secondary px-4">Back</a>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>
