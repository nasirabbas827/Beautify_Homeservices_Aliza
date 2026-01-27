<?php
include('config.php');
session_start();

$username = $password = "";
$username_err = $password_err = "";
$login_msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // ==========================
    // Username Validation
    // ==========================
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // ==========================
    // Password Validation
    // ==========================
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // ==========================
    // Authentication
    // ==========================
    if (empty($username_err) && empty($password_err)) {

        $sql = "SELECT id, full_name, username, email, password_hash 
                FROM users 
                WHERE username = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {

            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) === 1) {

                mysqli_stmt_bind_result(
                    $stmt,
                    $id,
                    $full_name,
                    $username_db,
                    $email_db,
                    $password_hash
                );

                mysqli_stmt_fetch($stmt);

                if (password_verify($password, $password_hash)) {

                    // ==========================
                    // Session Setup
                    // ==========================
                    $_SESSION['user_id']   = $id;
                    $_SESSION['username']  = $username_db;
                    $_SESSION['full_name'] = $full_name;
                    $_SESSION['email']     = $email_db;

                    header("Location: user/dashboard.php");
                    exit();

                } else {
                    $password_err = "Invalid password.";
                }

            } else {
                $username_err = "Invalid username.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<?php include('navbar.php'); ?>

<div class="container mt-5">
    <div class="card mx-auto shadow-sm" style="max-width: 450px;">
        <div class="card-body">

            <h3 class="text-center mb-4">User Login</h3>

            <?php if (!empty($login_msg)): ?>
                <div class="alert alert-danger text-center">
                    <?= $login_msg ?>
                </div>
            <?php endif; ?>

            <form method="post" autocomplete="off">

                <!-- Username -->
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Username</label>
                        <input type="text"
                               name="username"
                               class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo htmlspecialchars($username); ?>"
                               placeholder="Enter Username">
                        <span class="invalid-feedback"><?php echo $username_err; ?></span>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Password</label>
                        <input type="password"
                               name="password"
                               class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"
                               placeholder="Enter Password">
                        <span class="invalid-feedback"><?php echo $password_err; ?></span>
                    </div>
                </div>

                <!-- Submit -->
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary px-4">
                        Login
                    </button>
                </div>

            </form>

            <p class="text-center mt-3">
                Don’t have an account?
                <a href="register.php">Register here</a>
            </p>

        </div>
    </div>
</div>

</body>
</html>
