<!-- my_tour.php -->
<?php
session_start(); // Add this line to start the session

// Include your database connection files
include('config.php');
include('db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or handle accordingly
    header("Location: login.php");
    exit();
}

// Get the user ID
$userId = $_SESSION['user_id']; // Assuming you store user ID in a session

// Fetch user details from user_db
$userQuery = "SELECT * FROM user_form WHERE id = $userId";
$userResult = mysqli_query($conn, $userQuery);

// Check if user exists
if (!$userResult) {
    echo "Error fetching user details: " . mysqli_error($conn);
    exit();
}

$user = mysqli_fetch_assoc($userResult);

// Fetch tours for the user from tour_recommendation
$tourQuery = "SELECT * FROM booking WHERE user_id = $userId";
$tourResult = mysqli_query($conn, $tourQuery);

// Check if tour query is successful
if (!$tourResult) {
    echo "Error fetching tour details: " . mysqli_error($conn);
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tours</title>
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
    .main_container{
       max-width: 800px;
        margin: 0 auto;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        padding: 10px;
    }
    .details{
      border-style: ridge;
      padding: 1rem;
    }
    h2{
      text-align: center;
      padding: 5rem;
      font-size: 5rem;
      letter-spacing: 5px;
      font-family: var(--ff-abril-fatface);
    }
    .section-subtitle{
      text-align: center;
      padding: 20px;
    }

    .navigation-bar {
        display: flex;
        justify-content: center; 
        padding: 10px;
    }

    .navigation-bar ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        display: flex; 
    }

    .navigation-bar li {
        margin-right: 10px;
    }

    .navigation-bar a {
        text-decoration: none;
        color: #333;
        padding: 5px 10px;
        border-radius: 5px;
    }

    .navigation-bar a:hover {
        background-color: #ddd;
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
         </ul>

        <a href="logout.php" class="btn btn-danger">Logout</a>

      </nav>
      </div>
   </header>
    <main>
    <p class="section-subtitle">My Tours</p>
      <div class="navigation-bar">
        <ul>
            <li><a href="my_tour.php" data-category="all">All</a></li>
            <li><a href="./mytour/aggrement.php" data-category="contract-agreement">Contract Agreement</a></li>
            <li><a href="./mytour/scheduled.php" data-category="scheduled">Scheduled for School Visit</a></li>
            <li><a href="./mytour/bookingstatus.php" data-category="accepted">Booking Accepted</a></li>
            <li><a href="./mytour/torate.php" data-category="to-rate">To Rate</a></li>
        </ul>
      </div>


      <div class="main_container">
            <?php
          while ($tour = $tourResult->fetch_assoc()) {
            echo '<div class="details">';
            echo "Tour Date: " . $tour['date'] . "<br>";
            echo "Province: " . $tour['province'] . "<br>";

      if ($tour['confirmed'] == 0) {
          // If not confirmed, display "Contract not signed" and provide a link to contract.php
          echo "Status: Agreement not done <br>";
          // Use the correct booking ID for each tour
          echo '<a href="contract.php?booking_id=' . $tour['id'] . '&province=' . $tour['province'] . '">Review Agreement</a><br>';
      } else {
          // If confirmed, fetch 'school_visit_date' and 'meeting_status' from 'tour_status' table
          $tourStatusQuery = "SELECT school_visit_date, meeting_status FROM tour_status WHERE tour_id = " . $tour['id'];
          $tourStatusResult = mysqli_query($conn, $tourStatusQuery);

          if (!$tourStatusResult) {
              echo "Error fetching tour status: " . mysqli_error($conn);
              exit();
          }

            // Fetch the 'school_visit_date', 'meeting_status', and 'tour_finished' values
            $tourStatusQuery = "SELECT school_visit_date, meeting_status, tour_finished FROM tour_status WHERE tour_id = " . $tour['id'];
            $tourStatusResult = mysqli_query($conn, $tourStatusQuery);

            if (!$tourStatusResult) {
                echo "Error fetching tour status: " . mysqli_error($conn);
                exit();
            }

            // Fetch the 'school_visit_date', 'meeting_status', and 'tour_finished' values
            $tourStatus = mysqli_fetch_assoc($tourStatusResult);

            // Check if $tourStatus is not null
            if ($tourStatus !== null) {
              // Display status based on 'meeting_status'
              if ($tourStatus['meeting_status'] === 'pending') {
                  // If meeting_status is pending, check if school_visit_date is null or not
                  if ($tourStatus['school_visit_date'] === null) {
                      echo "Status: Pending (Please wait for confirmation)<br>";
                  } else {
                      echo "<br>Status: Pending (Our tour manager is scheduled for School visit on " . $tourStatus['school_visit_date'] . ")<br>";
                  }
              } elseif ($tourStatus['meeting_status'] === 'accepted') {
                  // If meeting_status is accepted, check if tour_finished is 1
                  if ($tourStatus['tour_finished'] == 1) {
                      // If tour_finished is 1, do not display anything
                  } else {
                      // If tour_finished is not 1, display the message
                      echo "<br>Your booking is accepted! <br>";
                      echo '<a href="./mytour/view_inspection.php?booking_id=' . $tour['id'] . '" class="view-inspection-link">View Vehicle Inspection</a>';

                  }
              } elseif ($tourStatus['meeting_status'] === 'cancelled') {
                  // If meeting_status is cancelled, show: Sorry your booking is cancelled
                  echo "<br>Sorry, your booking is cancelled <br>";
              }
            } else {
              // If $tourStatus is null, display a generic message
              echo "<br>Status: Booking Confirmed (Please wait until one of our tour managers sets a School visit)<br>";
            }


            // Check if 'tour_finished' is 1 and if there is a record in the ratings table
          if ($tourStatus !== null && array_key_exists('tour_finished', $tourStatus)) {
            if ($tourStatus['tour_finished'] == 1) {
                // Query to check if there is a record in the ratings table for the current tour_id
                $ratingCheckQuery = "SELECT * FROM ratings WHERE tour_id = " . $tour['id'];
                $ratingCheckResult = mysqli_query($conn, $ratingCheckQuery);

                if (!$ratingCheckResult) {
                    echo "Error checking rating status: " . mysqli_error($conn);
                    exit();
                }

                // Check if there is a record in the ratings table
                if (mysqli_num_rows($ratingCheckResult) > 0) {
                    // If there is a record, do not display the rating link
                    echo "<br>Your tour is finished! Thank you for providing feedback.<br>";
                } else {
                    // If there is no record, display the rating link
                    echo "<br>Your tour is finished! Kindly rate and provide feedback <a href='rating/rating_form.php?tour_id=" . $tour['id'] . "'>here</a>.<br>";
                }

                // Free the result of the rating check query
                mysqli_free_result($ratingCheckResult);
            }
          }


          // Close the $tourStatusResult since it is no longer needed
          mysqli_free_result($tourStatusResult);
      }
      echo "</div>";
      // Display other tour details as needed
      echo "<hr>";
    }

    // Close database connection after all queries are done
    mysqli_close($conn);
    ?>
        </div>  
        </div>
  </main>

    
</body>
</html>