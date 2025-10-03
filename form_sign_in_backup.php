<?php session_start(); 


if (isset($_COOKIE['login_renember'])) {
	require 'admin/connect_database.php';
	$token = $_COOKIE['login_renember'];
	$sql_command_select = "select * from customers where token = '$token' limit 1 ";
	$query_sql_command_select = mysqli_query($connect_database, $sql_command_select);
	$array_customer = mysqli_fetch_array($query_sql_command_select);
	$count_account = mysqli_num_rows($query_sql_command_select);
	if ($count_account == 1) {
		$_SESSION['id'] = $array_customer['id'];
		$_SESSION['name'] = $array_customer['name'];		
	}

}


if (isset($_SESSION['id'])) {
	header('location:index_user.php');
	exit;	
}


?>

<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Đăng nhập - ShopModern</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
	<div class="login-container">
		<div class="login-box">
			<div class="login-header">
				<h2>Đăng Nhập</h2>
				<p>Chào mừng bạn trở lại ShopModern</p>
			</div>

			<?php 
			echo '<div class="error-message">' . $_SESSION['error'] . '</div>';
			unset($_SESSION['error']);
		}
		
		if (isset($_GET['registered']) && $_GET['registered'] == '1') {
			echo '<div class="success-message">Đăng ký thành công! Vui lòng đăng nhập.</div>';
		}
		?>

		<form method="POST" action="process_sign_in.php" class="login-form">
			<div class="input-group">
				<input type="email" name="email" placeholder="Email" required>
			</div>

			<div class="input-group">
				<input type="password" name="password" placeholder="Mật khẩu" required>
			</div>

			<div class="options">
				<label class="remember-me">
					<input type="checkbox" name="login_renember">
					<span>Ghi nhớ đăng nhập</span>
				</label>
				<a href="form_forgot_password.php" class="forgot-password">Quên mật khẩu?</a>
			</div>

			<button type="submit" class="login-btn">Đăng Nhập</button>

			<div class="register-link">
				<p>Chưa có tài khoản? <a href="form_sign_up_new.php">Đăng ký ngay</a></p>
			</div>
		</form>
		</div>
	</div>
</body>
</html>