<?php
session_start();
$email = $_POST['email'];
$password = md5($_POST['password']); // Mã hóa MD5

require 'connect_database.php';

$stmt = $connect_database->prepare("SELECT id, name, level FROM admins WHERE email = ? AND password = ? LIMIT 1");
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$res = $stmt->get_result();

if ($res && $res->num_rows === 1) {
    $row = $res->fetch_assoc();
    $_SESSION['id'] = $row['id'];
    $_SESSION['name'] = $row['name'];
    $_SESSION['level'] = $row['level'];
    header('Location: root/index.php');
    exit();
}

header('Location: index.php?error=Sai tài khoản hoặc mật khẩu');
exit();
;
?>
	