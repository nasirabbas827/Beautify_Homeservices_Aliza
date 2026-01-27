<?php
session_start();
include('config.php');

// Auth guard (optional - remove if this page should be public)
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Get category
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

// Search query
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// Service type filter
$service_type_filter = isset($_GET['service_type']) ? $_GET['service_type'] : "";

// Fetch category name
$category_name = "All Services";
if ($category_id > 0) {
    $stmt = mysqli_prepare($conn, "SELECT category_name FROM service_categories WHERE category_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $category_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $category_name);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

// Build dynamic query
$where = [];
$params = [];
$types = "";

// Category filter
if ($category_id > 0) {
    $where[] = "category_id = ?";
    $params[] = $category_id;
    $types .= "i";
}

// Search filter
if ($search !== "") {
    $where[] = "service_name LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}

// Service type filter
if ($service_type_filter && in_array($service_type_filter, ['Men', 'Women', 'Both'])) {
    $where[] = "service_type = ?";
    $params[] = $service_type_filter;
    $types .= "s";
}

// Final query
$sql = "SELECT * FROM services";
if (count($where) > 0) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY service_name ASC";

$stmt = mysqli_prepare($conn, $sql);
if (count($params) > 0) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$services = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($category_name) ?> - Beautify Services</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        :root {
            --accent-gold: #c5a059;
            --soft-gray: #f9f9f9;
            --dark-text: #2c2c2c;
        }
        body { background: var(--soft-gray); font-family: 'Segoe UI', sans-serif; }
        .page-header {
            background: linear-gradient(135deg, #fffcf5, #f9f5eb);
            padding: 60px 0 40px;
            text-align: center;
            margin-bottom: 40px;
        }
        .service-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            background: white;
        }
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        }
        .service-img {
            height: 220px;
            object-fit: cover;
            width: 100%;
        }
        .card-body {
            padding: 20px;
        }
        .service-title {
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 10px;
            color: var(--dark-text);
        }
        .btn-book {
            background: var(--accent-gold);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 30px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-book:hover {
            background: #b38c47;
            transform: translateY(-2px);
        }
        .filter-form .form-control, .filter-form .btn {
            border-radius: 10px;
        }
        .no-services {
            padding: 60px 20px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="page-header">
    <div class="container">
        <h2 class="font-weight-bold mb-2"><?= htmlspecialchars($category_name) ?></h2>
        <p class="lead text-muted">Discover professional beauty services delivered to your doorstep</p>
    </div>
</div>

<div class="container mb-5">

    <!-- Search & Filter -->
    <div class="card shadow-sm mb-5 filter-form">
        <div class="card-body">
            <form method="get" class="row g-3 align-items-end">
                <?php if ($category_id > 0): ?>
                    <input type="hidden" name="category_id" value="<?= $category_id ?>">
                <?php endif; ?>
                
                <div class="col-md-5">
                    <label class="form-label"><i class="fas fa-search mr-2"></i>Search Services</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="e.g. Bridal Makeup, HydraFacial..." 
                           value="<?= htmlspecialchars($search) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label"><i class="fas fa-user mr-2"></i>For</label>
                    <select name="service_type" class="form-control">
                        <option value="">All (Men, Women, Both)</option>
                        <option value="Men" <?= $service_type_filter === 'Men' ? 'selected' : '' ?>>Men</option>
                        <option value="Women" <?= $service_type_filter === 'Women' ? 'selected' : '' ?>>Women</option>
                        <option value="Both" <?= $service_type_filter === 'Both' ? 'selected' : '' ?>>Both</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Find Services</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Services Grid -->
    <?php if (count($services) > 0): ?>
        <div class="row">
            <?php foreach ($services as $s): ?>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="service-card h-100">
                        <?php if (!empty($s['image_url'])): ?>
                            <img src="../images/<?= htmlspecialchars($s['image_url']) ?>" 
                                 class="service-img" 
                                 alt="<?= htmlspecialchars($s['service_name']) ?>">
                        <?php else: ?>
                            <img src="../images/default_service.png" 
                                 class="service-img" 
                                 alt="Service">
                        <?php endif; ?>

                        <div class="card-body text-center">
                            <h5 class="service-title"><?= htmlspecialchars($s['service_name']) ?></h5>
                            
                            <p class="text-muted small mb-3" style="min-height: 60px;">
                                <?= htmlspecialchars(substr($s['description'] ?? '', 0, 100)) ?>...
                            </p>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <strong>PKR <?= number_format($s['price'], 0) ?></strong>
                                </div>
                                <div>
                                    <span class="badge badge-pill badge-secondary">
                                        <?= $s['duration_minutes'] ?> min
                                    </span>
                                </div>
                            </div>

                            <a href="book_service.php?service_id=<?= $s['service_id'] ?>" 
                               class="btn btn-book">Book Now</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-services text-center mx-auto" style="max-width: 600px;">
            <i class="fas fa-search fa-3x text-muted mb-4"></i>
            <h4>No services found</h4>
            <p class="text-muted mb-4">
                Try adjusting your search or filters.<br>
                We have many beauty services waiting for you!
            </p>
            <a href="services.php" class="btn btn-outline-dark">View All Services</a>
        </div>
    <?php endif; ?>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>