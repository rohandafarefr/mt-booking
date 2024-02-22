<?php
session_start();

// Check if the admin is logged in, redirect to login page if not
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../../login.php");
    exit();
}

// Include database connection file
require_once '../../includes/db_connection.php';

// Define variables and initialize with empty values
$movie_id = $timing = $available_seats = "";
$movie_id_err = $timing_err = $available_seats_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate movie ID
    if (empty(trim($_POST["movie_id"]))) {
        $movie_id_err = "Please select a movie.";
    } else {
        $movie_id = trim($_POST["movie_id"]);
    }

    // Validate timing
    if (empty(trim($_POST["timing"]))) {
        $timing_err = "Please enter the show timing.";
    } else {
        $timing = trim($_POST["timing"]);
    }

    // Validate available seats
    if (empty(trim($_POST["available_seats"]))) {
        $available_seats_err = "Please enter the number of available seats.";
    } else {
        $available_seats = trim($_POST["available_seats"]);
    }

    // Check input errors before inserting into database
    if (empty($movie_id_err) && empty($timing_err) && empty($available_seats_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO show_timings (movie_id, timing, available_seats) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("iss", $param_movie_id, $param_timing, $param_available_seats);

            // Set parameters
            $param_movie_id = $movie_id;
            $param_timing = $timing;
            $param_available_seats = $available_seats;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to show timings page after successful creation
                header("Location: read.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Show Timing</title>
    <link rel="stylesheet" href="../../css/add_show_timing.css">
</head>
<body>
    <div class="container">
        <h2>Add Show Timing</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($movie_id_err)) ? 'has-error' : ''; ?>">
                <label>Movie:</label>
                <select name="movie_id">
                    <!-- Populate options dynamically from the database -->
                    <!-- Example: <option value="1">Movie Title 1</option> -->
                </select>
                <span class="error"><?php echo $movie_id_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($timing_err)) ? 'has-error' : ''; ?>">
                <label>Timing:</label>
                <input type="datetime-local" name="timing" value="<?php echo date('Y-m-d\TH:i'); ?>">
                <span class="error"><?php echo $timing_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($available_seats_err)) ? 'has-error' : ''; ?>">
                <label>Available Seats:</label>
                <input type="number" name="available_seats" value="<?php echo $available_seats; ?>">
                <span class="error"><?php echo $available_seats_err; ?></span>
            </div>
            <div class="form-group">
                <button type="submit">Submit</button>
                <a href="read.php">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
