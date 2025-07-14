<?php
session_start();
require_once 'db_connection.php';

if (isset($_GET['movie_id']) && isset($_SESSION['username'])) {
    $movieId = $_GET['movie_id'];
    $username = $_SESSION['username'];

    // Check if the user has already rated this movie
    $stmt = $conn->prepare("SELECT * FROM reviews WHERE movie_id = ? AND username = ?");
    $stmt->bind_param("is", $movieId, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    echo json_encode(['alreadyRated' => $result->num_rows > 0]);
} else {
    echo json_encode(['alreadyRated' => false]);
}
?>
