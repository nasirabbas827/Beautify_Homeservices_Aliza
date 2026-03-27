<?php
include('config.php');
session_start();

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);

    mysqli_query($conn, "
        INSERT INTO faqs (question, answer)
        VALUES ('$question', '$answer')
    ");

    header("Location: faqs_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add FAQ</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <div class="card mx-auto shadow" style="max-width: 600px;">
        <div class="card-body">

            <h4 class="text-center mb-4">Add FAQ</h4>

            <form method="post">

                <div class="form-group">
                    <label>Question</label>
                    <input type="text" name="question" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Answer</label>
                    <textarea name="answer" class="form-control" rows="4" required></textarea>
                </div>

                <div class="text-center">
                    <button class="btn btn-success">Save</button>
                    <a href="faqs_list.php" class="btn btn-secondary">Back</a>
                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>