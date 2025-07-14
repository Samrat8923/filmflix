<?php
session_start();
include 'db.php';  // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in']);
    exit();
}

// Get the data from the POST request
$review_id = $_POST['review_id'];  // ID of the comment being replied to
$reply = $_POST['reply'];
$user_id = $_SESSION['user_id'];

// Insert the reply into the replies table
$sql = "INSERT INTO replies (review_id, user_id, reply) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$review_id, $user_id, $reply]);

// Respond with success
echo json_encode(['success' => true, 'message' => 'Reply submitted successfully']);
?>
