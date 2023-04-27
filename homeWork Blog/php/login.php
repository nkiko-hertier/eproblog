<?php
session_start();
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            header('Location: dashboard.php');
            exit();
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Invalid email or password";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Login | Eproblog</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<div class="login-container">
		<h1>Login</h1>
		<form action="" method="post">
			<div>
				<label for="email">Email:</label>
				<input type="email" name="email" required>
			</div>
			<div>
				<label for="password">Password:</label>
				<input type="password" name="password" required>
			</div>
			<?php if(isset($error)) { ?>
			<p class="error"><?php echo $error; ?></p>
			<?php } ?>
			<button type="submit" class="login-btn">Login</button>
		</form>
	</div>
</body>
</html>
