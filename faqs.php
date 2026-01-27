<?php
session_start();
include('config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frequently Asked Questions | Beautify Services</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">  

    <style>
        :root {
            --accent-gold: #c5a059;
            --dark-navy: #002d5b;
            --light-bg: #f8f9fa;
        }


        .faq-header {
            background-color: var(--dark-navy);
            color: white;
            padding: 80px 0;
            text-align: center;
        }

        .faq-container { margin-top: -40px; margin-bottom: 80px; }

        .faq-card {
            background: white;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            padding: 30px;
        }

        /* Accordion Styling */
        .accordion .card {
            border: none;
            border-bottom: 1px solid #eee !important;
            border-radius: 0 !important;
        }

        .accordion .card-header {
            background: white;
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
        }

        .btn-link:hover, .btn-link:focus { color: var(--accent-gold); }

        .faq-category-title {
            color: var(--accent-gold);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.9rem;
            margin-top: 40px;
            margin-bottom: 20px;
        }

        .search-box {
            max-width: 600px;
            margin: 20px auto 0;
            position: relative;
        }

        .search-box input {
            height: 50px;
            border-radius: 25px;
            padding-left: 25px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="faq-header">
    <div class="container">
        <h1 class="font-weight-bold text-white">How can we help?</h1>
        <p>Everything you need to know about our services and booking process.</p>
        <div class="search-box">
            <input type="text" class="form-control" placeholder="Search for a question...">
        </div>
    </div>
</div>

<div class="container faq-container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="faq-card">
                
                <div class="faq-category-title">General Information</div>
                <div class="accordion" id="generalFaq">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne">
                                What is Beautify Services?
                                <i class="fas fa-chevron-down fa-xs"></i>
                            </button>
                        </div>
                        <div id="collapseOne" class="collapse show" data-parent="#generalFaq">
                            <div class="card-body text-muted">
                                Beautify Services is a professional home-service platform that connects verified professionals (Salon, Wellness, and Counseling) with clients in the comfort of their homes.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="faq-category-title">Bookings & Payments</div>
                <div class="accordion" id="bookingFaq">
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo">
                                How do I book a service?
                                <i class="fas fa-chevron-down fa-xs"></i>
                            </button>
                        </div>
                        <div id="collapseTwo" class="collapse" data-parent="#bookingFaq">
                            <div class="card-body text-muted">
                                Simply browse our categories, select your desired services, choose a convenient date and time, and confirm your booking. You will receive an SMS and email confirmation once a professional is assigned.
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" id="headingThree">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree">
                                What payment methods do you accept?
                                <i class="fas fa-chevron-down fa-xs"></i>
                            </button>
                        </div>
                        <div id="collapseThree" class="collapse" data-parent="#bookingFaq">
                            <div class="card-body text-muted">
                                We accept Cash on Delivery (COD), Credit/Debit Cards, EasyPaisa, and JazzCash. You can select your preferred method during checkout.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="faq-category-title">Safety & Privacy</div>
                <div class="accordion" id="safetyFaq">
                    <div class="card">
                        <div class="card-header" id="headingFour">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour">
                                Are your professionals verified?
                                <i class="fas fa-chevron-down fa-xs"></i>
                            </button>
                        </div>
                        <div id="collapseFour" class="collapse" data-parent="#safetyFaq">
                            <div class="card-body text-muted">
                                Absolutely. Every service provider on Beautify Services undergoes a strict background check, CNIC verification, and practical skill testing before joining our platform.
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="text-center mt-5">
                <p class="text-muted">Didn't find your answer?</p>
                <a href="contact_us.php" class="btn btn-outline-dark" style="border-radius: 50px; padding: 10px 30px;">Contact Support</a>
            </div>
        </div>
    </div>
</div>

<footer class="py-5 border-top text-center text-muted small">
    <p>© <?php echo date("Y"); ?> Beautify Home Beauty Services – Professional & Hygienic At-Home Care</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Simple script to toggle chevron icons
    $('.collapse').on('shown.bs.collapse', function(){
        $(this).parent().find(".fa-chevron-down").removeClass("fa-chevron-down").addClass("fa-chevron-up");
    }).on('hidden.bs.collapse', function(){
        $(this).parent().find(".fa-chevron-up").removeClass("fa-chevron-up").addClass("fa-chevron-down");
    });
</script>

</body>
</html>