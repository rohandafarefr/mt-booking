<?php
// Include database configuration file
require_once '../includes/db_connection.php';

// Check if all required parameters are set
if (isset($_GET['movie_id'], $_GET['show_timing_id'], $_GET['seating_category'])) {
    $movieId = $_GET['movie_id'];
    $showTimingId = $_GET['show_timing_id'];
    $seatingCategory = $_GET['seating_category'];

    // Fetch seat numbers from the database based on the provided parameters
    $sql = "SELECT seat_number FROM seating WHERE movie_id = ? AND show_timing_id = ? AND category = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $movieId, $showTimingId, $seatingCategory);
    $stmt->execute();
    $result = $stmt->get_result();

    // Store seat numbers in an array
    $seatNumbers = [];
    while ($row = $result->fetch_assoc()) {
        $seatNumbers[] = $row['seat_number'];
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    // Return seat numbers as JSON data
    echo json_encode($seatNumbers);
} else {
    // Return an error message if any required parameter is missing
    echo json_encode(['error' => 'Missing parameters']);
}
?>
