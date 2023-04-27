<?php
session_start();
if(!isset($_SESSION["username"])) {
  header("Location: login.php");
  exit();
}

require_once "config.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = mysqli_real_escape_string($conn, $_POST["title"]);
  $content = mysqli_real_escape_string($conn, $_POST["content"]);
  $tags = explode(",", $_POST["tags"]);

  // Insert post into database
  $query = "INSERT INTO Post (title, content, author_id, category_id) VALUES ('$title', '$content', {$_SESSION['user_id']}, 1)";
  mysqli_query($conn, $query);
  $post_id = mysqli_insert_id($conn);

  // Add tags to database if they don't already exist
  $tag_ids = [];
  foreach ($tags as $tag_name) {
    $tag_name = trim($tag_name);
    if (!empty($tag_name)) {
      $query = "SELECT id FROM Tag WHERE name = '$tag_name'";
      $result = mysqli_query($conn, $query);
      if (mysqli_num_rows($result) == 0) {
        $query = "INSERT INTO Tag (name) VALUES ('$tag_name')";
        mysqli_query($conn, $query);
        $tag_id = mysqli_insert_id($conn);
      } else {
        $tag_id = mysqli_fetch_assoc($result)["id"];
      }
      $tag_ids[] = $tag_id;
    }
  }

  // Add post-tag relationships to database
  foreach ($tag_ids as $tag_id) {
    $query = "INSERT INTO Post_Tag (post_id, tag_id) VALUES ($post_id, $tag_id)";
    mysqli_query($conn, $query);
  }

  mysqli_close($conn);
  header("Location: index.php");
  exit();
}

// Handle tag suggestions
if (isset($_GET["q"])) {
  $query = "SELECT name FROM Tag WHERE name LIKE '{$_GET['q']}%' LIMIT 10";
  $result = mysqli_query($conn, $query);
  $tag_names = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $tag_names[] = $row["name"];
  }
  echo json_encode($tag_names);
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Create Post - Eproblog</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      $("#tags").on("input", function() {
        let query = $(this).val();
        if (query.length > 0) {
          $.get("create_post.php?q=" + query, function(data) {
            let tagSuggestions = JSON.parse(data);
            let suggestionsHTML = "";
            for (let i = 0; i < tagSuggestions.length; i++) {
              suggestionsHTML += "<div class='tag-suggestion'>" + tagSuggestions[i] + "</div>";
            }
            $("#tag-suggestions").html(suggestionsHTML);
          });
          $("#tag-suggestions").show();
        } else {
          $("#tag-suggestions").hide();
        }
      });

      $(document).on("click", ".tag-suggestion", function() {
        let tag = $(this).text();
        let currentTags = $("#tags").val().split(",");
        currentTags.pop();
        currentTags.push(tag);
        currentTags.push("");$("#tags").val(currentTags.join(", "));
$("#tag-suggestions").hide();
});
});
</script>

</head>
<body>
  <div id="wrapper">
    <h1>Create Post</h1>
    <form method="post">
      <div>
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>
      </div>
      <div>
        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="10" required></textarea>
      </div>
      <div>
        <label for="tags">Tags:</label>
        <input type="text" id="tags" name="tags">
        <div id="tag-suggestions"></div>
      </div>
      <div>
        <button type="submit">Create</button>
        <a href="index.php">Cancel</a>
      </div>
    </form>
  </div>
</body>
</html>
