<?php 
require '../check_admin_login.php';
require '../connect_database.php';
if (empty($_GET['id'])){
	$_SESSION['error'] = 'Chưa nhập id khách hàng';
	header('location:index.php');
	exit;
}
$id = $_GET['id'];

$check = mysqli_num_rows(mysqli_query($connect_database, "SELECT id FROM customers WHERE id = '$id' "));
if ( empty($check) ) {
	$_SESSION['error'] = 'Sai id khách hàng';
	header('location:index.php');
	exit;
}

$sql_select_name = "SELECT name FROM customers WHERE id = '$id'";
$name = mysqli_fetch_array(mysqli_query($connect_database, $sql_select_name))['name'] ;



if (isset($_GET['index'])) {
	$i = $_GET['index'];
} else {
	$i = 1;
}

//lấy ra tổng số hóa đơn
$sql_command_select_receipts = "select count(*) from receipts where (status = 2 or status = 4) and customer_id= '$id'";
$query_sql_command_select_receipts = mysqli_query($connect_database, $sql_command_select_receipts);
$count_receipts = mysqli_fetch_array($query_sql_command_select_receipts)['count(*)'];

//lấy ra số hóa đơn trên 1 trang
$receipts_on_page = 5;

//lấy ra số trang
$count_pages = ceil ($count_receipts / $receipts_on_page);

//lấy ra số trang bỏ qua theo thú tự trang
$skip_receipts_page = ( $i - 1 ) * $receipts_on_page;




$sql_select = "
	SELECT receipts.*, customers.name as 'customer_name', customers.email as 'customer_email', customers.phone as 'customer_phone' 
	FROM receipts
	JOIN customers on customers.id = receipts.customer_id
	WHERE customer_id = '$id' and receipts.status in (2, 4)
	ORDER BY receipts.order_time desc
	limit $receipts_on_page offset $skip_receipts_page
";
$query_select = mysqli_query($connect_database, $sql_select);

$page_title = 'Đơn hàng của ' . $name . ' - Admin Panel';
$page_heading = 'Đơn hàng khách hàng';
require '../header.php';
?>

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-file-invoice"></i>
		</div>
		<div class="page-info">
			<h1>Đơn hàng chưa xử lý của <?php echo $name ?></h1>
			<p>Quản lý đơn hàng đang chờ xử lý và đang giao</p>
		</div>
	</div>
	<div style="display: flex; gap: 10px;">
		<a href="view_receipts_finished.php?id=<?php echo $id ?>" class="btn-add-new" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);">
			<i class="fas fa-check-circle"></i>
			Đơn đã xử lý
		</a>
		<a href="detail_customer.php?id=<?php echo $id ?>" class="btn-add-new" style="background: linear-gradient(135deg, #718096 0%, #4a5568 100%);">
			<i class="fas fa-arrow-left"></i>
			Quay lại
		</a>
	</div>
</div>

<?php require '../validate.php' ?>

<table class="table">
	<thead>
		<tr>
			<th>MÃ</th>
			<th>THỜI GIAN ĐẶT</th>
			<th>NGƯỜI ĐẶT</th>
			<th>NGƯỜI NHẬN</th>
			<th>TRẠNG THÁI</th>
			<th>TỔNG TIỀN</th>
			<th>CHI TIẾT</th>
			<th>THAO TÁC</th>
		</tr>
	</thead>
	<tbody> 

		<?php foreach ($query_select as $each_receipt) : ?>
		<?php if ($each_receipt['status'] == 2 || $each_receipt['status'] == 4 ) { ?>
		<tr>
			<td><span class="badge-id">#<?php echo $each_receipt['id'] ?></span></td>
			<td>
				<i class="fas fa-clock" style="color: #718096; margin-right: 5px;"></i>
				<?php echo $each_receipt['order_time'] ?>
			</td>
			<td>
				<div class="customer-info">
					<i class="fas fa-user-circle customer-avatar"></i>
					<span><?php echo $each_receipt['customer_name'] ?></span>
				</div>
			</td>
			<td>
				<div style="line-height: 1.6;">
					<strong><?php echo $each_receipt['receiver_name'] ?></strong><br>
					<i class="fas fa-phone" style="color: #718096; margin-right: 5px;"></i><?php echo $each_receipt['receiver_phone'] ?><br>
					<i class="fas fa-map-marker-alt" style="color: #718096; margin-right: 5px;"></i><?php echo $each_receipt['receiver_address'] ?>
				</div>
			</td>
			<td>
				<?php 
				switch ($each_receipt['status']) {
					case 2:
						echo '<span class="badge-id" style="background: #fed7d7; color: #c53030;"><i class="fas fa-hourglass-half"></i> Chờ xác nhận</span>';
						break;							
					case 4:
						echo '<span class="badge-id" style="background: #bee3f8; color: #2c5282;"><i class="fas fa-shipping-fast"></i> Đang giao hàng</span>';
						break;
				}
				?>
			</td>
			<td><span class="badge-money"><?php echo number_format($each_receipt['total_price'], 0, ',', '.') ?>₫</span></td>
			<td>
				<a href="../receipts/detail_receipt.php?id=<?php echo $each_receipt['id'] ?>" class="btn-action btn-view" title="Xem chi tiết">
					<i class="fas fa-eye"></i>
				</a>
			</td>
			<td>
				<div class="action-buttons">
					<?php if ( $each_receipt['status'] == 2 ) { ?>
						<a href="../receipts/update_receipt.php?id=<?php echo $each_receipt['id'] ?>&status=4&customer_id=<?php echo $id ?>" class="btn-action" style="background: #48bb78; color: white;" title="Duyệt đơn">
							<i class="fas fa-check"></i>
						</a>
					<?php } ?>
					<?php if ( $each_receipt['status'] == 4 ) { ?>
						<a href="../receipts/update_receipt.php?id=<?php echo $each_receipt['id'] ?>&status=5&customer_id=<?php echo $id ?>" class="btn-action" style="background: #4299e1; color: white;" title="Hoàn thành">
							<i class="fas fa-check-circle"></i>
						</a>
					<?php } ?>
					<a href="../receipts/update_receipt.php?id=<?php echo $each_receipt['id'] ?>&status=3&customer_id=<?php echo $id ?>" class="btn-action btn-delete" title="Hủy đơn" onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
						<i class="fas fa-times"></i>
					</a>
				</div>
			</td>
		</tr>
		<?php } ?>
		<?php endforeach ?>
	</tbody>
</table>

<div class="pagination">
	<?php for ($page_num = 1; $page_num <= $count_pages; $page_num++) { ?>
		<a href="?id=<?php echo $id ?>&index=<?php echo $page_num?>" 
		   class="<?php echo ($page_num == $i) ? 'active' : '' ?>">
			<?php echo $page_num ?>
		</a>
	<?php } ?>
</div>

<?php require '../footer.php'; ?>
