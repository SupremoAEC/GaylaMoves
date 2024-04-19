<!-- tour.php -->
<?php
include_once('admin_tour.php');

// Retrieve the slug from the URL
$slug = $_GET['slug'];

// Fetch tour details based on the slug
$tours = readTours();

// Find the tour with the matching slug
$tourDetails = null;
foreach ($tours as $tour) {
    if (strtolower(str_replace(' ', '-', $tour['famous_place'])) === $slug) {
        $tourDetails = $tour;
        break;
    }
}

if (!$tourDetails) {
    echo "Tour not found!";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package Selection</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="../favicon.svg" type="image/svg+xml">

    <!-- Custom CSS Link -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Google Font Link -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Comforter+Brush&family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Inline Styles -->
    <style>
        .book__btn {
            display: inline-block;
            padding: 10px 25px;
            font-size: 16px;
            font-weight: 700;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            background: var(--viridian-green);
            color: var(--white-1);
            border: 2px solid var(--viridian-green);
            border-radius: 6px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .book__btn:hover {
            background: transparent;
            color: var(--viridian-green);
        }
    </style>
</head>

<body id="top" class="tour-page">
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
                <a href="tours.php" class="btn btn-secondary">Go back</a>
            </nav>
        </div>
    </header>

    <main>
        <article>
            <!-- Destination Section -->
            <section class="section destination">
                <div class="container">
                    <p class="section-subtitle">Destination</p>
                    <h2 class="h2 section-title"><?= $tourDetails['province'] ?>, Philippines</h2>

                    <ul class="destination-list">
                        <!-- Destination Card 1 -->
                        <li class="w-50">
                            <a href="#" class="destination-card">
                                <figure class="card-banner">
                                    <img src="<?= $tourDetails['photo_url'] ?>" width="1140" height="1100" loading="lazy"
                                        alt="<?= $tourDetails['famous_place'] ?>" class="img-cover">
                                </figure>

                                <div class="card-content">
                                    <p class="card-subtitle"><?= $tourDetails['famous_place'] ?></p>
                                    <h3 class="h3 card-title"><?= $tourDetails['province'] ?></h3>
                                </div>
                            </a>
                        </li>

                        <!-- Tour Details -->
                        <li class="w-50">
                            <h1>Tour Description</h1>
                            <p><?= $tourDetails['description'] ?></p>

                            <br>

                            <!-- Travel Itinerary -->
                                <h1>Travel Itinerary</h1>
                                <?php 
                                foreach ($tourDetails['travel_itinerary'] as $place):
                                ?>
                                    <p><?= $place ?></p>
                                <?php
                                endforeach;
                                ?>


                            <br>

                            <h1>Pricing</h1>
                            <p><?= $tourDetails['pricing'] ?>/person</p>

                            <!-- Book Now Button -->
                            <button class="book__btn"><a href="book.php?slug=<?= $slug ?>">Book Now!</a></button>                        </li>




                    </ul>
                </div>
            </section>
        </article>
    </main>

    <!-- Go to Top Button -->
    <a href="#top" class="go-top" data-go-top aria-label="Go To Top">
        <ion-icon name="chevron-up-outline"></ion-icon>
    </a>

    <!-- Custom JS Link -->
    <script src="../assets/js/script.js"></script>

    <!-- Ionicon Link -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>
