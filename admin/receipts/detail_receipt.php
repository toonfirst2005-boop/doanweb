<?php 
require '../check_admin_login.php';

if ( empty($_GET['id']) ){
	$_SESSION['error'] = 'Chưa nhập id hóa đơn';
	header('location:index.php');
	exit;
}

$id = $_GET['id'];

require '../connect_database.php';

//kiểm tra xem có id hóa đơn đó không
$sql_command_select = "select * from receipts where id = '$id' ";
$query_sql_command_select = mysqli_query($connect_database, $sql_command_select);
$check = mysqli_num_rows($query_sql_command_select);
if ( $check !== 1 ) {
	$_SESSION['error'] = 'Không tồn tại hóa đơn này';
	header('location:index.php');
	exit();
}


$sql_command_select = "select * from receipt_detail join products on receipt_detail.product_id = products.id where 	receipt_detail.receipt_id = $id";

$query_sql_command_select = mysqli_query($connect_database, $sql_command_select);
$money_of_all = 0;

$page_title = 'Chi tiết đơn hàng #' . $id . ' - Admin Panel';
$page_heading = 'Chi tiết đơn hàng';
require '../header.php';
?>

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-file-invoice"></i>
		</div>
		<div class="page-info">
			<h1>Chi tiết đơn hàng #<?php echo $id ?></h1>
			<p>Xem chi tiết sản phẩm trong đơn hàng</p>
		</div>
	</div>
	<a href="javascript:history.back()" class="btn-add-new" style="background: linear-gradient(135deg, #718096 0%, #4a5568 100%);">
		<i class="fas fa-arrow-left"></i>
		Quay lại
	</a>
</div>

<?php require '../validate.php' ?>

<div class="products-detail-container">
	<table class="table">
		<thead>
			<tr>
				<th>ẢNH</th>
				<th>TÊN SẢN PHẨM</th>
				<th>GIÁ</th>
				<th>SỐ LƯỢNG</th>
				<th>TỔNG TIỀN</th>
			</tr>
		</thead>
		<tbody>


		<?php foreach ($query_sql_command_select as $array_receipts) : ?>
		<tr>
			<td>
				<div class="product-image-cell">
					<img src="../products/<?php echo $array_receipts['image'] ?>" alt="<?php echo $array_receipts['name'] ?>">
				</div>
			</td>
			<td>
				<div class="product-name-cell">
					<i class="fas fa-box" style="color: #667eea; margin-right: 8px;"></i>
					<strong><?php echo $array_receipts['name'] ?></strong>
				</div>
			</td>
			<td>
				<span class="price-cell"><?php echo number_format($array_receipts['price'], 0, ',', '.') ?>₫</span>
			</td>
			<td>
				<span class="quantity-badge">x<?php echo $array_receipts['quantity'] ?></span>
			</td>
			<td>
				<span class="total-cell"><?php echo number_format($array_receipts['quantity'] * $array_receipts['price'], 0, ',', '.') ?>₫</span>
			</td>
		</tr>
		
		<?php $money_of_all += $array_receipts['quantity'] * $array_receipts['price'] ?>

		<?php endforeach ?>
		</tbody>
	</table>

	<div class="total-summary">
		<div class="total-label">Tổng tiền:</div>
		<div class="total-amount"><?php echo number_format($money_of_all, 0, ',', '.') ?>₫</div>
	</div>
</div>

<style>
.products-detail-container {
	margin-top: 20px;
}

.product-image-cell {
	width: 100px;
	height: 100px;
	border-radius: 12px;
	overflow: hidden;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
	margin: 0 auto;
}

.product-image-cell img {
	width: 100%;
	height: 100%;
	object-fit: cover;
}

.product-name-cell {
	display: flex;
	align-items: center;
	font-size: 15px;
}

.price-cell {
	color: #718096;
	font-size: 15px;
	font-weight: 500;
}

.quantity-badge {
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	color: white;
	padding: 6px 16px;
	border-radius: 20px;
	font-weight: 600;
	font-size: 14px;
	display: inline-block;
}

.total-cell {
	color: #48bb78;
	font-size: 16px;
	font-weight: 700;
}

.total-summary {
	margin-top: 30px;
	padding: 25px;
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	border-radius: 16px;
	display: flex;
	justify-content: space-between;
	align-items: center;
	box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.total-label {
	color: white;
	font-size: 20px;
	font-weight: 600;
}

.total-amount {
	color: white;
	font-size: 32px;
	font-weight: 700;
}
</style>

<?php require '../footer.php'; ?>