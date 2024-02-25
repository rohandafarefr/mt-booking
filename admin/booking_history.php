<?php
require_once '../includes/db_connection.php';

// Fetch booked tickets from the database
$sql = "SELECT * FROM tickets";
$result = mysqli_query($conn, $sql);

// Initialize an array to store booked tickets grouped by booking ID
$bookings = array();

// Check if there are any booked tickets
if (mysqli_num_rows($result) > 0) {
    // Loop through each booked ticket and group them by booking ID
    while ($row = mysqli_fetch_assoc($result)) {
        $bookingId = $row['booking_id'];
        $seatNumber = $row['seat_number'];

        // If the booking ID is not already in the array, add it with an empty array
        if (!isset($bookings[$bookingId])) {
            $bookings[$bookingId] = array();
        }

        // Add the seat number to the array corresponding to the booking ID
        $bookings[$bookingId][] = $seatNumber;
    }

    // Loop through the grouped bookings and display them
    foreach ($bookings as $bookingId => $seatNumbers) {
        // Display booked ticket information
        echo "<div class='ticket'>";
        echo "<p>Booking ID: $bookingId</p>";
        echo "<p>Seat Numbers: " . implode(', ', $seatNumbers) . "</p>";
        echo "<a href='tickets.php?booking_id=$bookingId' class='view-ticket-btn'>View Ticket</a>";
        echo "</div>";
    }
} else {
    echo "<p>No booked tickets found.</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
    <link rel="stylesheet" href="../css/bh.css">
</head>
<body>
    
</body>
</html>
