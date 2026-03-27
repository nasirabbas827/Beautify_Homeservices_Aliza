<?php
session_start();
include 'config.php'; 

// Fetch all stories from the database
$sql = "SELECT * FROM stories ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Stories | Beautify Luxe</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">

    
    <style>
        :root {
            --accent-gold: #c5a059;
            --bg-silk: #fdfbf7;
            --glass-white: rgba(255, 255, 255, 0.85);
            --text-main: #333;
        }

        body {
            background-color: var(--bg-silk);
            font-family: 'Poppins', sans-serif;
            color: var(--text-main);
        }

        .story-header {
            padding: 100px 0 60px;
            background: radial-gradient(circle at top right, #fff5e6, transparent);
        }

        .story-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            color: #1a1a1a;
        }

        /* Modern Card Styling */
        .story-card {
            background: var(--glass-white);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(197, 160, 89, 0.1);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
        }

        .story-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.08);
            border-color: var(--accent-gold);
        }

        .image-container {
            position: relative;
            height: 380px;
            overflow: hidden;
        }

        .story-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s ease;
        }

        .story-card:hover .story-img {
            transform: scale(1.1);
        }

        .category-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: var(--accent-gold);
            color: white;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .story-body {
            padding: 25px;
            text-align: center;
        }

        .service-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            margin-bottom: 10px;
            color: #1a1a1a;
        }

        .testimonial-text {
            font-size: 0.95rem;
            color: #666;
            line-height: 1.6;
            font-style: italic;
            margin-bottom: 20px;
        }

        .client-meta {
            border-top: 1px solid #eee;
            padding-top: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            color: var(--accent-gold);
            font-weight: 600;
            font-size: 0.85rem;
        }

        .btn-book {
            background: var(--accent-gold);
            color: white;
            border-radius: 50px;
            padding: 12px 35px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: 0.3s;
            border: none;
        }

        .btn-book:hover {
            background: #b38e4a;
            color: white;
            box-shadow: 0 10px 20px rgba(197, 160, 89, 0.3);
        }

        /* Masonry-like spacing */
        .card-columns {
            column-count: 3;
            gap: 1.25rem;
        }

        @media (max-width: 992px) { .card-columns { column-count: 2; } }
        @media (max-width: 576px) { .card-columns { column-count: 1; } }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<header class="story-header text-center">
    <div class="container">
        <span class="section-tag" style="color: var(--accent-gold); font-weight: 700; letter-spacing: 4px;">TESTIMONIALS</span>
        <h1 class="mt-2">Beauty Unveiled</h1>
        <p class="text-muted">Explore the confidence and elegance our clients found at home.</p>
    </div>
</header>

<main class="container mb-5">
    <div class="card-columns">
        <?php 
        if ($result && $result->num_rows > 0): 
            while($row = $result->fetch_assoc()): 
        ?>
            <div class="story-card">
                <div class="image-container">
                    <img src="images/<?php echo htmlspecialchars($row['image_url']); ?>" 
                         class="story-img" 
                         alt="Beautify Transformation">
                    <span class="category-badge">Client Story</span>
                </div>
                
                <div class="story-body">
                    <h3 class="service-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p class="testimonial-text">
                        "<?php echo htmlspecialchars($row['content']); ?>"
                    </p>
                    <div class="client-meta">
                        <i class="far fa-heart"></i>
                        <span>Verified Transformation</span>
                    </div>
                </div>
            </div>
        <?php 
            endwhile; 
        else: 
        ?>
            <div class="text-center w-100 py-5">
                <img src="https://cdn-icons-png.flaticon.com/512/13515/13515364.png" width="80" class="mb-3 opacity-50">
                <p class="text-muted">Our gallery is currently being updated with fresh looks.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="text-center mt-5 py-5">
        <h2 class="font-weight-bold mb-4" style="font-family: 'Playfair Display';">Inspired by these results?</h2>
        <a href="booking.php" class="btn btn-book btn-lg">Reserve Your Session</a>
    </div>
</main>

<footer class="py-4 text-center border-top">
    <p class="small text-muted mb-0">© <?php echo date("Y"); ?> Beautify Home Services. Elegance Delivered.</p>
</footer>

</body>
</html>