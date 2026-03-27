<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Story | Beautify Luxe</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">

    
    <style>
        :root {
            --accent-gold: #c5a059;
            --soft-cream: #fdfbf7;
            --dark-navy: #002d5b;
            --text-gray: #666;
        }

        body {
            background-color: var(--soft-cream);
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        /* Hero Section */
        .about-hero {
            height: 60vh;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                        url('https://images.unsplash.com/photo-1560750588-73207b1ef5b8?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }

        .about-hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 4rem;
            letter-spacing: 2px;
        }

        /* Vision Section */
        .vision-section {
            padding: 100px 0;
        }

        .vision-text h2 {
            font-family: 'Playfair Display', serif;
            color: var(--dark-navy);
            font-size: 2.5rem;
            margin-bottom: 25px;
        }

        .vision-image img {
            border-radius: 30px;
            box-shadow: 20px 20px 0px var(--accent-gold);
            width: 100%;
        }

        /* Stats Section */
        .stats-bar {
            background: var(--dark-navy);
            color: white;
            padding: 60px 0;
            margin: 50px 0;
        }

        .stat-item h2 {
            color: var(--accent-gold);
            font-weight: 700;
            font-size: 2.5rem;
        }

        /* Values Section */
        .value-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            border: 1px solid rgba(197, 160, 89, 0.1);
            transition: 0.3s;
            height: 100%;
        }

        .value-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        }

        .value-icon {
            font-size: 2.5rem;
            color: var(--accent-gold);
            margin-bottom: 20px;
        }

        /* The Founder Section */
        .founder-box {
            background: white;
            border-radius: 40px;
            padding: 50px;
            margin-top: 100px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.03);
        }

        .signature {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            color: var(--accent-gold);
            font-size: 1.5rem;
        }

        footer {
            background: white;
            padding: 40px 0;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<section class="about-hero">
    <div class="container">
        <span class="text-uppercase" style="letter-spacing: 5px; color: var(--accent-gold);">Since 2024</span>
        <h1 class="text-white">Our Journey</h1>
    </div>
</section>

<section class="container vision-section">
    <div class="row align-items-center">
        <div class="col-lg-6 vision-text">
            <span class="text-muted small font-weight-bold text-uppercase">Who We Are</span>
            <h2>Redefining Beauty <br>at Your Doorstep</h2>
            <p class="lead text-muted">Beautify Luxe was born from a simple idea: that premium self-care shouldn't require a commute.</p>
            <p>We connect highly-skilled, verified beauty professionals with people who value their time and comfort. Whether it's a bridal transformation or a relaxing Sunday facial, we bring the salon experience to your living room with zero compromise on hygiene or quality.</p>
        </div>
        <div class="col-lg-6 vision-image mt-5 mt-lg-0">
            <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?ixlib=rb-1.2.1&auto=format&fit=crop&w=1365&q=80" alt="Professional Beauty Service">
        </div>
    </div>
</section>

<section class="stats-bar text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-4 stat-item">
                <h2>500+</h2>
                <p>Happy Clients</p>
            </div>
            <div class="col-md-4 stat-item">
                <h2>50+</h2>
                <p>Expert Stylists</p>
            </div>
            <div class="col-md-4 stat-item">
                <h2>15+</h2>
                <p>Service Cities</p>
            </div>
        </div>
    </div>
</section>

<section class="container py-5">
    <div class="text-center mb-5">
        <h2 style="font-family: 'Playfair Display'; font-size: 2.5rem;">The Core Pillars</h2>
    </div>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="value-card text-center">
                <i class="fas fa-gem value-icon"></i>
                <h4>Premium Quality</h4>
                <p class="text-muted">We use only high-end, dermatologically tested products to ensure your skin gets the best care.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="value-card text-center">
                <i class="fas fa-shield-alt value-icon"></i>
                <h4>Safety First</h4>
                <p class="text-muted">Every professional undergoes a 3-step background check and strictly follows COVID-19 safety protocols.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="value-card text-center">
                <i class="fas fa-heart value-icon"></i>
                <h4>Comfort & Care</h4>
                <p class="text-muted">We believe beauty is about feeling good. We prioritize your relaxation above everything else.</p>
            </div>
        </div>
    </div>
</section>

<section class="container mb-5">
    <div class="founder-box">
        <div class="row align-items-center">
            <div class="col-md-3 text-center mb-4 mb-md-0">
                <img src="https://cdn-icons-png.flaticon.com/512/6997/6997662.png" alt="Founder" class="img-fluid" style="max-width: 150px; border-radius: 50%;">
            </div>
            <div class="col-md-9">
                <p class="font-italic text-muted">"Our mission is to empower every woman to feel like the best version of herself, without the stress of navigating traffic or waiting in salons. We bring luxury to you, so you can focus on being beautiful."</p>
                <h5 class="mb-0 mt-3">Aliza</h5>
                <span class="signature">Founder of Beautify Luxe</span>
            </div>
        </div>
    </div>
</section>

<footer class="text-center">
    <div class="container">
        <p class="mb-0 text-muted small">© <?php echo date("Y"); ?> Beautify Luxe – Premium At-Home Beauty Solutions.</p>
    </div>
</footer>

</body>
</html>