<?php
// Include the database connection file
include 'db_connection.php';

// Check if the booking_id parameter is set in the URL
if (isset($_GET['booking_id'])) {
    // Sanitize and retrieve the booking_id
    $booking_id = mysqli_real_escape_string($conn, $_GET['booking_id']);

    // Query to retrieve booking details with user's full name based on the booking_id
    $sql = "SELECT booking.*, CONCAT(user_form.first_name, ' ', IFNULL(user_form.middle_name, ''), ' ', user_form.last_name) AS full_name
            FROM booking
            INNER JOIN user_form ON booking.user_id = user_form.id
            WHERE booking.id = $booking_id";

    $result = mysqli_query($conn, $sql);

    // Check if the query was successful
    if ($result && mysqli_num_rows($result) > 0) {
        $bookingDetails = mysqli_fetch_assoc($result);
    } else {
        // Redirect or display an error if the booking_id is invalid or not found
        header("Location: admin_booking.php"); // Redirect to booking page
        exit();
    }
} else {
    // Redirect or display an error if the booking_id parameter is not set
    header("Location: admin_booking.php"); // Redirect to booking page
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the set_visit_date button was clicked
    if (isset($_POST['set_visit_date'])) {
        // Sanitize and retrieve the visit_date
        $visit_date = mysqli_real_escape_string($conn, $_POST['visit_date']);

        // Check if the tour_id already exists in tour_status
        $checkTourStatusSql = "SELECT COUNT(*) AS count FROM tour_status WHERE tour_id = $booking_id";
        $checkResult = mysqli_query($conn, $checkTourStatusSql);

        if ($checkResult) {
            $rowCount = mysqli_fetch_assoc($checkResult)['count'];

            if ($rowCount > 0) {
                // Tour_id already exists, update the school_visit_date
                $updateVisitDateSql = "UPDATE tour_status SET school_visit_date = '$visit_date' WHERE tour_id = $booking_id";
                $result = mysqli_query($conn, $updateVisitDateSql);

                if ($result) {
                    // Display a success message
                    echo "Visit date set successfully!";
                    // Redirect to admin_booking.php
                    header("Location: admin_booking.php");
                    exit();
                } else {
                    // Display an error message or handle the error as needed
                    echo "Error setting visit date: " . mysqli_error($conn);
                    echo "Query: " . $updateVisitDateSql;
                }
            } else {
                // Tour_id does not exist, insert a new record
                $insertTourStatusSql = "INSERT INTO tour_status (tour_id, status, school_visit_date, meeting_status, tour_finished)
                                        VALUES ($booking_id, 'processing', '$visit_date', 'pending', 0)";

                $insertResult = mysqli_query($conn, $insertTourStatusSql);

                if ($insertResult) {
                    // Display a success message
                    echo "Tour status record inserted successfully!";
                    // Redirect to admin_booking.php
                    header("Location: admin_booking.php");
                    exit();
                } else {
                    // Display an error message or handle the error as needed
                    echo "Error inserting tour status record: " . mysqli_error($conn);
                    echo "Query: " . $insertTourStatusSql;
                }
            }
        } else {
            // Display an error message or handle the error as needed
            echo "Error checking tour status: " . mysqli_error($conn);
            echo "Query: " . $checkTourStatusSql;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Add your head content here -->
</head>
<body>

    <!-- Add your header content here -->

    <!-- Booking Details Section -->
    <div>
        <h2>Booking Details</h2>

        <?php if (isset($bookingDetails)) : ?>
            <div>
                <!-- Display booking details here -->
            </div>
            <!-- Form to set visit date -->
            <form method="post" action="">
                <label for="visit_date">Set Visit Date:</label>
                <?php
                // Calculate one week before the booking date
                $oneWeekBeforeBookingDate = date('Y-m-d', strtotime('-1 week', strtotime($bookingDetails['date'])));

                // Set the default date to the current date
                $currentDate = date('Y-m-d');
                
                // Set the minimum date allowed to today
                $minDate = $currentDate;

                // Set the maximum date allowed to one week before the booking date
                $maxDate = $oneWeekBeforeBookingDate;

                // Check if a visit date exists and set the value attribute accordingly
                $existingVisitDate = isset($bookingDetails['school_visit_date']) ? date('Y-m-d', strtotime($bookingDetails['school_visit_date'])) : '';
                ?>
                <input type="date" id="visit_date" name="visit_date" value="<?php echo $existingVisitDate; ?>" min="<?php echo $minDate; ?>" max="<?php echo $maxDate; ?>" required>
                <button type="submit" name="set_visit_date">Set Visit Date</button>
            </form>

        <?php else : ?>
            <p>No booking details found.</p>
        <?php endif; ?>
    </div>

</body>
</html>
