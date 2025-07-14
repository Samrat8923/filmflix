<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

include 'db_connection.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Profile picture upload or deletion logic
    if (isset($_POST['delete_profile_pic'])) {
        $username = $_SESSION['username'];
        $sql = "UPDATE users SET profile_pic = NULL WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $message = "Profile picture deleted successfully.";
    }

    // Handle profile picture upload
    if (isset($_FILES['profile_pic'])) {
        $profilePic = $_FILES['profile_pic'];
        $uploadDir = 'uploads/profile_pics/';
        $uploadFile = $uploadDir . basename($profilePic['name']);
        
        if (move_uploaded_file($profilePic['tmp_name'], $uploadFile)) {
            $username = $_SESSION['username'];
            $sql = "UPDATE users SET profile_pic = ? WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $uploadFile, $username);
            $stmt->execute();
            $message = "Profile picture updated successfully.";
        } else {
            $message = "Failed to upload the profile picture.";
        }
    }

    // Change username functionality
    if (isset($_POST['new_username'])) {
        $newUsername = $_POST['new_username'];
        $username = $_SESSION['username'];
        $sql = "UPDATE users SET username = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $newUsername, $username);
        if ($stmt->execute()) {
            $_SESSION['username'] = $newUsername;
            $message = "Username updated successfully.";
        } else {
            $message = "Failed to update username.";
        }
    }

    // Change password functionality
    if (isset($_POST['new_password'])) {
        $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $username = $_SESSION['username'];
        $sql = "UPDATE users SET password = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $newPassword, $username);
        if ($stmt->execute()) {
            $message = "Password updated successfully.";
        } else {
            $message = "Failed to update password.";
        }
    }

    // Delete account functionality
    if (isset($_POST['delete_account'])) {
        $username = $_SESSION['username'];
        
        // Delete the user's profile picture if it exists
        $sql = "SELECT profile_pic FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($profilePic);
        $stmt->fetch();
        
        if ($profilePic && file_exists($profilePic)) {
            unlink($profilePic);  // Delete the profile picture file from server
        }

        // Delete the user from the database
        $sql = "DELETE FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        if ($stmt->execute()) {
            session_destroy();  // Destroy session after account deletion
            header("Location: index.php");  // Redirect to homepage after account is deleted
            exit();
        } else {
            $message = "Failed to delete account.";
        }
    }
}

$username = $_SESSION['username'];
$sql = "SELECT profile_pic, username FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($profilePic, $currentUsername);
$stmt->fetch();
$currentProfilePic = $profilePic ? $profilePic : 'uploads/profile_pics/default.png'; // Default image if none set
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings - Film Flix</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #121212;
            margin: 0;
            padding: 0;
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            background-color: #333;
            color: white;
        }
        header h1 {
            margin: 0;
            font-size: 30px;
        }
        .container {
            flex: 1;
            width: 50%;
            max-width: 600px;
            background-color: #222233;
            margin: 30px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }
        .profile-pic-container {
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-pic-container img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 5px solid #3498db;
            margin-bottom: 20px;
        }
        .icon {
            font-size: 60px;
            color: #3498db;
            margin-bottom: 15px;
        }
        .btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 14px 24px;
            text-align: center;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s ease;
            margin: 10px 0;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .btn-red {
            background-color: #e74c3c;
        }
        .btn-red:hover {
            background-color: #c0392b;
        }
        .message, .error {
            text-align: center;
            margin-bottom: 20px;
        }
        .message {
            color: green;
        }
        .error {
            color: red;
        }
        footer {
            background-color: #000;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 30px;
        }
        footer a {
            color: #e74c3c;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <header>
        <h1>Film Flix</h1>
    </header>

    <div class="container">
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <h2>Profile Settings</h2>

        <div class="profile-pic-container">
            <?php if ($currentProfilePic === 'uploads/profile_pics/default.png'): ?>
                <div class="icon">👤</div>
            <?php else: ?>
                <img src="<?php echo $currentProfilePic; ?>" alt="Profile Picture">
            <?php endif; ?>

            <button id="change-pic-btn" class="btn" onclick="showUploadForm()">Change Profile Picture</button>

            <div id="upload-form" style="display: none;">
                <form action="settings.php" method="POST" enctype="multipart/form-data">
                    <input type="file" name="profile_pic" id="file-input" accept="image/*" required>
                    <button type="submit" class="btn">Upload New Picture</button>
                </form>
            </div>

            <form action="settings.php" method="POST">
                <button type="submit" name="delete_profile_pic" class="btn btn-red">Delete Profile Picture</button>
            </form>
        </div>

        <form action="settings.php" method="POST">
            <div class="form-group">
                <label for="new_username">New Username</label>
                <input type="text" name="new_username" id="new_username" required>
            </div>
            <button type="submit" class="btn">Change Username</button>
        </form>

        <form action="settings.php" method="POST">
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" name="new_password" id="new_password" required>
            </div>
            <button type="submit" class="btn">Change Password</button>
        </form>

        <form action="settings.php" method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action is irreversible!');">
            <button type="submit" name="delete_account" class="btn btn-red">Delete Account</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 Film Flix | <a href="privacy.php">Privacy Policy</a></p>
    </footer>

    <script>
        function showUploadForm() {
            document.getElementById('upload-form').style.display = 'block';
        }
    </script>
</body>
</html>
