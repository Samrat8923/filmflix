<?php
// search.php

// Database connection settings (replace with your actual database details)
$servername = "localhost";
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "filmflix"; // Your database name

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search term from the request (via GET or POST)
$searchTerm = isset($_GET['query']) ? $_GET['query'] : '';

// SQL query to search for movies in the database
$sql = "SELECT * FROM movies WHERE title LIKE ? LIMIT 24";
$stmt = $conn->prepare($sql);
$searchTerm = '%' . $searchTerm . '%'; // Add wildcards for partial matching
$stmt->bind_param("s", $searchTerm);

// Execute the query and get the results
$stmt->execute();
$result = $stmt->get_result();

// Prepare an array of movies
$movies = [];
while ($row = $result->fetch_assoc()) {
    $movies[] = $row;
}

// Close the database connection
$stmt->close();
$conn->close();

// Return the results as JSON
header('Content-Type: application/json');
echo json_encode($movies);
?>
