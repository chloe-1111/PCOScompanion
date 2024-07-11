<!--container 1: patient information -->
<div class="container" id="profile-container">
    <h1> Profile</h1>
    <table class="table" id="patienttable">
        <tr>
            <td>First name</td>
            <td><?php echo isset($patient['firstname']) ? $patient['firstname'] : ''; ?></td>
        </tr>
        <tr>
            <td>Last name</td>
            <td><?php echo isset($patient['lastname']) ? $patient['lastname'] : ''; ?></td>
        </tr>
        <tr>
            <td>Patient ID</td>
            <td><?php echo isset($patient['patientid']) ? $patient['patientid'] : ''; ?></td>
        </tr>
        <tr>
            <td>Birthdate</td>
            <td><?php echo isset($patient['birthdate']) ? $patient['birthdate'] : ''; ?></td>
        </tr>
        <tr>
            <td>Weight (kg)</td>
            <td><?php echo isset($patient['weight']) ? $patient['weight'] : ''; ?></td>
        </tr>
        <tr>
            <td>Height (cm)</td>
            <td><?php echo isset($patient['height']) ? $patient['height'] : ''; ?></td>
        </tr>
        <tr>
            <td>Birth Control</td>
            <td><?php echo isset($patient['birthcontrol']) ? $patient['birthcontrol'] : ''; ?></td>
        </tr>
        <tr>
            <td>Diet</td>
            <td><?php echo isset($patient['diet']) ? $patient['diet'] : ''; ?></td>
        </tr>
        <tr>
            <td>Allergies</td>
            <td><?php echo isset($patient['allergies']) ? $patient['allergies'] : ''; ?></td>
        </tr>
        <tr>
            <td>Symptoms</td>
            <td><?php echo isset($patient['symptoms']) ? $patient['symptoms'] : ''; ?></td>
        </tr>
    </table>

    <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#editPatientModal">Edit </button> <br><br>
</div>

<!--popup: edit patient information-->
<div class="modal fade" id="editPatientModal" tabindex="-1" role="dialog" aria-labelledby="editPatientModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPatientModalLabel">Edit Patient</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="home.php?patientid=<?php echo $patientid; ?>" method="POST">
                    <div class="form-group">
                        <label for="firstname">First Name:</label>
                        <input type="text" id="firstname" name="firstname" class="form-control" value="<?php echo isset($patient['firstname']) ? $patient['firstname'] : ''; ?>">
                        <label for="lastname">Last Name:</label>
                        <input type="text" id="lastname" name="lastname" class="form-control" value="<?php echo isset($patient['lastname']) ? $patient['lastname'] : ''; ?>">
                        <label for="birthdate">Birthdate:</label>
                        <input type="text" id="birthdate" name="birthdate" class="form-control" value="<?php echo isset($patient['birthdate']) ? $patient['birthdate'] : ''; ?>">
                        <label for="weight">Weight (kg):</label>
                        <input type="text" id="weight" name="weight" class="form-control" value="<?php echo isset($patient['weight']) ? $patient['weight'] : ''; ?>">
                        <label for="height">Height (cm):</label>
                        <input type="text" id="height" name="height" class="form-control" value="<?php echo isset($patient['height']) ? $patient['height'] : ''; ?>">
                        <label for="birthcontrol">Birth Control:</label>
                        <input type="text" id="birthcontrol" name="birthcontrol" class="form-control" value="<?php echo isset($patient['birthcontrol']) ? $patient['birthcontrol'] : ''; ?>">
                        <label for="diet">Diet:</label>
                        <input type="text" id="diet" name="diet" class="form-control" value="<?php echo isset($patient['diet']) ? $patient['diet'] : ''; ?>">
                        <label for="allergies">Allergies:</label>
                        <input type="text" id="allergies" name="allergies" class="form-control" value="<?php echo isset($patient['allergies']) ? $patient['allergies'] : ''; ?>">
                        <label for="symptoms">Symptoms:</label>
                        <input type="text" id="symptoms" name="symptoms" class="form-control" value="<?php echo isset($patient['symptoms']) ? $patient['symptoms'] : ''; ?>">
                    </div>
                    <input type="submit" name="updatepatient" class="btn btn-outline-secondary float-right"> 
                </form> 
            </div>
        </div>
    </div>
</div>

<!-- PHP: edit patient information -->
<?php
 if (isset($_POST['updatepatient'])) {
    // Retrieve and sanitize form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $birthdate = $_POST['birthdate'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $birthcontrol = $_POST['birthcontrol'];
    $diet = $_POST['diet'];
    $allergies = $_POST['allergies'];
    $patientid = $_GET['patientid']; // You also need to retrieve $patientid from the URL

    try {
        $sql = "UPDATE patient SET 
                firstname = :firstname, 
                lastname = :lastname, 
                birthdate = :birthdate, 
                weight = :weight, 
                height = :height, 
                birthcontrol = :birthcontrol, 
                diet = :diet, 
                allergies = :allergies 
                WHERE patientid = :patientid"; // Update SQL query to include all fields
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':firstname', $firstname);
        $stmt->bindValue(':lastname', $lastname);
        $stmt->bindValue(':birthdate', $birthdate);
        $stmt->bindValue(':weight', $weight);
        $stmt->bindValue(':height', $height);
        $stmt->bindValue(':birthcontrol', $birthcontrol);
        $stmt->bindValue(':diet', $diet);
        $stmt->bindValue(':allergies', $allergies);
        $stmt->bindValue(':patientid', $patientid); // Bind patientid parameter

        $stmt->execute(); // Execute the query

        // Redirect after successful update
        echo "<script>alert('Patient information updated successfully!')</script>";
        echo "<script>window.location.href='home.php?patientid=$patientid'</script>";
        
    } catch (PDOException $e) {
        // Handle exceptions
        echo "Error: " . $e->getMessage();
    }
}

?>