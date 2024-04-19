<?php
// Include the database connection file
include 'db_connection.php';

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the booking ID is provided in the URL
if (isset($_GET['booking_id'])) {
    $bookingId = $_GET['booking_id'];

    // Update the database to set tour_finished to 1 for the specified booking ID
    $updateSql = "UPDATE tour_status SET tour_finished = 1 WHERE tour_id = $bookingId";

    if (mysqli_query($conn, $updateSql)) {
        echo "Tour marked as finished successfully.";

        // Redirect to admin_booking.php after successful update
        header("Location: admin_booking.php");
        exit(); // Ensure that no further code is executed after the redirect
    } else {
        echo "Error updating tour status: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request. Booking ID not provided.";
}

mysqli_close($conn);
?>
