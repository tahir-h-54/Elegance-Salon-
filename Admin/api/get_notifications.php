<?php
include "../../Database/connect_to_db.php";

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != '1') {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$notifications = [];

// Get new bookings (appointments created in last 7 days with status 'booked')
$bookings_query = "SELECT a.*, c.name as client_name, s.service_name 
                   FROM appointments a
                   LEFT JOIN clients c ON a.client_id = c.client_id
                   LEFT JOIN services s ON a.service_id = s.service_id
                   WHERE a.status = 'booked' 
                   AND a.appointment_date >= CURDATE()
                   AND DATE(a.appointment_date) <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                   ORDER BY a.appointment_date DESC, a.appointment_time DESC
                   LIMIT 10";
$bookings_result = mysqli_query($conn, $bookings_query);
while($row = mysqli_fetch_assoc($bookings_result)) {
    $notifications[] = [
        'type' => 'booking',
        'title' => 'New Booking',
        'message' => $row['client_name'] . ' booked ' . $row['service_name'] . ' on ' . date('M d, Y', strtotime($row['appointment_date'])) . ' at ' . date('g:i A', strtotime($row['appointment_time'])),
        'date' => $row['appointment_date'] . ' ' . $row['appointment_time'],
        'id' => $row['appointment_id']
    ];
}

// Get low stock alerts
$low_stock_query = "SELECT * FROM inventory 
                   WHERE quantity <= reorder_level 
                   ORDER BY quantity ASC
                   LIMIT 10";
$low_stock_result = mysqli_query($conn, $low_stock_query);
while($row = mysqli_fetch_assoc($low_stock_result)) {
    $status = $row['quantity'] <= ($row['reorder_level'] * 0.5) ? 'critical' : 'low';
    $notifications[] = [
        'type' => 'stock',
        'title' => 'Low Stock Alert',
        'message' => $row['item_name'] . ' is running low. Current stock: ' . $row['quantity'] . ' units',
        'date' => date('Y-m-d H:i:s'),
        'id' => $row['item_id'],
        'status' => $status
    ];
}

header('Content-Type: application/json');
echo json_encode([
    'notifications' => $notifications,
    'count' => count($notifications)
]);
?>

