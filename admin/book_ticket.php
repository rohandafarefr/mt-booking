<?php
require_once '../includes/db_connection.php';

$movies = array();
$show_timings = array();
$available_seats = array(
    'VIP' => array(),
    'PREMIUM' => array(),
    'GOLD' => array()
);

$sql = "SELECT * FROM movies";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $movies[$row['movie_id']] = $row;
}

if (isset($_POST['submit_movie'])) {
    $movieId = $_POST['movie_id'];

    $sql = "SELECT * FROM show_timings WHERE movie_id = $movieId";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $show_timings[] = $row;
    }
}

if (isset($_POST['submit_timing'])) {
    $movieId = $_POST['movie_id'];
    $timingId = $_POST['timing_id'];

    $sql = "SELECT seat_number, category FROM seating WHERE category IN (
    SELECT category FROM show_timings WHERE timing_id = $timingId
  ) AND seat_number NOT IN (
    SELECT seat_number FROM tickets WHERE timing_id = $timingId
  )";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $available_seats[$row['category']][] = $row['seat_number'];
    }
}

if (isset($_POST['submit_seat'])) {
    $movieId = $_POST['movie_id'];
    $timingId = $_POST['timing_id'];
    $selectedSeats = $_POST['seats'];

    if (!empty($selectedSeats)) {
        $bookingId = mt_rand(1000, 9999);

        $stmt = $conn->prepare("INSERT INTO tickets (movie_id, timing_id, seat_number, booking_id, amount) VALUES (?, ?, ?, ?, ?)");

        foreach ($selectedSeats as $seat) {
            $category = getCategoryForSeat($seat);
            $amount = $movies[$movieId][$category];
            $stmt->bind_param("iisid", $movieId, $timingId, $seat, $bookingId, $amount);
            if (!$stmt->execute()) {
                echo "Error booking ticket: " . $stmt->error;
                $stmt->close();
                exit();
            }
        }

        $stmt->close();

        header("Location: tickets.php?booking_id=$bookingId");
    } else {
        echo "Please select at least one seat.";
    }
}

function getCategoryForSeat($seat) {
    $row = substr($seat, 1);
    $category = strtoupper(substr($seat, 0, 1));

    $vipRows = range(1, 5);
    $premiumRows = range(6, 15);
    $goldRows = range(16, 30);

    if (in_array($row, $vipRows)) {
        return 'VIP';
    } elseif (in_array($row, $premiumRows)) {
        return 'PREMIUM';
    } elseif (in_array($row, $goldRows)) {
        return 'GOLD';
    } else {
        return 'UNKNOWN';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/book.css">
    <title>Book Ticket</title>
</head>
<body>
    <h1>Book Ticket</h1>
    <form method="post">
        <select name="movie_id">
            <option value="">Select Movie</option>
            <?php foreach ($movies as $movieId => $movie): ?>
                <option value="<?php echo $movieId; ?>"><?php echo $movie['title']; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" name="submit_movie" value="Select Movie">
    </form>

    <?php if (isset($show_timings) && !empty($show_timings)): ?>
        <h2>Show Timings</h2>
        <form method="post">
            <input type="hidden" name="movie_id" value="<?php echo $movieId; ?>">
            <?php foreach ($show_timings as $timing): ?>
                <label for="timing_<?php echo $timing['timing_id']; ?>">
                    <input type="radio" name="timing_id" id="timing_<?php echo $timing['timing_id']; ?>" value="<?php echo $timing['timing_id']; ?>">
                    <?php echo $timing['start_time'] . ' - ' . $timing['end_time']; ?>
                </label><br>
            <?php endforeach; ?>
            <input type="submit" name="submit_timing" value="Select Timing">
        </form>
    <?php endif; ?>

    <?php if (isset($available_seats) && !empty($available_seats)): ?>
        <h2>Available Seats</h2>
        <div class="seat-container">
            <form method="post">
                <input type="hidden" name="movie_id" value="<?php echo $movieId; ?>">
                <input type="hidden" name="timing_id" value="<?php echo $timingId; ?>">
                <?php foreach ($available_seats as $category => $seats): ?>
                    <?php if (!empty($seats)): ?>
                        <h3><?php echo $category; ?> Seats : ₹<?php echo $movies[$movieId][$category]; ?></h3>
                        <div class="seat-grid">
                            <?php
                            $columns = range('A', 'C');
                            $rows = range(1, 8);
                            if ($category == 'PREMIUM') {
                                $columns = range('D', 'F');
                                $rows = range(1, 18);
                            } elseif ($category == 'GOLD') {
                                $columns = range('G', 'I');
                                $rows = range(1, 20);
                            }

                            foreach ($rows as $row): ?>
                                <div class="seat-row">
                                    <?php foreach ($columns as $column):
                                        $seat = $column . $row; ?>
                                        <div class="seat-item">
                                            <label class="seat-label" for="<?php echo $seat; ?>">
                                                <?php echo $seat . '<br>'; ?>
                                                <input type="checkbox" id="<?php echo $seat; ?>" name="seats[]" value="<?php echo $seat; ?>">
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <input type="submit" name="submit_seat" value="Book Ticket">
            </form>
        </div>
    <?php endif; ?>

    <script>
        document.querySelectorAll('.seat-label input[type="checkbox"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    this.parentElement.classList.add('checked');
                } else {
                    this.parentElement.classList.remove('checked');
                }
            });
        });
    </script>

</body>
</html>
