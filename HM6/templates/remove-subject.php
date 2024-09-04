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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subject_name'])) {

    $mainPanel->removeSubject();
    header("Location: remove-subject.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Remove Subject - Admin Panel</title>
	<link rel="icon" type="image/x-icon" href="images/graduation-cap.png">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="CSS/custom.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">

<?php include 'navbar.html'; ?>

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

	<div class="card shadow-lg mt-5 mx-3">
		<div class="card-header">
			<h2>Remove a Subject</h2>
		</div>
		<form method="post" action="remove-subject.php" class="m-3">
			<div class="mb-3">
				<label class="form-label">Select Subject to Remove</label>
				<div class="form-check">
                    <?php if (!empty($mainPanel->Subjects)): ?>
                        <?php foreach ($mainPanel->Subjects as $subject): ?>
							<div class="form-check">
								<input type="radio" class="form-check-input" name="subject_name"
									   id="subject_<?php echo htmlspecialchars($subject->getName()); ?>"
									   value="<?php echo htmlspecialchars($subject->getName()); ?>" required>
								<label class="form-check-label"
									   for="subject_<?php echo htmlspecialchars($subject->getName()); ?>">
                                    <?php echo htmlspecialchars($subject->getName()); ?>
								</label>
							</div>
                        <?php endforeach; ?>
                    <?php else: ?>
						<p>No subjects available to remove.</p>
                    <?php endif; ?>
				</div>
			</div>
			<button type="submit" class="btn btn-danger">Remove Subject</button>
		</form>
	</div>
</div>

<?php include 'footer.html' ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
