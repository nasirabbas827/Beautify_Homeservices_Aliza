<?php
include('config.php');
session_start();

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM stories WHERE story_id = $id");
    header("Location: stories_list.php");
    exit;
}

$stories = mysqli_query($conn, "SELECT * FROM stories ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Stories</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">

    <div class=" mb-3">
        <h3>Stories</h3>
        <a href="story_add.php" class="btn btn-primary">Add Story</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Image</th>
                <th>Created</th>
                <th width="180">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($stories)): ?>
            <tr>
                <td><?= $row['story_id'] ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td>
                    <?php if ($row['image_url']): ?>
                        <img src="../images/<?= $row['image_url'] ?>" width="60">
                    <?php endif; ?>
                </td>
                <td><?= $row['created_at'] ?></td>
                <td>
                    <a href="story_edit.php?id=<?= $row['story_id'] ?>" class="btn btn-sm btn-info">Edit</a>
                    <a href="?delete=<?= $row['story_id'] ?>"
                       onclick="return confirm('Delete this story?')"
                       class="btn btn-sm btn-danger">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

</div>

</body>
</html>