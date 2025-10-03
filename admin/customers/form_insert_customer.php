<?php 
require '../check_admin_login.php';
require '../connect_database.php';

$page_title = 'Thêm khách hàng mới - Admin Panel';
$page_heading = 'Thêm khách hàng';
require '../header.php';
?>

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-user-plus"></i>
		</div>
		<div class="page-info">
			<h1>Thêm khách hàng mới</h1>
			<p>Tạo tài khoản khách hàng mới trong hệ thống</p>
		</div>
	</div>
	<a href="index.php" class="btn-add-new" style="background: linear-gradient(135deg, #718096 0%, #4a5568 100%);">
		<i class="fas fa-arrow-left"></i>
		Quay lại
	</a>
</div>

<?php require '../validate.php' ?>

<div class="form-container-modern">
	<form method="POST" action="process_insert_customer.php" class="form-modern">
		<div class="form-grid">
			<div class="form-group">
				<label for="name">
					<i class="fas fa-user"></i>
					Họ và tên <span class="required">*</span>
				</label>
				<input type="text" id="name" name="name" placeholder="Nhập họ và tên khách hàng" required>
			</div>

			<div class="form-group">
				<label for="email">
					<i class="fas fa-envelope"></i>
					Email <span class="required">*</span>
				</label>
				<input type="email" id="email" name="email" placeholder="example@email.com" required>
			</div>

			<div class="form-group">
				<label for="phone">
					<i class="fas fa-phone"></i>
					Số điện thoại <span class="required">*</span>
				</label>
				<input type="tel" id="phone" name="phone" placeholder="0123456789" required>
			</div>

			<div class="form-group">
				<label for="address">
					<i class="fas fa-map-marker-alt"></i>
					Địa chỉ <span class="required">*</span>
				</label>
				<input type="text" id="address" name="address" placeholder="Nhập địa chỉ" required>
			</div>
		</div>

		<div class="info-note">
			<i class="fas fa-info-circle"></i>
			<p>Mật khẩu mặc định sẽ là <strong>123456</strong>. Khách hàng có thể đổi mật khẩu sau khi đăng nhập.</p>
		</div>

		<div class="form-actions">
			<button type="submit" class="btn-submit">
				<i class="fas fa-save"></i>
				Thêm khách hàng
			</button>
			<a href="index.php" class="btn-cancel">
				<i class="fas fa-times"></i>
				Hủy bỏ
			</a>
		</div>
	</form>
</div>

<style>
.form-container-modern {
	background: white;
	padding: 30px;
	border-radius: 16px;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
	margin-top: 20px;
}

.form-modern {
	max-width: 100%;
}

.form-grid {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: 25px;
	margin-bottom: 20px;
}

.info-note {
	background: linear-gradient(135deg, #ebf8ff 0%, #bee3f8 100%);
	border-left: 4px solid #4299e1;
	padding: 15px 20px;
	border-radius: 10px;
	margin-bottom: 30px;
	display: flex;
	align-items: center;
	gap: 12px;
}

.info-note i {
	color: #4299e1;
	font-size: 20px;
}

.info-note p {
	margin: 0;
	color: #2c5282;
	font-size: 14px;
	line-height: 1.6;
}

.info-note strong {
	color: #2b6cb0;
	font-weight: 700;
}

.form-group {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.form-group label {
	color: #2d3748;
	font-weight: 600;
	font-size: 15px;
	display: flex;
	align-items: center;
	gap: 8px;
}

.form-group label i {
	color: #667eea;
	width: 20px;
}

.required {
	color: #e53e3e;
}

.form-group input,
.form-group textarea,
.form-group select {
	padding: 12px 15px;
	border: 2px solid #e2e8f0;
	border-radius: 10px;
	font-size: 15px;
	transition: all 0.3s;
	font-family: inherit;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
	outline: none;
	border-color: #667eea;
	box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-group textarea {
	min-height: 100px;
	resize: vertical;
}

.form-actions {
	display: flex;
	gap: 15px;
	justify-content: center;
	padding-top: 20px;
	border-top: 2px solid #e2e8f0;
}

.btn-submit,
.btn-cancel {
	padding: 14px 32px;
	border-radius: 10px;
	font-size: 16px;
	font-weight: 600;
	cursor: pointer;
	display: flex;
	align-items: center;
	gap: 10px;
	transition: all 0.3s;
	border: none;
	text-decoration: none;
}

.btn-submit {
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	color: white;
	box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
}

.btn-submit:hover {
	transform: translateY(-2px);
	box-shadow: 0 6px 12px rgba(102, 126, 234, 0.4);
}

.btn-cancel {
	background: linear-gradient(135deg, #718096 0%, #4a5568 100%);
	color: white;
	box-shadow: 0 4px 6px rgba(113, 128, 150, 0.3);
}

.btn-cancel:hover {
	transform: translateY(-2px);
	box-shadow: 0 6px 12px rgba(113, 128, 150, 0.4);
}

@media (max-width: 768px) {
	.form-grid {
		grid-template-columns: 1fr;
	}
}
</style>

<?php require '../footer.php'; ?>
