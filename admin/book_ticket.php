<?php
require_once '../includes/db_connection.php';

$movies = array();
$show_timings = array();
$available_seats = array(
    'VIP' => array(),
    'Premium' => array(),
    'Gold' => array()
);

$sql = "SELECT * FROM movies";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
  $movies[] = $row;
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
    $sql = "INSERT INTO tickets (movie_id, timing_id, seat_number) VALUES ";
    $values = array();
    foreach ($selectedSeats as $seat) {
      $values[] = "($movieId, $timingId, '$seat')";
    }
    $sql .= implode(',', $values);

    if (mysqli_query($conn, $sql)) {
      echo "Ticket booked successfully!";
    } else {
      echo "Error booking ticket: " . mysqli_error($conn);
    }
  } else {
    echo "Please select at least one seat.";
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
      <?php foreach ($movies as $movie): ?>
        <option value="<?php echo $movie['movie_id']; ?>"><?php echo $movie['title']; ?></option>
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

      <?php if (!empty($available_seats['VIP'])): ?>
        <h3>VIP Seats : ₹350</h3>
        <div class="seat-grid">
          <?php
          $columns = range('A', 'C');
          $rows = range(1, 8);

          foreach ($rows as $row) {
            echo '<div class="seat-row">';
            foreach ($columns as $column) {
              $seat = $column . $row;
              echo '<div class="seat-item">';
              echo '<label class="seat-label" for="' . $seat . '">';
              echo $seat . '<br>';
              echo '<input type="checkbox" id="' . $seat . '" name="seats[]" value="' . $seat . '">';
              echo '</label>';
              echo '</div>';
            }
            echo '</div>';
          }
          ?>
        </div>
      


      <h3>Premium Seats : ₹250</h3>
      <div class="seat-grid">
          <?php
          $columns = range('D', 'F');
          $rows = range(1, 18);

          foreach ($rows as $row) {
            echo '<div class="seat-row">';
            foreach ($columns as $column) {
              $seat = $column . $row;
              echo '<div class="seat-item">';
              echo '<label class="seat-label" for="' . $seat . '">';
              echo $seat . '<br>';
              echo '<input type="checkbox" id="' . $seat . '" name="seats[]" value="' . $seat . '">';
              echo '</label>';
              echo '</div>';
            }
            echo '</div>';
          }
          ?>
        </div>

      <h3>Gold Seats : ₹200</h3>
      <div class="seat-grid">
          <?php
          $columns = range('G', 'K');
          $rows = range(1, 20);

          foreach ($rows as $row) {
            echo '<div class="seat-row">';
            foreach ($columns as $column) {
              $seat = $column . $row;
              echo '<div class="seat-item">';
              echo '<label class="seat-label" for="' . $seat . '">';
              echo $seat . '<br>';
              echo '<input type="checkbox" id="' . $seat . '" name="seats[]" value="' . $seat . '">';
              echo '</label>';
              echo '</div>';
            }
            echo '</div>';
          }
          ?>
        </div>
        <?php endif; ?>
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
