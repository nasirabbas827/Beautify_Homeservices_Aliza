<?php
include('config.php');
session_start();

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $image = null;

    if (!empty($_FILES['image']['name'])) {
        $image = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../images/" . $image);
    }

    mysqli_query($conn, "
        INSERT INTO stories (title, content, image_url)
        VALUES ('$title', '$content', '$image')
    ");

    header("Location: stories_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Story</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <div class="card mx-auto shadow" style="max-width: 600px;">
        <div class="card-body">

            <h4 class="text-center mb-4">Add Story</h4>

            <form method="post" enctype="multipart/form-data">

                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Content</label>
                    <textarea name="content" class="form-control" rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label>Image</label>
                    <input type="file" name="image" class="form-control-file">
                </div>

                <div class="text-center">
                    <button class="btn btn-success">Save</button>
                    <a href="stories_list.php" class="btn btn-secondary">Back</a>
                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>