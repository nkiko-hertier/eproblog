<?php
require_once "dbconfig.php"; // Database configuration file with DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $status = $_POST["status"];

  // Hash password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert new user into database
  $stmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
  $stmt->execute([$username, $email, $hashed_password, $status]);

  // Retrieve assigned user ID
  $user_id = $db->lastInsertId();

  // Redirect to homepage
  header("Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register - Eproblog</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h1>Register</h1>
    <form method="post" action="register.php">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>

      <label for="status">Status:</label>
      <select id="status" name="status">
        <option value="subscriber">Subscriber</option>
        <?php if (empty(getUsersByRole("admin"))): ?>
          <!-- Only show "Admin" option if there are no admins in the database -->
          <option value="admin">Admin</option>
        <?php endif; ?>
        <option value="author">Author</option>
      </select>

      <input type="submit" value="Register">
    </form>
  </div>
</body>
</html>
