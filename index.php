<?php
session_start();
$loginLink = "login.php";
$signUpLink = "signup.php";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "disco";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$logged_in_user = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
$sql_posts = "SELECT posts.id, posts.description, posts.image, posts.uploaded_by, posts.food_name, posts.created_at, users.fullname 
              FROM posts
              JOIN users ON posts.uploaded_by = users.username
              WHERE posts.uploaded_by != '$logged_in_user' 
              ORDER BY posts.created_at DESC";

$result_posts = $conn->query($sql_posts);
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
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
<?php if (!isset($_SESSION['user_id'])) : ?>
    <div style="background-color:#f1f1f1;padding:15px;">
        <div class="content">
            <div class="welcome-section">
                <div class="welcome-box">
                    <h1>Welcome to Discovery</h1>
                    <h3>Are you registered?</h3>
                    <div class="answer-options">
                        <a href="<?php echo $loginLink; ?>" style="text-decoration: none;" class="yes-option">😎 Yes</a>
                        <a href="<?php echo $signUpLink; ?>" style="text-decoration: none;" class="no-option">😢 No</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div style="overflow:auto">
<div class="menu">
        <a href="index.php" class="menuitem">Home</a>
        <a href="userdashboard.php" class="menuitem">Profile</a>
        <a href="post-upload.php" class="menuitem">Post</a>
    </div>
        
    <div class="main">
        <?php
        if ($result_posts->num_rows === 0) {
            echo '<p>No posts are available currently. Please check back later.</p>';
        } else {
            while ($row_posts = $result_posts->fetch_assoc()) {
                $postId = $row_posts['id'];
                $postDescription = $row_posts['description'];
                $postImage = $row_posts['image'];
                $postCreatedAt = $row_posts['created_at'];
                $postFoodName = $row_posts['food_name'];
                $postUploadedByFullName = $row_posts['fullname']; // Fetched full name from query result
                
              
                // Get like count for this post
                $sql_likes = "SELECT COUNT(*) AS like_count FROM likes WHERE post_id = '$postId'";
                $result_likes = $conn->query($sql_likes);
                $like_count = ($result_likes->num_rows > 0) ? $result_likes->fetch_assoc()['like_count'] : 0;
                
                // Get comment count for this post
                $sql_comments = "SELECT COUNT(*) AS comment_count FROM comments WHERE post_id = '$postId'";
                $result_comments = $conn->query($sql_comments);
                $comment_count = ($result_comments->num_rows > 0) ? $result_comments->fetch_assoc()['comment_count'] : 0;
        ?>
             <div class="post">
    <img src="postspic/<?php echo $postImage; ?>" alt="Post Image">
    <p><strong>Food Name:</strong> <a href="viewpost.php?post_id=<?php echo $postId; ?>"><?php echo $postFoodName; ?></a></p>
    <p><strong>Posted by:</strong> <?php echo $postUploadedByFullName; ?></p>
    <p><strong>Description:</strong> <?php echo $postDescription; ?></p>
    <p><strong>Posted on:</strong> <?php echo $postCreatedAt; ?></p>
                
                <!-- Like and Comment section for each post -->
            <div class="actions-section">
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <form action="like-post.php" method="post" class="like-form">
                        <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
                        <button type="submit">Like</button>
                        <span class="like-count"><?php echo $like_count; ?> likes</span>
                    </form>
                    <form action="add-comment.php" method="post" class="comment-form" name="commentForm" >
    <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
    <textarea name="comment" placeholder="Leave a comment"></textarea>
    <span id="commentError" style="color: red;"></span> <!-- Display error messages -->
    <button type="submit">Submit Comment</button>
</form>


 

                    
<span class="comment-count"><?php echo $comment_count; ?> comments</span>
                <?php } else { ?>
                    <p><a href="login.php">Log in</a> to like and comment on this post.</p>
                <?php } ?>
            </div>
        </div>
        <?php
            }
        }
        ?>
    </div>
    
    <div class="right">
        <h2>Quick Links</h2>
        <p><a href="#" style="text-decoration: none;">About us</a></p>
        <p><a href="#" style="text-decoration: none;">Terms & Conditions</a></p>
        <p><a href="#" style="text-decoration: none;">Privacy Policy</a></p>
        <p><a href="#" style="text-decoration: none;">Contact us</a></p>
    </div>
</div>

<footer class="site-footer">
    <p>&copy; <?php echo date("Y"); ?> Discovery</p>
</footer>
</body>
</html>
