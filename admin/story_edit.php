<?php
include('config.php');
session_start();

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);
$story = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM stories WHERE story_id = $id"));

if (!$story) {
    header("Location: stories_list.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $image = $story['image_url'];

    if (!empty($_FILES['image']['name'])) {
        $image = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../images/" . $image);
    }

    mysqli_query($conn, "
        UPDATE stories
        SET title='$title', content='$content', image_url='$image'
        WHERE story_id=$id
    ");

    header("Location: stories_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Story</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <div class="card mx-auto shadow" style="max-width: 600px;">
        <div class="card-body">

            <h4 class="text-center mb-4">Edit Story</h4>

            <form method="post" enctype="multipart/form-data">

                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control"
                           value="<?= htmlspecialchars($story['title']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Content</label>
                    <textarea name="content" class="form-control" rows="4"><?= htmlspecialchars($story['content']) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Current Image</label><br>
                    <?php if ($story['image_url']): ?>
                        <img src="../images/<?= $story['image_url'] ?>" width="80">
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Change Image</label>
                    <input type="file" name="image" class="form-control-file">
                </div>

                <div class="text-center">
                    <button class="btn btn-primary">Update</button>
                    <a href="stories_list.php" class="btn btn-secondary">Back</a>
                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>