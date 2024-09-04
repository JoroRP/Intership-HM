<?php

session_start();

require '../vendor/autoload.php';

use HM6\MainPanel;
use HM6\User;

$mainPanel = new MainPanel();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

$mainPanel->loadSubjects();
$mainPanel->loadUsers();

$subjects = $mainPanel->getSubjects();
$users = $mainPanel->getUsers();

$students = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subjectName = $_POST['subject'] ?? '';
    $username = $_POST['username'] ?? '';
    $grade = (float)($_POST['grade'] ?? 0);

    $mainPanel->gradeStudent($subjectName, $username, $grade);

}

$subjectName = $_GET['subject'] ?? '';
if ($subjectName) {
    foreach ($subjects as $subject) {
        if ($subject->getName() === $subjectName) {
            $students = array_map(fn($student) => $student->getUsername(), $subject->getStudents());
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Grade Student</title>
	<link rel="icon" type="image/x-icon" href="images/graduation-cap.png">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="CSS/custom.css" rel="stylesheet">
</head>

<body>

<?php include 'navbar-teacher.html'; ?>

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
	
	<div class="card shadow-lg mb-4 mx-auto" style="width: 50rem;">
		<div class="card-header">
			<h2>Grade Student</h2>
		</div>
		<div class="card-body">
			<form method="post" action="">
				<div class="mb-3">
					<label for="subject" class="form-label">Select Subject</label>
					<select name="subject" id="subject" class="form-select" required>
						<option value="" disabled selected>Select subject</option>
                        <?php foreach ($subjects as $subject): ?>
							<option value="<?php echo htmlspecialchars($subject->getName()); ?>"
                                <?php echo isset($subjectName) && $subjectName === $subject->getName() ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($subject->getName()); ?>
							</option>
                        <?php endforeach; ?>
					</select>
				</div>
				
				<div class="mb-3">
					<label for="username" class="form-label">Select Student</label>
					<select name="username" id="username" class="form-select" required>
						<option value="" disabled selected>Select student</option>
                        <?php foreach ($users as $user): ?>
                            <?php if ($user->getRole() === 'student'): ?>
								<option value="<?php echo htmlspecialchars($user->getUsername()); ?>">
                                    <?php echo htmlspecialchars($user->getName()) . " (" . htmlspecialchars($user->getUsername()) . ")"; ?>
								</option>
                            <?php endif; ?>
                        <?php endforeach; ?>
					</select>
				</div>
				
				<div class="mb-3">
					<label for="grade" class="form-label">Grade</label>
					<input type="number" step="0.1" name="grade" id="grade" class="form-control" min="2" max="6"
						   required>
				</div>
				
				<button type="submit" class="btn btn-primary">Submit Grade</button>
			</form>
		</div>
	</div>
</div>

<?php include 'footer.html' ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
