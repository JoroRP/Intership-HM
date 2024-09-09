<?php

include 'common-items.php';

$teams = [];
$sql = "SELECT * FROM sports_teams";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $teams[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $position = $_POST['position'];
    $team_id = $_POST['team_id'] ?? null;

    $stmt = $conn->prepare("INSERT INTO players (name, age, position, team_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sisi", $name, $age, $position, $team_id);

    try {
        $stmt->execute();
        echo "Player added successfully.";
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $e->getMessage();
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
	<title>Add Player</title>
	<link rel="icon" type="image/x-icon" href="images/team-icon.png">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"
		  rel="stylesheet">
	<link href="CSS/custom.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-5">
	<h2>Add Player</h2>
	<form action="add-player.php" method="post">
		<div class="mb-3">
			<label for="name" class="form-label">Name</label>
			<input type="text" class="form-control" id="name" name="name" required>
		</div>
		<div class="mb-3">
			<label for="age" class="form-label">Age</label>
			<input type="number" class="form-control" id="age" name="age" required>
		</div>
		<div class="mb-3">
			<label for="position" class="form-label">Position</label>
			<select id="position" name="position" class="form-select" required>
				<option value="goalkeeper">Goalkeeper</option>
				<option value="defender">Defender</option>
				<option value="midfielder">Midfielder</option>
				<option value="forward">Forward</option>
			</select>
		</div>
		<div class="mb-3">
			<label for="team_id" class="form-label">Team</label>
			<select id="team_id" name="team_id" class="form-select">
				<option value="">No Team</option>
                <?php foreach ($teams as $team): ?>
					<option value="<?php echo $team['id']; ?>"><?php echo htmlspecialchars($team['name']); ?></option>
                <?php endforeach; ?>
			</select>
		</div>
		<button type="submit" class="btn btn-primary">Add Player</button>
	</form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
