<?php
// Include the database connection file
include 'db_connection.php';

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to retrieve all bookings with user details
$sql = "SELECT booking.*, 
               CONCAT(user_form.first_name, ' ', IFNULL(user_form.middle_name, ''), ' ', user_form.last_name) AS username, 
               user_form.school, 
               tour_status.school_visit_date,
               IFNULL(tour_status.meeting_status, 'pending') AS meeting_status,
               tour_status.tour_finished
        FROM booking
        INNER JOIN user_form ON booking.user_id = user_form.id
        LEFT JOIN tour_status ON booking.id = tour_status.tour_id
        WHERE booking.confirmed = 1
        ORDER BY booking.date ASC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>booking</title>

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
  /* Add custom CSS styles for the box */
  .booking-box {
    border: 1px solid #ccc;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
    
  }.visit-date-box {
    /* border: 1px solid #ccc; */
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
  }

  /* Style for the meeting status buttons */
  .meeting-status-buttons {
    margin-top: 10px;
  }

  .btn-confirm {
    background-color: #4CAF50;
    color: white;
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-right: 10px;
  }

  .btn-cancel {
    background-color: #f44336;
    color: white;
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
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
               <a href="admin.php" class="navbar-link">Home</a>
            </li>
            <li>
               <a href="admin_panel.php" class="navbar-link">Tour Organizer</a>
            </li>
            <li>
               <a href="admin_booking.php" class="navbar-link">Booking</a>
            </li>
            <li>
               <a href="#" class="navbar-link">About Us Organizer</a>
            </li>
            <li>
              <a href="#" class="navbar-link">Vehicle Maintenance</a>
            </li>
         </ul>
         </ul>

         <a href="logout.php" class="btn btn-secondary">Log Out</a>
      </nav>
      </div>
   </header>

   <!-- Booking Display Section -->
   <div class="container">
        <h2>All Bookings</h2>

        <?php
        // Check if there are any bookings
        if (mysqli_num_rows($result) > 0) {
            // Output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                // Display booking information with user details
                echo "<div class='booking-box'>";
                echo "<p>Province: " . $row['province'] . "</p>";
                echo "<p>School: " . $row['school'] . "</p>";
                echo "<p>User Name: " . $row['username'] . "</p>";
                echo "<p>Date: " . $row['date'] . "</p>";
                  
                // Display School Visit Date in a separate div box
                echo "<div class='visit-date-box'>";
                echo "<p>School Visit Date:</p>";

                if ($row['meeting_status'] == 'pending') {
                    if (isset($row['school_visit_date']) && $row['school_visit_date']) {
                        echo "<p>" . $row['school_visit_date'] . "</p>";
                        echo "<p><a href='admin_date.php?booking_id=" . $row['id'] . "'>Change Date</a></p>";
                    } else {
                        // Provide a link to set the date
                        echo "<p>No set date</p>";
                        echo "<p><a href='admin_date.php?booking_id=" . $row['id'] . "'>Set Date</a></p>";
                    }
                } else {
                    // If meeting status is not 'pending', hide the buttons
                    echo "<p>" . $row['school_visit_date'] . "</p>";
                }

                echo "</div>";

                 // Display content based on meeting status and tour_finished
                echo "<div class='meeting-status-buttons'>";
                if ($row['meeting_status'] == 'pending') {
                    echo "<button class='btn btn-primary' onclick='setMeetingStatus(" . $row['id'] . ", \"accepted\")'>Confirm</button>";
                    echo "<button class='btn btn-secondary' onclick='setMeetingStatus(" . $row['id'] . ", \"cancelled\")'>Cancel</button>";
                } elseif ($row['meeting_status'] == 'accepted') {
                    if ($row['tour_finished'] == 0) {
                        echo "<p>Tour Confirmed</p>";
                        echo "<br><button class='btn btn-primary' onclick='finishTour(" . $row['id'] . ")'>Finish Tour</button>";
                    } else {
                        echo "<p>Tour is finished</p>";
                    }
                } elseif ($row['meeting_status'] == 'cancelled') {
                    echo "<p>Tour Cancelled</p>";
                }
                echo "</div>";



                echo "</div>";
            }
        } else {
            echo "No bookings found";
        }

        // Close the database connection
        mysqli_close($conn);
        ?>
    </div>
    
    <script>
        function setMeetingStatus(bookingId, status) {
            // You can use AJAX or other methods to send the update to the server
            // For simplicity, this example uses a confirmation prompt
            var confirmation = confirm("Are you sure you want to set the meeting status to " + status + "?");

            if (confirmation) {
                // You can perform the actual update using AJAX or form submission
                // For simplicity, this example redirects to a PHP script
                window.location.href = 'admin/update_meeting_status.php?booking_id=' + bookingId + '&status=' + status;
            }
        }

        function finishTour(bookingId) {
        var confirmation = confirm("Are you sure you want to finish the tour?");

        if (confirmation) {
            window.location.href = 'admin_finish_tour.php?booking_id=' + bookingId;
        }
    }
    </script>
</body>
</html>
