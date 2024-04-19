<!-- user_profile.php -->

<?php
@include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location: login_form.php');
    exit;
}

// Check for success message in the session
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
unset($_SESSION['success_message']);  // Clear the success message from the session

$sql = "SELECT * FROM user_form WHERE id = {$_SESSION['user_id']}";
$result = mysqli_query($conn, $sql);

if ($result) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "Error: " . mysqli_error($conn);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Your existing code for updating user details
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Profile</title>

   <!-- 
    - favicon
  -->
  <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml">

<!-- 
  - custom css link
-->
<link rel="stylesheet" href="./assets/css/style.css">

<!-- 
  - google font link
-->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
  href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Comforter+Brush&family=Heebo:wght@400;500;600;700&display=swap"
  rel="stylesheet">
  <script>
        // JavaScript function to toggle between display and edit modes
        function toggleEditMode() {
            var displaySection = document.getElementById('displaySection');
            var editSection = document.getElementById('editSection');

            displaySection.style.display = displaySection.style.display === 'none' ? '' : 'none';
            editSection.style.display = editSection.style.display === 'none' ? '' : 'none';
        }

        // JavaScript function to display success message if available
        window.onload = function () {
            var successMessage = '<?= $success_message ?>';
            if (successMessage) {
                alert(successMessage);
            }
        };
    </script>

   
  <style>
    main {
        padding: 40px 0;
        background-color: #f5f5f5;
    }

    .user-profile {
        max-width: 600px;
        margin: 0 auto;
        background-color: #f5f5f5;
        padding: 20px;border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .section-subtitle{
        text-align: center;
        margin: 30px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        font-size: 1.5rem;
    }

    input,
    select {
        width: 100%;
        padding: 8px;
        margin-bottom: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    button,
    .save-changes-btn {
        background-color: var(--viridian-green);
        color: var(--white-1);
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
    }

    button:is(:hover, :focus),
    .save-changes-btn:is(:hover, :focus) {
        background-color: var(--white-1);
        color: var(--viridian-green);
    }
</style>

</head>
<body>
   <!-- Header -->
   <header class="header" data-header>
      <div class="container">
         <a href="#">
            <h1 class="logo">Gayla Moves</h1>
         </a>
      

      <button class="nav-toggle-btn" data-nav-toggle-btn aria-label="Toggle Menu">
        <ion-icon name="menu-outline" class="open"></ion-icon>
        <ion-icon name="close-outline" class="close"></ion-icon>
      </button>
      

      <nav class="navbar">
         <ul class="navbar-list">
            <li>
               <a href="home.php" class="navbar-link">Home</a>
            </li>
            <li>
               <a href="tours.php" class="navbar-link">Tours</a>
            </li>
            <li>
               <a href="aboutus.php" class="navbar-link">About Us</a>
            </li>
            <li>
              <a href="user_profile.php" class="navbar-link">User Profile</a>
            </li>
            <li>
              <a href="rating/rating.php" class="navbar-link">Ratings</a>
            </li>
            <li>
              <a href="my_tour.php" class="navbar-link">My Tours</a>
            </li>
         </ul>
         </ul>

        <a href="logout.php" class="btn btn-danger">Logout</a>

      </nav>
      </div>
   </header>
<main>

    <section class="section user-profile">
        <div class="container">
        <p class="section-subtitle">User Profile</p>
            

            <?php
                // Display error message if available
                $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
                unset($_SESSION['error_message']);  // Clear the error message from the session
                ?>

                <?php if (!empty($error_message)) : ?>
                    <div class="error-msg"><?= $error_message ?></div>
                <?php endif; ?>

            <!-- Display section -->
            <div id="displaySection">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" value="<?= $user['first_name'] ?>" readonly>

                <label for="middle_name">Middle Name:</label>
                <input type="text" name="middle_name" value="<?= $user['middle_name'] ?>" readonly>

                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" value="<?= $user['last_name'] ?>" readonly>

                <!-- <label for="email">Email:</label>
                <input type="email" name="email" value="<?= $user['email'] ?>" readonly> -->

                <label for="birthdate">Birthdate:</label>
                <input type="date" name="birthdate" value="<?= $user['birthdate'] ?>" readonly>

                <label for="gender">Gender:</label>
                <input type="text" name="gender" value="<?= $user['gender'] ?>" readonly>

                <label for="phone">Phone:</label>
                <input type="tel" name="phone" value="<?= $user['phone'] ?>" readonly>

                <label for="school">School:</label>
                <input type="text" name="school" value="<?= $user['school'] ?>" readonly>

                <label for="school_address">School Address:</label>
                <input type="text" name="school_address" value="<?= $user['school_address'] ?>" readonly>

                <!-- "Edit" button to toggle edit mode -->
                <button type="button" onclick="toggleEditMode()">Edit</button>
            </div>

            <!-- Edit section -->
            <form method="post" action="update_user.php" id="editSection" style="display: none;">
                <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">

                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" value="<?= $user['first_name'] ?>" required>
                
                <label for="middle_name">Middle Name:</label>
                <input type="text" name="middle_name" value="<?= $user['middle_name'] ?>" required>
                
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" value="<?= $user['last_name'] ?>" required>

                <!-- <label for="email">Email:</label>
                <input type="email" name="email" value="<?= $user['email'] ?>" required> -->

                <label for="birthdate">Birthdate:</label>
                <input type="date" name="birthdate" value="<?= $user['birthdate'] ?>" required>

                <label for="gender">Gender:</label>
                <select name="gender" required>
                    <option value="male" <?= ($user['gender'] == 'male') ? 'selected' : '' ?>>Male</option>
                    <option value="female" <?= ($user['gender'] == 'female') ? 'selected' : '' ?>>Female</option>
                    <option value="other" <?= ($user['gender'] == 'other') ? 'selected' : '' ?>>Other</option>
                </select>

                <label for="phone">Phone:</label>
                <input type="tel" name="phone" value="<?= $user['phone'] ?>" required>

                <label for="school">School:</label>
                <input type="text" name="school" value="<?= $user['school'] ?>" required>

                <label for="school_address">School Address:</label>
                <input type="text" name="school_address" value="<?= $user['school_address'] ?>" required>

                <!-- "Save Changes" button -->
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </section>
</main>
</body>
</html>