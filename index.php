<?php
include 'includes/db_connection.php';

$sql = "SELECT movies.*, show_timings.timing_id, show_timings.timing, show_timings.available_seats 
        FROM movies 
        INNER JOIN show_timings ON movies.movie_id = show_timings.movie_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cinema Hall Booking System</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <div class="container">
        <h1>Available Movies</h1>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="movie">
                    <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>">
                    <h2><?php echo $row['title']; ?></h2>
                    <p><?php echo $row['description']; ?></p>
                    <h3>Show Timings:</h3>
                    <ul>
                        <li><?php echo date('D, d M Y', strtotime($row['timing'])); ?> - <?php echo date('h:i A', strtotime($row['timing'])); ?> (Available Seats: <?php echo $row['available_seats']; ?>)</li>
                    </ul>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No movies available</p>
        <?php endif; ?>
    </div>
</body>
</html>
