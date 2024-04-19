<!-- admin_tour.php -->

<?php
include_once('db_connection.php');

function createTour($famous_place, $province, $description, $pricing, $photo_url, $travel_itinerary, $categories) {
    global $conn;

    // Generate a unique slug based on the famous place
    $slug = strtolower(str_replace(' ', '-', $famous_place));

    // Insert into tours table
    // $tourInsertQuery = "INSERT INTO tours (famous_place, province, description, pricing, photo_url, slug) 
    //             VALUES (?, ?, ?, ?, ?, ?)";

    $tourInsertQuery = "INSERT INTO tour_recommendation.tours (famous_place, province, description, pricing, photo_url, slug) 
                VALUES (?, ?, ?, ?, ?, ?)";


    $tourInsertStmt = mysqli_prepare($conn, $tourInsertQuery);

    if ($tourInsertStmt) {
        mysqli_stmt_bind_param($tourInsertStmt, "ssssss", $famous_place, $province, $description, $pricing, $photo_url, $slug);
        mysqli_stmt_execute($tourInsertStmt);

        // Get the ID of the inserted tour
        $tourId = mysqli_insert_id($conn);

        // Insert travel itinerary places into travel_itinerary table
        foreach ($travel_itinerary as $placeIndex => $place) {
            $itineraryInsertQuery = "INSERT INTO travel_itinerary (tour_id, place_name) VALUES (?, ?)";
            $itineraryInsertStmt = mysqli_prepare($conn, $itineraryInsertQuery);
            mysqli_stmt_bind_param($itineraryInsertStmt, "is", $tourId, $place);
            mysqli_stmt_execute($itineraryInsertStmt);
            mysqli_stmt_close($itineraryInsertStmt);

            // Get the ID of the inserted itinerary place
            $itineraryPlaceId = mysqli_insert_id($conn);

            // Check if categories for this place are set in $_POST
            if (isset($categories[$placeIndex])) {
                foreach ($categories[$placeIndex] as $categoryId) {
                    // Insert category relationships into place_categories table
                    $categoryInsertQuery = "INSERT INTO place_categories (place_id, category_id) VALUES (?, ?)";
                    $categoryInsertStmt = mysqli_prepare($conn, $categoryInsertQuery);
                    mysqli_stmt_bind_param($categoryInsertStmt, "ii", $itineraryPlaceId, $categoryId);
                    mysqli_stmt_execute($categoryInsertStmt);
                    mysqli_stmt_close($categoryInsertStmt);
                }
            }
        }

        mysqli_stmt_close($tourInsertStmt);
        return true;
    } else {
        echo "Error: " . mysqli_error($conn);
        return false;
    }
}
function readTours() {
    global $conn;

    // $sql = "SELECT * FROM tours";
    $sql = "SELECT * FROM tour_recommendation.tours";

    $result = mysqli_query($conn, $sql);

    $tours = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $row['travel_itinerary'] = getTravelItinerary($row['id']);
            $tours[] = $row;
        }
        mysqli_free_result($result);
    }

    return $tours;
}

function getTravelItinerary($tourId) {
    global $conn;

    $itinerary = [];

    // $sql = "SELECT * FROM travel_itinerary WHERE tour_id = ?";
    $sql = "SELECT * FROM tour_recommendation.travel_itinerary WHERE tour_id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $tourId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $itinerary[] = $row['place_name'];
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    return $itinerary;
}

//------------------------------//

function editTour($editTourId, $editFamousPlace, $editProvince, $editDescription, $editPricing, $editPhotoUrl) {
    global $conn;

    // Update the existing tour details in the tours table
    $editTourQuery = "UPDATE tours SET famous_place = ?, province = ?, description = ?, pricing = ?, photo_url = ? WHERE id = ?";
    $editTourStmt = mysqli_prepare($conn, $editTourQuery);

    if ($editTourStmt) {
        mysqli_stmt_bind_param($editTourStmt, "sssssi", $editFamousPlace, $editProvince, $editDescription, $editPricing, $editPhotoUrl, $editTourId);
        mysqli_stmt_execute($editTourStmt);
        mysqli_stmt_close($editTourStmt);

        return true;
    } else {
        echo "Error updating tour: " . mysqli_error($conn);
        return false;
    }
}


function getTourDetails($tourId) {
    global $conn;

    // Fetch tour details from the tours table
    $tourDetailsQuery = "SELECT * FROM tours WHERE id = ?";
    $tourDetailsStmt = mysqli_prepare($conn, $tourDetailsQuery);

    if ($tourDetailsStmt) {
        mysqli_stmt_bind_param($tourDetailsStmt, "i", $tourId);
        mysqli_stmt_execute($tourDetailsStmt);
        $tourDetailsResult = mysqli_stmt_get_result($tourDetailsStmt);
        $tourDetails = mysqli_fetch_assoc($tourDetailsResult);
        mysqli_stmt_close($tourDetailsStmt);

        // Fetch travel itinerary places for the tour
        $travelItineraryQuery = "SELECT * FROM travel_itinerary WHERE tour_id = ?";
        $travelItineraryStmt = mysqli_prepare($conn, $travelItineraryQuery);

        if ($travelItineraryStmt) {
            mysqli_stmt_bind_param($travelItineraryStmt, "i", $tourId);
            mysqli_stmt_execute($travelItineraryStmt);
            $travelItineraryResult = mysqli_stmt_get_result($travelItineraryStmt);

            while ($row = mysqli_fetch_assoc($travelItineraryResult)) {
                $tourDetails['travel_itinerary'][] = $row['place_name'];
            }

            mysqli_stmt_close($travelItineraryStmt);

            // Fetch categories for each travel itinerary place
            foreach ($tourDetails['travel_itinerary'] as &$place) {
                // $categoriesQuery = "SELECT c.name FROM categories c
                //                     JOIN place_categories pc ON c.id = pc.category_id
                //                     WHERE pc.place_id IN (
                //                         SELECT id FROM travel_itinerary WHERE tour_id = ? AND place_name = ?
                //                     )";
                $categoriesQuery = "SELECT c.name FROM tour_recommendation.categories c
                JOIN tour_recommendation.place_categories pc ON c.id = pc.category_id
                WHERE pc.place_id IN (
                    SELECT id FROM tour_recommendation.travel_itinerary WHERE tour_id = ? AND place_name = ?
                )";

                $categoriesStmt = mysqli_prepare($conn, $categoriesQuery);

                if ($categoriesStmt) {
                    mysqli_stmt_bind_param($categoriesStmt, "is", $tourId, $place);
                    mysqli_stmt_execute($categoriesStmt);
                    $categoriesResult = mysqli_stmt_get_result($categoriesStmt);

                    while ($category = mysqli_fetch_assoc($categoriesResult)) {
                        $placeCategories[] = $category['name'];
                    }

                    $place['categories'] = $placeCategories;

                    mysqli_stmt_close($categoriesStmt);
                } else {
                    echo "Error fetching categories: " . mysqli_error($conn);
                }
            }

        } else {
            echo "Error fetching travel itinerary: " . mysqli_error($conn);
        }

        return $tourDetails;
    } else {
        echo "Error fetching tour details: " . mysqli_error($conn);
        return null;
    }
}



function archiveTour($archiveTourId) {
    global $conn;

    // Update the archived status to 1 (archived)
    $archiveTourQuery = "UPDATE tours SET archived = 1 WHERE id = ?";
    $archiveTourStmt = mysqli_prepare($conn, $archiveTourQuery);

    if ($archiveTourStmt) {
        mysqli_stmt_bind_param($archiveTourStmt, "i", $archiveTourId);
        mysqli_stmt_execute($archiveTourStmt);
        mysqli_stmt_close($archiveTourStmt);

        return true;
    } else {
        echo "Error archiving tour: " . mysqli_error($conn);
        return false;
    }
}

function addTour($addTourId) {
    global $conn;

    // Update the archived status to 0 (not archived)
    $addTourQuery = "UPDATE tours SET archived = 0 WHERE id = ?";
    $addTourStmt = mysqli_prepare($conn, $addTourQuery);

    if ($addTourStmt) {
        mysqli_stmt_bind_param($addTourStmt, "i", $addTourId);
        mysqli_stmt_execute($addTourStmt);
        mysqli_stmt_close($addTourStmt);

        return true;
    } else {
        echo "Error adding tour: " . mysqli_error($conn);
        return false;
    }
}
?>