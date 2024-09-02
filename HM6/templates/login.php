<?php

require '../vendor/autoload.php';

use HM6\MainPanel;

session_start();

$mainPanel = new MainPanel();
$mainPanel->loadUsers();
$mainPanel->addAdmin();

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'] ?? '';
    $username = trim($username);
    $password = $_POST['password'] ?? '';
    $password = trim($password);

    $loggedIn = $mainPanel->authenticateUser($username, $password);

    if ($loggedIn) {

        if (isset($_SESSION['role'])) {

            if ($_SESSION['role'] === 'admin') {
                header("Location: homepage-admin.php");
                exit();
            } else if ($_SESSION['role'] === 'teacher') {
                header("Location: homepage-teacher.php");
                exit();
            } else if ($_SESSION['role'] === 'student') {
                header("Location: homepage-student.php");
                exit();
            } else {
                $errorMessage = "Invalid username or password.";
            }
        }
    }
}
?>
<!doctype html>
<html lang="en" data-bs-theme="auto">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sign In</title>
	<link rel="icon" type="image/x-icon" href="images/graduation-cap.png">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
		  integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<link href="CSS/custom.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">
<main class="container form-signin w-100 m-auto">
	<form action="login.php" class="p-2 rounded text-body-emphasis bg-body-secondary" style="margin: -80px"
		  method="post">
		<div>
			<img class="d-block mt-1 mb-3 mx-auto" src="images/graduation-cap.png" alt="" width="72" height="72">
			<h3 class=" mb-3 fw-normal text-center">Sign in to ®Daskalo</h3>
			<h5 class=" mb-3 fw-normal text-center">(Not affiliated to Shkolo in any way!)</h5>
			
			<div class="form-floating">
				<input type="text" class="form-control" id="floatingInput" name="username" placeholder="">
				<label for="floatingInput">Username</label>
			</div>
			<div class="form-floating">
				<input type="password" class="form-control" id="floatingPassword" name="password" placeholder="">
				<label for="floatingPassword">Password</label>
			</div>

            <?php if ($errorMessage): ?>
				<div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($errorMessage); ?>
                    <?php echo htmlspecialchars($_SESSION['role']); ?>
				</div>
            <?php endif; ?>
			
			<button class="btn btn-primary w-100 my-5 py-2" type="submit">Sign in</button>
		</div>
	</form>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
