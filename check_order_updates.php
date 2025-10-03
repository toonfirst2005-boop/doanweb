<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (empty($_SESSION['id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require 'admin/connect_database.php';

$customer_id = $_SESSION['id'];
$last_check = isset($_GET['last_check']) ? $_GET['last_check'] : date('Y-m-d H:i:s', strtotime('-1 hour'));

// Get recent order status updates for this customer
$sql = "SELECT DISTINCT r.id, r.status, r.total_price, r.order_time, 
               osl.update_time, osl.updated_by, osl.old_status, osl.new_status
        FROM receipts r 
        INNER JOIN order_status_log osl ON r.id = osl.receipt_id
        WHERE r.customer_id = '$customer_id' 
        AND osl.update_time > '$last_check'
        ORDER BY osl.update_time DESC";

$result = mysqli_query($connect_database, $sql);
$updates = [];

while ($update = mysqli_fetch_assoc($result)) {
    $status_info = getStatusInfo($update['status']);
    $old_status_info = getStatusInfo($update['old_status']);
    
    $updates[] = [
        'order_id' => $update['id'],
        'old_status' => $old_status_info,
        'new_status' => $status_info,
        'updated_by' => $update['updated_by'],
        'update_time' => $update['update_time'],
        'total_price' => $update['total_price']
    ];
}

echo json_encode([
    'success' => true,
    'updates' => $updates,
    'current_time' => date('Y-m-d H:i:s')
]);

function getStatusInfo($status) {
    switch($status) {
        case 0:
            return [
                'class' => 'status-pending',
                'text' => 'Chờ xử lý',
                'icon' => 'fas fa-clock',
                'color' => '#856404',
                'bg_color' => '#fff3cd',
                'progress' => 10
            ];
        case 2:
            return [
                'class' => 'status-pending',
                'text' => 'Chờ xác nhận',
                'icon' => 'fas fa-hourglass-half',
                'color' => '#856404',
                'bg_color' => '#fff3cd',
                'progress' => 25
            ];
        case 3:
            return [
                'class' => 'status-cancelled',
                'text' => 'Shop đã hủy',
                'icon' => 'fas fa-ban',
                'color' => '#842029',
                'bg_color' => '#f8d7da',
                'progress' => 0
            ];
        case 4:
            return [
                'class' => 'status-processing',
                'text' => 'Đang giao hàng',
                'icon' => 'fas fa-shipping-fast',
                'color' => '#084298',
                'bg_color' => '#cfe2ff',
                'progress' => 75
            ];
        case 5:
            return [
                'class' => 'status-completed',
                'text' => 'Hoàn thành',
                'icon' => 'fas fa-check-circle',
                'color' => '#0f5132',
                'bg_color' => '#d1e7dd',
                'progress' => 100
            ];
        case 7:
            return [
                'class' => 'status-cancelled',
                'text' => 'Khách hủy',
                'icon' => 'fas fa-user-times',
                'color' => '#842029',
                'bg_color' => '#f8d7da',
                'progress' => 0
            ];
        default:
            return [
                'class' => 'status-pending',
                'text' => 'Chờ xử lý',
                'icon' => 'fas fa-clock',
                'color' => '#856404',
                'bg_color' => '#fff3cd',
                'progress' => 10
            ];
    }
}

mysqli_close($connect_database);
?>
