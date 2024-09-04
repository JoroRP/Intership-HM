<?php

session_start();

require '../vendor/autoload.php';

use HM6\MainPanel;
use HM6\User;


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}


$mainPanel = new MainPanel();
$mainPanel->loadSubjects();


$role = $_SESSION['role'];
$username = $_SESSION['username'];
$name = $_SESSION['name'];

$teacherInfo = new User($username, '', $role, $name);

$assignedSubjectsHtml = '';
foreach ($mainPanel->getSubjects() as $subject) {
    foreach ($subject->getTeachers() as $teacher) {
        if ($teacher->getUsername() === $teacherInfo->getUsername()) {
            $assignedSubjectsHtml .= '<tr><td colspan="2">' . htmlspecialchars($subject->getName()) . '</td></tr>';
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Teacher Dashboard</title>
	<link rel="icon" type="image/x-icon" href="images/graduation-cap.png">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="CSS/custom.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">

<?php include 'navbar-teacher.html' ?>

<div class="mt-3 container-fluid">

	<div class="card shadow-lg mb-4 mx-auto" style="width: 35rem; position: ">
		<div class="card-header">
			<h4>The Subjects you teach are:</h4>
		</div>
		<div class="card-body">
			<table class="table text-center">
				<thead>
				<tr>
					<th>Subject</th>
				</tr>
				</thead>
				<tbody class="table-info">
                <?php echo $assignedSubjectsHtml; ?>
				</tbody>
			</table>
		</div>
	</div>

	<div class="card shadow-lg mb-4 mx-4">
		<div class="card-header">
			<h2>Teacher Panel</h2>
		</div>
		<div class="card-body">
			<table class="table table-striped">
				<thead>
				<tr>
					<th>Action</th>
					<th>Link</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td>Grade a student</td>
					<td><a href="grade-student.php" class="btn btn-primary">Grade</a></td>
				</tr>

				</tbody>
			</table>
		</div>
	</div>

</div>

<?php include 'footer.html' ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
