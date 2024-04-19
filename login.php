<?php

require 'config.php';

session_start();

if (isset($_SESSION['user_id'])) {
    // Redirect to the homepage
    header('location: home.php');
    exit;
}


if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);

    // Check if the provided credentials match the default admin credentials
    if ($email == 'admin@gmail.com' && $password == md5('1234567890')) {
        $_SESSION['user_id'] = 0;
        $_SESSION['user_name'] = 'Admin';
        $_SESSION['user_type'] = 'admin';
        header('location: admin.php');
        exit;
    } else if ($email == 'superadmin@gmail.com' && $password == md5('1234567890')) {
        $_SESSION['user_id'] = 0;
        $_SESSION['user_name'] = 'Super Admin';
        $_SESSION['user_type'] = 'superadmin';
        header('location: superadmin/superadmin.php');
        exit;
    }

    // If not the admin, check the database for regular users
    $select = "SELECT id, name, user_type, is_verified FROM user_form WHERE email = ? AND password = ?";
    
    $stmt = mysqli_prepare($conn, $select);
    mysqli_stmt_bind_param($stmt, "ss", $email, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Check if the email is verified
        if ($row['is_verified'] == 1) {
            // Set user_id and user_name in the session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];

            // Redirect based on user_type
            if ($row['user_type'] == 'admin') {
                header('location: admin.php'); 
            } elseif ($row['user_type'] == 'school') {
                header('location: home.php'); 
            } elseif ($row['user_type'] == 'inspector') {
                header('location: inspector/i_home.php'); 
            }
            exit;
        } else {
            // Email not verified, show notification
            $error = 'Email not verified. Please check your email and click on the verification link.';
        }
    } else {
        $error = 'Incorrect email or password!';
    }

    mysqli_stmt_close($stmt);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>

    <script src="https://kit.fontawesome.com/53886a703e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles.css" /> 
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
            <div class="main__content__title">
                <h1>GAYLA MOVES</h1>
                <h2>Travel and Tours</h2>
                <p>We travel to learn</p>
            </div>
            <div class="main__content">
                <h1>Login Now</h1>

                <form action="" method="post">
                    <div class="notification-container">
                        <?php
                        if(isset($error)){
                            echo '<div class="notification error">'.$error.'</div>';
                        }
                        ?>
                    </div>

                    <input type="email" name="email" required placeholder="Enter your email"><br>
                    <input type="password" name="password" required placeholder="Enter your password"><br>
                    <input type="submit" name="submit" value="Login now" class="form-btn">
                    <p>Don't have an account? <a href="signupp.php">Register now</a></p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
