<?php
require '../check_super_admin_login.php';
require '../connect_database.php';

if (empty($_POST['id']) || empty($_POST['name']) || empty($_POST['email']) || 
    empty($_POST['phone']) || empty($_POST['address']) || empty($_POST['gender']) || empty($_POST['dob'])) {
    $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
    header('location: form_edit_customer.php?id=' . $_POST['id']);
    exit;
}

$id = $_POST['id'];
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);
$gender = $_POST['gender'];
$dob = $_POST['dob'];

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Email không hợp lệ';
    header('location: form_edit_customer.php?id=' . $id);
    exit;
}

// Validate phone (10 digits)
if (!preg_match('/^[0-9]{10}$/', $phone)) {
    $_SESSION['error'] = 'Số điện thoại phải có đúng 10 số';
    header('location: form_edit_customer.php?id=' . $id);
    exit;
}

// Kiểm tra email đã tồn tại chưa (trừ email của chính khách hàng này)
$sql_check_email = "SELECT id FROM customers WHERE email = '$email' AND id != '$id'";
$result_check = mysqli_query($connect_database, $sql_check_email);

if (mysqli_num_rows($result_check) > 0) {
    $_SESSION['error'] = 'Email đã được sử dụng bởi khách hàng khác';
    mysqli_close($connect_database);
    header('location: form_edit_customer.php?id=' . $id);
    exit;
}

// Cập nhật thông tin khách hàng
$sql_update = "UPDATE customers SET 
    name = '$name',
    email = '$email',
    phone = '$phone',
    address = '$address',
    gender = '$gender',
    dob = '$dob'
    WHERE id = '$id'";

if (mysqli_query($connect_database, $sql_update)) {
    $_SESSION['success'] = 'Cập nhật thông tin khách hàng thành công';
    
    // Log activity
    $admin_id = $_SESSION['id'];
    $admin_name = $_SESSION['name'];
    $activity = "cập nhật";
    $object = "khách hàng";
    $object_name = $name;
    
    mysqli_close($connect_database);
    require '../activity_log/insert_activity.php';
    
    header('location: detail_customer.php?id=' . $id);
    exit();
} else {
    $_SESSION['error'] = 'Lỗi: ' . mysqli_error($connect_database);
    mysqli_close($connect_database);
    header('location: form_edit_customer.php?id=' . $id);
    exit();
}
?>
