<?php
session_start();
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "filmflix");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    switch ($action) {
        case 'block':
            $userId = $_POST['user_id'];
            $conn->query("UPDATE users SET status='blocked' WHERE id=$userId");
            break;
        case 'unblock':
            $userId = $_POST['user_id'];
            $conn->query("UPDATE users SET status='active' WHERE id=$userId");
            break;
        case 'delete_user':
            $userId = $_POST['user_id'];
            $conn->query("DELETE FROM users WHERE id=$userId");
            break;
        case 'delete_comment':
            $commentId = $_POST['comment_id'];
            $conn->query("DELETE FROM comments WHERE id=$commentId");
            break;
        case 'delete_movie':
            $movieId = $_POST['movie_id'];
            $conn->query("DELETE FROM movies WHERE id=$movieId");
            break;
    }
}

header("Location: admin_dashboard.php");
exit();
?>
