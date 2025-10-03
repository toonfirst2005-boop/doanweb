<?php 
require '../check_super_admin_login.php';
require '../connect_database.php';

if (empty($_GET['id'])){
	$_SESSION['error'] = 'Chưa nhập id nhà sản xuất cần sửa';
	header('location:index_manufacturers.php');
	exit;
}

$id = $_GET['id'];

$sql_command_select = "select * from manufacturers where id = '$id'";
$query_sql_command_select = mysqli_query($connect_database, $sql_command_select);
$array_manufacturers = mysqli_fetch_array($query_sql_command_select);

//validate nếu nhập id sai
$count_rows = mysqli_num_rows($query_sql_command_select);
if ($count_rows !== 1) {
	$_SESSION['error'] = 'Chưa nhập id nhà sản xuất cần sửa';
	mysqli_close($connect_database);
	header('location:index_manufacturers.php');
	exit;
}

mysqli_close($connect_database);

// Page configuration
$page_title = 'Sửa Nhà sản xuất - Admin Panel';
$page_heading = 'Sửa Nhà sản xuất';
require '../header.php';
?>

<link rel="stylesheet" href="../style_modern_form.css">

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-edit"></i>
		</div>
		<div class="page-info">
			<h1>Chỉnh sửa nhà sản xuất</h1>
			<p>Cập nhật thông tin nhà sản xuất</p>
		</div>
	</div>
	<a href="index_manufacturers.php" class="btn-add-new" style="background: linear-gradient(135deg, #718096 0%, #4a5568 100%);">
		<i class="fas fa-arrow-left"></i>
		Quay lại
	</a>
</div>

<?php require '../validate.php' ?>

<div class="form-container">
	<form action="process_update_manufacturers.php" method="post" id="form-update-manufactures">
		<input type="hidden" name="id" value="<?php echo $array_manufacturers['id'] ?>">
		
		<div class="form-grid">
			<div class="form-group">
				<label class="form-label">
					<i class="fas fa-building"></i>
					Tên nhà sản xuất <span class="required">*</span>
				</label>
				<input type="text" name="name" id="name-input" class="form-input" value="<?php echo $array_manufacturers['name'] ?>" placeholder="Nhập tên nhà sản xuất">
				<p class="form-hint" id="name-hint">Có thể bao gồm chữ, số, dấu cách và dấu</p>
			</div>

			<div class="form-group">
				<label class="form-label">
					<i class="fas fa-phone"></i>
					Số điện thoại <span class="required">*</span>
				</label>
				<input type="text" name="phone" id="phone-input" class="form-input" value="<?php echo $array_manufacturers['phone'] ?>" placeholder="Nhập số điện thoại">
				<p class="form-hint">Nhập đúng 10 số</p>
			</div>

			<div class="form-group full-width">
				<label class="form-label">
					<i class="fas fa-map-marker-alt"></i>
					Địa chỉ <span class="required">*</span>
				</label>
				<textarea name="address" class="form-textarea" placeholder="Nhập địa chỉ"><?php echo $array_manufacturers['address'] ?></textarea>
			</div>

			<div class="form-group full-width">
				<label class="form-label">
					<i class="fas fa-link"></i>
					Hình ảnh (URL) <span class="required">*</span>
				</label>
				<input type="text" name="image" class="form-input" value="<?php echo $array_manufacturers['image'] ?>" placeholder="Nhập URL hình ảnh">
				<p class="form-hint">Nhập đường dẫn URL đến hình ảnh logo nhà sản xuất</p>
			</div>
		</div>

		<div class="form-actions">
			<button type="submit" class="btn-submit">
				<i class="fas fa-save"></i>
				Cập nhật nhà sản xuất
			</button>
			<a href="index_manufacturers.php" class="btn-cancel">
				<i class="fas fa-times"></i>
				Hủy
			</a>
		</div>
	</form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script>
// Disable jQuery validation to use custom validation
$(document).ready(function() {
	// Remove jQuery validation if it exists
	if ($.validator && $('#form-update-manufactures').data('validator')) {
		$('#form-update-manufactures').data('validator', null);
	}
});
</script>
<script>
// Name validation - allow letters, numbers, spaces, and Vietnamese characters
const nameInput = document.getElementById('name-input');
const nameHint = document.getElementById('name-hint');

if (nameInput) {
	nameInput.addEventListener('input', function(e) {
		let value = e.target.value;
		
		// Allow letters (including Vietnamese), numbers, spaces, and common punctuation
		// Remove only special characters that are not allowed
		value = value.replace(/[^a-zA-ZÀ-ỹ0-9\s\-\.\,\(\)\/\&]/g, '');
		
		e.target.value = value;
		
		// Show feedback
		if (value.length > 0) {
			if (value.length < 2) {
				nameInput.style.borderColor = '#f56565';
				nameHint.style.color = '#f56565';
				nameHint.textContent = 'Tên nhà sản xuất phải có ít nhất 2 ký tự';
			} else {
				nameInput.style.borderColor = '#48bb78';
				nameHint.style.color = '#48bb78';
				nameHint.textContent = '✓ Tên hợp lệ';
			}
		} else {
			nameInput.style.borderColor = '#e2e8f0';
			nameHint.style.color = '#718096';
			nameHint.textContent = 'Có thể bao gồm chữ, số, dấu cách và dấu';
		}
	});
}

// Phone number validation - only allow digits, no length limit while typing
const phoneInput = document.getElementById('phone-input');

if (phoneInput) {
	const phoneHint = phoneInput.nextElementSibling;

	phoneInput.addEventListener('input', function(e) {
		// Remove all non-numeric characters but allow any length
		let value = e.target.value.replace(/\D/g, '');
		e.target.value = value;
		
		// Show real-time feedback
		if (phoneHint) {
			if (value.length > 0 && value.length !== 10) {
				phoneInput.style.borderColor = '#f56565';
				phoneHint.style.color = '#f56565';
				phoneHint.textContent = 'Số điện thoại phải có đúng 10 số (hiện tại: ' + value.length + ' số)';
			} else if (value.length === 10) {
				phoneInput.style.borderColor = '#48bb78';
				phoneHint.style.color = '#48bb78';
				phoneHint.textContent = '✓ Số điện thoại hợp lệ';
			} else {
				phoneInput.style.borderColor = '#e2e8f0';
				phoneHint.style.color = '#718096';
				phoneHint.textContent = 'Nhập đúng 10 số';
			}
		}
	});

	// Validate on form submit - must be exactly 10 digits
	const form = document.getElementById('form-update-manufactures');
	if (form) {
		form.addEventListener('submit', function(e) {
			let hasError = false;
			let errorMessage = '';
			
			// Validate name
			const name = nameInput ? nameInput.value.trim() : '';
			if (name.length < 2) {
				hasError = true;
				errorMessage = 'Tên nhà sản xuất phải có ít nhất 2 ký tự.';
				if (nameInput) {
					nameInput.style.borderColor = '#f56565';
					nameInput.focus();
				}
			}
			
			// Validate phone
			const phone = phoneInput.value;
			if (!hasError && phone.length !== 10) {
				hasError = true;
				errorMessage = 'Số điện thoại phải có đúng 10 số. Bạn đã nhập ' + phone.length + ' số.';
				phoneInput.style.borderColor = '#f56565';
				if (!name || name.length >= 2) {
					phoneInput.focus();
				}
			}
			
			if (hasError) {
				e.preventDefault();
				e.stopPropagation();
				
				// Show notice box
				const existingNotice = document.querySelector('.validation-notice');
				if (existingNotice) existingNotice.remove();
				
				const notice = document.createElement('div');
				notice.className = 'notice-box notice-error validation-notice';
				notice.innerHTML = '<i class="fas fa-exclamation-circle"></i><div class="notice-box-content"><div class="notice-box-title">Lỗi!</div><div class="notice-box-message">' + errorMessage + '</div></div>';
				
				const formContainer = document.querySelector('.form-container');
				if (formContainer) {
					formContainer.insertBefore(notice, form);
				}
				
				// Auto remove after 5 seconds
				setTimeout(function() {
					if (notice.parentNode) {
						notice.remove();
					}
				}, 5000);
				
				return false;
			}
			
			// If all valid, allow form to submit
			return true;
		});
	}
}
</script>

<?php require '../footer.php'; ?>