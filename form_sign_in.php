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
<html>
<head>
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="css/style2.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Đăng nhập</h2>
            
            <?php 
            if (isset($_SESSION['error'])) {
                echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
                unset($_SESSION['error']);
            }
            ?>

            <form method="POST" action="process_sign_in.php">
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
                </div>
                
                <div class="form-check">
                    <input type="checkbox" name="login_renember" id="remember">
                    <label for="remember">Ghi nhớ đăng nhập</label>
                </div>
                
                <button type="submit" class="btn-login">Đăng nhập</button>
                
                <div class="form-footer">
                    <a href="form_forgot_password.php" class="forgot-link">Quên mật khẩu?</a>
                    <span class="divider">•</span>
                    <a href="form_sign_up.php" class="register-link">Đăng ký tài khoản mới</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>