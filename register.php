<?php
// Include the database connection
include('db_connection.php');
session_start();

// Define error messages
$error = '';
$message = '';

// Handle the sign-up form submission
if (isset($_POST['signup'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    
    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = 'Passwords do not match!';
    } else {
        // Check if the username already exists
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $error = 'Username already exists!';
        } else {
            // Hash the password before storing
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
            
            if (mysqli_query($conn, $sql)) {
                $message = 'Sign up successful. You can now log in.';
            } else {
                $error = 'Error during sign up. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film Flix - Sign Up</title>
    <style>
        /* Similar styling to login page */
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Sign Up</h2>

        <!-- Display error or success message -->
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php elseif ($message): ?>
            <div class="success-message"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Sign-Up Form -->
        <form method="POST">
            <input type="text" name="username" class="input-field" placeholder="Username" required>
            <input type="password" name="password" class="input-field" placeholder="Password" required>
            <input type="password" name="confirm_password" class="input-field" placeholder="Confirm Password" required>
            <button type="submit" name="signup" class="btn">Sign Up</button>
        </form>

        <div class="toggle-link">
            <a href="login.php">Already have an account? Login</a>
        </div>
    </div>

</body>
</html>
