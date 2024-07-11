<?php
session_start();
include("templates/conn.php");

// Check if patient ID is present in the URL
if (isset($_GET['patientid'])) {
    // Retrieve patient ID from the URL
    $patientID = $_GET['patientid'];

    // Prepare and execute query to retrieve patient information based on patient ID
    $stmt = $conn->prepare("SELECT * FROM patient WHERE patientid = :patientid");
    $stmt->bindValue(':patientid', $patientID);
    $stmt->execute();
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Redirect or handle the case where patient ID is not present in the URL
    // For example, redirect to the search page
    header("Location: search.php");
    exit(); // Stop further execution
}
?>

<!--cycle -->
<?php
// Prepare the SQL query to retrieve the start and end dates of cycles for the patient
$query = $conn->prepare("SELECT startcycle, endcycle FROM cycle WHERE patientid = :patientid");
$query->bindParam(':patientid', $patientID);
$query->execute();

// Fetch the results 
$cycles = $query->fetchAll(PDO::FETCH_ASSOC);

// Create an array to store the data for the heatmap
$data = [];

// Process each cycle and mark days accordingly
foreach ($cycles as $cycle) {
    // Get the start and end dates of the cycle
    $startcycle = $cycle['startcycle'];
    $endcycle = $cycle['endcycle'];

    // Create a new DateTime object for the start and end date
    $startDate = DateTime::createFromFormat('d-m-Y', $startcycle);
    $endDate = DateTime::createFromFormat('d-m-Y', $endcycle);

    // Iterate over the days between the start and end dates
    while ($startDate <= $endDate) {
        // Format the date as a string
        $day = $startDate->format("Y-m-d");

        // Set the value for the day
        $value = 0;
        if ($day == $startcycle || $day == $endcycle) {
            $value = 1; // Highlight start and end dates
        } else {
            $value = 2; // Highlight days in between
        }

        // Add the timestamp and value to the data array
        $timestamp = $startDate->getTimestamp();
        $data[$timestamp] = $value;

        // Increment the date by one day
        $startDate->modify('+1 day');
    }
}

// Convert the data array to JSON format
$data_json = json_encode($data);
?>
