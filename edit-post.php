<?php
session_start();

if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "disco";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_name = $_SESSION['user_name'];

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    // Fetch the post for editing
    $sql_post = "SELECT id, description, image, food_name FROM posts WHERE id = '$post_id' AND uploaded_by = '$user_name'";
    $result_post = $conn->query($sql_post);

    if ($result_post->num_rows == 1) {
        $row_post = $result_post->fetch_assoc();
        $postDescription = $row_post['description'];
        $postImage = $row_post['image'];
        $foodName = $row_post['food_name'];
    } else {
        echo "Post not found.";
        exit();
    }
} else {
    echo "Post ID not provided.";
    exit();
}

$updateSuccessMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission for editing the post
    $newDescription = $_POST['description'];
    $newFoodName = $_POST['food_name'];

    if ($_FILES['new_image']['error'] === UPLOAD_ERR_OK) {
        $newImageName = $_FILES['new_image']['name'];
        $newImageTmpPath = $_FILES['new_image']['tmp_name'];
        
        // Move the uploaded image to a suitable location
        $newImagePath = "postspic/" . $newImageName;
        move_uploaded_file($newImageTmpPath, $newImagePath);

        // Update the image path in the database
        $sql_update_image = "UPDATE posts SET image = '$newImageName' WHERE id = '$post_id'";
        if ($conn->query($sql_update_image) !== TRUE) {
            echo "Error updating image: " . $conn->error;
        }
    }

    // Update description and food name
    $sql_update = "UPDATE posts SET description = '$newDescription', food_name = '$newFoodName' WHERE id = '$post_id'";
    if ($conn->query($sql_update) === TRUE) {
        $updateSuccessMessage = "Post updated successfully.";
        
        // Redirect to userdashboard.php after successful update
        header("Location: userdashboard.php");
        exit();
    } else {
        echo "Error updating post: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Edit Post</title>
</head>
<body style="font-family: Verdana;">
<header>
    <div class="logo">
        <a href="index.php" style="text-decoration: none; color: inherit;">Discovery</a>
    </div>
    <nav>
        <form action="search.php" method="GET">
            <div class="search-container">
                <input type="text" placeholder="Search" class="search-box" name="search_query">
                <button type="submit" class="search-button">Search</button>
            </div>
        </form>
    </nav>
    <div class="profile">
        <a href="logout.php" style="text-decoration: none;">Log Out</a>
    </div>
</header>

<main class="container">
    <section class="edit-post-section">
        <h2>Edit Post</h2>
        <?php if (!empty($updateSuccessMessage)) : ?>
            <p style="color: green;"><?php echo $updateSuccessMessage; ?></p>
        <?php endif; ?>
        <div class="edit-post-form">
            <form method="POST" enctype="multipart/form-data">
                <label for="new_image">New Image:</label>
                <input type="file" name="new_image" accept="image/*">

                <label for="description">Description:</label>
                <textarea name="description" id="description" rows="4" required><?php echo $postDescription; ?></textarea>

                <label for="food_name">Food Name:</label>
                <input type="text" name="food_name" id="food_name" value="<?php echo $foodName; ?>" required>

                <input type="submit" value="Save Changes" class="edit-button">
            </form>
        </div>
    </section>
</main>

<footer class="site-footer">
    <p>&copy; <?php echo date("Y"); ?> Discovery</p>
</footer>
</body>
</html>
