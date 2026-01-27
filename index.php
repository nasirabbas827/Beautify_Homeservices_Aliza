<?php
session_start();
include('config.php');

// 1. Fetch Categories for the Service Grid
$categories = [];
if (isset($conn)) {
    $result = $conn->query("SELECT * FROM service_categories LIMIT 8");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }
}

// 2. Fetch total services count for social proof
$total_services = 0;
try {
    $count_res = $conn->query("SELECT COUNT(*) FROM services");
    $total_services = $count_res->fetch_row()[0] ?? 0;
} catch (Exception $e) {
    $total_services = "Many";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beautify Services | Professional Services at Your Doorstep</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">

    <style>
        :root {
            --primary-blue: #0056b3;
            --accent-gold: #c5a059;
            --soft-bg: #fdfdfd;
            --card-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--soft-bg); }

        /* Hero */
        .hero-section {
            padding: 100px 0;
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=1350&q=80') center/cover;
            color: white;
            text-align: center;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
        }
        .hero-title { font-weight: 800; font-size: 3.5rem; text-shadow: 2px 2px 10px rgba(0,0,0,0.3); }

        /* Category Cards */
        .cat-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: var(--card-shadow);
            margin-bottom: 30px;
            background: white;
            text-align: center;
        }
        .cat-card:hover { transform: translateY(-10px); }
        .cat-img { height: 180px; object-fit: cover; width: 100%; }
        .cat-name { padding: 15px; font-weight: 600; color: #333; }

        /* How It Works */
        .step-circle {
            width: 80px; height: 80px;
            background: #fff;
            color: var(--primary-blue);
            line-height: 80px;
            border-radius: 50%;
            font-size: 1.5rem;
            margin: 0 auto 20px;
            box-shadow: var(--card-shadow);
        }

        /* Latest Stories */
        .story-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease;
        }
        .story-card:hover { transform: translateY(-8px); }
        .story-img { height: 240px; object-fit: cover; width: 100%; }
        .story-overlay {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.7));
            color: white;
            padding: 15px;
            opacity: 0;
            transition: opacity 0.4s;
        }
        .story-card:hover .story-overlay { opacity: 1; }
        .story-info { padding: 12px; background: white; }
        .service-name { font-weight: 700; font-size: 1.1rem; }

        /* Contact Us */
        .contact-section {
            background: linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)), url('https://images.squarespace-cdn.com/content/v1/68a73efe5018ad24408abad0/3c51de33-577c-4bd9-9be9-2485b82d76ab/3.png') center/cover;
            color: white;
            padding: 80px 0;
            border-radius: 30px;
            margin: 60px 0;
        }
        .contact-form .form-control { border-radius: 10px; }
        .contact-info i { font-size: 1.8rem; color: var(--accent-gold); }

        .section-padding { padding: 80px 0; }
        .btn-main { background: #ff4d4d; color: white; padding: 12px 35px; border-radius: 50px; font-weight: bold; border: none; }
        .btn-main:hover { background: #e60000; }
        .btn-gold { background: var(--accent-gold); color: white; border: none; border-radius: 50px; padding: 12px 35px; }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<section class="hero-section">
    <div class="container">
        <h1 class="hero-title text-white">Professional Salon & <br>Wellness at Home</h1>
        <p class="lead mt-3">Choose from over <?= $total_services ?>+ verified services</p>
        <div class="mt-5">
            <a href="#explore" class="btn btn-main btn-lg">Book a Service Now</a>
        </div>
    </div>
</section>

<section id="explore" class="container section-padding">
    <h2 class="text-center font-weight-bold mb-5">Explore Categories</h2>
    <div class="row">
        <?php if(!empty($categories)): ?>
            <?php foreach($categories as $cat): ?>
            <div class="col-md-3 col-6">
                <a href="services.php?cat=<?= $cat['category_id'] ?>" class="text-decoration-none">
                    <div class="cat-card">
                        <img src="images/<?= $cat['image_url'] ?? './images/default-cat.jpg' ?>" class="cat-img" alt="<?= $cat['category_name'] ?>">
                        <div class="cat-name"><?= $cat['category_name'] ?></div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center text-muted">No categories found in database.</div>
        <?php endif; ?>
    </div>
</section>

<section class="bg-light section-padding">
    <div class="container text-center">
        <h2 class="font-weight-bold mb-5">How It Works</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="step-circle"><i class="fas fa-search"></i></div>
                <h5>Select a Service</h5>
                <p class="text-muted">Pick from hair, makeup, or skin care.</p>
            </div>
            <div class="col-md-4">
                <div class="step-circle"><i class="fas fa-calendar-alt"></i></div>
                <h5>Choose Date & Time</h5>
                <p class="text-muted">Schedule at your convenience.</p>
            </div>
            <div class="col-md-4">
                <div class="step-circle"><i class="fas fa-home"></i></div>
                <h5>Enjoy at Home</h5>
                <p class="text-muted">Our professional arrives at your door.</p>
            </div>
        </div>
    </div>
</section>

<!-- Latest Stories Section -->
<section class="container section-padding">
    <h2 class="text-center font-weight-bold mb-5">Latest Client Stories</h2>
    <div class="row">
        <!-- Story 1 -->
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="story-card">
                <div class="position-relative">
                    <img src="https://images.pexels.com/photos/8751928/pexels-photo-8751928.jpeg" class="story-img" alt="Bridal Makeup">
                    <div class="story-overlay">
                        <p>"Felt like a queen at home!"</p>
                    </div>
                </div>
                <div class="story-info">
                    <div class="service-name">Bridal Makeup</div>
                    <p class="text-muted small">Ayesha K. • Faisalabad</p>
                </div>
            </div>
        </div>

        <!-- Story 2 -->
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="story-card">
                <div class="position-relative">
                    <img src="https://images.pexels.com/photos/1229414/pexels-photo-1229414.jpeg" class="story-img" alt="Glowing Facial">
                    <div class="story-overlay">
                        <p>"Instant radiant skin!"</p>
                    </div>
                </div>
                <div class="story-info">
                    <div class="service-name">HydraFacial</div>
                    <p class="text-muted small">Mariam Z. • Lahore</p>
                </div>
            </div>
        </div>

        <!-- Story 3 -->
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="story-card">
                <div class="position-relative">
                    <img src="https://images.pexels.com/photos/25950172/pexels-photo-25950172/free-photo-of-brunette-with-veil.jpeg" class="story-img" alt="Bridal Hairstyle">
                    <div class="story-overlay">
                        <p>"Perfect wedding look!"</p>
                    </div>
                </div>
                <div class="story-info">
                    <div class="service-name">Bridal Hairstyle</div>
                    <p class="text-muted small">Noor J. • Islamabad</p>
                </div>
            </div>
        </div>

        <!-- Story 4 -->
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="story-card">
                <div class="position-relative">
                    <img src="https://images.pexels.com/photos/973405/pexels-photo-973405.jpeg" class="story-img" alt="Signature Mani Pedi">
                    <div class="story-overlay">
                        <p>"Pampered nails!"</p>
                    </div>
                </div>
                <div class="story-info">
                    <div class="service-name">Signature Mani-Pedi</div>
                    <p class="text-muted small">Sana R. • Karachi</p>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mt-4">
        <a href="stories.php" class="btn btn-outline-dark btn-lg px-5">View All Stories</a>
    </div>
</section>

<!-- Contact Us Section -->
<section class="contact-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <h2 class="font-weight-bold mb-4 text-white">Get in Touch</h2>
                <p class="lead mb-4">We're here to help! Reach out for bookings, questions, or custom requests.</p>
                
                <div class="contact-info mb-4">
                    <p><i class="fas fa-phone mr-3"></i> +92 300 1234567</p>
                    <p><i class="fas fa-envelope mr-3"></i> support@beautify.pk</p>
                    <p><i class="fas fa-map-marker-alt mr-3"></i> Faisalabad, Lahore, Islamabad & Karachi</p>
                </div>

                <div>
                    <a href="#" class="text-white mr-4"><i class="fab fa-facebook fa-2x"></i></a>
                    <a href="#" class="text-white mr-4"><i class="fab fa-instagram fa-2x"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-whatsapp fa-2x"></i></a>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="bg-white p-4 rounded shadow" style="color:#333;">
                    <form action="contact_process.php" method="POST">
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" placeholder="Your Name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="email" class="form-control" placeholder="Email Address" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <input type="tel" class="form-control" placeholder="Phone Number" required>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" rows="4" placeholder="Your Message" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-gold btn-block">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="py-5 bg-dark text-white text-center">
    <div class="container">
        <p>© <?php echo date("Y"); ?> Beautify Services. All Rights Reserved.</p>
        <div>
            <i class="fab fa-facebook fa-2x mx-2"></i>
            <i class="fab fa-instagram fa-2x mx-2"></i>
            <i class="fab fa-whatsapp fa-2x mx-2"></i>
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>