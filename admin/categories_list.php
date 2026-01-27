<?php
include('config.php');
session_start();

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

// Delete category
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM service_categories WHERE category_id = $id");
    header("Location: categories_list.php");
    exit;
}

$categories = mysqli_query($conn, "SELECT * FROM service_categories ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Service Categories</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <div class="row mb-3">
        <div class="col">
            <h3>Service Categories</h3>
        </div>
        <div class="col text-right">
            <a href="category_add.php" class="btn btn-primary">Add Category</a>
        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Image</th>
                <th>Created</th>
                <th width="180">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($categories)): ?>
            <tr>
                <td><?= $row['category_id'] ?></td>
                <td><?= htmlspecialchars($row['category_name']) ?></td>
                <td>
                    <?php if ($row['image_url']): ?>
                        <img src="../images/<?= $row['image_url'] ?>" width="60">
                    <?php endif; ?>
                </td>
                <td><?= $row['created_at'] ?></td>
                <td>
                    <a href="category_edit.php?id=<?= $row['category_id'] ?>" class="btn btn-sm btn-info">Edit</a>
                    <a href="?delete=<?= $row['category_id'] ?>"
                       onclick="return confirm('Delete this category?')"
                       class="btn btn-sm btn-danger">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
