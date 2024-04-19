<!-- db_connection.php -->
<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "tour_recommendation";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
