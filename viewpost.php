<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "disco";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    // Fetch the post details from the database
    $sql_post = "SELECT * FROM posts WHERE id = '$post_id'";
    $result_post = $conn->query($sql_post);

    if ($result_post->num_rows > 0) {
        $row_post = $result_post->fetch_assoc();
        $postDescription = $row_post['description'];
        $postImage = $row_post['image'];
        $postCreatedAt = $row_post['created_at'];
        $postFoodName = $row_post['food_name'];
        
        // Fetch liked by users' information
        $sql_liked_by = "SELECT users.fullname FROM likes
                        JOIN users ON likes.user_id = users.id
                        WHERE likes.post_id = '$post_id'";
        $result_liked_by = $conn->query($sql_liked_by);
        
        // Fetch all comments for the post
        $sql_comments = "SELECT users.id AS user_id, users.fullname, comments.id AS comment_id, comments.comment_text, comments.created_at
                         FROM comments
                         JOIN users ON comments.user_id = users.id
                         WHERE comments.post_id = '$post_id'
                         ORDER BY comments.created_at ASC";
        $result_comments = $conn->query($sql_comments);
    } else {
        echo "Post not found.";
    }
} else {
    echo "Invalid post ID.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="viewpost.css">
    <!-- Add other head elements and styles as needed -->
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
    <div class="post-details">
        <img src="postspic/<?php echo $postImage; ?>" alt="Post Image">
        <h2><?php echo $postFoodName; ?></h2>
        <p><strong>Description:</strong> <?php echo $postDescription; ?></p>
        <p><strong>Posted on:</strong> <?php echo $postCreatedAt; ?></p>
        
        <!-- Display Liked By section -->
        <div class="liked-by-section">
            <p>Liked by:</p>
            <?php
            if ($result_liked_by->num_rows > 0) {
                $liked_users = array();
                while ($row_liked_by = $result_liked_by->fetch_assoc()) {
                    $liked_users[] = $row_liked_by['fullname'];
                }
                $liked_count = count($liked_users);
                if ($liked_count == 1) {
                    echo "Liked by " . $liked_users[0];
                } else {
                    echo "Liked by " . $liked_users[0] . " ";
                    echo '<a href="#" id="show-others">' . ($liked_count - 1) . ' others</a>';
                }
            } else {
                echo "No likes yet.";
            }
            ?>
            <div id="others-list" style="display: none;">
                <?php
                for ($i = 1; $i < $liked_count; $i++) {
                    echo $liked_users[$i] . "<br>";
                }
                ?>
            </div>
        </div>
        
        <!-- Display Comments section -->
        <div class="comments-section">
            <h3>Comments:</h3>
            <?php
            if ($result_comments->num_rows > 0) {
                while ($row_comment = $result_comments->fetch_assoc()) {
                    echo "<div class='comment'>";
                    echo "<p><strong>{$row_comment['fullname']}</strong> ({$row_comment['created_at']}): {$row_comment['comment_text']}</p>";
                    
                    // Add edit and delete options for the comment owner
                    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row_comment['user_id']) {
                        echo "<span class='comment-actions'>";
                        echo "<a href='edit-comment.php?comment_id={$row_comment['comment_id']}'>Edit</a>";
                        echo " | ";
                        echo "<a href='delete-comment.php?comment_id={$row_comment['comment_id']}'>Delete</a>";
                        echo "</span>";
                    }
                    
                    echo "</div>";
                }
            } else {
                echo "No comments yet.";
            }
            ?>
        </div>
    </div>
</div>

<footer class="site-footer">
    <p>&copy; <?php echo date("Y"); ?> Discovery</p>
</footer>

<script>
    document.getElementById('show-others').addEventListener('click', function() {
        document.getElementById('others-list').style.display = 'block';
        this.style.display = 'none';
    });
</script>
</body>
</html>
