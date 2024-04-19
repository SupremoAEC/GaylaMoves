<!-- recommendations.php -->
<?php
include_once('db_connection.php');

// Get priorities from the form submission
$firstPriority = $_POST['first_category'];
$secondPriority = $_POST['second_category'];
$thirdPriority = $_POST['third_category'];

// Query to get provinces with the most places for the first priority
$firstPriorityQuery = "SELECT province, COUNT(tours.id) as totalCategories, tours.photo_url, tours.slug
                       FROM tours
                       JOIN travel_itinerary ON tours.id = travel_itinerary.tour_id
                       JOIN place_categories ON travel_itinerary.id = place_categories.place_id
                       JOIN categories ON place_categories.category_id = categories.id
                       WHERE categories.name = '$firstPriority'
                       GROUP BY province
                       ORDER BY totalCategories DESC";


$firstPriorityResult = mysqli_query($conn, $firstPriorityQuery);

// Check for errors in the first priority query
if (!$firstPriorityResult) {
    die("First Priority Query Error: " . mysqli_error($conn));
}

// If there are no provinces for the first priority, move to the second priority
if (mysqli_num_rows($firstPriorityResult) == 0) {
    $secondPriorityQuery = "SELECT province, COUNT(tours.id) as totalCategories, tours.photo_url, tours.slug
                        FROM tours
                        JOIN travel_itinerary ON tours.id = travel_itinerary.tour_id
                        JOIN place_categories ON travel_itinerary.id = place_categories.place_id
                        JOIN categories ON place_categories.category_id = categories.id
                        WHERE categories.name = '$secondPriority'
                        GROUP BY province
                        ORDER BY totalCategories DESC";


    $secondPriorityResult = mysqli_query($conn, $secondPriorityQuery);

    // Check for errors in the second priority query
    if (!$secondPriorityResult) {
        die("Second Priority Query Error: " . mysqli_error($conn));
    }

    // If there are no provinces for the second priority, move to the third priority
    if (mysqli_num_rows($secondPriorityResult) == 0) {
        $thirdPriorityQuery = "SELECT province, COUNT(tours.id) as totalCategories, tours.photo_url, tours.slug
        FROM tours
        JOIN travel_itinerary ON tours.id = travel_itinerary.tour_id
        JOIN place_categories ON travel_itinerary.id = place_categories.place_id
        JOIN categories ON place_categories.category_id = categories.id
        WHERE categories.name = '$thirdPriority'
        GROUP BY province
        ORDER BY totalCategories DESC";


        $thirdPriorityResult = mysqli_query($conn, $thirdPriorityQuery);

        // Check for errors in the third priority query
        if (!$thirdPriorityResult) {
            die("Third Priority Query Error: " . mysqli_error($conn));
        }

        // Display results for the third priority
        displayResults($thirdPriorityResult, $thirdPriority);
    } else {
        // Display results for the second priority
        displayResults($secondPriorityResult, $secondPriority);
    }
} else {
    // Display results for the first priority
    displayResults($firstPriorityResult, $firstPriority);
}

// Function to display results
function displayResults($result, $priority) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Recommendations</title>
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
    <body class="tours-page">
        <main>
            <article>
                <section class="section destination">
                    <div class="container">
                        <p class="section-subtitle">Recommended Places</p>
                        <h2 class="h2 section-title">Based on <?= $priority ?> Priority</h2>

                        <ul class="destination-list">
                            <?php
                            $ranking = 1; // Initialize the ranking counter
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <li class="w-50">
                            <!-- Update the href to include the slug -->
                            <a href="tour.php?slug=<?= $row['slug'] ?>" class="destination-card">
                                <figure class="card-banner">
                                    <?php
                                    $photoUrl = isset($row['photo_url']) ? $row['photo_url'] : 'default_image.jpg';
                                    ?>
                                    <img src="<?= $photoUrl ?>" width="1140" height="1100" loading="lazy" alt="<?= $row['province'] ?>" class="img-cover">
                                </figure>

                                <div class="card-content">
                                    <p class="card-subtitle"><?= $row['province'] ?></p>
                                    <h3 class="h3 card-title"><?= $priority ?>'s: <?= $row['totalCategories'] ?></h3>
                                </div>
                            </a>
                        </li>
                                <?php
                                $ranking++; // Increment the ranking counter
                            }
                            ?>
                        </ul>
                    </div>
                </section>
            </article>
        </main>
        <script src="./assets/js/script.js"></script>
    </body>
    </html>
    <?php
}


// Close the database connection
mysqli_close($conn);
?>
