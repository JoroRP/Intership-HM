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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Manage Teams</title>
	<link rel="icon" type="image/x-icon" href="images/team-icon.png">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"
		  rel="stylesheet">
	<link href="CSS/custom.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-5">
	<h2>Manage Teams</h2>
	<a href="add-team.php" class="btn btn-primary mb-3">Add New Team</a>
	<table class="table table-bordered table-hover table-striped">
		<thead>
		<tr>
			<th>ID</th>
			<th>Team Name</th>
			<th>City</th>
			<th>Actions</th>
		</tr>
		</thead>
		<tbody>
        <?php foreach ($teams as $team): ?>
			<tr>
				<td><?php echo htmlspecialchars($team['id']); ?></td>
				<td><?php echo htmlspecialchars($team['name']); ?></td>
				<td><?php echo htmlspecialchars($team['city']); ?></td>
				<td>
					<a href="edit-team.php?id=<?php echo $team['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
					<a href="delete_team.php?id=<?php echo $team['id']; ?>" class="btn btn-danger btn-sm"
					   onclick="return confirm('Are you sure you want to delete this team?');">Delete</a>
					<a href="players.php?team_id=<?php echo $team['id']; ?>" class="btn btn-secondary btn-sm">Manage
						Players</a>
				</td>
			</tr>
        <?php endforeach; ?>
		</tbody>
	</table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
