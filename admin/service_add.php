<?php
include('config.php');
session_start();

// -------------------------------------------
// Authorization check
// -------------------------------------------
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

// -------------------------------------------
// Fetch categories
// -------------------------------------------
$categories = mysqli_query($conn, "SELECT * FROM service_categories ORDER BY category_name ASC");

// -------------------------------------------
// Handle form submission
// -------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $category_id = intval($_POST['category_id']);
    $service_name = trim($_POST['service_name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $duration = intval($_POST['duration_minutes']);
    $service_type = $_POST['service_type'] ?? 'Both';
    $image = null;

    if (!empty($_FILES['image']['name'])) {
        $image = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $image);
    }

    mysqli_query($conn, "
        INSERT INTO services (category_id, service_name, description, price, duration_minutes, image_url, service_type)
        VALUES ($category_id, '$service_name', '$description', $price, $duration, '$image', '$service_type')
    ");

    header("Location: services_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Service</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">

    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-body">

            <h4 class="text-center mb-4">Add Service</h4>

            <form method="post" enctype="multipart/form-data">

                <!-- Category -->
                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Select Category</option>
                        <?php while($cat = mysqli_fetch_assoc($categories)): ?>
                            <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Service Name -->
                <div class="form-group">
                    <label>Service Name</label>
                    <input type="text" name="service_name" class="form-control" required>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>

                <!-- Price -->
                <div class="form-group">
                    <label>Price</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>

                <!-- Duration -->
                <div class="form-group">
                    <label>Duration (Minutes)</label>
                    <input type="number" name="duration_minutes" class="form-control" value="60">
                </div>

                <!-- Service Type -->
                <div class="form-group">
                    <label>Service Type</label>
                    <select name="service_type" class="form-control" required>
                        <option value="Men">Men</option>
                        <option value="Women">Women</option>
                        <option value="Both" selected>Both</option>
                    </select>
                </div>

                <!-- Image -->
                <div class="form-group">
                    <label>Image</label>
                    <input type="file" name="image" class="form-control-file">
                </div>

                <!-- Buttons -->
                <div class="text-center">
                    <button class="btn btn-success px-4">Save</button>
                    <a href="services_list.php" class="btn btn-secondary px-4">Back</a>
                </div>

            </form>

        </div>
    </div>

</div>

</body>
</html>
