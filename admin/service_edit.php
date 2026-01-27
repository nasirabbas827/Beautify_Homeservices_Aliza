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
// Fetch service by ID
// -------------------------------------------
$id = intval($_GET['id'] ?? 0);
$service = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM services WHERE service_id = $id"));
if (!$service) {
    header("Location: services_list.php");
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
    $image = $service['image_url'];

    if (!empty($_FILES['image']['name'])) {
        $image = time().'_'.$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $image);
    }

    mysqli_query($conn, "
        UPDATE services 
        SET category_id=$category_id, service_name='$service_name', description='$description',
            price=$price, duration_minutes=$duration, image_url='$image', service_type='$service_type'
        WHERE service_id=$id
    ");

    header("Location: services_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Service</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">

    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-body">

            <h4 class="text-center mb-4">Edit Service</h4>

            <form method="post" enctype="multipart/form-data">

                <!-- Category -->
                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Select Category</option>
                        <?php while($cat = mysqli_fetch_assoc($categories)): ?>
                            <option value="<?= $cat['category_id'] ?>" <?= ($service['category_id']==$cat['category_id'])?'selected':'' ?>>
                                <?= htmlspecialchars($cat['category_name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Service Name -->
                <div class="form-group">
                    <label>Service Name</label>
                    <input type="text" name="service_name" class="form-control" value="<?= htmlspecialchars($service['service_name']) ?>" required>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control"><?= htmlspecialchars($service['description']) ?></textarea>
                </div>

                <!-- Price -->
                <div class="form-group">
                    <label>Price</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="<?= $service['price'] ?>" required>
                </div>

                <!-- Duration -->
                <div class="form-group">
                    <label>Duration (Minutes)</label>
                    <input type="number" name="duration_minutes" class="form-control" value="<?= $service['duration_minutes'] ?>">
                </div>

                <!-- Service Type -->
                <div class="form-group">
                    <label>Service Type</label>
                    <select name="service_type" class="form-control" required>
                        <option value="Men" <?= ($service['service_type']=='Men')?'selected':'' ?>>Men</option>
                        <option value="Women" <?= ($service['service_type']=='Women')?'selected':'' ?>>Women</option>
                        <option value="Both" <?= ($service['service_type']=='Both')?'selected':'' ?>>Both</option>
                    </select>
                </div>

                <!-- Current Image -->
                <div class="form-group">
                    <label>Current Image</label><br>
                    <?php if ($service['image_url']): ?>
                        <img src="../images/<?= $service['image_url'] ?>" width="80">
                    <?php endif; ?>
                </div>

                <!-- Change Image -->
                <div class="form-group">
                    <label>Change Image</label>
                    <input type="file" name="image" class="form-control-file">
                </div>

                <!-- Buttons -->
                <div class="text-center">
                    <button class="btn btn-primary px-4">Update</button>
                    <a href="services_list.php" class="btn btn-secondary px-4">Back</a>
                </div>

            </form>

        </div>
    </div>

</div>

</body>
</html>
