<?php
session_start();

// Check if the admin is logged in, redirect to login page if not
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../../login.php");
    exit();
}

// Include database connection file
require_once '../../includes/db_connection.php';

// Define an empty array to store show timings
$show_timings = array();

// Fetch show timings from the database
$sql = "SELECT st.timing_id, st.movie_id, st.start_time, st.end_time, m.title
        FROM show_timings st
        INNER JOIN movies m ON st.movie_id = m.movie_id
        ORDER BY st.start_time ASC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Loop through each row and store show timings in the array
    while ($row = $result->fetch_assoc()) {
        $show_timings[] = $row;
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Show Timings</title>
    <link rel="stylesheet" href="../../css/show_timings.css">
</head>
<body>
    <div class="container">
        <h2>Show Timings</h2>
        <?php if (!empty($show_timings)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>Movie Title</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($show_timings as $timing) : ?>
                        <tr>
                            <td><?php echo $timing['title']; ?></td>
                            <td><?php echo $timing['start_time']; ?></td>
                            <td><?php echo $timing['end_time']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No show timings available.</p>
        <?php endif; ?>
        <a href="add.php">Add Show Timing</a>
    </div>
</body>
</html>
