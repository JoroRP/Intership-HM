<?php
require '../vendor/autoload.php';
require '../src/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $playerId = $_POST['player_id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $position = $_POST['position'];
    $teamId = $_POST['team_id'] === '' ? null : $_POST['team_id']; // Handle empty value

    $validPositions = ['goalkeeper', 'defender', 'midfielder', 'forward'];
    if (!in_array($position, $validPositions, true)) {
        die("Invalid position value: " . htmlspecialchars($position));
    }

    $sql = "UPDATE players SET name = ?, age = ?, position = ?, team_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("ssiii", $name, $age, $position, $teamId, $playerId);

    if ($stmt->execute()) {
        header("Location: players.php");
        exit();
    } else {
        echo "Error updating record: " . htmlspecialchars($conn->error);
    }

    $stmt->close();
}

$playerId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$playerSql = "SELECT * FROM players WHERE id = ?";
$stmt = $conn->prepare($playerSql);
if ($stmt === false) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}
$stmt->bind_param("i", $playerId);
$stmt->execute();
$playerResult = $stmt->get_result();
$player = $playerResult->fetch_assoc();
$stmt->close();

$teams = [];
$teamSql = "SELECT * FROM sports_teams";
$result = $conn->query($teamSql);
while ($row = $result->fetch_assoc()) {
    $teams[] = $row;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit Player</title>
	<link rel="icon" type="image/x-icon" href="images/team-icon.png">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="CSS/custom.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
	<h2>Edit Player</h2>
	<form method="POST" action="edit-player.php">
		<input type="hidden" name="player_id" value="<?php echo htmlspecialchars($player['id']); ?>">
		<div class="mb-3">
			<label for="name" class="form-label">Name</label>
			<input type="text" class="form-control" id="name" name="name"
				   value="<?php echo htmlspecialchars($player['name']); ?>" required>
		</div>
		<div class="mb-3">
			<label for="age" class="form-label">Age</label>
			<input type="number" class="form-control" id="age" name="age"
				   value="<?php echo htmlspecialchars($player['age']); ?>" required>
		</div>
		<div class="mb-3">
			<label for="position" class="form-label">Position</label>
			<select id="position" name="position" class="form-select" required>
				<option value="goalkeeper" <?php echo $player['position'] === 'goalkeeper' ? 'selected' : ''; ?>>
					Goalkeeper
				</option>
				<option value="defender" <?php echo $player['position'] === 'defender' ? 'selected' : ''; ?>>Defender
				</option>
				<option value="midfielder" <?php echo $player['position'] === 'midfielder' ? 'selected' : ''; ?>>
					Midfielder
				</option>
				<option value="forward" <?php echo $player['position'] === 'forward' ? 'selected' : ''; ?>>Forward
				</option>
			</select>
		</div>
		<div class="mb-3">
			<label for="team" class="form-label">Team</label>
			<select id="team" name="team_id" class="form-select">
				<option value="">No Team</option>
                <?php foreach ($teams as $team): ?>
					<option value="<?php echo htmlspecialchars($team['id']); ?>" <?php echo $player['team_id'] == $team['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($team['name']); ?>
					</option>
                <?php endforeach; ?>
			</select>
		</div>
		<button type="submit" class="btn btn-primary">Save Changes</button>
	</form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
