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
$mainPanel->loadUsers();

$role = isset($_GET['role']) ? trim($_GET['role']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $postedRole = $_POST['role'] ?? '';
    $mainPanel->removeUser($postedRole);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Remove User - Admin Panel</title>
	<link rel="icon" type="image/x-icon" href="images/graduation-cap.png">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="CSS/custom.css" rel="stylesheet">
</head>

<body>

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
	
	<div class="card shadow-lg mb-4 mx-3">
		<div class="card-header">
			<h2>Remove a <?php echo htmlspecialchars($role); ?></h2>
		</div>
		<form method="post" action="remove-user.php" class="m-3">
			<input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">

			<div class="mb-3">
				<label for="username" class="form-label">Select User to Remove</label>
				<select name="username" id="username" class="form-select" required>
					<option value="" disabled selected>Select username</option>
                    <?php
                    $roleUsers = array_filter($mainPanel->Users, function ($user) use ($role) {
                        return $user->getRole() === $role;
                    });

                    if (!empty($roleUsers)) {
                        foreach ($roleUsers as $user) {
                            echo "<option value=\"" . htmlspecialchars($user->getUsername()) . "\">"
                                . htmlspecialchars($user->getName()) . " (" . htmlspecialchars($user->getUsername()) . ")"
                                . "</option>";
                        }
                    } else {
                        echo "<option value=\"\" disabled>No users available</option>";
                    }
                    ?>
				</select>
			</div>
			<button type="submit" class="btn btn-danger">Remove User</button>
		</form>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
