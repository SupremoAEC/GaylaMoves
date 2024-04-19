<!-- config.php -->
<?php

// $conn = mysqli_connect('localhost', 'root', '', 'user_db');
$conn = mysqli_connect('localhost', 'root', '', 'tour_recommendation');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
 