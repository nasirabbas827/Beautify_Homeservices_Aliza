<?php
session_start();
include('config.php');

// Fetch FAQs from database
$sql = "SELECT * FROM faqs ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frequently Asked Questions | Beautify Luxe</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">

    <style>
        :root {
            --accent-gold: #c5a059;
            --dark-navy: #002d5b;
            --bg-silk: #fdfbf7;
            --glass-white: rgba(255, 255, 255, 0.9);
        }

        body {
            background-color: var(--bg-silk);
            font-family: 'Poppins', sans-serif;
            color: #333;
        }

        .faq-header {
            background: linear-gradient(135deg, var(--dark-navy) 0%, #001a35 100%);
            color: white;
            padding: 100px 0 80px;
            text-align: center;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
        }

        .faq-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .search-box {
            max-width: 600px;
            margin: 30px auto 0;
            position: relative;
        }

        .search-box input {
            height: 60px;
            border-radius: 30px;
            padding-left: 30px;
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            font-size: 1rem;
        }

        .faq-container { margin-top: -50px; margin-bottom: 80px; }

        .faq-card {
            background: var(--glass-white);
            border: 1px solid rgba(197, 160, 89, 0.1);
            border-radius: 25px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.05);
            padding: 40px;
            backdrop-filter: blur(10px);
        }

        /* Modern Accordion */
        .accordion .card {
            border: none;
            background: transparent;
            margin-bottom: 10px;
            border-bottom: 1px solid rgba(0,0,0,0.05) !important;
        }

        .accordion .card-header {
            background: transparent;
            border: none;
            padding: 20px 0;
        }

        .btn-link {
            color: var(--dark-navy);
            font-weight: 600;
            text-decoration: none !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 0;
            font-size: 1.1rem;
            text-align: left;
            transition: 0.3s;
        }

        .btn-link:hover { color: var(--accent-gold); }

        .btn-link i {
            font-size: 0.8rem;
            transition: transform 0.3s ease;
            color: var(--accent-gold);
        }

        .btn-link:not(.collapsed) i {
            transform: rotate(180deg);
        }

        .card-body {
            padding: 0 0 25px;
            line-height: 1.7;
            color: #666;
        }

        .faq-category-title {
            color: var(--accent-gold);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-size: 0.85rem;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
        }

        .faq-category-title::after {
            content: "";
            height: 1px;
            flex-grow: 1;
            background: rgba(197, 160, 89, 0.2);
            margin-left: 20px;
        }

        .contact-support-btn {
            background: var(--accent-gold);
            color: white;
            border-radius: 50px;
            padding: 12px 40px;
            font-weight: 600;
            transition: 0.3s;
            border: none;
        }

        .contact-support-btn:hover {
            box-shadow: 0 10px 20px rgba(197, 160, 89, 0.3);
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<header class="faq-header">
    <div class="container">
        <h1 class="text-white">Concierge Support</h1>
        <p class="opacity-75">Your questions about our at-home luxury services, answered.</p>
        <div class="search-box">
            <input type="text" id="faqSearch" class="form-control" placeholder="Search for a topic (e.g. 'Safety', 'Payment')...">
        </div>
    </div>
</header>

<div class="container faq-container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="faq-card">
                
                <div class="faq-category-title">General Questions</div>
                
                <div class="accordion" id="dynamicFaq">
                    <?php 
                    if ($result && $result->num_rows > 0): 
                        $count = 0;
                        while($row = $result->fetch_assoc()): 
                            $count++;
                            $collapseId = "collapse" . $row['faq_id'];
                            $headingId = "heading" . $row['faq_id'];
                    ?>
                        <div class="card faq-item">
                            <div class="card-header" id="<?php echo $headingId; ?>">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#<?php echo $collapseId; ?>">
                                    <?php echo htmlspecialchars($row['question']); ?>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                            <div id="<?php echo $collapseId; ?>" class="collapse" data-parent="#dynamicFaq">
                                <div class="card-body">
                                    <?php echo nl2br(htmlspecialchars($row['answer'])); ?>
                                </div>
                            </div>
                        </div>
                    <?php 
                        endwhile; 
                    else: 
                    ?>
                        <div class="text-center py-5">
                            <i class="fas fa-question-circle fa-3x mb-3 text-muted"></i>
                            <p class="text-muted">We're updating our FAQ list. Please check back later.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <div class="text-center mt-5">
                <p class="text-muted">Still have questions?</p>
                <a href="contact_us.php" class="btn contact-support-btn">Talk to a Specialist</a>
            </div>
        </div>
    </div>
</div>

<footer class="py-5 text-center text-muted small">
    <p>© <?php echo date("Y"); ?> Beautify Luxe – Premium At-Home Care</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Real-time Search Filter
    $(document).ready(function(){
        $("#faqSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".faq-item").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>

</body>
</html>