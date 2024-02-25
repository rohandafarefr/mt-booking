<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../../login.php");
    exit();
}

require_once '../../includes/db_connection.php';

$movie_id = $timing_err = $available_seats_err = "";
$timing_errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["movie_id"]))) {
        $movie_id_err = "Please select a movie.";
    } else {
        $movie_id = trim($_POST["movie_id"]);
    }

    $show_timings = $_POST['show_timings'];
    foreach ($show_timings as $index => $show_timing) {
        $start_time = trim($show_timing['start_time']);
        $end_time = trim($show_timing['end_time']);

        if (empty($start_time) || empty($end_time)) {
            $timing_errors[$index] = "Please enter both start time and end time.";
        }
    }

    if (empty($movie_id_err) && empty($timing_errors)) {
        $sql = "INSERT INTO show_timings (movie_id, start_time, end_time) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("iss", $param_movie_id, $param_start_time, $param_end_time);

            foreach ($show_timings as $index => $show_timing) {
                $param_movie_id = $movie_id;
                $param_start_time = $show_timing['start_time'];
                $param_end_time = $show_timing['end_time'];

                if (!$stmt->execute()) {
                    echo "Something went wrong. Please try again later.";
                    break;
                }
            }
            
            if (isset($stmt)) {
                $stmt->close();
            }

            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }

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
