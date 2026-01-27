<?php
session_start();
include('config.php');

// Auth guard (optional — remove if categories page should be public)
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Search query
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// Fetch categories with search
$sql = "SELECT * FROM service_categories";
$params = [];
$types = "";

if ($search !== "") {
    $sql .= " WHERE category_name LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}

$sql .= " ORDER BY category_name ASC";

$stmt = mysqli_prepare($conn, $sql);
if ($types !== "") {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Categories | Beautify</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        :root {
            --accent-gold: #c5a059;
            --soft-bg: #f9f9f9;
            --dark-text: #2c2c2c;
        }
        body { background: var(--soft-bg); font-family: 'Segoe UI', sans-serif; }
        .categories-header {
            background: linear-gradient(135deg, #fffcf5, #f9f5eb);
            padding: 60px 0 40px;
            text-align: center;
            margin-bottom: 40px;
        }
        .category-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            background: white;
        }
        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        }
        .cat-img {
            height: 220px;
            object-fit: cover;
            width: 100%;
        }
        .btn-explore {
            background: var(--accent-gold);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 28px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-explore:hover {
            background: #b38c47;
        }
        .no-categories {
            padding: 60px 20px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            text-align: center;
        }
        .search-group .form-control {
            border-radius: 10px 0 0 10px;
        }
        .search-group .btn {
            border-radius: 0 10px 10px 0;
        }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="categories-header">
    <div class="container">
        <h2 class="font-weight-bold mb-2">Beauty Service Categories</h2>
        <p class="lead text-muted">Choose from professional at-home beauty treatments</p>
    </div>
</div>

<div class="container pb-5">

    <!-- Search -->
    <div class="card shadow-sm mb-5">
        <div class="card-body">
            <form method="get" class="input-group search-group">
                <input type="text" name="search" class="form-control" 
                       placeholder="Search categories (e.g. Makeup, Nails...)" 
                       value="<?= htmlspecialchars($search) ?>">
                <div class="input-group-append">
                    <button class="btn btn-primary px-4" type="submit">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories Grid -->
    <?php if (count($categories) > 0): ?>
        <div class="row">
            <?php foreach ($categories as $cat): ?>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="category-card h-100">
                        <?php if (!empty($cat['image_url'])): ?>
                            <img src="../images/<?= htmlspecialchars($cat['image_url']) ?>" 
                                 class="cat-img" 
                                 alt="<?= htmlspecialchars($cat['category_name']) ?>">
                        <?php else: ?>
                            <img src="../images/default_service.png" 
                                 class="cat-img" 
                                 alt="Category">
                        <?php endif; ?>

                        <div class="card-body text-center">
                            <h5 class="card-title font-weight-bold mb-3">
                                <?= htmlspecialchars($cat['category_name']) ?>
                            </h5>

                            <?php if (!empty($cat['description'])): ?>
                                <p class="text-muted small mb-4" style="min-height: 60px;">
                                    <?= htmlspecialchars(substr($cat['description'], 0, 110)) ?>...
                                </p>
                            <?php endif; ?>

                            <a href="services.php?category_id=<?= $cat['category_id'] ?>" 
                               class="btn btn-explore">Explore Services</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-categories mx-auto" style="max-width: 600px;">
            <i class="fas fa-search fa-4x text-muted mb-4"></i>
            <h5>No categories found</h5>
            <p class="text-muted mb-4">
                No matching categories for "<?= htmlspecialchars($search) ?>".<br>
                Try a different keyword or view all categories.
            </p>
            <a href="categories.php" class="btn btn-outline-dark px-4">Clear Search</a>
        </div>
    <?php endif; ?>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>