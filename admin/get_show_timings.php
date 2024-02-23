<?php
// Include database configuration file
require_once '../includes/db_connection.php';

// Get movie ID from the request
$movieId = isset($_GET['movie_id']) ? $_GET['movie_id'] : '';

// Fetch show timings based on movie ID from the database
$sql = "SELECT * FROM show_timings";
$result = $conn->query($sql);

$showTimings = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $showTimings[] = $row;
    }
}

$conn->close();

// Return show timings as JSON data
header('Content-Type: application/json');
echo json_encode($showTimings);
?>
