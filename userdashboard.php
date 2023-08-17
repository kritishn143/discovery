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

// Fetch user's profile information
$sql_profile = "SELECT fullname, email, profile_picture FROM users WHERE username = '$user_name'";
$result_profile = $conn->query($sql_profile);

if ($result_profile->num_rows > 0) {
    $row_profile = $result_profile->fetch_assoc();
    $userProfile = array(
        'name' => $row_profile['fullname'],
        'email' => $row_profile['email'],
        'avatar' => $row_profile['profile_picture']
    );
} else {
    echo "No user found.";
}

// Fetch user's posts
$sql_posts = "SELECT id, description, image, created_at, food_name FROM posts WHERE uploaded_by = '$user_name' ORDER BY created_at DESC";
$result_posts = $conn->query($sql_posts);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
   
    <title>User Dashboard</title>
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
    <section class="content">
        <div class="user-profile-box">
            <div class="profile-header">
                <h2>Your Profile</h2>
                
                <!-- Edit Profile button inside the box's top right corner -->
                <div class="edit-profile-button">
                    <a href="edit-profile.php" class="edit-button">Edit Profile</a>
                </div>
            </div>
            <div class="profile-picture">
                <img src="<?php echo $userProfile['avatar']; ?>" alt="Profile Picture">
            </div>
            <div class="profile-details">
                <p><strong>Name:</strong> <?php echo $userProfile['name']; ?></p>
                <p><strong>Email:</strong> <?php echo $userProfile['email']; ?></p>
            </div>
        </div>
    </section>
</main>

<section class="edit-posts-section">
    <h2>Your Posts</h2>
    <div class="post-container"> <!-- Flex container for posts -->
        <?php
        if ($result_posts->num_rows > 0) {
            while ($row_posts = $result_posts->fetch_assoc()) {
                $postId = $row_posts['id'];
                $postDescription = $row_posts['description'];
                $postImage = $row_posts['image'];
                $postCreatedAt = $row_posts['created_at'];
                $foodName = $row_posts['food_name']; // New variable for food name
        ?>
        <div class="post">
            <img src="postspic/<?php echo $postImage; ?>" alt="Post Image">
            <p><strong>Food Name:</strong> <?php echo $foodName; ?></p> <!-- Display food name -->
            <p><strong>Description:</strong> <?php echo $postDescription; ?></p>
            <p><strong>Posted on:</strong> <?php echo $postCreatedAt; ?></p>
            <div class="post-actions">
                <a href="edit-post.php?id=<?php echo $postId; ?>" class="edit-button">Edit</a>
                <a href="delete-post.php?id=<?php echo $postId; ?>" class="delete-button">Delete</a>
            </div>
        </div>
        <?php
            }
        } else {
        ?>
        <p>No posts available.</p>
        <?php
        }
        ?>
    </div>
</section>

<footer class="site-footer">
    <p>&copy; <?php echo date("Y"); ?> Discovery</p>
</footer>
</body>
</html>
