<?php 
session_start(); 
if (isset($_SESSION['id'])) {
    header('location:index_user.php');
    exit;  
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Đăng ký</title>
    <link rel="stylesheet" href="css/style2.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Đăng ký tài khoản</h2>
            
            <div class="alert alert-danger" id="div-error" style="display: none">
            </div>

            <form method="post" id="form-sign-up">
                <div class="form-group">
                    <input type="text" name="name" class="form-control" placeholder="Họ và tên" required>
                </div>

                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>

                <div class="form-group">
                    <input type="text" name="phone" class="form-control" placeholder="Số điện thoại" required>
                </div>

                <div class="form-group gender-group">
                    <label>Giới tính:</label>
                    <div class="gender-options">
                        <label class="radio-label">
                            <input type="radio" value="Nam" name="gender" required>
                            <span>Nam</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" value="Nữ" name="gender" required>
                            <span>Nữ</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <input type="date" name="dob" class="form-control" required>
                </div>

                <div class="form-group">
                    <input type="text" name="address" class="form-control" placeholder="Địa chỉ" required>
                </div>

                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
                </div>

                <button type="submit" class="btn-login">Đăng ký</button>

                <div class="form-footer">
                    <span>Đã có tài khoản?</span>
                    <a href="form_sign_in.php" class="register-link">Đăng nhập ngay</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script type="text/javascript">
$(document).ready(function() {
    $.validator.addMethod("nameRegex", function(value, element) {          
        return this.optional(element) || /^[a-zA-ZÀ-ỹ\s]+$/u.test(value);
    }, "Tên chỉ được chứa chữ cái và khoảng trắng");

    $("#form-sign-up").validate({
        rules: {
            "name": {
                required: true,
                minlength: 2,
                maxlength: 50,
                nameRegex: true
            },
            "password": {
                required: true,
                minlength: 8
            },
            "email": {
                required: true,
                email: true
            }
        },
        messages: {
            "name": {
                required: "Vui lòng nhập họ tên",
                minlength: "Tên phải có ít nhất 2 ký tự",
                maxlength: "Tên không được vượt quá 50 ký tự",
                nameRegex: "Tên chỉ được chứa chữ cái và khoảng trắng"
            },
            "password": {
                required: "Bắt buộc nhập password",
                minlength: "Hãy nhập ít nhất 8 ký tự"
            },
            "email": {
                required: "Bắt buộc nhập email",
                email: "Sai dinh dang email"
            }
        },
        submitHandler: function() {
            $.ajax({
                url: 'process_sign_up.php',
                type: 'post',
                dataType: 'html',
                data: $("#form-sign-up").serializeArray(),
            })
            .done(function(response) {
                if (response != '1') {
                    $("#div-error").text(response)
                    $("#div-error").show()
                } else {
                    // Thay đổi đường dẫn chuyển hướng
                    alert("Đăng ký thành công!");
                    window.location.href = 'index_customers.php'; // Chuyển về trang index_customers
                }
            })
        }
    });
})
</script>
</body>
</html>