<!-- 
update_user.php -->
<?php
require 'config.php';  // Include your database connection
session_start();       // Start the session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data and perform validation
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($conn, $_POST['middle_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $school = mysqli_real_escape_string($conn, $_POST['school']);
    $school_address = mysqli_real_escape_string($conn, $_POST['school_address']);

    // Validate age restriction (assuming birthdate is in the format YYYY-MM-DD)
    $currentDate = date('Y-m-d');
    $age = date_diff(date_create($birthdate), date_create($currentDate))->y;

    if ($age < 18) {
        // Set an error message in the session
        $_SESSION['error_message'] = "You must be at least 18 years old to update your profile.";
        header('location: user_profile.php');
        exit;
    }

    // Check if the combination of first name and last name already exists
    $id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

    $checkNameQuery = "SELECT * FROM user_form WHERE first_name = '$first_name' AND middle_name = '$middle_name' AND last_name = '$last_name' AND id != $id";
    $checkNameResult = mysqli_query($conn, $checkNameQuery);

    if (mysqli_num_rows($checkNameResult) > 0) {
        // Set an error message in the session
        $_SESSION['error_message'] = "The combination of first name, middle name, and last name already exists.";
        header('location: user_profile.php');
        exit;
    }

    // Check if the phone number is exactly 11 digits
    if (!preg_match("/^\d{11}$/", $phone)) {
        // Set an error message in the session
        $_SESSION['error_message'] = 'Phone number must be exactly 11 digits.';
        header('location: user_profile.php');
        exit;
    }

    // Check if the phone number is already taken
    $checkPhoneQuery = "SELECT * FROM user_form WHERE phone = '$phone' AND id != $id";
    $checkPhoneResult = mysqli_query($conn, $checkPhoneQuery);

    if (mysqli_num_rows($checkPhoneResult) > 0) {
        // Set an error message in the session
        $_SESSION['error_message'] = 'Phone number is already taken.';
        header('location: user_profile.php');
        exit;
    }

    // Update user details in the database
    $sql = "UPDATE user_form SET
            first_name = '$first_name',
            middle_name = '$middle_name',
            last_name = '$last_name',
            birthdate = '$birthdate',
            gender = '$gender',
            phone = '$phone',
            school = '$school',
            school_address = '$school_address'
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        // Set a success message in the session
        $_SESSION['success_message'] = "Your profile has been updated successfully!";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

    // Redirect back to user_profile.php
    header('location: user_profile.php');
    exit;
}
?>

