<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the post_id is set and is a valid integer
if (isset($_POST['post_id']) && filter_var($_POST['post_id'], FILTER_VALIDATE_INT)) {
    $postId = $_POST['post_id'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "disco";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $userId = $_SESSION['user_id'];

    // Check if the user has already liked the post
    $checkQuery = "SELECT * FROM likes WHERE user_id = $userId AND post_id = $postId";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult) {
        if ($checkResult->num_rows === 0) {
            // Insert a new like
            $insertQuery = "INSERT INTO likes (user_id, post_id) VALUES ($userId, $postId)";
            $insertResult = $conn->query($insertQuery);

            if ($insertResult) {
                // Liked successfully
                header("Location: index.php");
                exit();
            } else {
                echo "Error adding like: " . $conn->error;
            }
        } else {
            // User has already liked this post, so remove the like
            $deleteQuery = "DELETE FROM likes WHERE user_id = $userId AND post_id = $postId";
            $deleteResult = $conn->query($deleteQuery);

            if ($deleteResult) {
                // Disliked successfully
                header("Location: index.php");
                exit();
            } else {
                echo "Error removing like: " . $conn->error;
            }
        }
    } else {
        echo "Error in database query: " . $conn->error;
    }

    $conn->close();
} else {
    // Invalid post_id or missing post_id
    header("Location: index.php");
    exit();
}
?>
