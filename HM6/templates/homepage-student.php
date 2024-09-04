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


$studentGradesToHtml = '';
foreach ($mainPanel->getSubjects() as $subject) {
    $subjectName = htmlspecialchars($subject->getName());
    $grades = $subject->getGrades();
    $enrolledSubject = $subject->getStudents();

    if (isset($grades[$username])) {
        $studentGrades = $grades[$username];
        $gradesText = is_array($studentGrades) ? implode(', ', $studentGrades) : $studentGrades;

        $studentGradesToHtml .= '<tr><td>' . $subjectName . '</td><td>' . htmlspecialchars($gradesText) . '</td></tr>';

    } else {

        foreach ($subject->getStudents() as $student) {
            if ($student->getUsername() === $studentInfo->getUsername()) {
                $studentGradesToHtml .= '<tr><td>' . $subjectName . '</td><td></td></tr>';
            }
        }
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

<body class="d-flex flex-column h-100">

<?php include 'navbar-student.html'; ?>

<div class="mt-3 container-fluid">

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
                <?php echo $studentGradesToHtml; ?>
				</tbody>
			</table>
		</div>
	</div>

</div>

<?php include 'footer.html' ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
