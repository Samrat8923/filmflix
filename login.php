<?php
// Include the database connection
include('db_connection.php');
session_start();

// Define error messages
$error = '';

// Handle the login form submission
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Fetch the user data from the database
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Check if the user is blocked
        if ($user['status'] == 'blocked') {
            $error = 'Your account is blocked. Please contact support.';
        } else {
            // Check if the password matches
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id']; // Store the user ID in session
                $_SESSION['username'] = $user['username']; // Store the username in session

                // Check if there is a 'redirect' parameter in the URL or in the form
                $redirectUrl = isset($_POST['redirect']) ? $_POST['redirect'] : 'index.php';

                // Redirect to the original page or default to homepage
                header("Location: $redirectUrl"); // Redirect to the original page
                exit;
            } else {
                $error = 'Incorrect password!';
            }
        }
    } else {
        $error = 'User not found!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film Flix - Login</title>
    <style>
        /* Basic styling for login page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .login-container {
            width: 300px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        .input-field {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            width: 100%;
            padding: 10px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #c0392b;
        }
        .error-message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>

        <!-- Display error message if exists -->
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST">
            <input type="text" name="username" class="input-field" placeholder="Username" required>
            <input type="password" name="password" class="input-field" placeholder="Password" required>
            <!-- Include redirect URL in hidden input if exists -->
            <?php if (isset($_GET['redirect']) && !empty($_GET['redirect'])): ?>
                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_GET['redirect']); ?>">
            <?php endif; ?>
            <button type="submit" name="login" class="btn">Login</button>
        </form>

        <div class="toggle-link">
            <a href="register.php">Don't have an account? Sign Up</a>
        </div>
    </div>

</body>
</html>
