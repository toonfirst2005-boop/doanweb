<?php 
require '../check_super_admin_login.php';
require '../connect_database.php';

// Kiểm tra và thêm cột is_active nếu chưa có
$sql_check_column = "SHOW COLUMNS FROM manufacturers LIKE 'is_active'";
$result_check_column = mysqli_query($connect_database, $sql_check_column);
if (mysqli_num_rows($result_check_column) == 0) {
    // Thêm cột is_active nếu chưa có
    $sql_add_column = "ALTER TABLE manufacturers ADD COLUMN is_active TINYINT(1) DEFAULT 1";
    mysqli_query($connect_database, $sql_add_column);
}

if (empty($_GET['id'])){
	$_SESSION['error'] = 'Chưa nhập id nhà sản xuất';
	header('location:index_manufacturers.php');
	exit;
}

$id = $_GET['id'];

// Lấy thông tin nhà sản xuất
$sql_select_manufacturers = "SELECT * FROM manufacturers WHERE id = '$id'";
$query_sql_select_manufacturers = mysqli_query($connect_database, $sql_select_manufacturers);

if (mysqli_num_rows($query_sql_select_manufacturers) == 0) {
	$_SESSION['error'] = 'Không tìm thấy nhà sản xuất';
	header('location:index_manufacturers.php');
	exit();
}

$each_manufacturer = mysqli_fetch_array($query_sql_select_manufacturers);

// Kiểm tra trạng thái nhà sản xuất
$is_active = isset($each_manufacturer['is_active']) ? $each_manufacturer['is_active'] : 1;

// Đếm số sản phẩm đang hoạt động
$sql_count_active_products = "SELECT COUNT(*) as count FROM products WHERE manufacturer_id = '$id' AND (is_deleted IS NULL OR is_deleted = 0)";
$query_count_active_products = mysqli_query($connect_database, $sql_count_active_products);
$count_active_products = mysqli_fetch_array($query_count_active_products)['count'];

// Đếm tổng số sản phẩm (bao gồm đã xóa)
$sql_count_total_products = "SELECT COUNT(*) as count FROM products WHERE manufacturer_id = '$id'";
$query_count_total_products = mysqli_query($connect_database, $sql_count_total_products);
$count_total_products = mysqli_fetch_array($query_count_total_products)['count'];

$count_deleted_products = $count_total_products - $count_active_products;

// Kiểm tra sản phẩm đã ngừng bán có trong đơn hàng không
$sql_check_deleted_in_orders = "
	SELECT COUNT(DISTINCT p.id) as count 
	FROM products p 
	JOIN receipt_detail rd ON p.id = rd.product_id 
	WHERE p.manufacturer_id = '$id' AND p.is_deleted = 1
";
$query_check_deleted = mysqli_query($connect_database, $sql_check_deleted_in_orders);
$deleted_products_in_orders = mysqli_fetch_array($query_check_deleted)['count'];

$page_title = 'Chi tiết nhà sản xuất - ' . $each_manufacturer['name'];
$page_heading = 'Chi tiết nhà sản xuất';
require '../header.php';
?>

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-industry"></i>
		</div>
		<div class="page-info">
			<h1><?php echo $each_manufacturer['name'] ?></h1>
			<p>Thông tin chi tiết về nhà sản xuất</p>
		</div>
	</div>
	<a href="index_manufacturers.php" class="btn-add-new" style="background: linear-gradient(135deg, #718096 0%, #4a5568 100%);">
		<i class="fas fa-arrow-left"></i>
		Quay lại
	</a>
</div>

<?php require '../validate.php' ?>

<div class="manufacturer-detail-container">
	<div class="detail-grid">
		<div class="detail-image-section">
			<div class="manufacturer-logo">
				<img src="<?php echo $each_manufacturer['image'] ?>" alt="<?php echo $each_manufacturer['name'] ?>">
			</div>
		</div>
		
		<div class="detail-info-section">
			<div class="info-card-detail">
				<h2><i class="fas fa-info-circle"></i> Thông tin cơ bản</h2>
				<div class="info-row">
					<div class="info-label">
						<i class="fas fa-industry"></i>
						Tên nhà sản xuất
					</div>
					<div class="info-value"><?php echo $each_manufacturer['name'] ?></div>
				</div>
				<div class="info-row">
					<div class="info-label">
						<i class="fas fa-phone"></i>
						Số điện thoại
					</div>
					<div class="info-value"><?php echo $each_manufacturer['phone'] ?></div>
				</div>
				<div class="info-row">
					<div class="info-label">
						<i class="fas fa-map-marker-alt"></i>
						Địa chỉ
					</div>
					<div class="info-value"><?php echo $each_manufacturer['address'] ?></div>
				</div>
				<div class="info-row">
					<div class="info-label">
						<i class="fas fa-handshake"></i>
						Trạng thái hợp tác
					</div>
					<div class="info-value">
						<?php if ($is_active): ?>
							<span class="status-badge status-active">
								<i class="fas fa-handshake"></i>
								Đang hợp tác
							</span>
						<?php else: ?>
							<span class="status-badge status-inactive">
								<i class="fas fa-handshake-slash"></i>
								Ngừng hợp tác
							</span>
						<?php endif; ?>
					</div>
				</div>
				<div class="info-row">
					<div class="info-label">
						<i class="fas fa-box"></i>
						Số sản phẩm
					</div>
					<div class="info-value">
						<div class="product-status-detail">
							<span class="badge-count-active"><?php echo $count_active_products ?> đang bán</span>
							<?php if ($count_deleted_products > 0): ?>
								<span class="badge-count-inactive"><?php echo $count_deleted_products ?> đã ngừng</span>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			
			<div class="action-buttons-detail">
				<a href="view_products_in_manufacturer.php?id=<?php echo $each_manufacturer['id'] ?>" class="btn-detail btn-primary">
					<i class="fas fa-chart-bar"></i>
					Xem sản phẩm
				</a>
				<a href="form_update_manufacturers.php?id=<?php echo $each_manufacturer['id'] ?>" class="btn-detail btn-warning">
					<i class="fas fa-edit"></i>
					Chỉnh sửa
				</a>
				<?php if ($is_active): ?>
					<?php if ($count_active_products > 0): ?>
						<span class="btn-detail btn-stop disabled" title="Không thể ngừng hợp tác: còn <?php echo $count_active_products ?> sản phẩm đang bày bán">
							<i class="fas fa-handshake-slash"></i>
							Không thể ngừng hợp tác
						</span>
					<?php else: ?>
						<a href="process_delete_manufacturers.php?id=<?php echo $each_manufacturer['id'] ?>" class="btn-detail btn-stop" onclick="return confirm('Bạn có chắc muốn ngừng hợp tác với nhà sản xuất này?\\n\\nDữ liệu sẽ được bảo tồn.')">
							<i class="fas fa-handshake-slash"></i>
							Ngừng hợp tác
						</a>
					<?php endif; ?>
				<?php else: ?>
					<a href="process_reactivate_manufacturers.php?id=<?php echo $each_manufacturer['id'] ?>" class="btn-detail btn-reactivate" onclick="return confirm('Bạn có chắc muốn tiếp tục hợp tác với nhà sản xuất này?')">
						<i class="fas fa-handshake"></i>
						Tiếp tục hợp tác
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<style>
.manufacturer-detail-container {
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
}

.manufacturer-logo {
	width: 100%;
	aspect-ratio: 1;
	border-radius: 16px;
	overflow: hidden;
	box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.manufacturer-logo img {
	width: 100%;
	height: 100%;
	object-fit: cover;
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

.product-status-detail {
	display: flex;
	flex-direction: column;
	gap: 8px;
	align-items: flex-end;
}

.badge-count-active {
	background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
	color: white;
	padding: 6px 12px;
	border-radius: 20px;
	font-size: 13px;
	font-weight: 600;
	display: inline-flex;
	align-items: center;
	gap: 4px;
}

.badge-count-active::before {
	content: "●";
	color: #c6f6d5;
}

.badge-count-inactive {
	background: linear-gradient(135deg, #718096 0%, #4a5568 100%);
	color: white;
	padding: 4px 10px;
	border-radius: 16px;
	font-size: 12px;
	font-weight: 600;
	display: inline-flex;
	align-items: center;
	gap: 4px;
}

.badge-count-inactive::before {
	content: "●";
	color: #cbd5e0;
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

.btn-warning {
	background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);
	color: white;
}

.btn-danger {
	background: linear-gradient(135deg, #fc8181 0%, #e53e3e 100%);
	color: white;
}

.btn-detail.disabled {
	background: #e2e8f0 !important;
	color: #a0aec0 !important;
	cursor: not-allowed !important;
	pointer-events: none;
	opacity: 0.6;
}

.btn-detail.disabled:hover {
	transform: none !important;
	box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
}

/* Status Badges */
.status-badge {
	padding: 8px 16px;
	border-radius: 20px;
	font-size: 14px;
	font-weight: 600;
	display: inline-flex;
	align-items: center;
	gap: 8px;
	white-space: nowrap;
}

.status-active {
	background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
	color: white;
}

.status-inactive {
	background: linear-gradient(135deg, #fc8181 0%, #e53e3e 100%);
	color: white;
}

/* Action Buttons */
.btn-stop {
	background: linear-gradient(135deg, #fc8181 0%, #e53e3e 100%);
	color: white;
}

.btn-reactivate {
	background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
	color: white;
}
</style>


<?php require '../footer.php'; ?>