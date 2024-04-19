    <!-- tours.php -->
    <?php
    include_once('admin_tour.php');

    // Fetch the tours data
    $tours = readTours();
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Package Selection</title>

      <style>
            .card-banner {
                height: 300px; /* Adjust the height to your preference */
                overflow: hidden;
            }

            .img-cover {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .center-text {
                text-align: center;
                margin-top: 10px; /* Adjust the margin as needed */
            }

          .center-button {
              display: flex;
              justify-content: center;
              margin-top: 20px; /* Adjust the margin as needed */
          }
        </style>

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
      <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Comforter+Brush&family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    </head>

    <body class="tours-page">
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

            
            <a href="logout.php" class="btn btn-secondary">Logout</a>


          </nav>
        </div>
      </header>

      <main>
        <article>
          <!-- #DESTINATION -->
          <section class="section destination">
            <div class="container">
              <p class="section-subtitle">Destinations</p>
              <h2 class="h2 section-title">Choose Your Place</h2>
              <h3 class="h2 section-title">or</h3>
              <div class="center-text center-button">
                  <a href="recommend.php" class="btn btn-primary">Recommend Tour</a>
              </div>

              <br><br><br>



             

              <ul class="destination-list">
                <?php foreach ($tours as $tour): ?>
                  <?php if ($tour['archived'] == 0): ?>
                    <li class="w-50">
                      <!-- Update the href to include the slug -->
                      <a href="tour.php?slug=<?= $tour['slug'] ?>" class="destination-card">
                        <figure class="card-banner">
                          <img src="<?= $tour['photo_url'] ?>" width="1140" height="1100"
                            loading="lazy" alt="<?= $tour['famous_place'] . ', ' . $tour['province'] ?>"
                            class="img-cover">
                        </figure>

                        <div class="card-content">
                          <p class="card-subtitle"><?= $tour['famous_place'] ?></p>
                          <h3 class="h3 card-title"><?= $tour['province'] ?></h3>
                        </div>
                      </a>
                    </li>
                  <?php endif; ?>
                <?php endforeach; ?>
              </ul>
            </div>
          </section>
        </article>
      </main>

      <!-- 
        - #GO TO TOP
      -->

      <a href="#top" class="go-top" data-go-top aria-label="Go To Top">
        <ion-icon name="chevron-up-outline"></ion-icon>
      </a>

      <!-- 
        - custom js link
      -->
      <script src="./assets/js/script.js"></script>

      <!-- 
        - ionicon link
      -->
      <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
      <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    </body>
    </html>
