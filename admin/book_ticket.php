<?php
// Include database configuration file
require_once '../includes/db_connection.php';

// Fetch movies from the database
$sql = "SELECT * FROM movies";
$result = $conn->query($sql);

$movies = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $movies[$row['movie_id']] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Tickets</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Add your CSS file here -->
    <style>
        /* Add your CSS styles for the seating layout here */
        .theater {
            width: 1000px;
            border: 1px solid #ccc;
            display: none; /* Hide by default */
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        .column {
            display: flex;
            flex-direction: column;
        }

        .row {
            display: flex;
            margin-bottom: 10px;
        }

        .seat {
            width: 30px;
            height: 30px;
            border: 1px solid #ccc;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 5px;
            cursor: pointer;
        }

        .seat.selected {
            background-color: #00ff00; /* Change to desired selected color */
        }

        .vip {
            background-color: #ffcc00;
        }

        .premium {
            background-color: #66ccff;
        }

        .gold {
            background-color: #ff9900;
        }
    </style>
</head>
<body>
    <h2>Book Tickets</h2>
    <form method="post" action="confirm_booking.php">
        <div>
            <label for="movie">Select Movie:</label>
            <select id="movie" name="movie" required>
                <option value="" disabled selected>Select a movie</option>
                <?php foreach ($movies as $movie_id => $movie) : ?>
                    <option value="<?php echo $movie_id; ?>"><?php echo $movie['title']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="showTiming">Select Show Timing:</label>
            <select id="showTiming" name="showTiming" required disabled>
                <option value="" disabled selected>Select a show timing</option>
            </select>
        </div>
        <div class="theater" id="seatingLayout">
            <!-- Seating layout will be loaded dynamically here -->
        </div>
        <button type="submit" id="bookNowBtn" disabled>Book Now</button>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var movieSelect = document.getElementById("movie");
            var showTimingSelect = document.getElementById("showTiming");
            var seatingLayout = document.getElementById("seatingLayout");
            var bookNowBtn = document.getElementById("bookNowBtn");

            // Event listener for movie selection
            movieSelect.addEventListener("change", function() {
                var selectedMovieId = this.value;
                if (selectedMovieId) {
                    // Enable show timing selection
                    showTimingSelect.disabled = false;
                    showTimingSelect.innerHTML = '<option value="" disabled selected>Loading...</option>';

                    // Fetch show timings for the selected movie
                    fetch('get_show_timings.php?movie_id=' + selectedMovieId)
                        .then(response => response.json())
                        .then(data => {
                            showTimingSelect.innerHTML = '<option value="" disabled selected>Select a show timing</option>';
                            data.forEach(function(showTiming) {
                                var option = document.createElement("option");
                                option.value = showTiming.timing_id;
                                option.textContent = showTiming.start_time + ' - ' + showTiming.end_time;
                                showTimingSelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                } else {
                    // Disable and reset show timing selection
                    showTimingSelect.disabled = true;
                    showTimingSelect.innerHTML = '<option value="" disabled selected>Select a show timing</option>';
                }
            });

            // Event listener for show timing selection
            showTimingSelect.addEventListener("change", function() {
                var selectedShowTimingId = this.value;
                if (selectedShowTimingId) {
                    // Fetch and display seating layout for the selected show timing
                    var selectedMovieId = movieSelect.value;
                    seatingLayout.innerHTML = 'Loading seating layout...';
                    fetch('get_seating_layout.php?movie_id=' + selectedMovieId + '&show_timing_id=' + selectedShowTimingId)
                        .then(response => response.text())
                        .then(data => {
                            seatingLayout.innerHTML = data;
                            seatingLayout.style.display = "flex"; // Show seating layout
                            bookNowBtn.disabled = false; // Enable booking button
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                } else {
                    // Hide seating layout if show timing is not selected
                    seatingLayout.style.display = "none";
                    bookNowBtn.disabled = true; // Disable booking button
                }
            });
        });
    </script>
</body>
</html>
