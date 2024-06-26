<?php
require_once '../../includes/db_connection.php';

$sql = "SELECT * FROM movies";
$result = $conn->query($sql);

if ($result) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>All Movies</title>
        <link rel="stylesheet" href="../../css/movies.css">
    </head>
    <body>
    <div class="sidebar">
        <h2>Menu</h2>
        <ul>
            <li><a href="add.php">Add Movie</a></li>
            <li><a href="../book_ticket.php">Book Ticket</a></li>
            <li><a href="../show-time/add.php">Show Timings</a></li>
            <li><a href="../booking_history.php">Bookings</a></li>
            <li><a href="../../backend/logout.php">Logout</a></li>
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
                        <p class="movie-pricing">
                            <strong>VIP:</strong> ₹ <?php echo $row['VIP']; ?><br>
                            <strong>PREMIUM:</strong> ₹ <?php echo $row['PREMIUM']; ?><br>
                            <strong>GOLD:</strong> ₹ <?php echo $row['GOLD']; ?>
                        </p>
                        <a href="edit.php?movie_id=<?php echo $row['movie_id']; ?>"><button>Edit</button></a>
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
    echo "Error: " . $conn->error;
}

$conn->close();
?>