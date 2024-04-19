<?php
include_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve user ID and province from the POST data
    $userId = $_POST['user_id'];
    $province = $_POST['province'];

    // Switch to the 'tour_recommendation' database
    mysqli_select_db($conn, 'tour_recommendation');

    // Update the confirmed field to 1 for the given user ID and province
    $sqlUpdate = "UPDATE booking SET confirmed = 1 WHERE user_id = $userId AND province = '$province'";
    $resultUpdate = mysqli_query($conn, $sqlUpdate);

    if ($resultUpdate) {
        echo 'Update successful';
    } else {
        echo 'Error updating the database: ' . mysqli_error($conn);
    }
} else {
    echo 'Invalid request';
}
?>
