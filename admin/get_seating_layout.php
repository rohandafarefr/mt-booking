<?php
// Include database configuration file
require_once '../includes/db_connection.php';

// Check if the 'category' parameter is set and not empty
if(isset($_GET['category']) && !empty($_GET['category'])) {
    // Get category from the request
    $category = $_GET['category'];

    // Fetch seating layout and prices based on category from the database
    $sql = "SELECT * FROM seating";
    $result = $conn->query($sql);

    $seating_data = [];
    $prices = [];

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $seating_data['VIP'] = explode(',', $row['VIP']);
        $seating_data['Premium'] = explode(',', $row['Premium']);
        $seating_data['Gold'] = explode(',', $row['Gold']);
        
        // Fetch prices from the movies table
        $sql_prices = "SELECT VIP, PREMIUM, GOLD FROM movies";
        $result_prices = $conn->query($sql_prices);
        
        if ($result_prices->num_rows > 0) {
            $row_prices = $result_prices->fetch_assoc();
            $prices['VIP'] = $row_prices['VIP'];
            $prices['Premium'] = $row_prices['PREMIUM'];
            $prices['Gold'] = $row_prices['GOLD'];
        }
    }

    $conn->close();

    // Construct HTML for seating layout and prices
    $html = '<div class="seating-layout">';
    $html .= '<h3>Seating Layout for ' . $category . '</h3>';
    $html .= '<p>Price for ' . $category . ': $' . $prices[$category] . '</p>';
    $html .= '<div class="row">';
    foreach ($seating_data[$category] as $seat) {
        $html .= '<div class="seat">' . $seat . '</div>';
    }
    $html .= '</div>';
    $html .= '</div>';

    // Return the HTML layout
    echo $html;
} else {
    // Category is not set or empty
    echo "Category is not set or empty.";
}
?>
