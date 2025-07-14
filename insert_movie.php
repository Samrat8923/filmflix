<?php
session_start();
include 'db_connection.php'; // Include your database connection

// Function to check if the movie already exists in the database
function movieExists($imdbID) {
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM movies WHERE imdbID = ?");
    $stmt->bind_param("s", $imdbID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Function to insert a movie into the database
function insertMovie($movie) {
    global $conn;

    $stmt = $conn->prepare("
        INSERT INTO movies (imdbID, title, year, genre, director, actors, plot, imdbRating, poster, runtime, language)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->bind_param(
        "sssssssssss",
        $movie['imdbID'],
        $movie['Title'],
        $movie['Year'],
        $movie['Genre'],
        $movie['Director'],
        $movie['Actors'],
        $movie['Plot'],
        $movie['imdbRating'],
        $movie['Poster'],
        $movie['Runtime'],
        $movie['Language']
    );

    return $stmt->execute();
}

// Get the movie data from the incoming request
$data = json_decode(file_get_contents("php://input"), true);

// Check if movie already exists in the database
if (movieExists($data['imdbID'])) {
    echo json_encode(['success' => false, 'message' => 'Movie already exists']);
} else {
    // Insert the movie into the database
    if (insertMovie($data)) {
        echo json_encode(['success' => true, 'message' => 'Movie added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add movie']);
    }
}
?>
