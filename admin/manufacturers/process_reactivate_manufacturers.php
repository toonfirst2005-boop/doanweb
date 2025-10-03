<?php require '../check_super_admin_login.php' ?>
<?php 

if (empty($_GET['id'])){
	$_SESSION['error'] = 'Chưa nhập id nhà sản xuất cần kích hoạt lại';
	header('location:index_manufacturers.php');
	exit;
}

$id = $_GET['id'];

require '../connect_database.php';

// Kiểm tra và thêm cột is_active nếu chưa có
$sql_check_column = "SHOW COLUMNS FROM manufacturers LIKE 'is_active'";
$result_check_column = mysqli_query($connect_database, $sql_check_column);
if (mysqli_num_rows($result_check_column) == 0) {
    // Thêm cột is_active nếu chưa có
    $sql_add_column = "ALTER TABLE manufacturers ADD COLUMN is_active TINYINT(1) DEFAULT 1";
    mysqli_query($connect_database, $sql_add_column);
}

//kiểm tra xem có id cần kích hoạt không
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

// Kiểm tra xem nhà sản xuất có đang ngừng hợp tác không
$is_active = isset($array_manufacturers['is_active']) ? $array_manufacturers['is_active'] : 1;
if ($is_active == 1) {
	$_SESSION['error'] = 'Nhà sản xuất "' . $array_manufacturers['name'] . '" đang trong trạng thái hợp tác!';
	mysqli_close($connect_database);
	header('location:index_manufacturers.php');
	exit();
}

// Kích hoạt lại nhà sản xuất
$sql_command_reactivate = "UPDATE manufacturers SET is_active = 1 WHERE id = '$id'";
$result = mysqli_query($connect_database, $sql_command_reactivate);

if (!$result) {
	$error = mysqli_error($connect_database);
	$_SESSION['error'] = 'Lỗi khi kích hoạt lại nhà sản xuất: ' . $error;
	mysqli_close($connect_database);
	header('location:index_manufacturers.php');
	exit();
}

mysqli_close($connect_database);

//insert vào bảng activity
$admin_id = $_SESSION['id'];
$admin_name = $_SESSION['name'];
$activity = "tiếp tục hợp tác";
$object = "nhà sản xuất";
$object_name = $array_manufacturers['name'];
require '../activity_log/insert_activity.php';

$_SESSION['success'] = '<i class="fas fa-handshake"></i> <strong>Đã tiếp tục hợp tác với nhà sản xuất "' . $array_manufacturers['name'] . '"!</strong><br><br>Trạng thái nhà sản xuất: <span style="color: #38a169;">Đang hợp tác</span>';

header('location:index_manufacturers.php');

?>
