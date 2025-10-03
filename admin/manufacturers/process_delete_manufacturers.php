<?php require '../check_super_admin_login.php' ?>
<?php 


if (empty($_GET['id'])){
	$_SESSION['error'] = 'Chưa nhập id nhà sản xuất cần xóa';
	header('location:index_manufacturers.php');
	exit;
}

$id = $_GET['id'];

require '../connect_database.php';

//kiểm tra xem có id cần xóa không
$sql_command_select = "select * from manufacturers where id = '$id'";
$query_sql_command_select = mysqli_query($connect_database, $sql_command_select);
$array_manufacturers = mysqli_fetch_array($query_sql_command_select);
$count_rows = mysqli_num_rows($query_sql_command_select);
if ($count_rows !== 1){
	$_SESSION['error'] = 'Sai id nhà sản xuất';
	mysqli_close($connect_database);
	header('location:index_manufacturers.php');
	exit();
}

// Kiểm tra tổng số sản phẩm (bao gồm cả đã xóa) - do foreign key constraint
$sql_check_total_products = "SELECT COUNT(*) as count FROM products WHERE manufacturer_id = '$id'";
$result_total = mysqli_query($connect_database, $sql_check_total_products);
$total_product_count = mysqli_fetch_array($result_total)['count'];

// Kiểm tra số sản phẩm đang hoạt động
$sql_check_active_products = "SELECT COUNT(*) as count FROM products WHERE manufacturer_id = '$id' AND (is_deleted IS NULL OR is_deleted = 0)";
$result_check = mysqli_query($connect_database, $sql_check_active_products);
$active_product_count = mysqli_fetch_array($result_check)['count'];

// Chỉ cấm xóa khi có sản phẩm đang bày bán
if ($active_product_count > 0) {
	$deleted_count = $total_product_count - $active_product_count;
	$_SESSION['error'] = 'Không thể đánh dấu ngừng hợp tác với nhà sản xuất "' . $array_manufacturers['name'] . '" vì:<br><br>' .
		'<strong>• ' . $active_product_count . ' sản phẩm đang được bày bán</strong><br>' .
		($deleted_count > 0 ? '• ' . $deleted_count . ' sản phẩm đã ngừng bán<br>' : '') .
		'<br>Vui lòng ngừng bán hoặc chuyển tất cả sản phẩm đang hoạt động sang nhà sản xuất khác trước.<br><br>' .
		'<i class="fas fa-info-circle"></i> <strong>Lưu ý:</strong> Sau khi ngừng bán tất cả sản phẩm, bạn có thể đánh dấu nhà sản xuất ngừng hợp tác để bảo tồn dữ liệu lịch sử.<br><br>' .
		'<a href="view_products_in_manufacturer.php?id=' . $id . '" class="btn btn-primary" style="display: inline-block; padding: 8px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin-top: 10px;">' .
		'<i class="fas fa-eye"></i> Xem sản phẩm của nhà sản xuất này</a>';
	
	mysqli_close($connect_database);
	header('location:index_manufacturers.php');
	exit();
}

// Kiểm tra xem cột is_active có tồn tại không, nếu không thì thêm
$sql_check_column = "SHOW COLUMNS FROM manufacturers LIKE 'is_active'";
$result_check_column = mysqli_query($connect_database, $sql_check_column);
if (mysqli_num_rows($result_check_column) == 0) {
    // Thêm cột is_active nếu chưa có
    $sql_add_column = "ALTER TABLE manufacturers ADD COLUMN is_active TINYINT(1) DEFAULT 1";
    mysqli_query($connect_database, $sql_add_column);
}

// Đánh dấu nhà sản xuất ngừng hợp tác thay vì xóa
$sql_command_deactivate = "UPDATE manufacturers SET is_active = 0 WHERE id = '$id'";
$result = mysqli_query($connect_database, $sql_command_deactivate);

$deleted_count = $total_product_count - $active_product_count;
if ($deleted_count > 0) {
	$_SESSION['info'] = 'Nhà sản xuất này có <strong>' . $total_product_count . ' sản phẩm trong hệ thống</strong> (' . $deleted_count . ' đã ngừng bán, ' . $active_product_count . ' đang bán).<br><br>' .
		'<i class="fas fa-info-circle"></i> <strong>Dữ liệu được bảo tồn:</strong> Tất cả sản phẩm và lịch sử đơn hàng vẫn được giữ nguyên để đảm bảo tính toàn vẹn dữ liệu.';
} else {
	$_SESSION['info'] = 'Nhà sản xuất này không có sản phẩm nào trong hệ thống.';
}

if (!$result) {
	$error = mysqli_error($connect_database);
	$_SESSION['error'] = 'Lỗi khi đánh dấu ngừng hợp tác: ' . $error;
	mysqli_close($connect_database);
	header('location:index_manufacturers.php');
	exit();
}

mysqli_close($connect_database);

//insert vào bảng activity
$admin_id = $_SESSION['id'];
$admin_name = $_SESSION['name'];
$activity = "đánh dấu ngừng hợp tác";
$object = "nhà sản xuất";
$object_name = $array_manufacturers['name'];
require '../activity_log/insert_activity.php';

if (isset($_SESSION['info'])) {
	$_SESSION['success'] = '<i class="fas fa-handshake-slash"></i> <strong>Đã đánh dấu ngừng hợp tác với nhà sản xuất "' . $array_manufacturers['name'] . '"!</strong><br><br>' . $_SESSION['info'];
	unset($_SESSION['info']);
} else {
	$_SESSION['success'] = '<i class="fas fa-handshake-slash"></i> <strong>Đã đánh dấu ngừng hợp tác với nhà sản xuất "' . $array_manufacturers['name'] . '"!</strong><br><br>Trạng thái nhà sản xuất: <span style="color: #dc3545;">Ngừng hợp tác</span>';
}
header('location:index_manufacturers.php');





 ?>