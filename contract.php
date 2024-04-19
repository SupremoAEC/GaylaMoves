<!-- contract.php -->
<?php
include_once 'db_connection.php'; 
include_once 'config.php'; 
include_once 'admin_tour.php';
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page or handle unauthorized access
    header('location: login.php');
    exit;
}

// Get the booking ID from the URL
$bookingId = $_GET['booking_id'];

// Switch to the 'tour_recommendation' database
mysqli_select_db($conn, 'tour_recommendation');

// Retrieve booking details from the database based on booking ID
$sqlBooking = "SELECT * FROM booking WHERE id = $bookingId";
$resultBooking = mysqli_query($conn, $sqlBooking);

if (!$resultBooking) {
    // Add error handling
    die('Error: ' . mysqli_error($conn));
}

$bookingDetails = mysqli_fetch_assoc($resultBooking);

if (!$bookingDetails) {
    // Handle the case where there are no booking details
    echo "Booking details not found!";
    exit;
}

// Get booking details
$tourDate = $bookingDetails['date'];
$province = $bookingDetails['province'];
$expectedGoers = $bookingDetails['goers'];
$choose1Bus = $bookingDetails['bus'];
$choose2Vans = $bookingDetails['van'];
$totalCost = $bookingDetails['total_cost'];
$ratePerHead = $totalCost / $expectedGoers; // Calculate rate per head
$ratePerHeadFormatted = number_format($ratePerHead, 2); // Display with 2 decimal places
// Retrieve travel itinerary:
$tourId = null; // Initialize tourId to null

// Check if province is set
if (isset($province)) {
    // Retrieve tour_id from tours table based on the province
    $sqlTourId = "SELECT id FROM tours WHERE province = '$province' LIMIT 1";
    $resultTourId = mysqli_query($conn, $sqlTourId);

    if ($resultTourId && mysqli_num_rows($resultTourId) > 0) {
        $rowTourId = mysqli_fetch_assoc($resultTourId);
        $tourId = $rowTourId['id'];
    } else {
        // Handle the case where tour_id is not found for the given province
        echo "Tour ID not found for the province: $province";
        exit;
    }
} else {
    // Handle the case where province is not set
    echo "Province not set!";
    exit;
}

// Now, continue to retrieve the travel itinerary based on the obtained tourId
if ($tourId !== null) {
    $sqlItinerary = "SELECT place_name FROM travel_itinerary WHERE tour_id = $tourId";
    $resultItinerary = mysqli_query($conn, $sqlItinerary);

    if ($resultItinerary) {
        $tourDetails['travel_itinerary'] = [];
        while ($rowItinerary = mysqli_fetch_assoc($resultItinerary)) {
            $tourDetails['travel_itinerary'][] = $rowItinerary['place_name'];
        }
    } else {
        // Add error handling
        die('Error: ' . mysqli_error($conn));
    }
} else {
    // Handle the case where tour_id is not set
    echo "Tour ID not found!";
    exit;
}
// Switch to the 'user_db' database
// mysqli_select_db($conn, 'user_db'); 

// Fetch user details
$sqlUser = "SELECT * FROM user_form WHERE id = {$_SESSION['user_id']}";
$resultUser = mysqli_query($conn, $sqlUser); // Use the correct connection

if ($resultUser && mysqli_num_rows($resultUser) > 0) {
    $row = mysqli_fetch_assoc($resultUser);
} else {
    // Handle the case where user details are not found
    echo "User details not found!";
    exit;
}

// Now you can use $row['school_name'] to get the school name
$schoolName = $row['school'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Contract</title>

       <!-- 
    - favicon
  -->
  <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml">

<!-- 
  - custom css link
-->
<link rel="stylesheet" href="./assets/css/style.css">

<!-- 
  - google font link
-->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
  href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Comforter+Brush&family=Heebo:wght@400;500;600;700&display=swap"
  rel="stylesheet">
  <style>
    main {
        max-width: 800px;
        margin: 0 auto;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        padding: 100px;
        margin-top: 100px;
        margin-bottom: 100px;
    }
    footer {
        max-width: 800px;
        margin: 0 auto;
    }

    .print-button {
        display: block;
        margin-top: 20px;
        padding: 10px;
        background-color: hsl(209.88, 100%, 50%);
        color: white;
        text-align: center;
        text-decoration: none;
        font-size: 16px;
        cursor: pointer;
    }
    .contact-details-container {
    display: flex;
    justify-content: space-between;
    margin-top: 10px; /* Adjust the margin as needed */
}

.organizer-details,
.representative-details {
    width: 48%; /* Adjust the width as needed */
}

.organizer-details p,
.representative-details p {
  text-align: center;
}
.checkbox {
    display: flex;
    align-items: center;
}

.checkbox input {
    margin-right: 8px;
    width: 18px;
    height: 18px;
}


@media print {
      .no-print, #agreeCheckbox, #reserveButton {
            display: none !important;
        }
        body {
            margin: 0; 
        }

        main {
            page-break-before: always;
        }

        .contact-details-container {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .organizer-details,
        .representative-details {
            width: 48%;
            text-align: center;
        }

        .representative-details {
            float: right;
        }
    }
</style>
</head>
<body>
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
         </ul>

         
        <a href="logout.php" class="btn btn-secondary">Logout</a>

      </nav>
      </div>
   </header>
   
    <main>
        <div style="font-weight: bold;">
            <h2 style="text-align:center; color:red">TOUR CONTRACT INCENTIVES AND REBATES</h2>
            <p>SCHOOL NAME: <font color="red"><?= $schoolName ?></font></p>
            <p>DATE OF TOUR: <font color="red"><?= $tourDate ?></font></p>
            <!-- Rate Per Head -->
            <p>RATE PER HEAD: Php <font color="red"><?= $ratePerHeadFormatted ?>/Head</font></p>
            <p>Total Cost:<font color="red"> Php <?= number_format($totalCost, 2) ?></font></p>

            <br>
            <h3>PROVINCE: </h3>
            <span style="text-indent: 3rem"><?= $province ?></span>
            <br>
        </div>
        <h3>ITINERARIES:</h3>
        <div style="font-weight: bold;">
            <?php
            // Display travel itinerary
            if ($tourDetails && isset($tourDetails['travel_itinerary'])) {
                foreach ($tourDetails['travel_itinerary'] as $place):
            ?>
            <p style="text-indent: 3rem">&#8226; <?= $place ?></p>
            <?php
                endforeach;
            } else {
                echo '<p>No itinerary available</p>';
            }
            ?>
        </div>
<div style="padding-top: 3rem; margin-bottom:3rem; margin-left: 3rem; font-weight:bold">
    <h3 style="text-align:center">INCENTIVES AND REBATES</h3>
    <p style="color:red">MINIMUM OF 1 BUS with 45 PAYEE per UNIT</p>
    <p>2 teacher <font color="red">FREE</font> per bus with 45 payee each bus</p>
    <p>500 pesos meal allowance per attending teacher</p>
    <p>FREE VAN Transportation for the school TEACHER outing within north area baguio/ilocos/vigan/sagada/baler aurora (OVERNIGHT)</p>
    <p>South Area - Manila/Cavite/Rizal/Laguna</p>
    <p>Php 200.00 per head/payee for school rebate</p>
    <p>20 per head teacher rebate per advisory student that will join the trip</p>
</div>

<h3 style="text-align:center">TOUR PACKAGE INCLUSIONS</h3>
<div style="padding: 3rem;font-weight:bold">
    <p>Back and forth Transfer Via GENESIS BUS TRANSPORT</p>
    <p>Entrance Fees on the Venues to Visit</p>
    <p>1 Tourguide/Facilitator per bus to assist the student on every location that will be visited.</p>
    <p>1 medic every 3 busses</p>
    <p>2 head coordinators/head guide</p>
    <p>1 Tarpaulin per Bus</p>
    <p>Bus number and tour paraphernalia.</p>
    <p>First aid kit and over-the-counter medicine</p>
    <p>Alcohol and Sanitizers</p>
    <p>Games and prizes inside the bus</p>
</div>
<p>Should you need more information and clarification, please do not hesitate to call us thru our contact numbers. Thank you very much and we hope to be of service to you and your group! More power and God Bless!!!</p>
<br><br>
<p>With you in service,<br>
<div class="contact-details-container">
    <div class="organizer-details">

        ARNOLD GAYLA<br>
        Tour and Event Organizer<br>
        0454 Banaba street Balon Anito Mariveles, Bataan<br>
        Contact Numbers: 09122198673/09955920059<br>
        Email Add: norbertravel01@gmail.com</p>
    </div>

    <div class="representative-details">

        <?php
        $firstName = $row['first_name'];
        $lastName = $row['last_name'];

        // Convert first name and last name to uppercase
        $uppercaseFirstName = strtoupper($firstName);
        $uppercaseLastName = strtoupper($lastName);
        ?>

        <!-- Move the signature container above the printed name and signature paragraph -->
        <p><?= $uppercaseFirstName ?> <?= $uppercaseLastName ?></p>
        <p>School Representative</p>
    </div>
 
</div>
<br><br><br>
    <div class="checkbox">
        <input type="checkbox" id="agreeCheckbox" required >
        <label for="agreeCheckbox">I agree to the terms and conditions</label>
    </div>
    <br>    
    <button class="btn btn-primary" id="submitContract" onclick="submitContract()">Submit</button>
    <br><br>
    <button class="btn btn-primary" onclick="printMainContent()">Print Contract</button>
</main>


<script>
    function printMainContent() {
        window.print();
    }

    function submitContract() {
    // Check if the agreement checkbox is checked
    var agreeCheckbox = document.getElementById('agreeCheckbox');
    if (!agreeCheckbox.checked) {
        alert('Please agree to the terms and conditions before submitting.');
        return;
    }

    // Get user ID and province from the PHP variables
    var userId = <?php echo $_SESSION['user_id']; ?>;
    var province = '<?php echo $province; ?>';

    // Send a POST request to the server to update the database
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_confirmed.php', true);

    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    // Handle the response from the server
    xhr.onload = function () {
        if (xhr.status == 200) {
            // Display the server response (success or error message)
            alert(xhr.responseText);

            // Redirect to my_tour.php after successful updating
            window.location.href = 'my_tour.php';
        } else {
            alert('Error submitting the contract.');
        }
    };

    // Send the POST data to the server
    xhr.send('user_id=' + userId + '&province=' + encodeURIComponent(province));
}

</script>
</body>
</html>
