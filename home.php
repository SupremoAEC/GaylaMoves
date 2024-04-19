<?php

@include 'config.php';

session_start();

if(!isset($_SESSION['user_name'])){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

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

      </nav>
      </div>
   </header>

   <main>
    <article>

      <!-- 
        - #HERO
      -->

      <section class="section hero"
        style="background-image: url('./assets/images/hero-bg-bottom.png') url('./assets/images/hero-bg-top.png')">
        <div class="container">

          <div class="hero-content">

            <p class="section-subtitle">We Travel to Learn</p>

            <h2 class="hero-title">Travel and Tours</h2>

            <p class="hero-text">
            Gayla Moves Travel and Tours: Where we travel to learn. Join us for enriching journeys that turn every destination into a classroom. Travel with purpose, learn with us.
            </p>

            <div class="btn-group">
              <a href="aboutus.php" class="btn btn-primary">Contact Us</a>

              <a href="aboutus.php" class="btn btn-outline">Learn More</a>
            </div>

          </div>

          <figure class="hero-banner">
            <img src="./assets/images/logo.png" width="686" height="812" loading="lazy" alt="hero banner"
              class="w-100">
          </figure>

        </div>
      </section>



    </article>
  </main>

</body>
</html>