<!--medicine json -->
<?php
  $patientid = $_GET['patientid'];
  //patientid json
  $patientid_json = json_encode($patientid) ;

  $sql = "SELECT patientmedicine.dosage, patientmedicine.date, medicine.medication 
          FROM patientmedicine 
          JOIN medicine ON patientmedicine.medicationid = medicine.medicationid 
          WHERE patientmedicine.patientid = :patientid ORDER BY patientmedicine.date
        ";

  try {
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':patientid', (int) $patientid);
    $stmt->execute();
    //fetch results
    $patientmedicine = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // sort patientmedicine array by date
    function sortByDate($a, $b) {
      return strtotime($a['date']) - strtotime($b['date']);
  }
  usort($patientmedicine, 'sortByDate');
  //patientmedicine json
  $patientmedicine_json = json_encode($patientmedicine);

  } catch (PDOException $e) {
    //error
    echo "Error: " . $e->getMessage();
  }
?>


<!--visit json -->
<?php
  // Prepare the SQL query to retrieve the check-in and check-out dates for the patient
  $query = $conn->prepare("SELECT checkin, checkout FROM hospital WHERE patientid = :patientid");
  $query->bindParam(':patientid', $patientid);
  $query->execute();

  // Fetch the results 
  $visits = $query->fetchAll(PDO::FETCH_ASSOC);

  // Create an array to store the data for the heatmap
  $data = [];

  if(!empty($visits)){
      //Iterate over the visit records
      foreach($visits as $visit){
          // Get the check-in and check-out dates
          $checkin = $visit['checkin'];
          $checkout = $visit['checkout'];

          // Create a new DateTime object for the start and end date
          $date = DateTime::createFromFormat('d-m-Y', $checkin);
          $enddate = DateTime::createFromFormat('d-m-Y', $checkout);

          // Iterate over the days between the check-in and check-out dates
          while($date <= $enddate) {
              // Format the date as a string
              $day = $date->format("Y-m-d");

              // Set the value for the day
              $value = 0;
              if( $day == $checkin || $day == $checkout) {
                  $value = 1;
              }else{
                  $value = 2;
              }

              // Add the timestamp and value to the data array
              $timestamp = $date->getTimestamp();
              $data[$timestamp] = $value;

              // Increment the date by one day
              $date->modify('+1 day');
          }
      }
  }
  $data_json = json_encode($data);
?>