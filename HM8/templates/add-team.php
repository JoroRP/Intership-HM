<?php

include 'common-items.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $city = $_POST['city'];

    $stmt = $conn->prepare("INSERT INTO sports_teams (name, city) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $city);

    if ($stmt->execute()) {
        header("Location: teams.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Add New Team</title>
	<link rel="icon" type="image/x-icon" href="images/team-icon.png">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"
		  rel="stylesheet">
	<link href="CSS/custom.css" rel="stylesheet">

</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-5">
	<h2>Add New Team</h2>
	<form action="add-team.php" method="POST">
		<div class="mb-3">
			<label for="name" class="form-label">Team Name</label>
			<input type="text" class="form-control" id="name" name="name" required>
		</div>
		<div class="mb-3">
			<label for="city" class="form-label">City</label>
			<input type="text" class="form-control" id="city" name="city" required>
		</div>
		<button type="submit" class="btn btn-primary">Add Team</button>
	</form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
