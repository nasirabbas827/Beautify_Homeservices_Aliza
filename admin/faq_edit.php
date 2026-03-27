<?php
include('config.php');
session_start();

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);
$faq = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM faqs WHERE faq_id = $id"));

if (!$faq) {
    header("Location: faqs_list.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);

    mysqli_query($conn, "
        UPDATE faqs
        SET question='$question', answer='$answer'
        WHERE faq_id=$id
    ");

    header("Location: faqs_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit FAQ</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <div class="card mx-auto shadow" style="max-width: 600px;">
        <div class="card-body">

            <h4 class="text-center mb-4">Edit FAQ</h4>

            <form method="post">

                <div class="form-group">
                    <label>Question</label>
                    <input type="text" name="question" class="form-control" value="<?= htmlspecialchars($faq['question']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Answer</label>
                    <textarea name="answer" class="form-control" rows="4" required><?= htmlspecialchars($faq['answer']) ?></textarea>
                </div>

                <div class="text-center">
                    <button class="btn btn-primary">Update</button>
                    <a href="faqs_list.php" class="btn btn-secondary">Back</a>
                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>