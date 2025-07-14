<?php
require_once 'db_connection.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to delete a comment.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$commentId = $data['commentId'];
$userId = $_SESSION['user_id']; // Logged-in user's ID

// Ensure commentId is valid and numeric
if (!is_numeric($commentId)) {
    echo json_encode(['success' => false, 'message' => 'Invalid comment ID']);
    exit;
}

// Check if the comment belongs to the user who is trying to delete it
$stmt = $conn->prepare("SELECT user_id FROM reviews WHERE id = ?");
$stmt->bind_param("i", $commentId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'Comment not found']);
    exit;
}

$comment = $result->fetch_assoc();

// If the user is not the owner of the comment, they cannot delete it
if ($comment['user_id'] != $userId) {
    echo json_encode(['success' => false, 'message' => 'You can only delete your own comments']);
    exit;
}

// Delete the comment from the database
$stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
$stmt->bind_param("i", $commentId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Comment deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete comment']);
}
?>
