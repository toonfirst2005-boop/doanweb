<?php
require '../check_super_admin_login.php';

if (empty($_POST['name']) || empty($_POST['phone']) || empty($_POST['address'])){
	$_SESSION['error'] = 'Chưa nhập đầy đủ thông tin';
	header('location:form_insert_manufacturers.php');
	exit;
}

// Debug: Check if file was uploaded
if (!isset($_FILES['image'])) {
	$_SESSION['error'] = 'Không nhận được file upload. Vui lòng kiểm tra lại form.';
	header('location:form_insert_manufacturers.php');
	exit;
}

// Check upload error
if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
	$errorMessages = [
		UPLOAD_ERR_INI_SIZE => 'File quá lớn (vượt quá upload_max_filesize trong php.ini)',
		UPLOAD_ERR_FORM_SIZE => 'File quá lớn (vượt quá MAX_FILE_SIZE trong form)',
		UPLOAD_ERR_PARTIAL => 'File chỉ được upload một phần',
		UPLOAD_ERR_NO_FILE => 'Không có file nào được upload',
		UPLOAD_ERR_NO_TMP_DIR => 'Thiếu thư mục tạm',
		UPLOAD_ERR_CANT_WRITE => 'Không thể ghi file vào disk',
		UPLOAD_ERR_EXTENSION => 'Upload bị chặn bởi extension'
	];
	
	$errorCode = $_FILES['image']['error'];
	$errorMsg = isset($errorMessages[$errorCode]) ? $errorMessages[$errorCode] : 'Lỗi upload không xác định (code: ' . $errorCode . ')';
	
	$_SESSION['error'] = 'Lỗi upload: ' . $errorMsg;
	header('location:form_insert_manufacturers.php');
	exit;
}

$name = trim($_POST['name']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);

// Handle file upload
$file = $_FILES['image'];
$fileName = $file['name'];
$fileTmpName = $file['tmp_name'];
$fileSize = $file['size'];
$fileError = $file['error'];

// Get file extension
$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

// Allowed extensions
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'tiff', 'tif', 'ico', 'heic', 'heif'];

if (!in_array($fileExt, $allowedExtensions)) {
	$_SESSION['error'] = 'Định dạng file không hợp lệ. Chỉ chấp nhận: JPG, JPEG, PNG, GIF, WEBP, SVG, BMP, TIFF, ICO, HEIC, HEIF';
	header('location:form_insert_manufacturers.php');
	exit;
}

// Check file size (5MB max)
if ($fileSize > 5 * 1024 * 1024) {
	$_SESSION['error'] = 'Kích thước file quá lớn. Tối đa 5MB';
	header('location:form_insert_manufacturers.php');
	exit;
}

// Generate unique filename
$newFileName = 'manufacturer_' . time() . '_' . uniqid() . '.' . $fileExt;
$uploadPath = 'uploads/' . $newFileName;

// Create uploads directory if not exists
if (!file_exists('uploads')) {
	mkdir('uploads', 0777, true);
}

// Move uploaded file
if (!move_uploaded_file($fileTmpName, $uploadPath)) {
	$_SESSION['error'] = 'Lỗi khi upload file';
	header('location:form_insert_manufacturers.php');
	exit;
}

$image = $uploadPath;

require '../connect_database.php';

// Kiểm tra tên nhà sản xuất đã tồn tại chưa
$sql_check = "SELECT id FROM manufacturers WHERE name = '$name'";
$result_check = mysqli_query($connect_database, $sql_check);

if (mysqli_num_rows($result_check) > 0) {
	$_SESSION['error'] = 'Tên nhà sản xuất "' . $name . '" đã tồn tại. Vui lòng chọn tên khác.';
	mysqli_close($connect_database);
	header('location:form_insert_manufacturers.php');
	exit();
}

// Insert nhà sản xuất mới
$sql_command_insert = "INSERT INTO manufacturers (name, phone, address, image)
VALUES ('$name', '$phone', '$address', '$image')";

if (mysqli_query($connect_database, $sql_command_insert)) {
	$_SESSION['success'] = 'Thêm nhà sản xuất thành công';
	
	//insert vào bảng activity
	$admin_id = $_SESSION['id'];
	$admin_name = $_SESSION['name'];
	$activity = "thêm";
	$object = "nhà cung cấp";
	$object_name = $name;
	
	mysqli_close($connect_database);
	require '../activity_log/insert_activity.php';
	
	header('location:index_manufacturers.php');
	exit();
} else {
	$error = mysqli_error($connect_database);
	$_SESSION['error'] = 'Lỗi: ' . $error;
	mysqli_close($connect_database);
	header('location:form_insert_manufacturers.php');	
	exit();
}






 
 ?>