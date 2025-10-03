<?php
require '../check_super_admin_login.php';
require '../connect_database.php';

if (empty($_GET['id'])) {
    $_SESSION['error'] = 'Chưa nhập id khách hàng cần xóa';
    header('location: index.php');
    exit;
}

$id = $_GET['id'];

// Kiểm tra khách hàng có tồn tại không
$sql_check = "SELECT id, name FROM customers WHERE id = '$id'";
$result_check = mysqli_query($connect_database, $sql_check);

if (mysqli_num_rows($result_check) == 0) {
    $_SESSION['error'] = 'Không tìm thấy khách hàng';
    mysqli_close($connect_database);
    header('location: index.php');
    exit;
}

$customer = mysqli_fetch_array($result_check);
$customer_name = $customer['name'];

// Kiểm tra xem khách hàng có đơn hàng chưa xử lý không (status = 2: Chưa duyệt, status = 4: Đang giao)
$sql_check_receipts = "SELECT COUNT(*) as count FROM receipts WHERE customer_id = '$id' AND status IN (2, 4)";
$result_receipts = mysqli_query($connect_database, $sql_check_receipts);
$receipt_count = mysqli_fetch_array($result_receipts)['count'];

if ($receipt_count > 0) {
    $_SESSION['error'] = 'Không thể xóa khách hàng "' . $customer_name . '" vì còn ' . $receipt_count . ' đơn hàng chưa xử lý';
    mysqli_close($connect_database);
    header('location: index.php');
    exit;
}

// Xóa khách hàng
$sql_delete = "DELETE FROM customers WHERE id = '$id'";

if (mysqli_query($connect_database, $sql_delete)) {
    $_SESSION['success'] = 'Xóa khách hàng "' . $customer_name . '" thành công';
    
    // Log activity
    $admin_id = $_SESSION['id'];
    $admin_name = $_SESSION['name'];
    $activity = "xóa";
    $object = "khách hàng";
    $object_name = $customer_name;
    
    mysqli_close($connect_database);
    require '../activity_log/insert_activity.php';
} else {
    $_SESSION['error'] = 'Lỗi khi xóa khách hàng: ' . mysqli_error($connect_database);
    mysqli_close($connect_database);
}

header('location: index.php');
exit;
?>
