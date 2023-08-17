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

    // Delete comments associated with the post
    $sql_delete_comments = "DELETE FROM comments WHERE post_id = '$post_id'";
    if ($conn->query($sql_delete_comments) === TRUE) {

        // Delete likes associated with the post
        $sql_delete_likes = "DELETE FROM likes WHERE post_id = '$post_id'";
        if ($conn->query($sql_delete_likes) === TRUE) {

            // Delete the post
            $sql_delete_post = "DELETE FROM posts WHERE id = '$post_id' AND uploaded_by = '$user_name'";
            if ($conn->query($sql_delete_post) === TRUE) {
                header("Location: userdashboard.php");
                exit();
            } else {
                echo "Error deleting post: " . $conn->error;
            }
        } else {
            echo "Error deleting likes: " . $conn->error;
        }
    } else {
        echo "Error deleting comments: " . $conn->error;
    }
}

$conn->close();
?>
