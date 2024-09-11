<?php

include 'common-items.php';

$teams = [];
$sql = "SELECT * FROM sports_teams";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $teams[$row['id']] = $row['name'];
    }
}

$selectedTeamId = isset($_GET['team_id']) ? (int)$_GET['team_id'] : 0;
$playerSql = $selectedTeamId > 0
    ? "SELECT * FROM players WHERE team_id = ?"
    : "SELECT * FROM players WHERE team_id IS NULL";

$stmt = $conn->prepare($playerSql);

if ($selectedTeamId > 0) {
    $stmt->bind_param("i", $selectedTeamId);
}

$stmt->execute();
$playerResult = $stmt->get_result();

$players = [];
if ($playerResult->num_rows > 0) {
    while ($row = $playerResult->fetch_assoc()) {
        $players[] = $row;
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Manage Players</title>
	<link rel="icon" type="image/x-icon" href="images/team-icon.png">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"
		  rel="stylesheet">
	<link href="CSS/custom.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-5">
	<h2>Manage Players</h2>
	<a href="add-player.php" class="btn btn-primary mb-3">Add New Player</a>

	<div class="mb-3">
		<form method="GET" action="players.php">
			<label for="teamFilter" class="form-label">Filter by Team:</label>
			<select id="teamFilter" name="team_id" class="form-select" onchange="this.form.submit()">
				<option value="0" <?php echo $selectedTeamId == 0 ? 'selected' : ''; ?>>No Team</option>
                <?php foreach ($teams as $teamId => $teamName): ?>
					<option value="<?php echo htmlspecialchars($teamId); ?>" <?php echo $selectedTeamId == $teamId ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($teamName); ?>
					</option>
                <?php endforeach; ?>
			</select>
		</form>
	</div>

	<table class="table table-bordered table-hover table-striped">
		<thead>
		<tr>
			<th>Name</th>
			<th>Age</th>
			<th>Position</th>
			<th>Team</th>
			<th>Actions</th>
		</tr>
		</thead>
		<tbody>
        <?php foreach ($players as $player): ?>
			<tr>
				<td><?php echo htmlspecialchars($player['name']); ?></td>
				<td><?php echo htmlspecialchars($player['age']); ?></td>
				<td><?php echo htmlspecialchars($player['position']); ?></td>
				<td>
                    <?php echo isset($teams[$player['team_id']]) ? htmlspecialchars($teams[$player['team_id']]) : 'No Team'; ?>
				</td>
				<td>
					<a href="edit-player.php?id=<?php echo $player['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
					<a href="delete-player.php?id=<?php echo $player['id']; ?>" class="btn btn-danger btn-sm"
					   onclick="return confirm('Are you sure you want to delete this player?');">Delete</a>
				</td>
			</tr>
        <?php endforeach; ?>
		</tbody>
	</table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
