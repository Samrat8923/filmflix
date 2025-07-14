<?php
session_start();
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header("Location: admin_login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "filmflix");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch data for display
$users = $conn->query("SELECT * FROM users");
$comments = $conn->query("SELECT * FROM reviews");
$movies = $conn->query("SELECT * FROM movies");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #333;
            color: #fff;
        }
        .action-btn {
            padding: 5px 10px;
            margin-right: 5px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .action-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <h2>Manage Users</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($user = $users->fetch_assoc()) { ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= $user['username'] ?></td>
                <td><?= $user['status'] ?></td>
                <td>
                    <form method="POST" action="admin_actions.php" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <button type="submit" name="action" value="block" class="action-btn">Block</button>
                        <button type="submit" name="action" value="unblock" class="action-btn">Unblock</button>
                        <button type="submit" name="action" value="delete_user" class="action-btn">Delete</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

    <h2>Manage Comments</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Movie ID</th>
            <th>User</th>
            <th>Comment</th>
            <th>Actions</th>
        </tr>
        <?php while ($comment = $comments->fetch_assoc()) { ?>
            <tr>
                <td><?= $comment['id'] ?></td>
                <td><?= $comment['movie_id'] ?></td>
                <td><?= $comment['user_id'] ?></td>
                <td><?= $comment['comment'] ?></td>
                <td>
                    <form method="POST" action="admin_actions.php" style="display:inline;">
                        <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                        <button type="submit" name="action" value="delete_comment" class="action-btn">Delete</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

    <h2>Manage Movies</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Year</th>
            <th>Actions</th>
        </tr>
        <?php while ($movie = $movies->fetch_assoc()) { ?>
            <tr>
                <td><?= $movie['id'] ?></td>
                <td><?= $movie['title'] ?></td>
                <td><?= $movie['year'] ?></td>
                <td>
                    <form method="POST" action="admin_actions.php" style="display:inline;">
                        <input type="hidden" name="movie_id" value="<?= $movie['id'] ?>">
                        <button type="submit" name="action" value="delete_movie" class="action-btn">Delete</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

    <a href="logout.php">Logout</a>
</body>
</html>
