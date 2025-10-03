<?php 
require '../check_super_admin_login.php';

// Page configuration
$page_title = 'Thêm Nhà sản xuất - Admin Panel';
$page_heading = 'Thêm Nhà sản xuất';
require '../header.php';
?>

<link rel="stylesheet" href="../style_modern_form.css">

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-industry"></i>
		</div>
		<div class="page-info">
			<h1>Thêm nhà sản xuất mới</h1>
			<p>Thêm nhà sản xuất mới vào hệ thống</p>
		</div>
	</div>
	<a href="index_manufacturers.php" class="btn-add-new" style="background: linear-gradient(135deg, #718096 0%, #4a5568 100%);">
		<i class="fas fa-arrow-left"></i>
		Quay lại
	</a>
</div>

<?php require '../validate.php' ?>

<div class="form-container">
	<form action="process_insert_manufacturers.php" method="post" id="form-insert-manufactures" enctype="multipart/form-data">
		<div class="form-grid">
			<div class="form-group">
				<label class="form-label">
					<i class="fas fa-building"></i>
					Tên nhà sản xuất <span class="required">*</span>
				</label>
				<input type="text" name="name" id="name-input" class="form-input" placeholder="Nhập tên nhà sản xuất" required>
				<p class="form-hint" id="name-hint">Có thể bao gồm chữ, số, dấu cách và dấu</p>
			</div>

			<div class="form-group">
				<label class="form-label">
					<i class="fas fa-phone"></i>
					Số điện thoại <span class="required">*</span>
				</label>
				<input type="text" name="phone" id="phone-input" class="form-input" placeholder="Nhập số điện thoại" required>
				<p class="form-hint">Nhập đúng 10 số</p>
			</div>

			<div class="form-group full-width">
				<label class="form-label">
					<i class="fas fa-map-marker-alt"></i>
					Địa chỉ <span class="required">*</span>
				</label>
				<textarea name="address" class="form-textarea" placeholder="Nhập địa chỉ" required></textarea>
			</div>

			<div class="form-group">
				<label class="form-label">
					<i class="fas fa-image"></i>
					Hình ảnh <span class="required">*</span>
				</label>
				<input type="file" name="image" id="image-input" class="form-file-input" accept=".jpg,.jpeg,.png,.gif,.bmp,.webp,.svg,.tiff,.tif,.ico,.heic,.heif">
				<label for="image-input" class="form-file-label">
					<i class="fas fa-cloud-upload-alt"></i>
					<span id="file-name">Chọn hình ảnh</span>
				</label>
				<p class="form-hint">Hỗ trợ: JPG, JPEG, PNG, GIF, BMP, WEBP, SVG, TIFF, ICO, HEIC, HEIF (Tối đa 5MB)</p>
			</div>
		</div>

		<div class="form-actions">
			<button type="submit" class="btn-submit">
				<i class="fas fa-save"></i>
				Lưu nhà sản xuất
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
		$('#form-insert-manufactures').data('validator', null);
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
	const form = document.getElementById('form-insert-manufactures');
	if (form) {
		form.addEventListener('submit', function(e) {
			const phone = phoneInput.value;
			
			if (phone.length !== 10) {
				e.preventDefault();
				e.stopPropagation();
				
				phoneInput.style.borderColor = '#f56565';
				phoneInput.focus();
				
				// Show notice box instead of alert
				const existingNotice = document.querySelector('.validation-notice');
				if (existingNotice) existingNotice.remove();
				
				const notice = document.createElement('div');
				notice.className = 'notice-box notice-error validation-notice';
				notice.innerHTML = '<i class="fas fa-exclamation-circle"></i><div class="notice-box-content"><div class="notice-box-title">Lỗi!</div><div class="notice-box-message">Số điện thoại phải có đúng 10 số. Bạn đã nhập ' + phone.length + ' số.</div></div>';
				
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
			// If valid, allow form to submit
			return true;
		});
	}
}

// File upload handling
const fileInput = document.getElementById('image-input');
const fileName = document.getElementById('file-name');

if (fileInput && fileName) {
	fileInput.addEventListener('change', function(e) {
		const file = e.target.files[0];
		if (file) {
			// Update label with file name
			fileName.textContent = file.name;
			
			// Validate file size (5MB)
			if (file.size > 5 * 1024 * 1024) {
				alert('File quá lớn! Vui lòng chọn file nhỏ hơn 5MB.');
				fileInput.value = '';
				fileName.textContent = 'Chọn hình ảnh';
				return;
			}
			
			// Validate file type
			const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/bmp', 'image/webp', 'image/svg+xml', 'image/tiff', 'image/x-icon', 'image/heic', 'image/heif'];
			if (!validTypes.includes(file.type)) {
				alert('Định dạng file không hợp lệ! Vui lòng chọn file ảnh.');
				fileInput.value = '';
				fileName.textContent = 'Chọn hình ảnh';
				return;
			}
			
			console.log('File selected:', file.name, 'Size:', file.size, 'Type:', file.type);
		} else {
			fileName.textContent = 'Chọn hình ảnh';
		}
	});
}

// Form submit validation
const form = document.getElementById('form-insert-manufactures');
if (form) {
	form.addEventListener('submit', function(e) {
		// Check if file is selected
		if (!fileInput.files || fileInput.files.length === 0) {
			e.preventDefault();
			alert('Vui lòng chọn hình ảnh!');
			return false;
		}
		
		console.log('Form submitting with file:', fileInput.files[0].name);
		return true;
	});
}
</script>

<?php require '../footer.php'; ?>