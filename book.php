<!-- book.php -->
<?php
session_start();

$tourDetails = null;
$slug = isset($_GET['slug']) ? $_GET['slug'] : 'N/A';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $expectedGoers = $_POST['expected_goers'];
    $totalCost = $_POST['total_cost'];

    // Calculate rate per head based on total cost and expected goers
    $ratePerHead = ($expectedGoers > 0) ? $totalCost / $expectedGoers : 0;

    // Store data in session variables
    $_SESSION['expected_goers'] = $expectedGoers;
    $_SESSION['rate_per_head'] = $ratePerHead;
    $_SESSION['total_cost'] = $totalCost;

    // Debug statements to log parameters
    error_log("Slug in book.php: $slug");
    error_log("Date in book.php: {$_POST['date']}");
    error_log("Rate Per Head in book.php: $ratePerHead");
    error_log("Total Cost in book.php: $totalCost"); // Add this line

    // Redirect to a confirmation page with parameters in the URL
    header("Location: save_booking.php?slug={$slug}&date={$_POST['date']}&total_cost={$totalCost}&rate_per_head={$ratePerHead}&province={$tourDetails['province']}");    exit;
} elseif (isset($_GET['slug']) && !isset($_SESSION['rate_per_head'])) {
    // retrieve tour details based on the slug only if rate_per_head is not set
    include_once('admin_tour.php');
    $slug = $_GET['slug'];
    $tours = readTours();

    foreach ($tours as $tour) {
        if (strtolower(str_replace(' ', '-', $tour['famous_place'])) === $slug) {
            $tourDetails = $tour;
            break;
        }
    }

    if (!$tourDetails) {
        // Handle the case where the tour is not found
        echo "Tour not found!";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservaion</title>

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
    main{
        max-width: 800px;
        margin: auto;
        border-style: solid;
        border-width: 2px;
        border-radius: 25px;
        padding: 50px;
        margin-top: 100px;
        margin-bottom: 100px;
    }
    .form-container {
        max-width: 600px;
        margin: 0 auto;
        text-align: center;
    }
    .user_input{
        border-style: solid;
        border-width: 1px;
        border-radius: 25px;
        padding: 5rem;
    }

    .vehicle_input{
        border-style: solid;
        border-radius: 25px;
        border-width: 1px;
        padding: 3rem;
    }
    
    .align{
        text-align: left;
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
        width: calc(100% - 22px); /* Adjusted width to accommodate checkboxes */
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
    #rate_per_head_container {
        display: none;
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


      </nav>
      </div>
   </header>

<main>
<section class="section destination">
        <div class="container">
            <?php if ($tourDetails): ?>
                <p class="section-subtitle">Selected Tour</p>
                <h2 class="h2 section-title"><?= $tourDetails['province'] ?> Tour</h2>

            <?php else: ?>
                <p class="section-subtitle">Tour not found!</p>
            <?php endif; ?>
        </div>
    </section>

<div class="form-container">
<form method="post" action="save_booking.php?province=<?= $tourDetails['province'] ?>" id="reservationForm">
    <input type="hidden" name="slug" value="<?= $slug ?>">



 <!-- Number of Expected Goers -->
        <div class="user_input">
            <label for="expected_goers">Number of Expected Goers:</label>
            <input type="number" name="expected_goers" id="expected_goers" min="1" required oninput="updateRecommendation()">

            <!-- Recommendation based on Expected Goers -->
            <label for="recommendation">Recommendation:</label>
            <input type="text" name="recommendation" id="recommendation" readonly>
            <p>(Van = 15 seaters, Bus = 49 seaters)</p>
        </div>
        <br><br>
        <div class="vehicle_input">
            
                <!-- Radio buttons for choosing 2 vans or 1 bus -->
                <label for="vehicle_choice">Choose Vehicle if Your are 16-30:</label>
            
            <div class="align">
                <input type="radio" name="vehicle_choice" id="choose_2_vans" value="2_vans" disabled>
                <label for="choose_2_vans">2 Vans (15 seaters each) - ₱20,000</label>

                <input type="radio" name="vehicle_choice" id="choose_1_bus" value="1_bus" disabled>
                <label for="choose_1_bus">1 Bus (49 seaters) - ₱35,000</label>
            </div>
        </div>
        <br><br>

        <!-- Total Cost -->
        <label for="total_cost">Total Cost:</label>
        <input type="text" name="total_cost" id="total_cost" readonly>
        <input type="hidden" name="total_cost_hidden" id="total_cost_hidden" value="<?= $totalCost ?>">


      
        <br><br>

        <!-- Date Selection -->
        <label for="date">Select Date:</label>
        <input type="date" name="date" id="date" required>
        

        <br><br>
        <!-- Add this paragraph element below the Total Cost input -->
        <p id="rate_per_head_container">
            Rate Per Head: ₱<span id="rate_per_head">0</span>
        </p>
        <input type="hidden" name="rate_per_head" id="rate_per_head_input" value="<?= $ratePerHead ?>">




        <button type="submit" class="book__btn" name="submit_booking">Submit</button>

 

    </form>

    <script>
       

       document.addEventListener("DOMContentLoaded", function () {
    // Get references to relevant elements
    var vanRadio = document.getElementById('choose_2_vans');
    var busRadio = document.getElementById('choose_1_bus');
    var totalCostInput = document.getElementById('total_cost');
    var expectedGoersInput = document.getElementById('expected_goers');

    // Add event listeners
    vanRadio.addEventListener('change', updateTotalCost);
    busRadio.addEventListener('change', updateTotalCost);

    // Trigger the initial update when the page loads
    updateTotalCost();

    // Update total cost when expected goers input changes
    expectedGoersInput.addEventListener('input', updateTotalCost);

    // Get the date input element
    var dateInput = document.getElementById('date');

    // Set the minimum date to today
    var today = new Date();
    var todayFormatted = today.toISOString().split('T')[0];
    dateInput.setAttribute('min', todayFormatted);

    // Calculate the minimum date with a 2-week notice
    var twoWeeksLater = new Date(today.getTime() + (14 * 24 * 60 * 60 * 1000));
    var twoWeeksLaterFormatted = twoWeeksLater.toISOString().split('T')[0];
    dateInput.setAttribute('min', twoWeeksLaterFormatted);

    // Add an event listener for input changes in the date field
    dateInput.addEventListener('input', updateTotalCost);


    // Add an event listener for the form submission
    document.getElementById('reservationForm').addEventListener('submit', function (event) {
        // Update the rate per head
        var expectedGoers = parseInt(document.getElementById('expected_goers').value);
        var totalCost = parseInt(document.getElementById('total_cost').value.replace(/\D/g, '')); // Remove non-numeric characters
        var ratePerHead = expectedGoers > 0 ? totalCost / expectedGoers : 0;

        // Update the hidden input field
        document.getElementById('rate_per_head_input').value = ratePerHead;
        
        // Update the hidden total cost field
        document.getElementById('total_cost_hidden').value = totalCost;

        // Log the rate per head for debugging
        console.log("Rate Per Head before submission: " + ratePerHead);

        // Submit the form
        return true;
    });
    });


    function updateRecommendation() {
        var expectedGoers = parseInt(document.getElementById('expected_goers').value);
        var recommendationInput = document.getElementById('recommendation');
        var vanRadio = document.getElementById('choose_2_vans');
        var busRadio = document.getElementById('choose_1_bus');
        var totalCostInput = document.getElementById('total_cost');

        if (expectedGoers <= 15) {
            recommendationInput.value = "1 Van (15 seaters) - ₱10,000";
            vanRadio.disabled = true;
            busRadio.disabled = true;
            totalCostInput.value = "₱10,000";
        } else if (expectedGoers <= 30) {
            recommendationInput.value = "Choose between 2 Vans (15 seaters each) or 1 Bus (49 seaters)";
            vanRadio.disabled = false;
            busRadio.disabled = false;
            // Call the updateTotalCost function here to ensure the initial state is considered
            updateTotalCost();
        } else {
            // Calculate the number of buses needed based on the formula
            var busesNeeded = Math.ceil(expectedGoers / 49);
            recommendationInput.value = busesNeeded + " Buses (49 seaters each)";
            vanRadio.disabled = true;
            busRadio.disabled = true;
            // Format the total cost and append the peso sign
            var totalCost = busesNeeded * 35000; // Cost of each bus
            totalCostInput.value = "₱" + totalCost.toLocaleString();
        }
    }



    function updateTotalCost() {
    var vanRadio = document.getElementById('choose_2_vans');
    var busRadio = document.getElementById('choose_1_bus');
    var totalCostInput = document.getElementById('total_cost');
    var expectedGoers = parseInt(document.getElementById('expected_goers').value);

    // Calculate the base vehicle cost
    var baseCost = 0;

    if (expectedGoers <= 15) {
        baseCost = 10000; // Cost for 1 Van
    } else if (expectedGoers <= 30) {
        if (vanRadio.checked) {
            baseCost = 20000; // Cost for 2 vans
        } else if (busRadio.checked) {
            baseCost = 35000; // Cost for 1 bus
        }
    } else {
        baseCost = 35000; // Cost for 1 bus
    }

    // Calculate the number of buses needed
    var busesNeeded = Math.ceil(expectedGoers / 49);

    // Calculate the total vehicle cost
    var totalVehicleCost = busesNeeded * baseCost;

    var totalCost = totalVehicleCost;

    // Ensure totalCost is a number
    if (!isNaN(totalCost)) {
        // Set the formatted total cost to the input field
        totalCostInput.value = "₱" + totalCost.toLocaleString();
    } else {
        totalCostInput.value = "Invalid Total Cost"; // Provide an error message or handle accordingly
    }

    // Update the rate per head
    var ratePerHead = expectedGoers > 0 ? totalCost / expectedGoers : 0;
    document.getElementById('rate_per_head').textContent = ratePerHead.toLocaleString();

    // Update the hidden input field
    document.getElementById('rate_per_head_input').value = ratePerHead;

}

    function handleExpectedGoersInput() {
        updateTotalCost();
    }
    document.getElementById('expected_goers').addEventListener('input', handleExpectedGoersInput);

    document.getElementById('choose_2_vans').addEventListener('change', function () {
    updateTotalCost();
    });

    document.getElementById('choose_1_bus').addEventListener('change', function () {
        updateTotalCost();
    });

    function handleDateInput() {
        // Get the selected date
        var selectedDate = new Date(document.getElementById('date').value);

        // Calculate the minimum date with a 2-week notice
        var twoWeeksLater = new Date();
        twoWeeksLater.setDate(twoWeeksLater.getDate() + 14);

        // Check if the selected date is valid (not in the past and has a 2-week notice)
        if (selectedDate < twoWeeksLater) {
            alert("Please select a date at least 2 weeks from today.");
            document.getElementById('date').value = ''; // Reset the date field
        }
    }
</script>
</div>

</body>
</html>