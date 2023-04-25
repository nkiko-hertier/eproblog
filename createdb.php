<?php
$servername = "localhost";
$username = "your_username";
$password = "your_password";

// Create connection
$conn = mysqli_connect($servername, $username, $password);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Create database
$sql = "CREATE DATABASE eproblog";
if (mysqli_query($conn, $sql)) {
  echo "Database created successfully";
} else {
  echo "Error creating database: " . mysqli_error($conn);
}

// Connect to the database
mysqli_select_db($conn, "eproblog");

// Create User table
$sql = "CREATE TABLE User (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(20) NOT NULL
)";
if (mysqli_query($conn, $sql)) {
  echo "User table created successfully";
} else {
  echo "Error creating User table: " . mysqli_error($conn);
}

// Create Category table
$sql = "CREATE TABLE Category (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL
)";
if (mysqli_query($conn, $sql)) {
  echo "Category table created successfully";
} else {
  echo "Error creating Category table: " . mysqli_error($conn);
}

// Create Post table
$sql = "CREATE TABLE Post (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100) NOT NULL,
  content TEXT NOT NULL,
  author_id INT(6) UNSIGNED,
  category_id INT(6) UNSIGNED,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (author_id) REFERENCES User(id),
  FOREIGN KEY (category_id) REFERENCES Category(id)
)";
if (mysqli_query($conn, $sql)) {
  echo "Post table created successfully";
} else {
  echo "Error creating Post table: " . mysqli_error($conn);
}

// Create Comment table
$sql = "CREATE TABLE Comment (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  content TEXT NOT NULL,
  author_id INT(6) UNSIGNED,
  post_id INT(6) UNSIGNED,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (author_id) REFERENCES User(id),
  FOREIGN KEY (post_id) REFERENCES Post(id)
)";
if (mysqli_query($conn, $sql)) {
  echo "Comment table created successfully";
} else {
  echo "Error creating Comment table: " . mysqli_error($conn);
}

// Create Tag table
$sql = "CREATE TABLE Tag (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL
)";
if (mysqli_query($conn, $sql)) {
  echo "Tag table created successfully";
} else {
  echo "Error creating Tag table: " . mysqli_error($conn);
}

// Create Post_Tag table
$sql = "CREATE TABLE Post_Tag (
  post_id INT(6) UNSIGNED,
  tag_id INT(6) UNSIGNED,
  FOREIGN KEY (post_id) REFERENCES Post(id),
  FOREIGN KEY (tag_id) REFERENCES Tag(id)
)";
if (mysqli_query($conn, $sql)) {
  echo "Post_Tag table created successfully";
} else {
  echo "Error creating Post_Tag table: " . mysqli_error($conn);
}

// Create Subscriber table
$sql = "CREATE TABLE Subscriber (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(50) NOT NULL
)";
if (mysqli_query($conn, $sql)) {
  echo "Subscriber table created successfully";
} else {
  echo "Error creating Subscriber table: " . mysqli_error($conn);
}

mysqli_close($conn);
?>

 
