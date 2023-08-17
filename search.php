<?php
session_start(); // Start the session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "disco";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['search_query'])) {
    $search_query = $_GET['search_query'];

    // Perform the search operation based on $search_query
    $sql_search = "SELECT p.*, u.username FROM posts p
                   INNER JOIN users u ON p.uploaded_by = u.username
                   WHERE p.description LIKE '%$search_query%' OR p.food_name LIKE '%$search_query%'";
    $result_posts = $conn->query($sql_search);
    $loginLink = "login.php";
$signUpLink = "signup.php";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="search.css">
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

<div class="main">
    <?php
    if (isset($_GET['search_query'])) {
        if ($result_posts !== false && $result_posts->num_rows > 0) {
            while ($row_posts = $result_posts->fetch_assoc()) {
                // Display search results here
                echo '<div class="post">';

                echo '<img src="postspic/' . $row_posts['image'] . '" alt="Post Image">';
                echo '<p><strong>Food Name:</strong> ' . $row_posts['food_name'] . '</p>';
                echo '<p><strong>Description:</strong> ' . $row_posts['description'] . '</p>';
               
                echo '<p><strong>Posted by:</strong> ' . $row_posts['username'] . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No search results found for "' . $search_query . '".</p>';
        }
    } else {
        echo '<p>Please enter a search query above.</p>';
    }
    ?>
</div>
<footer class="site-footer">
        <p>&copy; <?php echo date("Y"); ?> Discovery</p>
    </footer>
<!-- Rest of your HTML content -->

</body>
</html>
