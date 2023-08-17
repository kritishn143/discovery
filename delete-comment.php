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

    // Fetch the comment's user_id for verification
    $sql_comment = "SELECT user_id, post_id FROM comments WHERE id = '$comment_id'";
    $result_comment = $conn->query($sql_comment);

    if ($result_comment->num_rows == 1) {
        $row_comment = $result_comment->fetch_assoc();
        $comment_user_id = intval($row_comment['user_id']); // Convert to integer
        $post_id = $row_comment['post_id'];

        if ($user_id === $comment_user_id) { // Verify ownership before deletion
            $sql_delete_comment = "DELETE FROM comments WHERE id = '$comment_id'";
            
            if ($conn->query($sql_delete_comment) === TRUE) {
                header("Location: viewpost.php?post_id=" . $post_id);
                exit(); // Exit the script
            } else {
                echo "Error deleting comment: " . $conn->error;
            }
        } else {
            echo "You are not the owner of this comment.";
            exit(); // Exit the script
        }
    } else {
        echo "Comment not found.";
        exit(); // Exit the script
    }
} else {
    echo "Invalid request.";
    exit(); // Exit the script
}
?>
