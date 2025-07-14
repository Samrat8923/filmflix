<?php
session_start(); // Start the session to check if the user is logged in
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film Flix - Movie Search</title>
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            background-color: #333;
            color: white;
        }

        h1 {
            margin: 0;
            font-size: 2.5em;
            letter-spacing: 2px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-bar {
            display: flex;
            align-items: center;
        }

        .search-bar input[type="text"] {
            padding: 8px;
            font-size: 1em;
            border-radius: 4px;
            border: none;
            margin-right: 5px;
            width: 250px;
        }

        .search-bar button {
            padding: 8px 15px;
            font-size: 1em;
            cursor: pointer;
            background-color: #555;
            color: white;
            border: none;
            border-radius: 4px;
        }

        .sign-in-btn {
            padding: 8px 15px;
            font-size: 1em;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .logout-btn {
            padding: 8px 15px;
            font-size: 1em;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Movie List Grid */
        .movie-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Movie Poster Styling */
        .movie {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-align: center;
        }

        .movie:hover {
            transform: scale(1.05);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.3);
        }

        .movie img {
            width: 100%;
            height: auto;
            border-bottom: 1px solid #ddd;
            border-radius: 8px 8px 0 0;
        }

        .movie h4 {
            margin: 15px;
            font-size: 1.1em;
            color: #333;
        }

        /* Loading Spinner */
        #loading-spinner {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 20px;
            font-size: 1.5em;
            color: #555;
        }

        .spinner {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #333;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Error message styling */
        #error {
            text-align: center;
            color: red;
            font-size: 1.2em;
            margin-top: 20px;
        }

        /* Footer */
        footer {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
            margin-top: 20px;
        }

        footer a {
            color: #e74c3c;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Header with title, search bar, and sign-in/logout button -->
    <header>
        <h1>Film Flix</h1>
        <div class="header-right">
            <form id="searchForm" class="search-bar">
                <input type="text" id="searchInput" placeholder="Search movies...">
                <button type="submit">Search</button>
            </form>

            <!-- If user is logged in, display username, profile icon, and logout button -->
            <?php if (isset($_SESSION['username'])): ?>
                <span> <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                <a href="settings.php" class="profile-icon" title="Go to Profile">
                    <!-- Font Awesome Profile Icon -->
                    <i class="fas fa-user-circle" style="font-size: 30px; color: white;"></i>
                </a>
                <form action="logout.php" method="POST">
                    <button type="submit" name="logout" class="logout-btn">Logout</button>
                </form>
            <?php else: ?>
                <!-- If user is not logged in, show sign-in button -->
                <a href="login.php" class="sign-in-btn">Sign In</a>
            <?php endif; ?>
        </div>
    </header>

    <!-- Movie Gallery -->
    <div id="movie-list" class="movie-list">
        <div id="loading-spinner">
            <div class="spinner"></div>
            <span>Loading movies...</span>
        </div>
    </div>

    <div id="error" style="display: none;">Failed to load movies. Please try again later.</div>

    <!-- Footer with links -->
    <footer>
        <p>&copy; 2023 Film Flix | All Rights Reserved</p>
        <p><a href="contact.html">Contact Us</a> | <a href="privacy.html">Privacy Policy</a></p>
    </footer>

    <script>
        const apiKey = '15685706'; // Replace with your actual OMDb API key
        const apiBaseUrl = 'https://www.omdbapi.com';
        const placeholderImage = 'https://via.placeholder.com/500x750?text=No+Image';
        const moviesToDisplay = 24; // Number of movies you want to display

        document.addEventListener("DOMContentLoaded", function () {
            const movieList = document.getElementById("movie-list");
            const loadingSpinner = document.getElementById("loading-spinner");
            const errorMessage = document.getElementById("error");
            const searchForm = document.getElementById("searchForm");
            const searchInput = document.getElementById("searchInput");

            // Load initial popular movies (default search term: "star wars")
            fetchMovies("star wars");

            // Handle search form submission
            searchForm.addEventListener("submit", function (event) {
                event.preventDefault();
                const query = searchInput.value.trim();
                if (query) {
                    fetchMovies(query);
                }
            });

            // Function to fetch movies from OMDb API based on a search query
            function fetchMovies(query) {
                movieList.innerHTML = ""; // Clear previous results
                loadingSpinner.style.display = 'flex'; // Show loading spinner
                errorMessage.style.display = 'none';   // Hide error message

                let totalFetchedMovies = 0;
                let page = 1;

                function fetchPage() {
                    fetch(`${apiBaseUrl}/?s=${encodeURIComponent(query)}&apikey=${apiKey}&type=movie&page=${page}`)
                        .then(response => response.json())
                        .then(data => {
                            loadingSpinner.style.display = 'none'; // Hide loading spinner

                            if (data.Response === "False") {
                                errorMessage.style.display = 'block';   // Show error message
                                errorMessage.textContent = data.Error || "No movies found.";
                                return;
                            }

                            // Process the fetched movies
                            data.Search.forEach(movie => {
                                if (totalFetchedMovies < moviesToDisplay) {
                                    const movieDiv = document.createElement("div");
                                    movieDiv.className = "movie";

                                    const posterPath = movie.Poster !== "N/A" ? movie.Poster : placeholderImage;

                                    movieDiv.innerHTML = `
                                        <a href="movie.php?id=${movie.imdbID}">
                                            <img src="${posterPath}" alt="${movie.Title}">
                                            <h4>${movie.Title} (${movie.Year})</h4>
                                        </a>
                                    `;
                                    movieList.appendChild(movieDiv);
                                    totalFetchedMovies++;
                                }
                            });

                            // If we haven't fetched enough movies and there might be more, fetch the next page
                            if (totalFetchedMovies < moviesToDisplay && data.Search.length > 0) {
                                page++;
                                fetchPage(); // Fetch next page
                            }
                        })
                        .catch(error => {
                            console.error("Error fetching movies:", error);
                            loadingSpinner.style.display = 'none';  // Hide loading spinner
                            errorMessage.style.display = 'block';   // Show error message
                            errorMessage.textContent = "Failed to load movies. Please try again later.";
                        });
                }

                fetchPage(); // Start fetching movies from the first page
            }
        });
    </script>
</body>
</html>
