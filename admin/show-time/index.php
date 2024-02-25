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
    <style>
        /* show_timings.css */

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        a:hover {
            background-color: #0056b3;
        }

    </style>
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
