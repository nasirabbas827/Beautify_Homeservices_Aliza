<?php
include('config.php');
session_start();

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);
$category = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM service_categories WHERE category_id = $id"));

if (!$category) {
    header("Location: categories_list.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['category_name']);
    $desc = trim($_POST['description']);
    $image = $category['image_url'];

    if (!empty($_FILES['image']['name'])) {
        $image = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../images/" . $image);
    }

    mysqli_query($conn, "
        UPDATE service_categories
        SET category_name='$name', description='$desc', image_url='$image'
        WHERE category_id=$id
    ");

    header("Location: categories_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Category</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">

    <div class="card mx-auto shadow" style="max-width: 500px;">
        <div class="card-body">

            <h4 class="text-center mb-4">Edit Service Category</h4>

            <form method="post" enctype="multipart/form-data">

                <div class="form-group">
                    <label>Category Name</label>
                    <input type="text" name="category_name" class="form-control"
                           value="<?= htmlspecialchars($category['category_name']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control"><?= htmlspecialchars($category['description']) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Current Image</label><br>
                    <?php if ($category['image_url']): ?>
                        <img src="../images/<?= $category['image_url'] ?>" width="80">
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Change Image</label>
                    <input type="file" name="image" class="form-control-file">
                </div>

                <div class="text-center">
                    <button class="btn btn-primary px-4">Update</button>
                    <a href="categories_list.php" class="btn btn-secondary px-4">Back</a>
                </div>

            </form>

        </div>
    </div>

</div>

</body>
</html>
