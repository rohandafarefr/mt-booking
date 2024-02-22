<?php
// Fetch seating data from the database
require_once '../../includes/db_connection.php'; // Adjust the path as per your file structure

$sql = "SELECT * FROM seating";
$result = $conn->query($sql);

$seating_data = [];
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $seating_data['VIP'] = explode(',', $row['VIP']);
    $seating_data['Premium'] = explode(',', $row['Premium']);
    $seating_data['Gold'] = explode(',', $row['Gold']);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theater Seating Layout</title>
    <style>
        .theater {
            width: 1000px;
            border: 1px solid #ccc;
            display: flex;
            flex-direction: column;

            align-items: center;
        }

        .row {
            display: flex;
            margin-bottom: 10px;
        }

        .column {
            display: flex;
            flex-direction: column;
            margin-right: 10px;
        }

        .seat {
            width: 30px;
            height: 30px;
            border: 1px solid #ccc;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 5px; /* Adjust the margin here */
            margin-bottom: 5px; /* Add margin bottom */
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
    <div class="theater">
        <?php foreach ($seating_data as $category => $seats) : ?>
            <div class="column">
                <?php 
                    $mod_value = ($category === 'VIP') ? 8 : (($category === 'Premium') ? 18 : 20);
                    foreach ($seats as $index => $seat) : 
                ?>
                    <?php if ($index % $mod_value === 0) : ?>
                        <div class="row">
                    <?php endif; ?>
                    <div class="seat <?php echo strtolower($category); ?>"><?php echo $seat; ?></div>
                    <?php if (($index + 1) % $mod_value === 0 || $index === count($seats) - 1) : ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
