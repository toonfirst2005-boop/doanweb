<?php
header('Content-Type: application/json');
require '../check_admin_login.php';
require '../connect_database.php';

if (empty($_GET['id'])) {
    echo json_encode(['error' => 'ID không hợp lệ']);
    exit;
}

$id = $_GET['id'];

// Lấy thông tin đơn hàng
$sql_receipt = "SELECT receipts.*, customers.name as 'customer_name', customers.email as 'customer_email', customers.phone as 'customer_phone' 
FROM receipts
JOIN customers ON customers.id = receipts.customer_id
WHERE receipts.id = '$id'";

$query_receipt = mysqli_query($connect_database, $sql_receipt);
$receipt = mysqli_fetch_array($query_receipt);

if (!$receipt) {
    echo json_encode(['error' => 'Không tìm thấy đơn hàng']);
    exit;
}

// Lấy chi tiết sản phẩm trong đơn hàng
$sql_details = "SELECT receipt_detail.*, products.name as 'product_name', products.image as 'product_image'
FROM receipt_detail
JOIN products ON products.id = receipt_detail.product_id
WHERE receipt_detail.receipt_id = '$id'";

$query_details = mysqli_query($connect_database, $sql_details);
$products = [];
while ($row = mysqli_fetch_array($query_details)) {
    $products[] = $row;
}

mysqli_close($connect_database);

// Trả về JSON
echo json_encode([
    'receipt' => $receipt,
    'products' => $products
]);
?>
