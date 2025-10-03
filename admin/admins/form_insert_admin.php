<?php 
require '../check_super_admin_login.php';

// Vô hiệu hóa chức năng thêm admin
$_SESSION['error'] = 'Chức năng thêm admin đã bị vô hiệu hóa';
header('Location: index.php');
exit();

$page_title = 'Thêm Admin - Admin Panel';
$page_heading = 'Thêm Admin';
require '../header.php';
?>

<link rel="stylesheet" href="../style_modern_form.css">

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-user-shield"></i>
		</div>
		<div class="page-info">
			<h1>Thêm tài khoản Admin</h1>
			<p>Tạo tài khoản quản trị viên mới cho hệ thống</p>
		</div>
	</div>
	<a href="index_admins.php" class="btn-add-new" style="background: linear-gradient(135deg, #718096 0%, #4a5568 100%);">
		<i class="fas fa-arrow-left"></i>
		Quay lại
	</a>

<div class="form-container">
	<form action="process_insert_admin.php" method="post" id="form-insert-admin">
		<div class="form-grid">
			<div class="form-group full-width">
				<label class="form-label">
					<i class="fas fa-user"></i>
					Tên đăng nhập (Username) <span class="required">*</span>
				</label>
				<div class="username-wrapper">
					<input type="text" name="username" id="username-input" class="form-input username-input" placeholder="Nhập tên đăng nhập" required>
					<span class="username-suffix">@admin.com</span>
				</div>
				<p class="form-hint" id="username-hint">Chỉ chữ cái, số và dấu gạch dưới. Tối thiểu 4 ký tự. Tài khoản sẽ là: <strong id="full-username">username@admin.com</strong></p>
			</div>

			<div class="form-group full-width">
				<label class="form-label">
					<i class="fas fa-lock"></i>
					Mật khẩu mặc định
				</label>
				<input type="text" class="form-input" value="qwer1234" readonly style="background: #f7fafc; color: #667eea; font-weight: 600; font-size: 16px;">
				<p class="form-hint">Mật khẩu mặc định cho tất cả tài khoản admin mới. Admin có thể đổi mật khẩu sau khi đăng nhập.</p>
			</div>
		</div>

		<div class="info-note" style="margin-top: 20px;">
			<i class="fas fa-info-circle"></i>
			<div>
				<p><strong>Lưu ý:</strong></p>
				<ul style="margin: 10px 0 0 20px; line-height: 1.8;">
					<li>Tài khoản admin có toàn quyền truy cập hệ thống</li>
					<li>Tên đăng nhập không thể thay đổi sau khi tạo</li>
					<li>Mật khẩu mặc định: <strong>qwer1234</strong></li>
				</ul>
			</div>
		</div>

		<div class="form-actions">
			<button type="submit" class="btn-submit">
				<i class="fas fa-save"></i>
				Tạo tài khoản Admin
			</button>
			<a href="index_admins.php" class="btn-cancel">
				<i class="fas fa-times"></i>
				Hủy
			</a>
		</div>
	</form>
</div>

<style>
.username-wrapper {
	position: relative;
	display: flex;
	align-items: center;
}

.username-input {
	padding-right: 120px !important;
}

.username-suffix {
	position: absolute;
	right: 15px;
	color: #667eea;
	font-weight: 600;
	font-size: 15px;
	pointer-events: none;
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	-webkit-background-clip: text;
	-webkit-text-fill-color: transparent;
	background-clip: text;
}

#full-username {
	color: #667eea;
	font-weight: 700;
}

.password-wrapper {
	position: relative;
	display: flex;
	align-items: center;
}

.password-wrapper input {
	padding-right: 45px;
}

.toggle-password {
	position: absolute;
	right: 12px;
	background: none;
	border: none;
	color: #718096;
	cursor: pointer;
	padding: 8px;
	transition: all 0.3s;
}

.toggle-password:hover {
	color: #667eea;
}

.toggle-password i {
	font-size: 16px;
}

.info-note {
	background: linear-gradient(135deg, #e6f7ff 0%, #f0f9ff 100%);
	border-left: 4px solid #4299e1;
	padding: 20px;
	border-radius: 12px;
	display: flex;
	gap: 15px;
}

.info-note i {
	color: #4299e1;
	font-size: 24px;
	flex-shrink: 0;
}

.info-note p {
	margin: 0;
	color: #2d3748;
	font-weight: 600;
}

.info-note ul {
	color: #4a5568;
}

.info-note ul li {
	margin-bottom: 5px;
}
</style>

<script>
// Toggle password visibility
function togglePassword(inputId, button) {
	const input = document.getElementById(inputId);
	const icon = button.querySelector('i');
	
	if (input.type === 'password') {
		input.type = 'text';
		icon.classList.remove('fa-eye');
		icon.classList.add('fa-eye-slash');
	} else {
		input.type = 'password';
		icon.classList.remove('fa-eye-slash');
		icon.classList.add('fa-eye');
	}
}

// Username validation
const usernameInput = document.getElementById('username-input');
const usernameHint = document.getElementById('username-hint');
const fullUsername = document.getElementById('full-username');

if (usernameInput) {
	usernameInput.addEventListener('input', function(e) {
		let value = e.target.value;
		
		// Only allow letters, numbers, and underscores
		value = value.replace(/[^a-zA-Z0-9_]/g, '');
		e.target.value = value;
		
		// Update full username display
		if (fullUsername) {
			fullUsername.textContent = (value || 'username') + '@admin.com';
		}
		
		// Show feedback
		if (value.length > 0) {
			if (value.length < 4) {
				usernameInput.style.borderColor = '#f56565';
				usernameHint.innerHTML = 'Tên đăng nhập phải có ít nhất 4 ký tự. Tài khoản sẽ là: <strong id="full-username" style="color: #f56565;">' + value + '@admin.com</strong>';
			} else {
				usernameInput.style.borderColor = '#48bb78';
				usernameHint.innerHTML = '✓ Tên đăng nhập hợp lệ. Tài khoản sẽ là: <strong id="full-username" style="color: #48bb78;">' + value + '@admin.com</strong>';
			}
		} else {
			usernameInput.style.borderColor = '#e2e8f0';
			usernameHint.innerHTML = 'Chỉ chữ cái, số và dấu gạch dưới. Tối thiểu 4 ký tự. Tài khoản sẽ là: <strong id="full-username" style="color: #667eea;">username@admin.com</strong>';
		}
	});
}

// Password validation
const passwordInput = document.getElementById('password-input');
const passwordHint = document.getElementById('password-hint');
const passwordConfirmInput = document.getElementById('password-confirm-input');
const passwordConfirmHint = document.getElementById('password-confirm-hint');

if (passwordInput) {
	passwordInput.addEventListener('input', function(e) {
		const value = e.target.value;
		
		if (value.length > 0) {
			if (value.length < 6) {
				passwordInput.style.borderColor = '#f56565';
				passwordHint.style.color = '#f56565';
				passwordHint.textContent = 'Mật khẩu phải có ít nhất 6 ký tự';
			} else {
				passwordInput.style.borderColor = '#48bb78';
				passwordHint.style.color = '#48bb78';
				passwordHint.textContent = '✓ Mật khẩu đủ mạnh';
			}
		} else {
			passwordInput.style.borderColor = '#e2e8f0';
			passwordHint.style.color = '#718096';
			passwordHint.textContent = 'Tối thiểu 6 ký tự';
		}
		
		// Check password match
		checkPasswordMatch();
	});
}

if (passwordConfirmInput) {
	passwordConfirmInput.addEventListener('input', checkPasswordMatch);
}

function checkPasswordMatch() {
	const password = passwordInput.value;
	const passwordConfirm = passwordConfirmInput.value;
	
	if (passwordConfirm.length > 0) {
		if (password !== passwordConfirm) {
			passwordConfirmInput.style.borderColor = '#f56565';
			passwordConfirmHint.style.color = '#f56565';
			passwordConfirmHint.textContent = '✗ Mật khẩu không khớp';
		} else {
			passwordConfirmInput.style.borderColor = '#48bb78';
			passwordConfirmHint.style.color = '#48bb78';
			passwordConfirmHint.textContent = '✓ Mật khẩu khớp';
		}
	} else {
		passwordConfirmInput.style.borderColor = '#e2e8f0';
		passwordConfirmHint.style.color = '#718096';
		passwordConfirmHint.textContent = 'Nhập lại mật khẩu để xác nhận';
	}
}

// Form validation on submit
const form = document.getElementById('form-insert-admin');
if (form) {
	form.addEventListener('submit', function(e) {
		const username = usernameInput.value;
		const password = passwordInput.value;
		const passwordConfirm = passwordConfirmInput.value;
		
		// Validate username
		if (username.length < 4) {
			e.preventDefault();
			alert('Tên đăng nhập phải có ít nhất 4 ký tự!');
			usernameInput.focus();
			return false;
		}
		
		// Validate password
		if (password.length < 6) {
			e.preventDefault();
			alert('Mật khẩu phải có ít nhất 6 ký tự!');
			passwordInput.focus();
			return false;
		}
		
		// Check password match
		if (password !== passwordConfirm) {
			e.preventDefault();
			alert('Mật khẩu xác nhận không khớp!');
			passwordConfirmInput.focus();
			return false;
		}
		
		return true;
	});
}
</script>

<?php require '../footer.php'; ?>
