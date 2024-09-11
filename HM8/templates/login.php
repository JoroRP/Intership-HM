<?php

require '../vendor/autoload.php';
require '../src/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM Users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {

            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            header("Location: homepage.php");
            exit();
        } else {
            $errorMessage = "Invalid username or password.";
        }
    } else {
        $errorMessage = "Invalid username or password.";
    }

    $stmt->close();
    $conn->close();
}

?>

<!doctype html>
<html lang="en" data-bs-theme="auto">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sign In</title>
	<link rel="icon" type="image/team-icon" href="images/team-icon.png">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
		  integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<link href="CSS/custom.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">
<main class="container form-signin w-100 m-auto">
	<div class="container-fluid text-center">
		<img class="d-block mb-3 mx-auto" src="images/team-icon.png" alt="" width="72" height="72">
	</div>
	
	<form action="login.php" class="p-2 rounded text-body-emphasis shadow-lg" style="margin: 0px -50px"
		  method="post">
		<div>
			
			<h3 class=" mb-5 fw-normal text-center">Sign in</h3>
			
			
			<div class="form-floating">
				<input type="text" class="form-control" id="floatingInput" name="username" placeholder="" required>
				<label for="floatingInput">Username</label>
			</div>
			<div class="form-floating">
				<input type="password" class="form-control" id="floatingPassword" name="password" placeholder=""
					   required>
				<label for="floatingPassword">Password</label>
			</div>

            <?php if ($errorMessage): ?>
				<div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($errorMessage); ?>
				</div>
            <?php endif; ?>
			
			<button class="btn btn-primary w-100 mt-5 py-2" type="submit">Sign in</button>
		</div>

		<div class="container-fluid text-center mt-2">
			<span> Don't have an account?</span>
			<a href="register.php" <span>Register</span></a>
		</div>
	</form>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
