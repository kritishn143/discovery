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
$success_message = "";
$error_message = "";

$sql = "SELECT fullname, email, profile_picture FROM users WHERE username = '$user_name'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userProfile = array(
        'name' => $row['fullname'],
        'email' => $row['email'],
        'avatar' => $row['profile_picture']
    );
} else {
    echo "No user found.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle profile updates here

    // Example: Update the profile picture
    if ($_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $avatar_tmp = $_FILES['avatar']['tmp_name'];
        $avatar_name = $_FILES['avatar']['name'];
        $avatar_destination = "profiles/" . $avatar_name; // You might need to create the 'profiles' directory

        if (move_uploaded_file($avatar_tmp, $avatar_destination)) {
            // Update the profile_picture field in the database
            $update_sql = "UPDATE users SET profile_picture = '$avatar_destination' WHERE username = '$user_name'";
            if ($conn->query($update_sql) === TRUE) {
                // Profile picture updated successfully
                $success_message = "Profile picture updated successfully!";
            } else {
                $error_message = "Error updating profile picture: " . $conn->error;
            }
        } else {
            $error_message = "Error moving uploaded file.";
        }
    }

    // Update full name and email
    $new_fullname = $_POST['fullname'];
    $new_email = $_POST['email'];

    $update_sql = "UPDATE users SET fullname = '$new_fullname', email = '$new_email' WHERE username = '$user_name'";
    if ($conn->query($update_sql) === TRUE) {
        // Full name and email updated successfully
        $success_message = "Profile updated successfully!";
    } else {
        $error_message = "Error updating profile: " . $conn->error;
    }
}

$conn->close();

// Redirect to userdashboard.php if the profile update was successful
if (!empty($success_message)) {
    header("Location: userdashboard.php?success_message=" . urlencode($success_message));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
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
    

    <main class="container">
        <section class="content">
            <div class="edit-profile-box">
                <h2>Edit Profile</h2>
                <?php
                if (isset($success_message)) {
                    echo '<div class="success-message">' . $success_message . '</div>';
                }
                if (isset($error_message)) {
                    echo '<div class="error-message">' . $error_message . '</div>';
                }
                ?>
                <form method="post" enctype="multipart/form-data" class="edit-profile-form">
                    <label for="avatar">Profile Picture:</label>
                    <input type="file" name="avatar" id="avatar">

                    <label for="fullname">Full Name:</label>
                    <input type="text" name="fullname" id="fullname" value="<?php echo $userProfile['name']; ?>">

                    <label for="email">Email Address:</label>
                    <input type="email" name="email" id="email" value="<?php echo $userProfile['email']; ?>">

                    <!-- Add other fields for profile update -->
                    <button type="submit">Save Changes</button>
                </form>
            </div>
        </section>
    </main>

    <footer class="site-footer">
    <p>&copy; <?php echo date("Y"); ?> Discovery</p>
</footer>
</body>
</html>
