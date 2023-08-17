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

    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $username = $_POST['uname'];
    $password = $_POST['password'];

    // Hash the password using bcrypt
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Check if the email address is already registered
    $emailCheckQuery = "SELECT id FROM users WHERE email = ?";
    $stmtEmailCheck = $pdo->prepare($emailCheckQuery);
    $stmtEmailCheck->execute([$email]);

    if ($stmtEmailCheck->rowCount() > 0) {
        // Email address is already registered, redirect with an error message
        header("Location: signup.php?error=You already have an account please proceed to login");
        exit();
    }

    // Prepare and execute the query to insert the new user
    $query = "INSERT INTO users (fullname, email, username, password) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);

    if ($stmt) {
        $stmt->bindParam(1, $fullname);
        $stmt->bindParam(2, $email);
        $stmt->bindParam(3, $username);
        $stmt->bindParam(4, $passwordHash);
        if ($stmt->execute()) {
            // Registration successful, redirect to a success page or login page
            header("Location: login.php");
            exit();
        } else {
            // Registration failed, redirect back to signup.php with an error message
            header("Location: signup.php?error=registration_failed");
            exit();
        }
        $stmt->close();
    } else {
        // Error in preparing the statement
        header("Location: signup.php?error=database_error");
        exit();
    }

    // Close the database connection
    $pdo = null;
} else {
    // Redirect to signup.php if accessed directly without POST request
    header("Location: signup.php");
    exit();
}
?>
