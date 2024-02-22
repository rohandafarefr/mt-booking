<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="book_ticket.php">Book Ticket</a></li>
                <li><a href="add_movie.php">Add Movies</a></li>
                <li><a href="add_show_timing.php">Show Timings</a></li>
                <li><a href="booking_history.php">Bookings</a></li>
                <li><a href="backend/logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="content">
            <h1>UT CINEMA</h1>
        </div>
    </div>
</body>
</html>
