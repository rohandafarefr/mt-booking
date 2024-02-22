<?php
// Include database configuration file
require_once '../includes/db_connection.php';

// Fetch all movies from the database
$sql = "SELECT * FROM movies";
$result = $conn->query($sql);

// Check if movies exist
if ($result) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>All Movies</title>
        <link rel="stylesheet" href="../css/movies.css">
    </head>
    <body>
    <div class="sidebar">
            <h2>Menu</h2>
            <ul>
                <li><a href="book_ticket.php">Book Ticket</a></li>
                <li><a href="movie.php">Movies</a></li>
                <li><a href="add_show_timing.php">Show Timings</a></li>
                <li><a href="booking_history.php">Bookings</a></li>
                <li><a href="../backend/logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="container">
        
            <h2>All Movies</h2>
            <div class="movie-list">
                <?php
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="movie-card">
                    <img class="movie-image" src="<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">

                        <div class="movie-info">
                            <h3 class="movie-title"><?php echo $row['title']; ?></h3>
                            <p class="movie-description"><?php echo $row['description']; ?></p>
                            <a href="#"><button>Edit</button></a>
                            <a href="#"><button>Delete</button></a>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </body>
    </html>
    <?php
} else {
    // Handle error if query fails
    echo "Error: " . $conn->error;
}

// Close database connection
$conn->close();
?>
