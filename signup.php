<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="form.css">
    <title>Sign Up</title>
</head>
<body style="font-family: Verdana;">
<header>
    <div class="logo">
        <a href="index.php" style="text-decoration: none; color: inherit;">Discovery</a>
    </div>
    <nav>
        <div class="search-container">
            <input type="text" placeholder="Search" class="search-box">
            <button type="submit" class="search-button">Search</button>
        </div>
    </nav>
    <div class="profile"><a href="login.php" style="text-decoration: none;">Login</a></div>
</header>

<div class="container">
    <form action="signup_process.php" method="post">
        <h2 class="center-heading">Sign Up</h2>

        <?php if(isset($_GET['error'])) { ?>
        <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>

        <div class="input-group">
            <label for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname" placeholder="Enter your full name" required class="login-input" />
        </div>

        <div class="input-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required class="login-input" />
        </div>

        <div class="input-group">
            <label for="uname">Username</label>
            <input type="text" id="uname" name="uname" placeholder="Choose a username" required class="login-input" />
        </div>

        <div class="input-group">
    <label for="password">Password</label>
    <input type="password" id="password" name="password" placeholder="Choose a password" required class="login-input" minlength="8" />
</div>

        <button type="submit" class="btn">Sign Up</button>

        <div class="or-line">
            <span></span>
            <span class="or">or</span>
            <span></span>
        </div>

        <div class="signup-link">
            <p>Already a member? <a href="login.php">Log in</a></p>
        </div>
    </form>
</div>

<footer class="site-footer">
    <p>&copy; 2023 Discovery</p>
</footer>
</body>
</html>
