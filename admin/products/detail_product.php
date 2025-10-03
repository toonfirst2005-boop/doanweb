<?php 
require '../check_admin_login.php';
require '../connect_database.php';

if (empty($_GET['id'])){
	$_SESSION['error'] = 'Chưa nhập id sản phẩm cần xem';
	header('location:index_products.php');
	exit;
}
$id = $_GET['id'];

$sql_select_customers = "
	SELECT 
		products.*, 
		manufacturers.name AS 'manufacturers_name', 
		IFNULL(types.name, 'Không có thẻ nào') AS'type_name', 
		IFNULL(MAX(receipts.order_time), 'Chưa có ai mua') AS 'last_time',
		(SELECT IFNULL(SUM(receipt_detail.quantity), 0) AS 'quantity'
		FROM receipt_detail
		JOIN products ON products.id = receipt_detail.product_id
		WHERE products.id = '$id') AS 'quantity', 
		(SELECT IFNULL(SUM(receipt_detail.quantity * products.price), 0) AS 'total'
		FROM receipt_detail
		JOIN products ON products.id = receipt_detail.product_id
		WHERE products.id = '$id') AS 'total'
	FROM products
	JOIN manufacturers ON manufacturers.id = products.manufacturer_id
	LEFT JOIN product_type ON product_type.product_id = products.id
	LEFT JOIN types ON types.id = product_type.type_id
	LEFT JOIN receipt_detail ON  receipt_detail.product_id = products.id
	LEFT JOIN receipts ON receipts.id = receipt_detail.receipt_id
	WHERE products.id = '$id'
	GROUP BY types.name
";

$query_sql_select_products = mysqli_query($connect_database, $sql_select_customers);
$check = mysqli_num_rows($query_sql_select_products);


if ( $check == 0 ) {
	$_SESSION['error'] = 'Sai id sẩn phẩm';
	header('location:index_products.php');
	exit();
}

$each_product = mysqli_fetch_array($query_sql_select_products);
mysqli_close($connect_database);

// Page configuration
$page_title = 'Chi tiết Sản phẩm - Admin Panel';
$page_heading = 'Chi tiết Sản phẩm';
require '../header.php';
?>

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-box-open"></i>
		</div>
		<div class="page-info">
			<h1>Chi tiết Sản phẩm</h1>
			<p>Thông tin chi tiết về sản phẩm</p>
		</div>
	</div>
	<a href="index_products.php" class="btn-add-new" style="background: linear-gradient(135deg, #718096 0%, #4a5568 100%);">
		<i class="fas fa-arrow-left"></i>
		Quay lại
	</a>
</div>

<?php require '../validate.php' ?>

<div class="product-detail-container">
	<div class="product-detail-grid">
		<!-- Product Image -->
		<div class="product-image-section">
			<div class="product-image-wrapper">
				<img src="<?php echo $each_product['image'] ?>" alt="<?php echo $each_product['name'] ?>">
			</div>
		</div>

		<!-- Product Info -->
		<div class="product-info-section">
			<div class="product-header">
				<h1 class="product-title"><?php echo $each_product['name'] ?></h1>
				<div class="product-price">
					<span class="price-amount"><?php echo number_format($each_product['price'], 0, ',', '.') ?>₫</span>
				</div>
			</div>

			<div class="product-description">
				<h3><i class="fas fa-info-circle"></i> Mô tả</h3>
				<p><?php echo $each_product['description'] ?></p>
			</div>

			<div class="product-meta">
				<div class="meta-item">
					<i class="fas fa-industry"></i>
					<div>
						<span class="meta-label">Nhà sản xuất</span>
						<span class="meta-value"><?php echo $each_product['manufacturers_name'] ?></span>
					</div>
				</div>

				<div class="meta-item">
					<i class="fas fa-tags"></i>
					<div>
						<span class="meta-label">Thẻ</span>
						<span class="meta-value">
							<?php 
							$tags = [];
							foreach ($query_sql_select_products as $each) {
								$tags[] = $each['type_name'];
							}
							echo implode(', ', $tags);
							?>
						</span>
					</div>
				</div>
			</div>

			<div class="product-actions">
				<a href="form_update_products.php?id=<?php echo $each_product['id'] ?>" class="btn-edit-product">
					<i class="fas fa-edit"></i>
					Chỉnh sửa sản phẩm
				</a>
				<a href="process_delete_products.php?id=<?php echo $each_product['id'] ?>" class="btn-delete-product" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
					<i class="fas fa-trash"></i>
					Xóa sản phẩm
				</a>
			</div>
		</div>
	</div>

	<!-- Statistics Cards -->
	<div class="stats-grid">
		<div class="stat-card">
			<div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
				<i class="fas fa-shopping-cart"></i>
			</div>
			<div class="stat-info">
				<span class="stat-label">Số lượng đã bán</span>
				<span class="stat-value"><?php echo number_format($each_product['quantity'], 0, ',', '.') ?></span>
			</div>
		</div>

		<div class="stat-card">
			<div class="stat-icon" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);">
				<i class="fas fa-dollar-sign"></i>
			</div>
			<div class="stat-info">
				<span class="stat-label">Doanh thu</span>
				<span class="stat-value"><?php echo number_format($each_product['total'], 0, ',', '.') ?>₫</span>
			</div>
		</div>

		<div class="stat-card">
			<div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
				<i class="fas fa-clock"></i>
			</div>
			<div class="stat-info">
				<span class="stat-label">Lần cuối mua</span>
				<span class="stat-value" style="font-size: 14px;"><?php echo $each_product['last_time'] ?></span>
			</div>
		</div>
	</div>
</div>

<?php require '../footer.php'; ?>