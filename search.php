<?php
session_start();
ob_start(); // Start output buffering to prevent any output before headers

include("templates/conn.php");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patientid = $_POST['patientid'];
    $password = $_POST['password'];

    // Check if patientid and password match in the registration table
    $sql = "SELECT * FROM registration WHERE patientid=:patientid AND password=:password";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':patientid', $patientid);
    $stmt->bindValue(':password', $password); // Assuming password is stored as plaintext (not recommended)
    $stmt->execute();
    $registration = $stmt->fetch();

    if ($registration) {
        // Patient ID and password match, redirect to home page with patient ID in URL
        header("Location: home.php?patientid=$patientid");
        exit();
    } else {
        // Patient ID or password is incorrect
        $errorMessage = "Incorrect patient ID or password. Please try again.";
    }
}
ob_end_flush(); // Send the buffered output to the browser
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Search</title>
    <link rel="shortcut icon" href="images/logo.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="custom/custom.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</head>
<body>
<header>
    <div id="header-img" style="background-image: url(images/headerlg.png);"></div>
</header>

<div class="container">
    <h1>Log in</h1>
    <form method="POST" action="search.php">
        <label for="patientid" class="form-label">Patient ID:</label>
        <input type="text" class="form-control" id="patientid" name="patientid" required><br>
        <label for="password" class="form-label">Password:</label>
        <input type="password" class="form-control" id="password" name="password" required><br>
        <input type="submit" value="Submit" class="btn btn-outline-secondary float-right">
    </form>
    <?php if (isset($errorMessage)): ?>
        <div class="alert alert-danger mt-3" role="alert">
            <?php echo $errorMessage; ?>
        </div>
    <?php endif; ?>
</div>

<div class="container">
    <h1>Create a new account</h1>
    <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#addPatientModal">Add</button><br><br>
</div>

<div class="modal fade" id="addPatientModal" tabindex="-1" role="dialog" aria-labelledby="addPatientModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPatientModalLabel">Create a new account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="form-group">
                        <label for="firstname">First Name:</label>
                        <input type="text" id="firstname" name="firstname" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name:</label>
                        <input type="text" id="lastname" name="lastname" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="birthdate">Birthdate:</label>
                        <input type="text" id="birthdate" name="birthdate" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="birthcontrol">Birth Control:</label>
                        <select id="birthcontrol" name="birthcontrol" class="form-control" required>
                            <option value="None">None</option>
                            <option value="Pill">Pill</option>
                            <option value="Condom">Condom</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="weight">Weight:</label>
                        <input type="text" id="weight" name="weight" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="height">Height:</label>
                        <input type="text" id="height" name="height" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="ethnicity">Ethnicity:</label>
                        <select id="ethnicity" name="ethnicity" class="form-control" required>
                            <option value="Caucasian">Caucasian</option>
                            <option value="African American">African American</option>
                            <option value="Asian">Asian</select>
                    </div>
                    <div class="form-group">
                        <label for="diet">Diet:</label>
                        <select id="diet" name="diet" class="form-control" required>
                            <option value="Vegetarian">Vegetarian</option>
                            <option value="Vegan">Vegan</option>
                            <option value="Keto">Keto</select>
                    </div>
                    <div class="form-group">
                        <label for="allergies">Allergies:</label><br>
                        <input type="checkbox" id="allergy1" name="allergies[]" value="Peanuts">
                        <label for="allergy1">Peanuts</label><br>
                        <input type="checkbox" id="allergy2" name="allergies[]" value="Gluten">
                        <label for="allergy2">Gluten</label><br>
                    </div>
                    <div class="form-group">
                        <label for="symptom">Symptom:</label><br>
                        <input type="checkbox" id="symptom1" name="symptoms[]" value="Irregular Periods">
                        <label for="symptom1">Irregular Periods</label><br>
                        <input type="checkbox" id="symptom2" name="symptoms[]" value="Acne">
                        <label for="symptom2">Acne</label><br>
                    </div>
                    <input type="submit" name="addpatient" class="btn btn-outline-secondary float-right" value="Add Patient">
                </form>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_POST['addpatient'])) {
    if (isset($_POST['email'])) {
        $patientid = $_POST['email']; // using 'patientid' as email
    } else {
        echo "<script>alert('Email is required!')</script>";
        exit; // Exit script if email is not provided
    }
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $password = $_POST['password'];
    $birthdate = $_POST['birthdate'];
    $birthcontrol = $_POST['birthcontrol'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $ethnicity = $_POST['ethnicity'];
    $diet = $_POST['diet'];
    $allergies = isset($_POST['allergies']) ? implode(", ", $_POST['allergies']) : null;
    $symptoms = isset($_POST['symptoms']) ? implode(", ", $_POST['symptoms']) : null;

    try {
        // Insert email and password into registration table
        $add_registration = "INSERT INTO registration (patientid, password) VALUES (:patientid, :password)";
        $stmt_registration = $conn->prepare($add_registration);
        $stmt_registration->bindValue(':patientid', $patientid);
        $stmt_registration->bindValue(':password', $password);
        $stmt_registration->execute();

        // Insert additional information into patient table
        $add_patient_info = "INSERT INTO patient (patientid, firstname, lastname, birthdate, birthcontrol, weight, height, ethnicity, diet, allergies, symptoms) VALUES (:patientid, :firstname, :lastname, :birthdate, :birthcontrol, :weight, :height, :ethnicity, :diet, :allergies, :symptoms)";
        $stmt_patient_info = $conn->prepare($add_patient_info);
        $stmt_patient_info->bindValue(':patientid', $patientid);
        $stmt_patient_info->bindValue(':firstname', $firstname);
        $stmt_patient_info->bindValue(':lastname', $lastname);
        $stmt_patient_info->bindValue(':birthdate', $birthdate);
        $stmt_patient_info->bindValue(':birthcontrol', $birthcontrol);
        $stmt_patient_info->bindValue(':weight', $weight);
        $stmt_patient_info->bindValue(':height', $height);
        $stmt_patient_info->bindValue(':ethnicity', $ethnicity);
        $stmt_patient_info->bindValue(':diet', $diet);
        $stmt_patient_info->bindValue(':allergies', $allergies);
        $stmt_patient_info->bindValue(':symptoms', $symptoms);
        $stmt_patient_info->execute();

        echo "<script>alert('Patient added successfully!')</script>";
        // Redirect to the desired location
        // Example: echo "<script>window.location.href='desired_location.php'</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>


</body>
</html>





