<?php
require '../check_admin_login.php';
require '../connect_database.php';

// Validate input
if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phone']) || empty($_POST['address'])) {
    $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
    header('location: form_insert_customer.php');
    exit;
}

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);

// Mật khẩu mặc định
$default_password = '123456';

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Email không hợp lệ';
    header('location: form_insert_customer.php');
    exit;
}

// Check if email already exists
$sql_check_email = "SELECT id FROM customers WHERE email = '$email'";
$query_check_email = mysqli_query($connect_database, $sql_check_email);
if (mysqli_num_rows($query_check_email) > 0) {
    $_SESSION['error'] = 'Email đã tồn tại trong hệ thống';
    header('location: form_insert_customer.php');
    exit;
}

// Hash password mặc định
$password_hashed = md5($default_password);

// Insert customer
$sql_insert = "INSERT INTO customers (name, email, phone, address, password) 
               VALUES ('$name', '$email', '$phone', '$address', '$password_hashed')";

if (mysqli_query($connect_database, $sql_insert)) {
    $_SESSION['success'] = 'Thêm khách hàng thành công';
    header('location: index.php');
} else {
    $_SESSION['error'] = 'Lỗi: ' . mysqli_error($connect_database);
    header('location: form_insert_customer.php');
}

mysqli_close($connect_database);
exit;
?>
