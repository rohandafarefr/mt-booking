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
$movie_id = $timing_err = $available_seats_err = "";
$timing_errors = [];

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate movie ID
    if (empty(trim($_POST["movie_id"]))) {
        $movie_id_err = "Please select a movie.";
    } else {
        $movie_id = trim($_POST["movie_id"]);
    }

    // Validate show timings
    $show_timings = $_POST['show_timings'];
    foreach ($show_timings as $index => $show_timing) {
        $start_time = trim($show_timing['start_time']);
        $end_time = trim($show_timing['end_time']);

        if (empty($start_time) || empty($end_time)) {
            $timing_errors[$index] = "Please enter both start time and end time.";
        } else {
            // Perform any additional validation for start time and end time if needed
            // For example: Check if end time is greater than start time
        }
    }

    // Check if there are no timing errors before inserting into database
    if (empty($movie_id_err) && empty($timing_errors)) {
        // Prepare an insert statement
        $sql = "INSERT INTO show_timings (movie_id, start_time, end_time) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("iss", $param_movie_id, $param_start_time, $param_end_time);

            // Set parameters and execute the statement for each show timing
            foreach ($show_timings as $index => $show_timing) {
                $param_movie_id = $movie_id;
                $param_start_time = $show_timing['start_time'];
                $param_end_time = $show_timing['end_time'];

                // Attempt to execute the prepared statement
                if (!$stmt->execute()) {
                    echo "Something went wrong. Please try again later.";
                    break;
                }
            }

            // Redirect to show timings page after successful creation
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }

        // Close statement
        $stmt->close();
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
</head>
<body>
    <style>
        /* add_show_timing.css */

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

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="datetime-local"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button[type="button"], button[type="submit"], a {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        button[type="button"]:hover, button[type="submit"]:hover, a:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

    </style>
    <div class="container">
        <h2>Add Show Timing</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="movie_id">Select Movie:</label>
                <select name="movie_id" id="movie_id">
                    <?php
                    // Fetch movies from the database and populate the dropdown list
                    $sql = "SELECT movie_id, title FROM movies";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row["movie_id"] . "'>" . $row["title"] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <!-- Show Timings Fields -->
            <div id="show_timings_fields">
                <div class="show_timing_field">
                    <div class="form-group">
                        <label>Start Time:</label>
                        <input type="datetime-local" name="show_timings[0][start_time]" required>
                    </div>
                    <div class="form-group">
                        <label>End Time:</label>
                        <input type="datetime-local" name="show_timings[0][end_time]" required>
                    </div>
                </div>
            </div>


            <?php
            // Display timing errors if any
            foreach ($timing_errors as $error) {
                echo "<div class='error'>$error</div>";
            }
            ?>

            <div class="form-group">
                <button type="submit">Submit</button>
                <a href="read.php">Cancel</a>
            </div>
        </form>
    </div>

</body>
</html>
