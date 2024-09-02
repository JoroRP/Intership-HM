<?php

session_start();

require '../vendor/autoload.php';

use HM6\MainPanel;

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$mainPanel = new MainPanel();
$mainPanel->loadSubjects();

$role = $_SESSION['role'];
$username = $_SESSION['username'];
$name = $_SESSION['name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    if ($_GET['action'] === 'create_subject') {
        $mainPanel->createSubject();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Dashboard</title>
	<link rel="icon" type="image/x-icon" href="images/graduation-cap.png">
	
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="CSS/custom.css" rel="stylesheet">
</head>

<body>

<?php include 'navbar.html' ?>

<div class="mt-3 container-fluid">
	<h1 class="mb-5">Welcome, <?php echo htmlspecialchars($name) ?>!</h1>

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

	<div class="card shadow-lg mb-4 mx-4">
		<div class="card-header">
			<h2>Admin Panel</h2>
		</div>
		<div class="card-body">
			<table class="table table-hover table-striped">
				<thead>
				<tr>
					<th>Action</th>
					<th>Link</th>
				</tr>
				</thead>
				<tbody class="table-group-divider">
				<tr>
					<td>Create a subject</td>
					<td>
						<form method="post" action="?action=create_subject">
							<div class="row mb-2">
								<div class="col-sm-3 pe-0 align-self-center">
									<label for="subject_name" class="form-label">Subject Name:</label>
								</div>
								<div class="col-sm-6 px-0">
									<input type="text" class="form-control" id="subject_name" name="subject_name">
								</div>
								<div class="col-sm-3">
									<button type="submit" class="btn btn-primary">Create Subject</button>
								</div>
							</div>
						</form>
					</td>
				</tr>
				<tr>
					<td>Create a teacher</td>
					<td>
						<a href="create-user.php?role=teacher">
							<button type="submit" class="btn btn-primary">Create</button>
						</a>
					</td>
				</tr>
				<tr>
					<td>Create a student</td>
					<td>
						<a href="create-user.php?role=student">
							<button type="submit" class="btn btn-primary">Create</button>
						</a>
					</td>
				</tr>
				<tr>
					<td>Remove a subject</td>
					<td>
						<a href="remove-subject.php">
							<button type="submit" class="btn btn-primary">Remove</button>
						</a>
					</td>
				</tr>
				<tr>
					<td>Remove a teacher</td>
					<td>
						<a href="remove-user.php?role=teacher">
							<button type="submit" class="btn btn-primary">Remove</button>
						</a>
					</td>
				</tr>
				<tr>
					<td>Remove a student</td>
					<td>
						<a href="remove-user.php?role=student">
							<button type="submit" class="btn btn-primary">Remove</button>
						</a>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
