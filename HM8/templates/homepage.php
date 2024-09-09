<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sports Team & Matches Management</title>
	<link rel="icon" type="image/x-icon" href="images/team-icon.png">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"
		  rel="stylesheet">
	<link href="CSS/custom.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<section class="hero-section">
	<div class="hero-content">
		<h1>Manage Your Teams and Matches</h1>
		<p>Track your team’s performance, manage matches, players, and view detailed statistics.</p>
        <?php if (isset($_SESSION['user_id'])): ?>
			<a href="teams.php" class="btn btn-primary btn-lg mt-3">Get Started</a>
        <?php else: ?>
			<a href="login.php" class="btn btn-primary btn-lg mt-3">Get Started</a>
        <?php endif ?>
	</div>
</section>

<section class="info-section">
	<div class="container">
		<div class="row text-center mb-5">
			<h2 class="mb-4">About Football Manager</h2>
		</div>
		<div class="row">
			<div class="col-md-4 mb-4">
				<div class="card info-card h-100">
					<img src="images/team-management.jpg" class="card-img-top" alt="Football Team">
					<div class="card-body">
						<h5 class="card-title">Team Management</h5>
						<p class="card-text">Add, edit, and manage teams easily. Assign players to teams and organize
							them efficiently.</p>
						<a href="teams.php" class="btn btn-outline-primary">Manage Teams</a>
					</div>
				</div>
			</div>
			<div class="col-md-4 mb-4">
				<div class="card info-card h-100">
					<img src="images/field.jpeg" class="card-img-top" alt="Football Match">
					<div class="card-body">
						<h5 class="card-title">Match Scheduling</h5>
						<p class="card-text">Schedule matches between teams, keep track of results, and manage match
							details effectively.</p>
						<a href="#" class="btn btn-outline-primary">View Matches</a>
					</div>
				</div>
			</div>
			<div class="col-md-4 mb-4">
				<div class="card info-card h-100">
					<img src="images/players.jpeg" class="card-img-top" alt="Football Stats">
					<div class="card-body">
						<h5 class="card-title">Player & Team Stats</h5>
						<p class="card-text">Analyze team and player performance with comprehensive statistics and
							reports.</p>
						<a href="players.php" class="btn btn-outline-primary">View Stats</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<footer class="footer">
	<div class="container">
		<p class="mb-0">&copy; 2024 FC Manager</p>
	</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
