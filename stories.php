<?php
session_start();
// In real project: include 'db_connect.php'; or similar
// For now dummy version - no DB needed for display
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Stories & Transformations | Beautify Home Services</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">  
    <style>
        :root {
            --accent-gold: #c5a059;
            --soft-gray: #f9f9f9;
            --dark-text: #2c2c2c;
        }
        .story-header {
            padding: 80px 0;
            background: linear-gradient(to bottom, #fffcf5, #ffffff);
            text-align: center;
        }
        .section-tag {
            color: var(--accent-gold);
            text-transform: uppercase;
            letter-spacing: 3px;
            font-size: 0.9rem;
            font-weight: 700;
        }
        .story-card {
            border: none;
            margin-bottom: 40px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .story-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        .image-wrapper {
            overflow: hidden;
            border-radius: 12px;
            position: relative;
        }
        .story-img {
            width: 100%;
            height: 420px;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        .story-card:hover .story-img {
            transform: scale(1.08);
        }
        .story-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 25px 20px;
            background: linear-gradient(transparent, rgba(0,0,0,0.75));
            color: white;
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        .story-card:hover .story-overlay {
            opacity: 1;
        }
        .story-info {
            padding: 15px 0 0;
        }
        .service-name {
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 6px;
            color: var(--dark-text);
        }
        .ba-section {
            background-color: var(--soft-gray);
            padding: 80px 0;
            border-radius: 40px;
            margin: 60px 0;
        }
        .ba-card {
            background: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.06);
        }
        .ba-image-group {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
        }
        .ba-image-group > div {
            flex: 1 1 45%;
            min-width: 280px;
            position: relative;
        }
        .ba-image-group img {
            width: 100%;
            height: 340px;
            object-fit: cover;
            border-radius: 10px;
        }
        .ba-label {
            position: absolute;
            bottom: 15px;
            left: 15px;
            background: var(--accent-gold);
            color: white;
            padding: 6px 14px;
            font-size: 0.85rem;
            border-radius: 50px;
            font-weight: 600;
        }
        @media (max-width: 768px) {
            .story-img { height: 360px; }
            .ba-image-group img { height: 280px; }
        }
    </style>
</head>
<body>
<?php include('navbar.php'); ?>

<div class="story-header">
    <div class="container">
        <span class="section-tag">Visual Lookbook</span>
        <h1 class="display-4 font-weight-bold mt-3">Beautify Stories</h1>
        <p class="lead text-muted mt-3">Real transformations — delivered right at your doorstep.</p>
    </div>
</div>

<div class="container">
    <div class="row">
        <!-- Story 1: Bridal Makeup -->
        <div class="col-md-4">
            <div class="story-card">
                <div class="image-wrapper">
                    <img src="https://images.squarespace-cdn.com/content/v1/5e575ffdabec06285101e4d6/1745812770874-BP4P7BR68IY51RD8A375/sri-lankan-wedding-bride-dreamy-portrait-vibiana" 
                         class="story-img" alt="Bridal Makeup Transformation">
                    <div class="story-overlay">
                        <p>"I looked like royalty without stepping out!"</p>
                    </div>
                </div>
                <div class="story-info">
                    <div class="service-name">Bridal Makeup</div>
                    <p class="text-muted small"><i class="fas fa-user-circle mr-1"></i> Ayesha K. • Faisalabad</p>
                </div>
            </div>
        </div>

        <!-- Story 2: Party / Glam Makeup -->
        <div class="col-md-4">
            <div class="story-card">
                <div class="image-wrapper">
                    <img src="https://elements.togetherjournal.com/uploads/2024/07/f18c4254-tanesha-vinay-weddingday_high-097-1024x683.jpg" 
                         class="story-img" alt="Glam Party Makeup">
                    <div class="story-overlay">
                        <p>"Glowed up for the big night!"</p>
                    </div>
                </div>
                <div class="story-info">
                    <div class="service-name">Glam Party Makeup</div>
                    <p class="text-muted small"><i class="fas fa-user-circle mr-1"></i> Fatima S. • Lahore</p>
                </div>
            </div>
        </div>

        <!-- Story 3: Hair Styling -->
        <div class="col-md-4">
            <div class="story-card">
                <div class="image-wrapper">
                    <img src="https://images.squarespace-cdn.com/content/v1/6390d1213656941a08dc0d71/1670435105471-007P1ECH5314CWR4XOJF/Signature+Blowout+%234.jpg" 
                         class="story-img" alt="Bridal Hair Styling">
                    <div class="story-overlay">
                        <p>"Wedding-ready in just 90 minutes at home."</p>
                    </div>
                </div>
                <div class="story-info">
                    <div class="service-name">Bridal Hairstyle</div>
                    <p class="text-muted small"><i class="fas fa-user-circle mr-1"></i> Noor J. • Islamabad</p>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="container ba-section">
    <div class="text-center mb-5">
        <span class="section-tag">Real Results</span>
        <h2 class="font-weight-bold">Client Transformations</h2>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="ba-card">
                <div class="ba-image-group">
                    <div>
                        <img src="https://drchernoff.com/wp-content/uploads/2024/07/DaxxifyWS5-1024x604.jpg" alt="Before Facial">
                        <span class="ba-label">Before</span>
                    </div>
                    <div>
                        <img src="https://drchernoff.com/wp-content/uploads/2025/05/16-1-1024x651.jpg" alt="After HydraFacial">
                        <span class="ba-label">After HydraFacial</span>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <h4>Hydra-Radiance Facial</h4>
                    <p class="text-muted px-3">Deep cleansing + hydration for that instant dewy glow — perfect before any event.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-md-10">
            <div class="ba-card">
                <div class="ba-image-group">
                    <div>
                        <img src="https://c1.wallpaperflare.com/preview/567/48/7/596ed4055267a.jpg" alt="Before Nail Service">
                        <span class="ba-label">Before</span>
                    </div>
                    <div>
                        <img src="https://images.pexels.com/photos/973405/pexels-photo-973405.jpeg" alt="After Signature Mani Pedi">
                        <span class="ba-label">After Signature Mani-Pedi</span>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <h4>Signature Manicure + Pedicure</h4>
                    <p class="text-muted px-3">Nail shaping, cuticle care, polish & luxury massage — pampering at home.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container text-center mb-5">
    <h3 class="mb-4">Ready to Write Your Own Story?</h3>
    <a href="index.php#services" class="btn btn-lg px-5" style="background: var(--accent-gold); color: white; border-radius: 50px;">Book Now</a>
</div>

<footer class="py-5 border-top text-center text-muted small">
    <p>© <?php echo date("Y"); ?> Beautify Home Beauty Services – Professional & Hygienic At-Home Care</p>
</footer>

</body>
</html>