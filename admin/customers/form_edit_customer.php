<?php 
require '../check_super_admin_login.php';
require '../connect_database.php';

if (empty($_GET['id'])) {
    $_SESSION['error'] = 'Chưa nhập id khách hàng';
    header('location: index.php');
    exit;
}

$id = $_GET['id'];

// Lấy thông tin khách hàng
$sql_select = "SELECT * FROM customers WHERE id = '$id'";
$result = mysqli_query($connect_database, $sql_select);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = 'Không tìm thấy khách hàng';
    mysqli_close($connect_database);
    header('location: index.php');
    exit;
}

$customer = mysqli_fetch_array($result);
mysqli_close($connect_database);

$page_title = 'Chỉnh sửa khách hàng - ' . $customer['name'];
$page_heading = 'Chỉnh sửa khách hàng';
require '../header.php';
?>

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-user-edit"></i>
		</div>
		<div class="page-info">
			<h1>Chỉnh sửa khách hàng</h1>
			<p>Cập nhật thông tin khách hàng: <?php echo $customer['name'] ?></p>
		</div>
	</div>
	<a href="index.php" class="btn-add-new" style="background: linear-gradient(135deg, #718096 0%, #4a5568 100%);">
		<i class="fas fa-arrow-left"></i>
		Quay lại
	</a>
</div>

<?php require '../validate.php' ?>

<div class="form-container-modern">
	<form method="POST" action="process_edit_customer.php" class="form-modern">
		<input type="hidden" name="id" value="<?php echo $customer['id'] ?>">
		
		<div class="form-grid">
			<div class="form-group">
				<label for="name">
					<i class="fas fa-user"></i>
					Họ và tên <span class="required">*</span>
				</label>
				<input type="text" id="name" name="name" value="<?php echo htmlspecialchars($customer['name']) ?>" placeholder="Nhập họ và tên khách hàng" required>
			</div>

			<div class="form-group">
				<label for="email">
					<i class="fas fa-envelope"></i>
					Email <span class="required">*</span>
				</label>
				<input type="email" id="email" name="email" value="<?php echo htmlspecialchars($customer['email']) ?>" placeholder="example@email.com" required>
			</div>

			<div class="form-group">
				<label for="phone">
					<i class="fas fa-phone"></i>
					Số điện thoại <span class="required">*</span>
				</label>
				<input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($customer['phone']) ?>" placeholder="0123456789" required>
			</div>

			<div class="form-group">
				<label for="address">
					<i class="fas fa-map-marker-alt"></i>
					Địa chỉ <span class="required">*</span>
				</label>
				<input type="text" id="address" name="address" value="<?php echo htmlspecialchars($customer['address']) ?>" placeholder="Nhập địa chỉ" required>
			</div>

			<div class="form-group">
				<label for="gender">
					<i class="fas fa-venus-mars"></i>
					Giới tính <span class="required">*</span>
				</label>
				<select id="gender" name="gender" required>
					<option value="male" <?php echo ($customer['gender'] == 'male') ? 'selected' : '' ?>>Nam</option>
					<option value="female" <?php echo ($customer['gender'] == 'female') ? 'selected' : '' ?>>Nữ</option>
				</select>
			</div>

			<div class="form-group">
				<label for="dob">
					<i class="fas fa-birthday-cake"></i>
					Ngày sinh <span class="required">*</span>
				</label>
				<input type="date" id="dob" name="dob" value="<?php echo $customer['dob'] ?>" required>
			</div>
		</div>

		<div class="form-actions">
			<button type="submit" class="btn-submit">
				<i class="fas fa-save"></i>
				Cập nhật
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
	margin-bottom: 30px;
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
