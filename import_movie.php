<?php
// Database connection settings
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "filmflix"; 

// Create DB connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// OMDb API Key
$apiKey = '15685706';
$baseUrl = 'http://www.omdbapi.com/';

// Example movie categories to import
$movieCategories = ['popular', 'top_rated', 'action', 'comedy', 'drama']; // etc.

// Loop through each category and fetch movies
foreach ($movieCategories as $category) {
    // For this example, we fetch movies based on genres or keywords
    $url = $baseUrl . "?s=" . urlencode($category) . "&apikey=" . $apiKey;
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if ($data['Response'] === 'True') {
        // Insert movies into the database
        foreach ($data['Search'] as $movie) {
            $title = $movie['Title'];
            $year = $movie['Year'];
            $poster = ($movie['Poster'] !== 'N/A') ? $movie['Poster'] : null;
            $imdbID = $movie['imdbID'];

            // Prepare SQL query to insert the movie into the database
            $sql = "INSERT INTO movies (title, year, poster, imdb_id) VALUES (?, ?, ?, ?)";

            // Prepare the statement and bind parameters
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ssss", $title, $year, $poster, $imdbID);

                // Execute the query
                if ($stmt->execute()) {
                    echo "Inserted movie: $title<br>";
                } else {
                    echo "Error inserting movie: $title<br>";
                }

                $stmt->close();
            }
        }
    } else {
        echo "Error fetching data for category: $category<br>";
    }
}

// Close DB connection
$conn->close();
?>
