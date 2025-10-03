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

// Get specific order if order_id is provided
if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);
    $sql = "SELECT id, status, order_time, total_price, receiver_name, receiver_phone, receiver_address 
            FROM receipts 
            WHERE id = '$order_id' AND customer_id = '$customer_id'";
    $result = mysqli_query($connect_database, $sql);
    
    if ($order = mysqli_fetch_assoc($result)) {
        // Add status details
        $status_info = getStatusInfo($order['status']);
        $order['status_info'] = $status_info;
        echo json_encode(['success' => true, 'order' => $order]);
    } else {
        echo json_encode(['error' => 'Order not found']);
    }
} else {
    // Get all orders for the customer
    $sql = "SELECT id, status, order_time, total_price, receiver_name, receiver_phone, receiver_address 
            FROM receipts 
            WHERE customer_id = '$customer_id' 
            ORDER BY order_time DESC";
    $result = mysqli_query($connect_database, $sql);
    
    $orders = [];
    while ($order = mysqli_fetch_assoc($result)) {
        $status_info = getStatusInfo($order['status']);
        $order['status_info'] = $status_info;
        $orders[] = $order;
    }
    
    echo json_encode(['success' => true, 'orders' => $orders]);
}

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
