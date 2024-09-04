<?php

session_start();

require '../vendor/autoload.php';

use HM6\MainPanel;

$mainPanel = new MainPanel();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$mainPanel->loadSubjects();


$role = isset($_GET['role']) ? trim($_GET['role']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedRole = $_POST['role'] ?? '';
    $mainPanel->createUser($postedRole);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Create User - Admin Panel</title>
	<link rel="icon" type="image/x-icon" href="images/graduation-cap.png">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="CSS/custom.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">

<?php include 'navbar.html' ?>

<div class="container mt-5">

    <?php if (isset($_SESSION['admin_message'])): ?>
		<div id="message-container" style="margin-top: -2.5rem">
            <?php
            echo $_SESSION['admin_message'];
            unset($_SESSION['admin_message']);
            ?>
		</div>
		<script>
            setTimeout(function () {
                const messageContainer = document.getElementById('message-container');
                if (messageContainer) {
                    messageContainer.style.transition = "opacity 0.7s";
                    messageContainer.style.opacity = '0';
                    setTimeout(() => messageContainer.remove(), 500);
                }
            }, 4000);
		</script>

    <?php endif; ?>

	<div class="card shadow-lg mb-4 mx-3">
		<div class="card-header">
			<h2>Create a <?php echo htmlspecialchars($role); ?></h2>
		</div>
		<form method="post" action="create-user.php?role=<?php echo htmlspecialchars($role); ?>" class="m-3">
			<input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">
			<div class="mb-3">
				<label for="username" class="form-label">Username</label>
				<input type="text" name="username" id="username" class="form-control" required>
			</div>
			<div class="mb-3">
				<label for="password" class="form-label">Password</label>
				<input type="password" name="password" id="password" class="form-control" required>
			</div>
			<div class="mb-3">
				<label for="name" class="form-label">Name</label>
				<input type="text" name="name" id="name" class="form-control" required>
			</div>

			<div class="mb-2">
				<label class="form-label">Select Subjects</label>
				<div class="form-check">
                    <?php foreach ($mainPanel->Subjects as $subject): ?>
						<input type="checkbox" class="form-check-input" name="subjects[]"
							   value="<?php echo htmlspecialchars($subject->getName()); ?>">
						<label class="form-check-label"><?php echo htmlspecialchars($subject->getName()); ?></label><br>
                    <?php endforeach; ?>
				</div>
			</div>

			<button type="submit" class="btn btn-primary">Create User</button>
		</form>
	</div>
</div>

<?php include 'footer.html' ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
