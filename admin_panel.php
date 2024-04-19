<!-- admin_panel.php -->

<?php
include_once('admin_tour.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_tour'])) {
        // Extract values from the form
        $famous_place = $_POST['famous_place'];
        $province = $_POST['province'];
        $description = $_POST['description'];
        $pricing = $_POST['pricing'];
        $photo_url = $_POST['photo_url'];
        $travel_itinerary = $_POST['travel_itinerary'];
        $categories = $_POST['categories'];
    
        // Call the createTour function with the extracted values
        createTour($famous_place, $province, $description, $pricing, $photo_url, $travel_itinerary, $categories);
        header('Location: admin_panel.php');
        exit();
    }elseif (isset($_POST['edit_tour'])) {
        // Extract values from the edit form
        $editTourId = $_POST['edit_tour_id'];
        $editFamousPlace = $_POST['edit_famous_place'];
        $editProvince = $_POST['edit_province'];
        $editDescription = $_POST['edit_description'];
        $editPricing = $_POST['edit_pricing'];
        $editPhotoUrl = $_POST['edit_photo_url'];

        // Call the editTour function with the extracted values
        editTour($editTourId, $editFamousPlace, $editProvince, $editDescription, $editPricing, $editPhotoUrl);
    } elseif (isset($_POST['archive_tour'])) {
        // Extract the tour ID to be archived
        $archiveTourId = $_POST['edit_tour_id'];

        // Call the archiveTour function to archive the tour
        archiveTour($archiveTourId);
    } elseif (isset($_POST['add_tour'])) {
        // Extract the tour ID to be added
        $addTourId = $_POST['edit_tour_id'];

        // Call the addTour function to add the tour
        addTour($addTourId);
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
    <title>Tour Package Maintenance</title>

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
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            font-size: 16px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%; 
            padding: 8px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            box-sizing: border-box; 
        }


        .form-group button {
            padding: 10px 15px;
        }
        /* categories */
        .category-checkbox {
        display: flex;
        align-items: center;
        }
        .category-checkbox input {
            margin-right: 10px;
        }

        .category-checkbox-item {
            display: flex;
            align-items: center;
            margin-right: 10px;
        }

    .tours-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .tour-item {
        max-width: 300px;
        margin: 20px;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        text-align: center;
        background-color: #fff;
    }

    .tour-item h3 {
        font-size: 18px;
        margin-bottom: 10px;
    }

    /* Edit and Delete buttons */
    .tour-item .btn-primary,
    .tour-item .btn-outline {
        padding: 10px 15px;
        cursor: pointer;
    }

    .tour-item .btn-primary {
        background-color: var(--viridian-green);
        color: var(--white-1);
        border: none;
        border-radius: var(--radius-6);
    }

    .tour-item .btn-primary:hover {
        background-color: #2980b9;
    }

    .tour-item .btn-outline {
        color: var(--oxford-blue);
        border: 2px solid var(--silver-chalice);
        border-radius: var(--radius-6);
    }

    .tour-item .btn-outline:hover {
        color: var(--viridian-green);
        border-color: var(--viridian-green);
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
               <a href="admin_booking.php" class="navbar-link">Booking</a>
            </li>
            <li>
               <a href="#" class="navbar-link">About Us Organizer</a>
            </li>
            <li>
              <a href="#" class="navbar-link">Vehicle Maintenance</a>
            </li>
         </ul>
         </ul>

         <a href="logout.php" class="btn btn-secondary">Log Out</a>
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
                <select id="province" name="province" required>
                <option value="">Select Province</option> <!-- Add this line for default/placeholder -->
                    <?php
                    // List of provinces in Luzon
                    $luzonProvinces = [
                        "Abra", "Albay", "Apayao", "Aurora", "Bataan", "Batanes", "Batangas",
                        "Benguet", "Bulacan", "Cagayan", "Camarines Norte", "Camarines Sur", "Catanduanes",
                        "Cavite", "Ifugao", "Ilocos Norte", "Ilocos Sur", "Isabela", "Kalinga",
                        "La Union", "Laguna", "Marinduque", "Masbate", "Metro Manila", "Mountain Province",
                        "Nueva Ecija", "Nueva Vizcaya", "Occidental Mindoro", "Oriental Mindoro", "Palawan", "Pampanga",
                        "Pangasinan", "Quezon", "Quirino", "Rizal", "Romblon", "Sorsogon",
                        "Tarlac", "Tawi-Tawi", "Zambales", "Zamboanga del Norte", "Zamboanga del Sur", "Zamboanga Sibugay",
                    ];

                    // Exclude provinces that already have a tour
                    $provincesWithTour = array_column($tours, 'province');
                    $availableProvinces = array_diff($luzonProvinces, $provincesWithTour);

                    // Generate options for the dropdown
                    foreach ($availableProvinces as $provinceOption) {
                        echo '<option value="' . $provinceOption . '">' . $provinceOption . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" style="width: 100%; height: 150px;" required></textarea>
            </div>
        <!-- Travel Itinerary -->
        <div class="form-group">
            <label for="travel_itinerary">Travel Itinerary:</label>
        <div id="travel-itinerary-container">
            <div class="travel-itinerary-row">
                <input type="text" name="travel_itinerary[0]" placeholder="Place" required>
                <div class="category-checkbox">
                    <input type="checkbox" name="categories[0][amusement_park]" value="1">
                    <label>Amusement Park</label>

                    <input type="checkbox" name="categories[0][historical_site]" value="2">
                    <label>Historical Site</label>

                    <input type="checkbox" name="categories[0][natural_attraction]" value="3">
                    <label>Natural Attraction</label>

                    <input type="checkbox" name="categories[0][museum]" value="4">
                    <label>Museum</label>

                    <input type="checkbox" name="categories[0][zoo]" value="5">
                    <label>Wildlife Sanctuary / Zoo</label>

                    <input type="checkbox" name="categories[0][religious_site]" value="6">
                    <label>Religious Site</label>

                    <input type="checkbox" name="categories[0][mall]" value="7">
                    <label>Mall</label>
                </div>
            </div>
        </div>
        <button type="button" onclick="addTravelItineraryField()">+ Add more place</button>
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

<!-- Manage Tour section -->
<div class="tours-container">
    <?php foreach ($tours as $tour): ?>
        <div class="tour-item">
            <h3><?= $tour['province'] ?> Tour</h3>

            <form method="post" action="admin_panel.php">
                <input type="hidden" name="edit_tour_id" value="<?= $tour['id'] ?>">
                <div class="form-group">
                    <label for="edit_famous_place">Famous Place:</label>
                    <input type="text" id="edit_famous_place" name="edit_famous_place" value="<?= $tour['famous_place'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="edit_province">Province:</label>
                    <input type="text" id="edit_province" name="edit_province" value="<?= $tour['province'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="edit_description">Description:</label>
                    <textarea id="edit_description" name="edit_description" style="width: 100%; height: 150px;" required><?= $tour['description'] ?></textarea>
                </div>
                <div class="form-group">
                    <label for="edit_pricing">Pricing:</label>
                    <input type="number" id="edit_pricing" name="edit_pricing" value="<?= $tour['pricing'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="edit_photo_url">Photo URL:</label>
                    <input type="text" id="edit_photo_url" name="edit_photo_url" value="<?= $tour['photo_url'] ?>" required>
                </div>

                  <!-- Add or Archive button -->
                  <?php if ($tour['archived'] == 1): ?>
                    <button type="submit" name="add_tour" class="btn btn-outline">Add This Tour</button>
                <?php else: ?>
                    <button type="submit" name="archive_tour" class="btn btn-outline">Archive</button>
                <?php endif; ?>


                <button type="submit" name="edit_tour" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

        </article>
    </main>




    <script>
    function addTravelItineraryField() {
        const travelItineraryContainer = document.getElementById('travel-itinerary-container');
        const newIndex = travelItineraryContainer.children.length; // Get the new index

        // Create a new travel itinerary row
        const newTravelItineraryRow = document.createElement('div');
        newTravelItineraryRow.className = 'travel-itinerary-row';

        // Create travel itinerary text field
        const travelItineraryInput = document.createElement('input');
        travelItineraryInput.type = 'text';
        travelItineraryInput.name = `travel_itinerary[${newIndex}]`;
        travelItineraryInput.placeholder = 'Place';
        travelItineraryInput.required = true;

        // Create category checkboxes dynamically
        const categoryCheckboxContainer = document.createElement('div');
        categoryCheckboxContainer.className = 'category-checkbox';

        const categories = ['Amusement Park', 'Historical Site', 'Natural Attraction', 'Museum', 'Wildlife Sanctuary / Zoo', 'Religious Site', 'Mall'];

        categories.forEach((category, index) => {
            const categoryCheckboxItem = document.createElement('div');
            categoryCheckboxItem.className = 'category-checkbox-item';

            const categoryCheckbox = document.createElement('input');
            categoryCheckbox.type = 'checkbox';
            categoryCheckbox.name = `categories[${newIndex}][${category.toLowerCase().replace(/\s/g, '_')}]`;
            categoryCheckbox.value = index + 1; // Use index + 1 as the value

            const categoryLabel = document.createElement('label');
            categoryLabel.textContent = category;

            categoryCheckboxItem.appendChild(categoryCheckbox);
            categoryCheckboxItem.appendChild(categoryLabel);

            categoryCheckboxContainer.appendChild(categoryCheckboxItem);
        });

        // Append elements to the new travel itinerary row
        newTravelItineraryRow.appendChild(travelItineraryInput);
        newTravelItineraryRow.appendChild(categoryCheckboxContainer);

        // Append the new travel itinerary row to the container
        travelItineraryContainer.appendChild(newTravelItineraryRow);
    }
// ----------------------------//
function editTour(tourId) {
    console.log('Editing tour with ID:', tourId);

    const existingTourDetails = {
        famous_place: "Existing Famous Place",
        province: "Existing Province",
        description: "Existing Description",

    };


    const editFamousPlaceInput = document.getElementById('edit_famous_place');
    const editProvinceInput = document.getElementById('edit_province');
    const editDescriptionTextarea = document.getElementById('edit_description');

    editFamousPlaceInput.value = existingTourDetails.famous_place;
    editProvinceInput.value = existingTourDetails.province;
    editDescriptionTextarea.value = existingTourDetails.description;

    const editTourForm = document.getElementById('edit-form-container');
    editTourForm.style.display = 'block';
}



// Function to delete a tour
function deleteTour(tourId) {
    console.log('Deleting tour with ID:', tourId);

    const confirmDelete = confirm('Are you sure you want to delete this tour?');

    if (confirmDelete) {

        const xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Tour deleted successfully
                    console.log('Tour deleted successfully');
                    
                    // Display deletion complete popup
                    alert('Tour deleted successfully!');

                    // Delay page reload for a better user experience
                    setTimeout(() => {
                        // Reload the page
                        location.reload();
                    }, 500); // 500 milliseconds delay, adjust as needed

                } else {
                    // Error deleting the tour
                    console.error('Error deleting tour:', xhr.statusText);
                }
            }
        };

        xhr.open('POST', 'admin_panel.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send(`delete_tour=1&delete_tour_id=${tourId}`);
    }
}



    </script>





    </body>
    </html>
