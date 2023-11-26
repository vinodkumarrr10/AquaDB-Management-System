<?php



function getTotalFareForLatestBookingForEachCustomer($pdo) {
    $stmt = $pdo->query('
        SELECT customer_phone, SUM(fare) AS total_fare
        FROM rentpro r1
        WHERE booking_date = (
            SELECT MAX(booking_date) 
            FROM rentpro r2 
            WHERE r1.customer_phone = r2.customer_phone
        )
        GROUP BY customer_phone
    ');

    if (!$stmt) {
        // Handle the error, e.g., print an error message or log it
        print_r($pdo->errorInfo());
        return false;
    }

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

// Example usage:
$pdo = new PDO('mysql:host=localhost;dbname=aquadb', 'root', '');

$totalFareByCustomer = getTotalFareForLatestBookingForEachCustomer($pdo);

if ($totalFareByCustomer !== false) {
    foreach ($totalFareByCustomer as $data) {
        echo "Customer Phone: {$data['customer_phone']}, Total Fare on Latest Booking Date: {$data['total_fare']}<br>";
    }
}

?>
