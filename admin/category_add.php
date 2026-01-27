<?php
include('config.php');
session_start();

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['category_name']);
    $desc = trim($_POST['description']);
    $image = null;

    if (!empty($_FILES['image']['name'])) {
        $image = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../images/" . $image);
    }

    mysqli_query($conn, "
        INSERT INTO service_categories (category_name, description, image_url)
        VALUES ('$name', '$desc', '$image')
    ");

    header("Location: categories_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">

    <div class="card mx-auto shadow" style="max-width: 500px;">
        <div class="card-body">

            <h4 class="text-center mb-4">Add Service Category</h4>

            <form method="post" enctype="multipart/form-data">

                <div class="form-group">
                    <label>Category Name</label>
                    <input type="text" name="category_name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <label>Image</label>
                    <input type="file" name="image" class="form-control-file">
                </div>

                <div class="text-center">
                    <button class="btn btn-success px-4">Save</button>
                    <a href="categories_list.php" class="btn btn-secondary px-4">Back</a>
                </div>

            </form>

        </div>
    </div>

</div>

</body>
</html>
