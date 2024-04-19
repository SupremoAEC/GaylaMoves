<!-- recommend.php -->
<?php
include_once('db_connection.php');

$categoryQuery = "SELECT * FROM categories";
$categoryResult = mysqli_query($conn, $categoryQuery);

if (!$categoryResult) {
    die("Category Query Error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Recommendation Form</title>

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

  <style>
    .form-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 50px;
        text-align: center;
    }

    .section-subtitle{
        padding: 3rem;
    }

    label {
        display: block;
        font-weight: bold;
        font-size: 16px;
        margin-bottom: 5px;
        color: #333;
        text-align: left;
    }

    input[type="checkbox"],
    input[type="number"] {
        margin-bottom: 10px;
        text-align: left;
    }

    input[type="checkbox"] {
        margin-left: 5px;
    }

    input,
    select {
        width: calc(100% - 22px);
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    button {
        background-color: #5cb85c;
        color: white;
        padding: 10px 15px;
        border: none;
        font-size: 16px;
        cursor: pointer;
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
         <a href="logout.php" class="btn btn-danger">Logout</a>

        <!-- <a href="recommend.php" class="btn btn-secondary">Recommend Now</a> -->

      </nav>
      </div>
   </header>
<main>
<div class="form-container">
<form action="recommendations.php" method="POST">
<p class="section-subtitle">Tour Recommendation</p>

    <label for="first_category">First Category Priority:</label>
    <select name="first_category" id="first_category">
        <?php
        // Populate dropdown with categories
        mysqli_data_seek($categoryResult, 0);
        while ($row = mysqli_fetch_assoc($categoryResult)) {
            echo "<option value='{$row['name']}'>{$row['name']}</option>";
        }
        ?>
    </select>
    <br><br>
    <label for="second_category">Second Category Priority:</label>
    <select name="second_category" id="second_category">
        <?php
        // Reset pointer to the beginning of the category result
        mysqli_data_seek($categoryResult, 0);
        while ($row = mysqli_fetch_assoc($categoryResult)) {
            echo "<option value='{$row['name']}'>{$row['name']}</option>";
        }
        ?>
    </select>
    <br><br>
    <label for="third_category">Third Category Priority:</label>
    <select name="third_category" id="third_category">
        <?php
        // Reset pointer to the beginning of the category result
        mysqli_data_seek($categoryResult, 0);
        while ($row = mysqli_fetch_assoc($categoryResult)) {
            echo "<option value='{$row['name']}'>{$row['name']}</option>";
        }
        ?>
    </select>
    <br><br>



<button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>


</main>



</body>
</html>