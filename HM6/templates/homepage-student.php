<?php

session_start();

require '../vendor/autoload.php';

use HM6\MainPanel;
use HM6\User;

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$mainPanel = new MainPanel();
$mainPanel->loadSubjects();

$role = $_SESSION['role'];
$username = $_SESSION['username'];
$name = $_SESSION['name'];

$studentInfo = new User($username, '', $role, $name);


$studentGradesHtml = '';
foreach ($mainPanel->getSubjects() as $subject) {
    $subjectName = htmlspecialchars($subject->getName());
    $grades = $subject->getGrades();

    if (isset($grades[$username])) {
        $studentGrades = $grades[$username];
        $gradesText = is_array($studentGrades) ? implode(', ', $studentGrades) : $studentGrades;
        $studentGradesHtml .= '<tr><td>' . $subjectName . '</td><td>' . htmlspecialchars($gradesText) . '</td></tr>';
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Student Dashboard</title>
	<link rel="icon" type="image/x-icon" href="images/graduation-cap.png">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="CSS/custom.css" rel="stylesheet">
</head>

<body>

<?php include 'navbar-student.html'; ?>

<div class="mt-3 container-fluid">
	<h1 class="mb-5">Welcome, <?php echo htmlspecialchars($name); ?>!</h1>

	<div class="card shadow-lg mb-4 mx-auto" style="width: 35rem;">
		<div class="card-header">
			<h4>Student Grades</h4>
		</div>
		<div class="card-body">
			<table class="table table-striped">
				<thead>
				<tr>
					<th>Subject</th>
					<th>Grades</th>
				</tr>
				</thead>
				<tbody>
                <?php echo $studentGradesHtml; ?>
				</tbody>
			</table>
		</div>
	</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
