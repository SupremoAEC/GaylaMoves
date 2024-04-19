<!-- signupp.php -->
<?php

session_start(); 

require 'config.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

require "vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$first_name = '';
$middle_name = '';
$last_name = '';
$username = '';
$birthdate = '';
$gender = '';
$phone = '';
$email = '';
$pass = '';
$cpass = '';
$school = '';
$school_address = '';
$user_type = '';

$errors = [];

if (isset($_POST['submit'])) {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($conn, $_POST['middle_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = md5($_POST['password']);
    $cpass = md5($_POST['cpassword']);
    $school = mysqli_real_escape_string($conn, $_POST['school']);
    $school_address = mysqli_real_escape_string($conn, $_POST['school_address']);
    $user_type = $_POST['user_type'];

    // Check if the combination of first name, middle name and last name already exists
    $selectName = "SELECT * FROM user_form WHERE first_name = '$first_name' AND middle_name = '$middle_name' AND last_name = '$last_name'"; 
    $resultName = mysqli_query($conn, $selectName);
    if (mysqli_num_rows($resultName) > 0) {
        $errors['name'] = 'User with this name already exists.';
    }

    // Check if username already exists
    $selectUsername = "SELECT * FROM user_form WHERE username = '$username'";
    $resultUsername = mysqli_query($conn, $selectUsername);
    if (mysqli_num_rows($resultUsername) > 0) {
        $errors['username'] = 'Username already exists.';
    }

    // Validate age restriction (assuming birthdate is in the format YYYY-MM-DD)
    $birthdate_timestamp = strtotime($birthdate);
    $min_age_timestamp = strtotime('-18 years');

    if ($birthdate_timestamp > $min_age_timestamp) {
        $errors['birthdate'] = 'You must be at least 18 years old to register.';
    }

    // Check if the phone number is exactly 11 digits
    if (!preg_match("/^\d{11}$/", $phone)) {
        $errors['phone'] = 'Phone number must be exactly 11 digits.';
    }

    // Check if the phone number is already taken
    $selectPhone = "SELECT * FROM user_form WHERE phone = '$phone'";
    $resultPhone = mysqli_query($conn, $selectPhone);

    if (mysqli_num_rows($resultPhone) > 0) {
        $errors['phone'] = 'Phone number is already taken.';
    }

    // Check if the email is already taken
    $selectEmail = "SELECT * FROM user_form WHERE email = '$email'";
    $resultEmail = mysqli_query($conn, $selectEmail);

    // Check if email is already taken
    if (mysqli_num_rows($resultEmail) > 0) {
        $errors['email'] = 'Email is already taken.';
    }

    if ($pass != $cpass) {
        $errors['password'] = 'Password not matched!';
    }

    if (empty($errors)) {
        // Generate a random verification code
        $verificationCode = substr(bin2hex(random_bytes(4)), 0, 8); // Generate an 8-character verification code
    
        // Insert the new user with the verification code into the database
        $insert = "INSERT INTO user_form(first_name, middle_name, last_name, username, birthdate, gender, phone, email, password, school, school_address, user_type, is_verified, verification_code) VALUES('$first_name','$middle_name','$last_name','$username','$birthdate','$gender','$phone','$email','$pass','$school','$school_address','$user_type', 0, '$verificationCode')";
        mysqli_query($conn, $insert);
        
    
        // Set the verification code in the session
        $_SESSION['verification_code'] = $verificationCode;
    
        // Send verification email using PHPMailer
        $mail = new PHPMailer(true);
    
        try {
            

            $mail->SMTPDebug = 2; // Enable verbose debug output
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Your email provider's SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = 'supremoaecaec@gmail.com'; // Your Gmail email address
            $mail->Password   = 'mkwehimhgepbgpqm';    // Your Gmail email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            //$mail->SMTPSecure = 'tls';
            $mail->Port       = 587; // Use the appropriate port (587 for TLS, 465 for SSL)
    
            //Recipients
            $mail->setFrom('GaylaMoves@gmail.com', 'GaylaMoves');
            $mail->addAddress($email, $first_name . ' ' . $last_name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification - GAYLA MOVES';
            $mail->Body    = "$verificationCode is your code for GaylaMoves Email Validation";
            
            // Set the SMTP options to disable SSL verification
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->send();
    
            // Redirect to the verification page
            header('location: verify.php');
            exit;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Form</title>

    <script src="https://kit.fontawesome.com/53886a703e.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="styles.css" />

    <style>
        .input-field {
            width: 300px;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .error-msg {
            color: red;
        }
    </style>
</head>
<body>
    
    <!-- Navbar Section -->
    <nav class="navbar">
        <div class="navbar__container">
            <a href="/" id="navbar__logo"><i class="fa-solid fa-person-walking-luggage"></i>  GAYLA MOVES</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="main">
        <div class="main__container">
            <div class="main__content">
                <h1>GAYLA MOVES</h1>
                <h2>Travel and Tours</h2>
                <p>We travel to learn</p>
            </div>
            <div class="main__content">
                <h1>Register Now</h1>

                <form action="" method="post">
                    <?php
                    if (!empty($errors)) {
                        foreach ($errors as $errorMsg) {
                            echo '<span class="error-msg">' . $errorMsg . '</span>';
                        }
                    }
                    ?>
                    <input type="text" name="first_name" required placeholder="Enter your first name" value="<?php echo htmlspecialchars($first_name); ?>"><br>
                    <input type="text" name="middle_name" placeholder="Enter your middle name" value="<?php echo htmlspecialchars($middle_name); ?>"><br>
                    <input type="text" name="last_name" required placeholder="Enter your last name" value="<?php echo htmlspecialchars($last_name); ?>"><br>
                    <input type="text" name="username" required placeholder="Enter your username" value="<?php echo htmlspecialchars($username); ?>"><br>
                    <label for="birthdate">Birthdate:</label><br>
                    <input type="date" name="birthdate" required class="input-field" value="<?php echo htmlspecialchars($birthdate); ?>"><br>
                    <label for="gender">Gender:</label><br>
                        <select name="gender" required>
                            <option value="" disabled selected>Select Gender</option>
                            <option value="male" <?php echo ($gender === 'male') ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?php echo ($gender === 'female') ? 'selected' : ''; ?>>Female</option>
                            <option value="other" <?php echo ($gender === 'other') ? 'selected' : ''; ?>>Other</option>
                        </select><br>
                    <input type="text" name="phone" required placeholder="Enter your phone number" value="<?php echo htmlspecialchars($phone); ?>"><br>
                    <input type="email" name="email" required placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>"><br>
                    <input type="password" name="password" required placeholder="Enter your password"><br>
                    <input type="password" name="cpassword" required placeholder="Confirm your password"><br>
                    <input type="text" name="school" required placeholder="Enter your school" value="<?php echo htmlspecialchars($school); ?>"><br>
                    <input type="text" name="school_address" required placeholder="Enter your school address" value="<?php echo htmlspecialchars($school_address); ?>"><br>
                    <select name="user_type">
                        <option value="school" <?php echo ($user_type === 'school') ? 'selected' : ''; ?>>School Representative</option>
                        <option value="inspector" <?php echo ($user_type === 'inspector') ? 'selected' : ''; ?>>Inspector</option>
                    </select><br>
                    <input type="submit" name="submit" value="Register now" class="form-btn">
                    <p>Already have an account? <a href="login.php">Login now</a></p>
                </form>
            </div>
        </div>
    </div>

    <script src="app.js"></script>
</body>
</html>
