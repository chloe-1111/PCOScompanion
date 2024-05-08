<?php
include("header.php");
?>

<!-- display user name -->
<?php
	// Check if patientid is passed in the URL
	if (isset($_GET['patientid'])) {
		$patientid = $_GET['patientid'];
	} else {
		// Redirect to login page if patientid is not provided
		header("Location: search.php");
		exit();
	}

	// Check if patientid exists in the registration table
	$sql = "SELECT * FROM registration WHERE patientid=:patientid";
	$stmt = $conn->prepare($sql);
	$stmt->bindValue(':patientid', $patientid);
	$stmt->execute();
	$registration = $stmt->fetch();

	if (!$registration) {
		// Patient ID not found, display error message
		$errorMessage = "Patient ID not found.";
	}
?>


<body><br>
	<!-- Display Welcome Message or Error Message -->
	<div class="container">
		<?php if (isset($registration)) : ?>
			<h1>Welcome, <?php echo $patientid; ?>!</h1>
		<?php elseif (isset($errorMessage)) : ?>
			<p class="text-danger"><?php echo $errorMessage; ?></p>
		<?php endif; ?>
    </div>
</body>
</html>




