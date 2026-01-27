<?php
include('config.php');

$full_name = $dob = $cnic = $phone = $email = $username = $address = "";
$password = $confirm_password = "";

$errors = [];
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Full Name
    if (empty(trim($_POST['full_name']))) {
        $errors['full_name'] = "Full name is required.";
    } else {
        $full_name = trim($_POST['full_name']);
    }

    // DOB
    if (empty($_POST['dob'])) {
        $errors['dob'] = "Date of birth is required.";
    } else {
        $dob = $_POST['dob'];
    }

    // CNIC (Pakistani format: 12345-1234567-1)
    if (empty($_POST['cnic'])) {
        $errors['cnic'] = "CNIC is required.";
    } elseif (!preg_match('/^\d{5}-\d{7}-\d{1}$/', $_POST['cnic'])) {
        $errors['cnic'] = "Invalid CNIC format (XXXXX-XXXXXXX-X).";
    } else {
        $cnic = $_POST['cnic'];
    }

    // Phone (Pakistan: 03XXXXXXXXX)
    if (empty($_POST['phone'])) {
        $errors['phone'] = "Contact number is required.";
    } elseif (!preg_match('/^03\d{9}$/', $_POST['phone'])) {
        $errors['phone'] = "Invalid Pakistani phone number.";
    } else {
        $phone = $_POST['phone'];
    }

    // Email
    if (empty($_POST['email'])) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    } else {
        $email = trim($_POST['email']);
    }

    // Username
    if (empty($_POST['username'])) {
        $errors['username'] = "Username is required.";
    } else {
        $username = trim($_POST['username']);
    }

    // Address
    if (empty($_POST['address'])) {
        $errors['address'] = "Complete address is required.";
    } else {
        $address = trim($_POST['address']);
    }

    // Password
    if (empty($_POST['password'])) {
        $errors['password'] = "Password is required.";
    } elseif (strlen($_POST['password']) < 6) {
        $errors['password'] = "Password must be at least 6 characters.";
    } else {
        $password = $_POST['password'];
    }

    // Confirm Password
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $errors['confirm_password'] = "Passwords do not match.";
    }

    // Check duplicates
    if (empty($errors)) {
        $check = $conn->prepare("SELECT id FROM users WHERE email=? OR username=? OR cnic=?");
        $check->bind_param("sss", $email, $username, $cnic);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $errors['duplicate'] = "Email, Username, or CNIC already exists.";
        }
        $check->close();
    }

    // Insert
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            INSERT INTO users 
            (full_name, dob, cnic, phone, email, username, password_hash, address)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssssssss",
            $full_name,
            $dob,
            $cnic,
            $phone,
            $email,
            $username,
            $hashed_password,
            $address
        );

        if ($stmt->execute()) {
            $success_msg = "Registration successful.";
            $_POST = [];
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<?php include('navbar.php'); ?>

<div class="container mt-5">
    <div class="card mx-auto shadow" style="max-width: 850px;">
        <div class="card-body">

            <h3 class="text-center mb-4">User Registration</h3>

            <?php if (!empty($success_msg)): ?>
                <div class="alert alert-success text-center"><?= $success_msg ?></div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $e) echo "<div>$e</div>"; ?>
                </div>
            <?php endif; ?>

            <form method="post">

                <!-- Row 1 -->
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Full Name</label>
                        <input type="text" name="full_name" class="form-control"
                               value="<?= $full_name ?>" placeholder="Full Name">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" class="form-control">
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>CNIC</label>
                        <input type="text" name="cnic" class="form-control"
                               placeholder="12345-1234567-1">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Contact Number</label>
                        <input type="text" name="phone" class="form-control"
                               placeholder="03XXXXXXXXX">
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control"
                               placeholder="example@email.com">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control"
                               placeholder="Suggested Username">
                    </div>
                </div>

                <!-- Row 4 -->
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Complete Address</label>
                        <textarea name="address" class="form-control"
                                  rows="2" placeholder="Complete Address"></textarea>
                    </div>
                </div>

                <!-- Row 5 -->
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control"
                               placeholder="Password">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Re-enter Password</label>
                        <input type="password" name="confirm_password" class="form-control"
                               placeholder="Re-enter Password">
                    </div>
                </div>

                <!-- Submit -->
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary px-5">
                        Register
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>
