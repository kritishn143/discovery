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

if (isset($_SESSION['user_id']) && isset($_GET['comment_id'])) {
    $user_id = $_SESSION['user_id'];
    $comment_id = $_GET['comment_id'];

    // Fetch the comment to be edited, including user_id
    $sql_comment = "SELECT * FROM comments WHERE id = '$comment_id' AND user_id = '$user_id'";
    $result_comment = $conn->query($sql_comment);

    if ($result_comment->num_rows == 1) {
        $row_comment = $result_comment->fetch_assoc();
        $comment_text = $row_comment['comment_text'];
        $post_id = $row_comment['post_id'];
        $comment_user_id = $row_comment['user_id']; // Retrieve user_id
    } else {
        echo "Comment not found or you are not the owner of this comment.";
        exit(); // Exit the script
    }
} else {
    echo "Invalid request.";
    exit(); // Exit the script
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_comment_text = $_POST['new_comment_text'];
    
    // Validate and sanitize the input
    $new_comment_text = htmlspecialchars($new_comment_text);
    
    $sql_update_comment = "UPDATE comments SET comment_text = '$new_comment_text'
                           WHERE id = '$comment_id' AND user_id = '$user_id'";
    
    if ($conn->query($sql_update_comment) === TRUE) {
        header("Location: viewpost.php?post_id=" . $post_id);
        exit(); // Exit the script
    } else {
        echo "Error updating comment: " . $conn->error;
    }
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
        <div class="edit-comment-form">
            <h2>Edit Comment</h2>
            <form action="" method="post">
                <textarea name="new_comment_text" rows="4" cols="50"><?php echo $comment_text; ?></textarea>
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                <input type="submit" value="Save Changes">
            </form>
        </div>
    </div>

    <footer class="site-footer">
        <p>&copy; <?php echo date("Y"); ?> Discovery</p>
    </footer>
</body>
</html>
