<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database configuration
    $db_host = 'localhost';
    $db_name = 'disco'; // Your database name
    $db_user = 'root';
    $db_pass = '';
    // Create a PDO instance
    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        // Handle the database connection error
        echo "Database connection failed: " . $e->getMessage();
        exit();
    }

    $username = $_POST['uname'];
    $password = $_POST['password'];

    // Retrieve user data based on the provided username
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($query);

    if ($stmt) {
        $stmt->bindParam(1, $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user && password_verify($password, $user['password'])) {
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['username']; // Set the username from the database
    header("Location: index.php"); // Replace with the appropriate destination
    exit();
}
        else {
            // Incorrect username or password, redirect back to login.php with an error message
            header("Location: login.php?error=Incorrect Username or Password");
            exit();
        }
        $stmt->close();
    } else {
        // Error in preparing the statement
        header("Location: login.php?error=database_error");
        exit();
    }

    // Close the database connection
    $pdo = null;
} else {
    // Redirect to login.php if accessed directly without POST request
    header("Location: login.php");
    exit();
}
?>
