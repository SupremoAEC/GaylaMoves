<?php
include_once 'db_connection.php';
include_once 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page or handle unauthorized access
    header('location: login_form.php');
    exit;
}

// Retrieve user_id from the session
$user_id = $_SESSION['user_id'];

// Check if the form is submitted
if (isset($_POST['submit_booking'])) {
    // Your existing code for user authentication and form validation here

    // Retrieve other data from the form or URL
    $tourDate = isset($_POST['date']) ? $_POST['date'] : 'N/A';
    $expectedGoers = isset($_POST['expected_goers']) ? $_POST['expected_goers'] : 0;
    $totalCost = isset($_POST['total_cost_hidden']) ? $_POST['total_cost_hidden'] : 0;
    $ratePerHead = isset($_POST['rate_per_head']) ? $_POST['rate_per_head'] : 0; // Added this line
    $province = isset($_GET['province']) ? $_GET['province'] : 'N/A';

    // Initialize variables for bus and van
    $bus = 0;
    $van = 0;

    // Check user's choice and update bus and van accordingly
    if (isset($_POST['vehicle_choice'])) {
        $vehicleChoice = $_POST['vehicle_choice'];

        if ($vehicleChoice == '1_bus') {
            $bus = $expectedGoers > 0 ? ceil($expectedGoers / 49) : 0;
        } elseif ($vehicleChoice == '2_vans') {
            $van = $expectedGoers > 0 ? ceil($expectedGoers / 15) : 0;
        }
    } elseif ($expectedGoers <= 15) {
        // If expected goers are 15 or under, set 1 van
        $van = 1;
    } else {
        // Calculate the number of buses needed for 31 or more expected goers
        $busesNeeded = ceil($expectedGoers / 49);
        $bus = $busesNeeded;
    }

    // Save booking details to the database using MySQLi
    $insertBookingQuery = "INSERT INTO tour_recommendation.booking (user_id, date, goers, bus, van, total_cost, per_head, province)
    VALUES ('$user_id', '$tourDate', '$expectedGoers', '$bus', '$van', '$totalCost', '$ratePerHead', '$province')"; 

    $insertBookingResult = mysqli_query($conn, $insertBookingQuery);

    if ($insertBookingResult) {
        // Booking details successfully saved
        // Redirect to contract.php
        header('location: my_tour.php');
        exit;
    } else {
        // Handle the case where the booking details couldn't be saved
        $error = mysqli_error($conn);
        echo "Error: $error";
        error_log("Error saving booking details to database: $error");
        echo "<br>Query: $insertBookingQuery"; // Add this line
    }
} else {
    // Handle the case where the form is not submitted
    echo "Form not submitted.";
}
?>
