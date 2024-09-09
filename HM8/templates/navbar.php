<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg ">
	<div class="container">
		<a class="navbar-brand" href="homepage.php">
			<img src="images/team-icon.png" alt="FC Manager" width="35" height="35">
			<span> FC Manager </span>
		</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
				aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse justify-content-end" id="navbarNav">
			<ul class="navbar-nav">
                <?php if (isset($_SESSION['user_id'])): ?>
					<li class="nav-item">
						<a class="nav-link active" href="teams.php">Teams</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" href="players.php">Players</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" href="logout.php">Logout</a>
					</li>
                <?php else: ?>
					<li class="nav-item">
						<a class="nav-link active" href="login.php">Login</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" href="register.php">Register</a>
					</li>
                <?php endif; ?>
			</ul>
		</div>
	</div>
</nav>
