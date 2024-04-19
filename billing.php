<?php
// Include necessary files and start the session
@include 'config.php';
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_name'])) {
    header('location:login_form.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml">

    <!-- Custom CSS Link -->
    <link rel="stylesheet" href="./assets/css/style.css">

    <!-- Google Font Link -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Comforter+Brush&family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="header" data-header>
        <div class="container">
            <a href="#">
                <h1 class="logo">Gayla Moves</h1>
            </a>

            <button class="nav-toggle-btn" data-nav-toggle-btn aria-label="Toggle Menu">
                <ion-icon name="menu-outline" class="open"></ion-icon>
                <ion-icon name="close-outline" class="close"></ion-icon>
            </button>

            <nav class="navbar">
                <ul class="navbar-list">
                    <li>
                        <a href="home.php" class="navbar-link">Home</a>
                    </li>
                    <li>
                        <a href="tours.php" class="navbar-link">Tours</a>
                    </li>
                    <li>
                        <a href="aboutus.php" class="navbar-link">About Us</a>
                    </li>
                    <li>
                        <a href="user_profile.php" class="navbar-link">User Profile</a>
                    </li>
                    <li>
                        <a href="rating.php" class="navbar-link">Ratings</a>
                    </li>
                </ul>

                <!-- Add additional navigation links as needed -->

                <a href="recommend.php" class="btn btn-secondary">Recommend Now</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </nav>
        </div>
    </header>

    <main>
        <article>
            <!-- Add your billing content here -->
            <section class="section billing">
                <div class="container">
                    <h2 class="section-title">Billing Information</h2>

                    <!-- Invoice Details -->
                    <div class="invoice-details">
                        <h3>Invoice #12345</h3>
                        <p>Date: <?php echo date("Y-m-d"); ?></p>
                    </div>

                    <!-- Customer Details from Database -->
                    <?php
                    // Assuming you have a database connection in your config.php
                    include 'config.php';

                    // Fetch customer information from the database
                    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session after login
                    $query = "SELECT first_name, last_name, email FROM user_form WHERE id = $user_id";
                    $result = mysqli_query($conn, $query);

                    if ($result && mysqli_num_rows($result) > 0) {
                        $customer = mysqli_fetch_assoc($result);
                    ?>
                        <div class="customer-details">
                            <h3>Customer Information</h3>
                            <p>Name: <?php echo $customer['first_name'] . ' ' . $customer['last_name']; ?></p>
                            <p>Email: <?php echo $customer['email']; ?></p>
                        </div>
                    <?php
                    } else {
                        // Handle error or display a message if customer information is not found
                        echo '<p>Error retrieving customer information</p>';
                    }
                    ?>

                    <!-- Billing Details -->
                    <div class="billing-details">
                        <h3>Tour Details</h3>
                        <div class="order-item">
                            <p>Tour: Tour Package A</p>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="payment-method">
                        <h3>Payment Method</h3>
                    </div>

                    <form method="post" action="process_payment.php">
                        <button type="submit" class="btn btn-primary">Proceed to Payment</button>
                    </form>

                </div>
            </section>
        </article>
    </main>


</body>
</html>
