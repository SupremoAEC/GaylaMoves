<!-- admin_panel.php -->
<?php
include_once('admin_tour.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_tour'])) {
        $famous_place = $_POST['famous_place'];
        $province = $_POST['province'];
        $description = $_POST['description'];
        $travel_itinerary = $_POST['travel_itinerary'];
        $pricing = $_POST['pricing'];
        $photo_url = $_POST['photo_url'];

        createTour($famous_place, $province, $description, $travel_itinerary, $pricing, $photo_url);
    } elseif (isset($_POST['update_tour'])) {
        $id = $_POST['tour_id'];
        $famous_place = $_POST['famous_place'];
        $province = $_POST['province'];
        $description = $_POST['description'];
        $travel_itinerary = $_POST['travel_itinerary'];
        $pricing = $_POST['pricing'];
        $photo_url = $_POST['photo_url'];

        updateTour($id, $famous_place, $province, $description, $travel_itinerary, $pricing, $photo_url);
    } elseif (isset($_POST['delete_tour'])) {
        $delete_id = $_POST['delete_id'];
        deleteTour($delete_id);
    }
}

$tours = readTours();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>ADMIN</title>

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
        text-align: center;
    }

    .form-container .section-title{
        font-size: 50px;
    }

    .section-title{
        font-size: 50px;
        text-align: center;
        padding-top: 100px;
    }

    .form-group {
        text-align: left;
    }
      .form-group {
         margin-bottom: 20px; /* Add margin to separate form groups */
      }

      .form-group label {
         display: block; /* Ensure labels are on a new line */
         font-weight: bold;
         font-size: 16px; /* You can adjust the font size as needed */
      }

      .form-group input,
      .form-group textarea,
      .form-group select {
         width: 100%; /* Make input fields fill the entire width of the container */
         padding: 8px; /* Add padding inside the input fields */
         border: 1px solid #ccc; /* Add a 1px solid border around the input fields */
         border-radius: 4px; /* Optional: Add border-radius for rounded corners */
         box-sizing: border-box; /* Include padding and border in the element's total width and height */
      }

      /* Optional: Increase padding for buttons */
      .form-group button {
         padding: 10px 15px;
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
               <a href="admin.php" class="navbar-link">Home</a>
            </li>
            <li>
               <a href="admin_panel.php" class="navbar-link">Tour Organizer</a>
            </li>
            <li>
               <a href="#" class="navbar-link">About Us Organizer</a>
            </li>
            <li>
              <a href="#" class="navbar-link">Vehicle Maintenance</a>
            </li>
         </ul>
         </ul>

        <a href="#" class="btn btn-secondary">Log Out</a>

      </nav>
      </div>
   </header>

   <main>
    <article>

    <!-- Add Tour section -->
<div class="form-container">
    <h2 class="section-title">Add Tour</h2>
    <form method="post" action="admin_panel.php" class="form">
        <div class="form-group">
            <label for="famous_place">Famous Place:</label>
            <input type="text" id="famous_place" name="famous_place" required>
        </div>
        <div class="form-group">
            <label for="province">Province:</label>
            <input type="text" id="province" name="province" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" style="width: 100%; height: 150px;" required></textarea>
        </div>
        <div class="form-group">
            <label for="travel_itinerary">Travel Itinerary:</label>
            <textarea id="travel_itinerary" name="travel_itinerary" style="width: 100%; height: 150px;" required></textarea>
        </div>
        <div class="form-group">
            <label for="pricing">Pricing:</label>
            <input type="number" id="pricing" name="pricing" required>
        </div>
        <div class="form-group">
            <label for="photo_url">Photo URL:</label>
            <input type="text" id="photo_url" name="photo_url" required>
        </div>
        <button type="submit" name="create_tour" class="btn btn-primary">Add Tour</button>
    </form>
</div>

<h2 class="section-title">Manage Tours</h2>
<section class="section destination">
    <div class="container">
        <ul class="destination-list">
            <?php foreach ($tours as $tour): ?>
                <li class="w-50">
                    <form method="post" action="admin_panel.php" class="form">
                        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                        <a href="#" class="destination-card">
                            <figure class="card-banner">
                                <img src="<?= $tour['photo_url'] ?>" alt="<?= $tour['famous_place'] . ', ' . $tour['province'] ?>"
                                    class="img-cover">
                            </figure>
                            <div class="card-content">
                                <p class="card-subtitle"><?= $tour['famous_place'] ?></p>
                                <h3 class="h3 card-title"><?= $tour['province'] ?></h3>
                            </div>
                        </a>
                        <div class="form-group">
                            <label for="famous_place_<?= $tour['id'] ?>">Famous Place:</label>
                            <input type="text" id="famous_place_<?= $tour['id'] ?>" name="famous_place" value="<?= $tour['famous_place'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="province_<?= $tour['id'] ?>">Province:</label>
                            <input type="text" id="province_<?= $tour['id'] ?>" name="province" value="<?= $tour['province'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="description_<?= $tour['id'] ?>">Description:</label>
                            <textarea id="description_<?= $tour['id'] ?>" name="description" style="width: 100%; height: 150px;" required><?= $tour['description'] ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="travel_itinerary_<?= $tour['id'] ?>">Travel Itinerary:</label>
                            <textarea id="travel_itinerary_<?= $tour['id'] ?>" name="travel_itinerary" style="width: 100%; height: 150px;" required><?= $tour['travel_itinerary'] ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="pricing_<?= $tour['id'] ?>">Pricing:</label>
                            <input type="number" id="pricing_<?= $tour['id'] ?>" name="pricing" value="<?= $tour['pricing'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="photo_url_<?= $tour['id'] ?>">Photo URL:</label>
                            <input type="text" id="photo_url_<?= $tour['id'] ?>" name="photo_url" value="<?= $tour['photo_url'] ?>" required>
                        </div>
                        <button type="submit" name="update_tour" class="btn btn-primary">Update</button>
                        <button type="submit" name="delete_tour" class="btn btn-danger">Delete</button>
                        <input type="hidden" name="delete_id" value="<?= $tour['id'] ?>">
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>


</body>
</html>
