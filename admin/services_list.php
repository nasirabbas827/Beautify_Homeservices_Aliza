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
// Delete service
// -------------------------------------------
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM services WHERE service_id = $id");
    header("Location: services_list.php");
    exit;
}

// -------------------------------------------
// Fetch services with category name
// -------------------------------------------
$services = mysqli_query($conn, "
    SELECT s.*, c.category_name 
    FROM services s
    JOIN service_categories c ON s.category_id = c.category_id
    ORDER BY s.created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Services Management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <div class="row mb-3">
        <div class="col">
            <h3>Services</h3>
        </div>
        <div class="col text-right">
            <a href="service_add.php" class="btn btn-primary">Add Service</a>
        </div>
    </div>

    <table class="table table-bordered table-striped table-responsive-sm">
        <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>Category</th>
            <th>Service Name</th>
            <th>Service Type</th>
            <th>Price</th>
            <th>Duration</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($services)): ?>
            <tr>
                <td><?= $row['service_id'] ?></td>
                <td><?= htmlspecialchars($row['category_name']) ?></td>
                <td><?= htmlspecialchars($row['service_name']) ?></td>
                <td><?= $row['service_type'] ?></td>
                <td><?= number_format($row['price'],2) ?></td>
                <td><?= $row['duration_minutes'] ?> min</td>
                <td>
                    <?php if ($row['image_url']): ?>
                        <img src="../images/<?= $row['image_url'] ?>" width="60">
                    <?php endif; ?>
                </td>
                <td>
                    <a href="service_edit.php?id=<?= $row['service_id'] ?>" class="btn btn-sm btn-info m-1">Edit</a>
                    <a href="?delete=<?= $row['service_id'] ?>" onclick="return confirm('Delete this service?')" class="btn btn-sm btn-danger m-1">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
