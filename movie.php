<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film Flix - Movie Details</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #fff;
            background: linear-gradient(to right, #1e2a47, #2d3c6b);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header */
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 40px;
            background-color: #333;
            color: white;
        }

        header h1 {
            margin: 0;
            font-size: 2.5em;
        }

        .back-btn {
            background-color: #e74c3c;
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1.2em;
        }

        .back-btn:hover {
            background-color: #c0392b;
        }

        /* Movie Detail Section */
        .movie-detail-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px;
            padding: 0 20px;
        }

        .movie-detail {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 800px;
            background-color: #1c1f35;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            text-align: center;
        }

        .movie-detail img {
            width: 100%;
            max-width: 300px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .movie-detail img:hover {
            transform: scale(1.05);
        }

        .movie-detail h2 {
            font-size: 2.5em;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .movie-detail p {
            font-size: 1.1em;
            line-height: 1.8;
            margin-bottom: 10px;
        }

        .movie-detail span {
            font-weight: bold;
        }

        .movie-detail .genre {
            background-color: #e74c3c;
            color: white;
            padding: 5px 15px;
            font-size: 1em;
            border-radius: 25px;
            margin-top: 10px;
        }
        .streaming-options {
    margin-top: 20px;
    text-align: left;
    font-size: 1.2em;
}

.streaming-options ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.streaming-options li {
    margin: 10px 0;
}

.streaming-options a {
    color: #e74c3c;
    text-decoration: none;
    font-weight: bold;
}

.streaming-options a:hover {
    text-decoration: underline;
}

        /* Rating Stars */
        .rating-stars {
            margin: 15px 0;
        }

        .star {
            font-size: 1.5em;
            color: #f1c40f;
            margin: 0 2px;
        }

        .star.empty {
            color: #bdc3c7;
        }

        /* User Ratings and Comments */
        .user-rating-container {
            margin: 30px auto;
            width: 100%;
            max-width: 600px;
            background-color: #1c1f35;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
            color: #fff;
        }

        .user-rating-container h3 {
            font-size: 1.8em;
            margin-bottom: 15px;
        }

        .user-rating-container label {
            font-size: 1.1em;
            display: block;
            margin-top: 15px;
            text-align: left;
        }

        .user-rating-container select,
        .user-rating-container textarea {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            border-radius: 4px;
            border: 1px solid #555;
            background-color: #2d3c6b;
            color: #fff;
            margin-top: 5px;
            resize: vertical;
        }

        .user-rating-container button {
            margin-top: 15px;
            background-color: #e74c3c;
            color: white;
            padding: 12px 20px;
            font-size: 1.2em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .user-rating-container button:hover {
            background-color: #c0392b;
        }

        /* Comments Section */
        .comments-section {
            margin: 30px auto;
            width: 100%;
            max-width: 600px;
            background-color: #1c1f35;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            color: #fff;
        }

        .comments-section h3 {
            font-size: 1.8em;
            margin-bottom: 15px;
        }

        .comment-item {
            padding: 10px;
            border-bottom: 1px solid #333;
            font-size: 1.1em;
        }

        .comment-item:last-child {
            border-bottom: none;
        }

        .comment-item p {
            margin: 5px 0;
        }

        .comment-item small {
            color: #bdc3c7;
        }

        .delete-btn {
            background-color: #e74c3c;
            color: white;
            padding: 5px 10px;
            font-size: 1em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        /* Footer */
        footer {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: 1em;
            margin-top: auto;
        }

        footer a {
            color: #e74c3c;
            text-decoration: none;
            margin: 0 10px;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <h1>Film Flix</h1>
        <a href="index.php" class="back-btn">Back to Movies</a>
    </header>

    <!-- Movie Details Section -->
    <div id="movie-detail" class="movie-detail-container">
        <div class="loading-spinner" id="loading-spinner">
            <div class="spinner"></div>
            <span>Loading movie details...</span>
        </div>
    </div>

    <!-- User Rating and Comment Form -->
    <div class="user-rating-container">
        <h3>Rate and Comment</h3>
        <label for="rating">Rating:</label>
        <select id="rating">
            <option value="">Select Rating</option>
            <option value="1">1 Star</option>
            <option value="2">2 Stars</option>
            <option value="3">3 Stars</option>
            <option value="4">4 Stars</option>
            <option value="5">5 Stars</option>
        </select>

        <label for="comment">Comment:</label>
        <textarea id="comment" placeholder="Write your comment here..."></textarea>

        <button id="submitBtn">Submit Rating & Comment</button>
    </div>

    <!-- Comments Section -->
    <div class="comments-section" id="comments-section">
        <h3>User Comments</h3>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2023 Film Flix. All rights reserved.</p>
        <p>
            <a href="about.php">About Us</a> | 
            <a href="contact.php">Contact</a> | 
            <a href="privacy.php">Privacy Policy</a>
        </p>
    </footer>

    <script>
        const apiKey = '15685706'; // Replace with your actual OMDb API key
        const apiBaseUrl = 'https://www.omdbapi.com';

        // Pass PHP session variable to JavaScript
        const isLoggedIn = <?php echo isset($_SESSION['username']) ? 'true' : 'false'; ?>;
        const loggedInUser = <?php echo json_encode($_SESSION['username'] ?? ''); ?>;
        
        document.addEventListener("DOMContentLoaded", function () {
            const movieDetailContainer = document.getElementById("movie-detail");
            const loadingSpinner = document.getElementById("loading-spinner");
            const submitBtn = document.getElementById("submitBtn");
            const commentsSection = document.getElementById("comments-section");

            // Get the movie ID from the URL
            const urlParams = new URLSearchParams(window.location.search);
            const imdbID = urlParams.get("id");

            if (!imdbID) {
                movieDetailContainer.innerHTML = '<p>Error: Movie ID not found in URL.</p>';
                loadingSpinner.style.display = 'none';
                return;
            }

            // Fetch movie details from OMDb API
            fetch(`${apiBaseUrl}/?i=${imdbID}&apikey=${apiKey}`)
                .then(response => response.json())
                .then(data => {
                    loadingSpinner.style.display = 'none';

                    if (data.Response === "False") {
                        movieDetailContainer.innerHTML = `<p>Error: ${data.Error}</p>`;
                        return;
                    }

                    const movie = data;
                    const poster = movie.Poster !== "N/A" ? movie.Poster : 'https://via.placeholder.com/500x750?text=No+Image';

                    movieDetailContainer.innerHTML = ` 
                        <div class="movie-detail">
                            <img src="${poster}" alt="${movie.Title}">
                            <h2>${movie.Title} (${movie.Year})</h2>
                            <p><span>Genre:</span> ${movie.Genre}</p>
                            <p><span>Director:</span> ${movie.Director}</p>
                            <p><span>Actors:</span> ${movie.Actors}</p>
                            <p><span>Plot:</span> ${movie.Plot}</p>
                            <p><span>IMDb Rating:</span> ${movie.imdbRating}</p>
                            <div class="rating-stars">${getStarRating(movie.imdbRating)}</div>
                            <p><span>Runtime:</span> ${movie.Runtime}</p>
                            <p><span>Language:</span> ${movie.Language}</p>
                            <div class="genre">${movie.Genre}</div>
                        </div>
                    `;
                    
                })
                .catch(error => {
                    console.error("Error fetching movie details:", error);
                    loadingSpinner.style.display = 'none';
                    movieDetailContainer.innerHTML = '<p>Error: Failed to load movie details.</p>';
                });

            // Function to display star rating
            function getStarRating(imdbRating) {
                const rating = Math.round(imdbRating / 2); // Convert to 5-star rating
                let stars = '';
                for (let i = 1; i <= 5; i++) {
                    stars += `<span class="star${i <= rating ? '' : ' empty'}">&#9733;</span>`;
                }
                return stars;
            }

            // Load comments from the server
            function loadComments() {
                fetch(`get_reviews.php?movie_id=${imdbID}`)
                    .then(response => response.json())
                    .then(comments => {
                        commentsSection.innerHTML = `<h3>User Comments</h3>`;
                        if (comments.length === 0) {
                            commentsSection.innerHTML += `<p>No comments yet. Be the first to comment!</p>`;
                            return;
                        }
                        comments.forEach(comment => {
                            const canDelete = comment.username === loggedInUser;
                            commentsSection.innerHTML += `
                                <div class="comment-item">
                                    <p><strong>${comment.username}</strong> - ${getStarRatingFromHTML(comment.rating)} </p>
                                    <p>${comment.comment}</p>
                                    <p><small>Posted on: ${new Date(comment.created_at).toLocaleString()}</small></p>
                                    ${canDelete ? `<button class="delete-btn" data-comment-id="${comment.id}">Delete</button>` : ''}
                                </div>
                            `;
                        });

                        // Add delete button functionality
                        document.querySelectorAll('.delete-btn').forEach(button => {
                            button.addEventListener('click', function () {
                                const commentId = this.getAttribute('data-comment-id');
                                deleteComment(commentId);
                            });
                        });
                    })
                    .catch(error => {
                        console.error("Error loading comments:", error);
                        commentsSection.innerHTML = `<h3>User Comments</h3><p>Error loading comments.</p>`;
                    });
            }

            loadComments();  // Load comments initially

            // Submit new comment and rating
            submitBtn.addEventListener("click", function () {
                if (!isLoggedIn) {
                    alert("Please log in to submit a rating and comment.");
                    window.location.href = "login.php"; // Redirect to login page
                    return;
                }

                const rating = document.getElementById("rating").value;
                const comment = document.getElementById("comment").value.trim();

                if (rating === "" || comment === "") {
                    alert("Please select a rating and enter a comment.");
                    return;
                }

                // Send data to the backend
                fetch("submit_review.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ imdbID, rating, comment }),
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert("Rating and comment submitted successfully!");
                        document.getElementById("rating").value = "";
                        document.getElementById("comment").value = "";
                        loadComments();  // Reload comments to show the new one
                    } else {
                        alert("Failed to submit comment: " + result.message);
                    }
                })
                .catch(error => {
                    console.error("Error submitting comment:", error);
                    alert("An error occurred while submitting your comment. Please try again.");
                });
            });

            // Delete comment
            function deleteComment(commentId) {
                if (confirm("Are you sure you want to delete this comment?")) {
                    fetch("delete_review.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({ commentId })
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            alert("Comment deleted successfully!");
                            loadComments();  // Reload comments to reflect the deletion
                        } else {
                            alert("Failed to delete comment: " + result.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error deleting comment:", error);
                        alert("An error occurred while deleting the comment.");
                    });
                }
            }

            // Helper function to convert numeric rating to stars for comments
            function getStarRatingFromHTML(rating) {
                let stars = '';
                for (let i = 1; i <= 5; i++) {
                    stars += `<span class="star${i <= rating ? '' : ' empty'}">&#9733;</span>`;
                }
                return stars;
            }
        });
    </script>
</body>
</html>