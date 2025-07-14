<?php
require_once 'db_connection.php';

if (isset($_GET['movie_id'])) {
    $movieId = $_GET['movie_id'];

    // Fetch reviews with user details (like username) from the database
    $stmt = $conn->prepare("SELECT reviews.*, users.username FROM reviews INNER JOIN users ON reviews.user_id = users.id WHERE reviews.movie_id = ? ORDER BY reviews.created_at DESC");
    $stmt->bind_param("i", $movieId);
    $stmt->execute();
    $result = $stmt->get_result();

    $comments = [];
    
    // Loop through all reviews and convert rating to star HTML
    while ($row = $result->fetch_assoc()) {
        $rating = $row['rating'];
        $starHtml = generateStarRatingHtml($rating); // Generate star HTML for the rating
        
        // Add the rating stars HTML to the review
        $row['star_rating'] = $starHtml;
        $comments[] = $row;
    }

    // Output the comments with the star HTML
    echo json_encode($comments);
}

// Function to generate star HTML based on rating
function generateStarRatingHtml($rating) {
    $fullStar = "&#9733;";  // Unicode for filled star
    $emptyStar = "&#9734;"; // Unicode for empty star
    $stars = "";

    // Add full stars
    for ($i = 1; $i <= $rating; $i++) {
        $stars .= "<span class='star'>$fullStar</span>";
    }

    // Add empty stars (up to 5)
    for ($i = $rating + 1; $i <= 5; $i++) {
        $stars .= "<span class='star empty'>$emptyStar</span>";
    }

    return $stars;
}
?>
