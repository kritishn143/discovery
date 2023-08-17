<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['post_id']) && isset($_POST['comment'])) {
    $postId = $_POST['post_id'];
    $comment = $_POST['comment'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "disco";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $userId = $_SESSION['user_id'];

    // Insert the comment into the database
    $insertQuery = "INSERT INTO comments (user_id, post_id, comment_text) VALUES ($userId, $postId, '$comment')";
    $insertResult = $conn->query($insertQuery);

    if ($insertResult) {
        // Comment added successfully
        header("Location: index.php");
        exit();
    } else {
        echo "Error adding comment: " . $conn->error;
    }

    $conn->close();
} else {
    header("Location: index.php");
    exit();
}
?>
