<?php
session_start();

// Vô hiệu hóa chức năng thêm admin
$_SESSION['error'] = 'Chức năng thêm admin đã bị vô hiệu hóa';
header('Location: index.php');
exit();

require '../check_super_admin_login.php';
require '../connect_database.php';

// Validate input
if (empty($_POST['username'])) {
    $_SESSION['error'] = 'Vui lòng nhập tên đăng nhập';
    exit;
}

$username = trim($_POST['username']);

// Add @admin.com suffix to username
$full_username = $username . '@admin.com';

// Validate username format (only letters, numbers, underscore)
if (!preg_match('/^[a-zA-Z0-9_]{4,}$/', $username)) {
    $_SESSION['error'] = 'Tên đăng nhập chỉ chứa chữ cái, số và dấu gạch dưới. Tối thiểu 4 ký tự';
    header('location: form_insert_admin.php');
    exit;
}

// Set default password
$password = 'qwer1234';

// Check if username already exists (using 'name' column)
$sql_check = "SELECT id FROM admins WHERE name = '$full_username'";
$result_check = mysqli_query($connect_database, $sql_check);

if (mysqli_num_rows($result_check) > 0) {
    $_SESSION['error'] = 'Tên đăng nhập "' . $full_username . '" đã tồn tại. Vui lòng chọn tên khác';
    mysqli_close($connect_database);
    header('location: form_insert_admin.php');
    exit;
}

// Hash password
$password_hashed = md5($password);

// Insert admin (using 'name' column with full username including @admin.com)
$sql_insert = "INSERT INTO admins (name, password) VALUES ('$full_username', '$password_hashed')";

if (mysqli_query($connect_database, $sql_insert)) {
    $_SESSION['success'] = 'Tạo tài khoản admin "' . $full_username . '" thành công';
    
    // Log activity
    $admin_id = $_SESSION['id'];
    $admin_name = $_SESSION['name'];
    $activity = "thêm";
    $object = "admin";
    $object_name = $full_username;
    
    mysqli_close($connect_database);
    require '../activity_log/insert_activity.php';
    
    header('location: index.php');
    exit();
} else {
    $_SESSION['error'] = 'Lỗi: ' . mysqli_error($connect_database);
    mysqli_close($connect_database);
    header('location: form_insert_admin.php');
    exit();
}
?>
