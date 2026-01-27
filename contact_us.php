<?php
session_start();
include('config.php');

// Simple PHP logic to handle form submission message
$message_sent = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Here you would typically insert into a 'messages' table or send an email
    // For now, we'll just trigger a success state
    $message_sent = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Beautify Services Support</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">  

    <style>
        :root {
            --accent-color: #c5a059; /* Matching your luxury gold theme */
            --dark-blue: #002d5b;
        }


        .contact-header {
            background: linear-gradient(135deg, var(--dark-blue) 0%, #004a99 100%);
            color: white;
            padding: 100px 0;
            margin-bottom: -50px;
        }

        .contact-card {
            background: white;
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .info-sidebar {
            background-color: var(--dark-blue);
            color: white;
            padding: 40px;
            height: 100%;
        }

        .form-section { padding: 40px; }

        .info-item { display: flex; align-items: flex-start; margin-bottom: 30px; }
        .info-icon {
            background: rgba(255,255,255,0.1);
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--accent-color);
        }

        .form-control {
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 12px 15px;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: none;
        }

        .btn-send {
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            width: 100%;
            transition: 0.3s;
        }

        .btn-send:hover {
            background: #b08e4d;
            transform: translateY(-2px);
            color: white;
        }

        .social-links a {
            color: white;
            margin-right: 20px;
            font-size: 1.2rem;
            transition: 0.3s;
        }

        .social-links a:hover { color: var(--accent-color); }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="contact-header text-center">
    <div class="container">
        <h1 class="display-4 font-weight-bold text-white">Get In Touch</h1>
        <p class="lead">Have questions about our services? We're here to help you rise.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="contact-card">
        <div class="row no-gutters">
            <div class="col-md-5">
                <div class="info-sidebar">
                    <h3 class="text-white">Contact Information</h3>
                    <p class="mb-5 text-white-50">Reach out to us and we'll get back to you within 24 hours.</p>

                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-phone"></i></div>
                        <div>
                            <h6>Phone</h6>
                            <p>+92 300 1234567</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-envelope"></i></div>
                        <div>
                            <h6>Email</h6>
                            <p>support@Beautify Services.com</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <h6>Head Office</h6>
                            <p>123 Business Avenue, Gulberg III, Lahore, Pakistan</p>
                        </div>
                    </div>

                    <div class="social-links mt-5">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="form-section">
                    <?php if($message_sent): ?>
                        <div class="alert alert-success py-4">
                            <h5><i class="fas fa-check-circle mr-2"></i> Message Sent!</h5>
                            <p class="mb-0">Thank you for contacting Beautify Services. Our team will reach out to you shortly.</p>
                        </div>
                    <?php endif; ?>

                    <form action="contact_us.php" method="POST">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold">Your Name</label>
                                <input type="text" class="form-control" placeholder="John Doe" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold">Email Address</label>
                                <input type="email" class="form-control" placeholder="john@example.com" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold">Subject</label>
                            <select class="form-control">
                                <option>Service Inquiry</option>
                                <option>Booking Support</option>
                                <option>Partner with Us (NGO/Professional)</option>
                                <option>Feedback</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold">Your Message</label>
                            <textarea class="form-control" rows="5" placeholder="How can we help you?" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-send shadow-lg mt-3">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-0 mt-5">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3401.5516709874837!2d74.3414856!3d31.5090157!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3919045a19c6f2a9%3A0xc3194a0808a3d54a!2sGulberg%20III%2C%20Lahore!5e0!3m2!1sen!2spk!4v1690000000000!5m2!1sen!2spk" width="100%" height="350" style="border:0; filter: grayscale(100%) invert(92%) contrast(83%);" allowfullscreen="" loading="lazy"></iframe>
</div>

<footer class="py-5 border-top text-center text-muted small">
    <p>© <?php echo date("Y"); ?> Beautify Home Beauty Services – Professional & Hygienic At-Home Care</p>
</footer>

</body>
</html>