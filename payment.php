<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="./assets/css/style.css">

    <style>
        .main_container{
            border-style: solid;
            border-width: 2px;
            border-radius: 25px;
            max-width: 600px;
            padding: 30px;
            margin: 50px auto;
            font-size: large;
        }
        .form-group{
            border-style: solid;
            border-width: 2px;
            padding: 20px;
            margin: 5px;
            border-radius: 15px;
        }
        .text {
            border: 1px solid #ccc; 
            padding: 5px; 
            border-radius: 5px; 
            width: 100%; /* Ensure text inputs fill the entire width */
            box-sizing: border-box; /* Include padding and border in element's total width */
        }
    </style>
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
                        <a href="rating/rating.php" class="navbar-link">Ratings</a>
                    </li>
                    <li>
                        <a href="my_tour.php" class="navbar-link">My Tours</a>
                    </li>
                </ul>

                <a href="logout.php" class="btn btn-danger">Logout</a>

            </nav>
        </div>
    </header>

    <!-- Payment Form -->

    
    <div class="main_container">
    <div class="info">
        <label for="School"> School Name:</label><br>
        <label for="date">Tour Date:</label><br>
        <label for="total_cost"> Total Amount:</label>
    </div><br><br>
        <h2>Payment Details</h2>
        <form id="payment-form" method="post" action="process_payment.php">
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="text" id="amount" name="amount" placeholder="Enter amount">
            </div>
            <div class="form-group">
                <label for="name">Name on Card:</label>
                <input type="text" id="name" name="name" placeholder="Enter name on card">
            </div>
            <div class="form-group">
                <label for="card-number">Card Number:</label>
                <input type="text" id="card-number" name="card-number" placeholder="Enter card number">
            </div>
            <div class="form-group">
                <label for="expiry">Expiry Date:</label>
                <input type="text" id="expiry" name="expiry" placeholder="MM/YY">
            </div>
            <div class="form-group">
                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" placeholder="Enter CVV">
            </div>
            <button type="submit" class="btn btn-primary">Pay Now</button>
        </form>
    </div>

    <!-- Include the PayPal JavaScript SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id=YOUR_PAYPAL_CLIENT_ID"></script>
    <script>
        // Code to handle PayPal payments goes here
    </script>
</body>
</html>
