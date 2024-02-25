<?php
require_once '../includes/db_connection.php';

if (isset($_GET['booking_id'])) {
    $bookingId = $_GET['booking_id'];

    $sql = "SELECT * FROM tickets WHERE booking_id = $bookingId";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $bookingInfo = mysqli_fetch_assoc($result);
        $movieId = $bookingInfo['movie_id'];
        $timingId = $bookingInfo['timing_id'];
        $seatNumbers = array();

        $sql = "SELECT * FROM movies WHERE movie_id = $movieId";
        $result = mysqli_query($conn, $sql);
        $movieInfo = mysqli_fetch_assoc($result);

        $sql = "SELECT * FROM show_timings WHERE timing_id = $timingId";
        $result = mysqli_query($conn, $sql);
        $timingInfo = mysqli_fetch_assoc($result);

        $sql = "SELECT seat_number FROM tickets WHERE booking_id = $bookingId";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $seatNumbers[] = $row['seat_number'];
        }

        $totalAmount = 0;
        foreach ($seatNumbers as $seat) {
            $category = getCategoryForSeat($seat);
            $amount = $movieInfo[$category];
            $totalAmount += $amount;
        }
    } else {
        echo "<p>No booking found for the provided booking ID.</p>";
        exit();
    }
} else {
    echo "<p>Booking ID is not provided.</p>";
    exit();
}

function getCategoryForSeat($seat) {
    $category = strtoupper(substr($seat, 0, 1));
    
    switch ($category) {
        case 'A':
        case 'B':
        case 'C':
            return 'VIP';
        case 'D':
        case 'E':
        case 'F':
            return 'PREMIUM';
        case 'G':
        case 'H':
        case 'I':
            return 'GOLD';
        default:
            return '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket <?php echo $bookingId ?></title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link rel="stylesheet" href="../css/ticket.css">
    <style>
        .image{
            background-image: url("<?php echo $movieInfo['image']; ?>");
        }
    </style>
</head>
<body>

<div class="ticket created-by-anniedotexe">
    <div class="left">
        <div class="image">
            <p class="admit-one">
                <span>UT CINEMA</span>
                <span>UT CINEMA</span>
                <span>UT CINEMA</span>
            </p>
            <div class="ticket-number">
                <p><?php echo $bookingId; ?></p>
            </div>
        </div>
        <div class="ticket-info">
            <div class="show-name">
                <h1><?php echo $movieInfo['title']; ?></h1>
                <p>Seat <?php echo implode(', ', $seatNumbers); ?></p>
                <p>Total ₹<?php echo number_format($totalAmount, 2); ?></p>
            </div>
            <div class="time">
                <p><?php echo $timingInfo['start_time']; ?> <span>TO</span><?php echo $timingInfo['end_time'];?></p>
            </div>
            <p class="location"><span>UT Cinema, -</span>
                <span class="separator"></span><span> Kamptee, Nagpur, Maharashta</span>
            </p>
        </div>
    </div>
    <div class="right">
        <p class="admit-one">
            <span>UT CINEMA</span>
            <span>UT CINEMA</span>
            <span>UT CINEMA</span>
        </p>
        <div class="right-info-container">
            <div class="show-name">
                <h1><?php echo $movieInfo['title']; ?></h1>
                <p>Seat <?php echo implode(', ', $seatNumbers); ?></p>
            </div>
            <div class="time">
                <p><?php echo $timingInfo['start_time']; ?> <span>TO</span><?php echo $timingInfo['end_time'];?></p>
            </div>
            <div class="barcode">
                <div id="qr-code"></div>
            </div>
            <p class="ticket-number">
                <?php echo $bookingId; ?>
            </p>
        </div>
    </div>
</div>

<script>
    const qrcode = new QRCode(document.getElementById("qr-code"), {
        text: "<?php echo $bookingId; ?>",
        width: 100,
        height: 100
    });
</script>

</body>
</html>
