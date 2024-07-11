<?php
session_start();
// Include the Chart.js library
echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';

// Prepare the SQL query to retrieve symptom data for the patient
$query = $conn->prepare("
    SELECT ps.date, s.symptom, ps.symptomrate 
    FROM patientsymptom ps
    INNER JOIN symptoms s ON ps.symptomid = s.symptomid
    WHERE ps.patientid = :patientid
");
$query->bindParam(':patientid', $patientID);
$query->execute();

// Fetch the results 
$symptomData = $query->fetchAll(PDO::FETCH_ASSOC);

// Initialize an associative array to store symptom data
$symptomDataBySymptom = [];

// Process each row of the fetched data
foreach ($symptomData as $row) {
    $date = $row['date'];
    $symptom = $row['symptom'];
    $symptomRate = $row['symptomrate'];

    // If the symptom hasn't been encountered yet, initialize an array for it
    if (!isset($symptomDataBySymptom[$symptom])) {
        $symptomDataBySymptom[$symptom] = [
            'dates' => [],
            'symptomRates' => []
        ];
    }

    // Add date and symptom rate to the respective arrays for the symptom
    $symptomDataBySymptom[$symptom]['dates'][] = $date;
    $symptomDataBySymptom[$symptom]['symptomRates'][] = $symptomRate;
}

// Convert symptom data to JSON format for use in JavaScript
$symptomDataBySymptom_json = json_encode($symptomDataBySymptom);
?>


<!-- Display a canvas element for the chart -->
<div class="container" id="progress-container">
  <h1 id="symptomprogress"> Symptom Progress </h1>
  <canvas id="symptomChart" width="100%" height="50px"></canvas>

</div>


<script>
// Retrieve the symptom data by symptom from PHP
var symptomDataBySymptom = <?php echo $symptomDataBySymptom_json; ?>;

// Initialize a Chart.js line chart
var ctx = document.getElementById('symptomChart').getContext('2d');
var symptomChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [], // Labels will be added dynamically for each symptom
        datasets: [] // Datasets will be added dynamically for each symptom
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                suggestedMax: 5 // Optional: Set maximum y-axis value
            }
        }
    }
});

// Add datasets for each symptom
for (var symptom in symptomDataBySymptom) {
    if (symptomDataBySymptom.hasOwnProperty(symptom)) {
        var dates = symptomDataBySymptom[symptom]['dates'];
        var symptomRates = symptomDataBySymptom[symptom]['symptomRates'];

        // Add a new dataset for the symptom
        symptomChart.data.datasets.push({
            label: symptom,
            data: symptomRates,
            borderColor: getRandomColor(), // Function to generate random color
            backgroundColor: 'rgba(0, 0, 255, 0.1)', // Optional: Fill color
            borderWidth: 1
        });

        // Add labels dynamically for the first symptom
        if (symptomChart.data.labels.length === 0) {
            symptomChart.data.labels = dates;
        }
    }
}

// Update the chart
symptomChart.update();

// Function to generate a random color
function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}
</script>
