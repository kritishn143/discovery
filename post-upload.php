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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $foodName = $_POST['food_name']; // Added line for food name
    $description = $_POST['description'];
    $uploadedBy = $_SESSION['user_name'];
    $targetDir = "postspic/";

    $fileName = basename($_FILES['image']['name']);
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
        $insertQuery = "INSERT INTO posts (description, image, uploaded_by, food_name) VALUES ('$description', '$fileName', '$uploadedBy', '$foodName')";
        if ($conn->query($insertQuery) === TRUE) {
            header("Location: userdashboard.php?success=Post uploaded successfully!");
            exit();
        } else {
            header("Location: userdashboard.php?error=Error uploading post.");
            exit();
        }
    } else {
        header("Location: userdashboard.php?error=Error moving uploaded file.");
        exit();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> 
    <title>Upload a Post</title>
</head>
<body style="font-family: Verdana; ">
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
        <?php if (isset($_SESSION['user_id'])) : ?>
            <a href="userdashboard.php" style="text-decoration: none;">
                <?php echo ucfirst(strtolower($_SESSION['user_name'])); ?>
            </a>
        <?php else : ?>
            <a href="<?php echo $loginLink; ?>" style="text-decoration:none">Log In</a>
            <a href="<?php echo $signUpLink; ?>" style="text-decoration:none">Sign Up</a>
        <?php endif; ?>
    </div>
</header>

    <main class="container">
        <section class="content">
            <div class="post-upload-box">
                <h2>Upload a Post</h2>
                <?php if(isset($_GET['error'])) { ?>
                    <p class="error"><?php echo $_GET['error']; ?></p>
                <?php } ?>

                <form action="post-upload.php" method="post" enctype="multipart/form-data" class="post-upload-form">
                    <label for="image">Image (553x330px):</label>
                    <input type="file" name="image" id="image" accept="image/*" required>

                    <label for="food_name">Food Name:</label>
                    <input type="text" name="food_name" id="food_name" required>

                    <label for="description">Description:</label>
                    <textarea name="description" id="description" rows="4" required></textarea>

                    <button type="submit">Upload Post</button>
                </form>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <p>&copy; <?php echo date("Y"); ?> Discovery</p>
    </footer>
</body>
</html>
