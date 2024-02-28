<?php
require_once '../includes/db_connection.php';

$sql = "SELECT * FROM tickets";
$result = mysqli_query($conn, $sql);

$bookings = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $bookingId = $row['booking_id'];
        $seatNumber = $row['seat_number'];

        if (!isset($bookings[$bookingId])) {
            $bookings[$bookingId] = array();
        }

        $bookings[$bookingId][] = $seatNumber;
    }

    foreach ($bookings as $bookingId => $seatNumbers) {
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