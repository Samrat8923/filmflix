<?php
// Include the database connection
include('db_connection.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to submit a review.']);
    exit;
}

// Get POST data from the client (this should come as JSON)
$data = json_decode(file_get_contents('php://input'), true);

// Extract variables from POST data
$imdbID = $data['imdbID'];
$rating = $data['rating'];
$comment = $data['comment']; // Raw comment

// echo "<hr>".$imdbID."<hr>";

// Validate and sanitize the input data
if (empty($imdbID) || empty($rating) || empty($comment)) {
    echo json_encode(['success' => false, 'message' => 'Please provide a valid movie ID, rating, and comment.']);
    exit;
}

// Sanitize the comment to prevent SQL injection
$comment = mysqli_real_escape_string($conn, $comment);

// Get the logged-in user's ID
$user_id = $_SESSION['user_id']; // The logged-in user's ID

// Prepare the SQL query using prepared statements for security
$stmt = $conn->prepare("INSERT INTO reviews (movie_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
$stmt->bind_param("siis", $imdbID, $user_id, $rating, $comment);



// Execute the query and check for success
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Your review has been submitted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to submit the review. Please try again.']);
}

// Close the prepared statement
$stmt->close();
?>
