<?php require '../check_admin_login.php' ?>
<?php 

if (empty($_GET['id'])){
	$_SESSION['error'] = 'Chưa nhập id sản phẩm cần xóa';
	header('location:index_products.php');
	exit;
}

$id = $_GET['id'];

require '../connect_database.php';

//kiểm tra id nhập vào có đúng
$sql_command_select = "select * from products where id = '$id' ";
$query_sql_command_select = mysqli_query($connect_database, $sql_command_select);
$array_products = mysqli_fetch_array($query_sql_command_select);
$check = mysqli_num_rows($query_sql_command_select);
if ( $check !== 1 ) {
	$_SESSION['error'] = 'Sai id sản phẩm';
	header('location:index_products.php');
	exit();
}

// Kiểm tra xem sản phẩm có trong đơn hàng chưa xử lý không
// Status 0 = chờ xử lý, 2 = chờ xác nhận, 4 = đang giao hàng
$sql_check_pending_orders = "
	SELECT COUNT(*) as count 
	FROM receipt_detail rd 
	JOIN receipts r ON rd.receipt_id = r.id 
	WHERE rd.product_id = '$id' 
	AND r.status IN (0, 2, 4)
";
$result_check = mysqli_query($connect_database, $sql_check_pending_orders);
$pending_count = mysqli_fetch_array($result_check)['count'];

if ($pending_count > 0) {
	$_SESSION['error'] = 'Không thể xóa sản phẩm này vì còn có ' . $pending_count . ' đơn hàng chưa xử lý đang sử dụng sản phẩm này. Vui lòng hoàn thành tất cả đơn hàng trước khi xóa sản phẩm.';
	mysqli_close($connect_database);
	header('location:index_products.php');
	exit();
}

// Kiểm tra tổng số đơn hàng đã sử dụng sản phẩm này (để thông báo)
$sql_check_total = "SELECT COUNT(*) as count FROM receipt_detail WHERE product_id = '$id'";
$result_total = mysqli_query($connect_database, $sql_check_total);
$total_count = mysqli_fetch_array($result_total)['count'];

if ($total_count > 0) {
	// Có đơn hàng đã hoàn thành/hủy nhưng không có đơn hàng pending
	$_SESSION['info'] = 'Sản phẩm này đã được sử dụng trong ' . $total_count . ' đơn hàng trước đây (đã hoàn thành/hủy). Sản phẩm sẽ được xóa khỏi hệ thống.';
}

// Thay vì xóa thật, chúng ta sẽ đánh dấu sản phẩm là đã xóa (soft delete)
// Đầu tiên kiểm tra xem cột is_deleted có tồn tại không, nếu không thì thêm
$sql_check_column = "SHOW COLUMNS FROM products LIKE 'is_deleted'";
$result_check_column = mysqli_query($connect_database, $sql_check_column);
if (mysqli_num_rows($result_check_column) == 0) {
    // Thêm cột is_deleted nếu chưa có
    $sql_add_column = "ALTER TABLE products ADD COLUMN is_deleted TINYINT(1) DEFAULT 0";
    mysqli_query($connect_database, $sql_add_column);
}

// Đánh dấu sản phẩm là đã xóa thay vì xóa thật
$sql_command_soft_delete = "UPDATE products SET is_deleted = 1 WHERE id = '$id'";
$result = mysqli_query($connect_database, $sql_command_soft_delete);

if (!$result) {
	$error = mysqli_error($connect_database);
	$_SESSION['error'] = 'Lỗi khi xóa sản phẩm: ' . $error;
	mysqli_close($connect_database);
	header('location:index_products.php');
	exit();
}

mysqli_close($connect_database);

//nếu không có trong giỏ hàng thì báo xóa sản phẩm
if ( isset($_GET['type_id']) ) {
	$type_id = $_GET['type_id'];
	$header = "location:../hashtags/products_linked_hashtag.php?id=$type_id";
	header($header);
	exit();
}

//insert vào bảng activity
$admin_id = $_SESSION['id'];
$admin_name = $_SESSION['name'];
$activity = "xóa";
$object = "sản phẩm";
$object_name = $array_products['name'];
require '../activity_log/insert_activity.php';

if (isset($_SESSION['info'])) {
	$_SESSION['success'] = 'Xóa sản phẩm thành công. ' . $_SESSION['info'];
	unset($_SESSION['info']);
} else {
	$_SESSION['success'] = 'Xóa sản phẩm thành công';
}
header('location:index_products.php');

 ?>