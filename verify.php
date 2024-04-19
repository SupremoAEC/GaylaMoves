<!-- verify.php -->
<?php
require 'config.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle verification here
    if (isset($_POST['submit'])) {
        $verificationCode = mysqli_real_escape_string($conn, $_SESSION['verification_code']);

        // Validate the verification code
        $user_id = validateVerificationCode($conn, $verificationCode);

        if ($user_id !== false) {
            // Set user_id in the session
            // $_SESSION['user_id'] = $user_id;

            // Redirect based on user_type
            $selectUserType = "SELECT user_type FROM user_form WHERE id = $user_id";
            $resultUserType = mysqli_query($conn, $selectUserType);
            $rowUserType = mysqli_fetch_assoc($resultUserType);

            if ($rowUserType['user_type'] == 'school') {
                header('location: login.php');
            } elseif ($rowUserType['user_type'] == 'inspector') {
                header('location: login.php');
            }
            exit;
        } else {
            $error = 'Invalid verification code. Please try again.';
        }
    }
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Email Verification</title>
        <link rel="stylesheet" href="footer.css" />
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
                <div class="main__content">
                    <h1>GAYLA MOVES</h1>
                    <h2>Travel and Tours</h2>
                    <p>We travel to learn</p>
                </div>
                <div class="main__content">
                    <h1>Email Verification</h1>

                    <form action="" method="post">
                        <div class="notification-container">
                            <?php
                            if(isset($error)){
                                echo '<div class="notification error">'.$error.'</div>';
                            }
                            ?>
                        </div>

                        <label for="verification_code">Enter Verification Code:</label>
                        <input type="text" name="verification_code" required>
                        <input type="submit" name="submit" value="Verify" class="form-btn">
                    </form>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
}

// Function to validate the verification code and return user_id
function validateVerificationCode($conn, $verificationCode) {
    $selectUser = "SELECT id FROM user_form WHERE verification_code = '$verificationCode'";
    $resultUser = mysqli_query($conn, $selectUser);

    if ($row = mysqli_fetch_assoc($resultUser)) {
        // Update user as verified
        $userId = $row['id'];
        $updateVerification = "UPDATE user_form SET is_verified = 1, verification_code = NULL WHERE id = $userId";
        mysqli_query($conn, $updateVerification);

        return $userId;
    }

    return false;
}
?>
