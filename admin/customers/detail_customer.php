<?php 
require '../check_super_admin_login.php';
require '../connect_database.php';

if (empty($_GET['id'])){
	$_SESSION['error'] = 'Chưa nhập id khách hàng';
	header('location:index.php');
	exit;
}

$id = $_GET['id'];

$check = mysqli_num_rows(mysqli_query($connect_database, "SELECT id FROM customers WHERE id = '$id' "));
if ( empty($check) ) {
	$_SESSION['error'] = 'Không tìm thấy khách hàng';
	header('location:index.php');
	exit;
}

$sql_select_customers = "
	SELECT customers.*, IFNULL(sum(receipts.total_price),0) as 'money', IFNULL(MAX(receipts.order_time), 'Chưa mua lần nào') as 'last_time', COUNT(receipts.id) as 'order_count'
	FROM receipts
	RIGHT JOIN customers ON receipts.customer_id = customers.id
	WHERE  customers.id = '$id'
	GROUP BY customers.id
";
$query_sql_select_customers = mysqli_query($connect_database, $sql_select_customers);
$each_customer = mysqli_fetch_array($query_sql_select_customers);

$page_title = 'Chi tiết khách hàng - ' . $each_customer['name'];
$page_heading = 'Chi tiết khách hàng';
require '../header.php';
?>

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-user"></i>
		</div>
		<div class="page-info">
			<h1><?php echo $each_customer['name'] ?></h1>
			<p>Thông tin chi tiết về khách hàng</p>
		</div>
	</div>
	<a href="index.php" class="btn-add-new" style="background: linear-gradient(135deg, #718096 0%, #4a5568 100%);">
		<i class="fas fa-arrow-left"></i>
		Quay lại
	</a>
</div>

<?php require '../validate.php' ?>

<div class="customer-detail-container">
	<div class="detail-grid">
		<div class="detail-image-section">
			<div class="customer-avatar">
				<i class="fas fa-user-circle"></i>
			</div>
			<div class="customer-badge">
				<i class="fas fa-star"></i>
				<span>Khách hàng #<?php echo $each_customer['id'] ?></span>
			</div>
		</div>
		
		<div class="detail-info-section">
			<div class="info-card-detail">
				<h2><i class="fas fa-info-circle"></i> Thông tin cơ bản</h2>
				<div class="info-row">
					<div class="info-label">
						<i class="fas fa-user"></i>
						Họ và tên
					</div>
					<div class="info-value"><?php echo $each_customer['name'] ?></div>
				</div>
				<div class="info-row">
					<div class="info-label">
						<i class="fas fa-envelope"></i>
						Email
					</div>
					<div class="info-value"><?php echo $each_customer['email'] ?></div>
				</div>
				<div class="info-row">
					<div class="info-label">
						<i class="fas fa-phone"></i>
						Số điện thoại
					</div>
					<div class="info-value"><?php echo $each_customer['phone'] ?></div>
				</div>
				<div class="info-row">
					<div class="info-label">
						<i class="fas fa-map-marker-alt"></i>
						Địa chỉ
					</div>
					<div class="info-value"><?php echo $each_customer['address'] ?></div>
				</div>
				<div class="info-row">
					<div class="info-label">
						<i class="<?php echo ($each_customer['gender'] == 'male') ? 'fas fa-mars' : 'fas fa-venus' ?>"></i>
						Giới tính
					</div>
					<div class="info-value"><?php echo ($each_customer['gender'] == 'male') ? 'Nam' : 'Nữ' ?></div>
				</div>
				<div class="info-row">
					<div class="info-label">
						<i class="fas fa-birthday-cake"></i>
						Ngày sinh
					</div>
					<div class="info-value"><?php echo $each_customer['dob'] ?></div>
				</div>
			</div>
			
			<div class="info-card-detail">
				<h2><i class="fas fa-shopping-cart"></i> Thống kê mua hàng</h2>
				<div class="info-row">
					<div class="info-label">
						<i class="fas fa-receipt"></i>
						Số đơn hàng
					</div>
					<div class="info-value">
						<span class="badge-count"><?php echo $each_customer['order_count'] ?> đơn</span>
					</div>
				</div>
				<div class="info-row">
					<div class="info-label">
						<i class="fas fa-money-bill-wave"></i>
						Tổng chi tiêu
					</div>
					<div class="info-value">
						<span class="price-highlight"><?php echo number_format($each_customer['money'], 0, ',', '.') ?>₫</span>
					</div>
				</div>
				<div class="info-row">
					<div class="info-label">
						<i class="fas fa-clock"></i>
						Lần cuối mua
					</div>
					<div class="info-value"><?php echo $each_customer['last_time'] ?></div>
				</div>
			</div>
			
			<div class="action-buttons-detail">
				<a href="view_receipt.php?id=<?php echo $each_customer['id'] ?>" class="btn-detail btn-primary">
					<i class="fas fa-file-invoice"></i>
					Xem đơn hàng
				</a>
			</div>
		</div>
	</div>
</div>

<style>
.customer-detail-container {
	margin-top: 20px;
}

.detail-grid {
	display: grid;
	grid-template-columns: 400px 1fr;
	gap: 30px;
}

.detail-image-section {
	background: white;
	padding: 30px;
	border-radius: 16px;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: 20px;
}

.customer-avatar {
	width: 250px;
	height: 250px;
	border-radius: 50%;
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	display: flex;
	align-items: center;
	justify-content: center;
	box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.customer-avatar i {
	font-size: 120px;
	color: white;
}

.customer-badge {
	background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);
	color: white;
	padding: 12px 24px;
	border-radius: 25px;
	display: flex;
	align-items: center;
	gap: 10px;
	font-weight: 600;
	font-size: 16px;
	box-shadow: 0 4px 12px rgba(246, 173, 85, 0.3);
}

.customer-badge i {
	font-size: 18px;
}

.detail-info-section {
	display: flex;
	flex-direction: column;
	gap: 20px;
}

.info-card-detail {
	background: white;
	padding: 30px;
	border-radius: 16px;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.info-card-detail h2 {
	color: #2d3748;
	font-size: 20px;
	margin: 0 0 25px 0;
	display: flex;
	align-items: center;
	gap: 10px;
	padding-bottom: 15px;
	border-bottom: 2px solid #e2e8f0;
}

.info-card-detail h2 i {
	color: #667eea;
}

.info-row {
	display: flex;
	justify-content: space-between;
	padding: 15px 0;
	border-bottom: 1px solid #e2e8f0;
}

.info-row:last-child {
	border-bottom: none;
}

.info-label {
	color: #718096;
	font-weight: 600;
	display: flex;
	align-items: center;
	gap: 10px;
}

.info-label i {
	width: 20px;
	text-align: center;
}

.info-value {
	color: #2d3748;
	font-weight: 600;
	text-align: right;
}

.badge-count {
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	color: white;
	padding: 6px 16px;
	border-radius: 20px;
	font-size: 14px;
}

.price-highlight {
	color: #48bb78;
	font-size: 20px;
	font-weight: 700;
}

.action-buttons-detail {
	display: flex;
	gap: 15px;
}

.btn-detail {
	flex: 1;
	padding: 15px 24px;
	border-radius: 12px;
	font-size: 16px;
	font-weight: 600;
	text-decoration: none;
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 10px;
	transition: all 0.3s;
	box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn-detail:hover {
	transform: translateY(-2px);
	box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.btn-primary {
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	color: white;
}
</style>

<?php require '../footer.php'; ?>