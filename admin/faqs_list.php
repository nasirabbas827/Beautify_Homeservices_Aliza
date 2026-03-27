<?php
include('config.php');
session_start();

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

// Delete FAQ
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM faqs WHERE faq_id = $id");
    header("Location: faqs_list.php");
    exit;
}

$faqs = mysqli_query($conn, "SELECT * FROM faqs ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>FAQs</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">

    <div class=" mb-3">
        <h3>FAQs</h3>
        <a href="faq_add.php" class="btn btn-primary">Add FAQ</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Question</th>
                <th>Answer</th>
                <th>Created</th>
                <th width="180">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($faqs)): ?>
            <tr>
                <td><?= $row['faq_id'] ?></td>
                <td><?= htmlspecialchars($row['question']) ?></td>
                <td><?= htmlspecialchars($row['answer']) ?></td>
                <td><?= $row['created_at'] ?></td>
                <td>
                    <a href="faq_edit.php?id=<?= $row['faq_id'] ?>" class="btn btn-sm btn-info">Edit</a>
                    <a href="?delete=<?= $row['faq_id'] ?>"
                       onclick="return confirm('Delete this FAQ?')"
                       class="btn btn-sm btn-danger">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

</div>

</body>
</html>