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
$sql_command_select_receipts = "select count(*) from receipts where (status = 3 or status = 7 or status = 5) and customer_id = '$id' ";
$query_sql_command_select_receipts = mysqli_query($connect_database, $sql_command_select_receipts);
$count_receipts = mysqli_fetch_array($query_sql_command_select_receipts)['count(*)'];

//lấy ra số hóa đơn trên 1 trang
$receipts_on_page = 5;


//lấy ra số trang
$count_pages = ceil ($count_receipts / $receipts_on_page);

//lấy ra số trang bỏ qua theo thú tự trang
$skip_receipts_page = ( $i - 1 ) * $receipts_on_page;


$sql_command_select = "SELECT receipts.*, customers.name as 'customer_name', customers.email as 'customer_email', customers.phone as 'customer_phone' 
from receipts
JOIN customers on customers.id = receipts.customer_id
WHERE receipts.status in (3, 5, 7) and customer_id = '$id'
ORDER BY receipts.order_time desc
limit $receipts_on_page offset $skip_receipts_page";

$query_sql_command_select = mysqli_query($connect_database, $sql_command_select);
if ( mysqli_num_rows($query_sql_command_select) != 0 ) {
	$customer_id = mysqli_fetch_array($query_sql_command_select)['customer_id'];
	$query_sql_command_select = mysqli_query($connect_database, $sql_command_select); // Re-query
}

$page_title = 'Đơn hàng đã xử lý của ' . $name . ' - Admin Panel';
$page_heading = 'Đơn hàng khách hàng';
require '../header.php';
?>

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-check-circle"></i>
		</div>
		<div class="page-info">
			<h1>Đơn hàng đã xử lý của <?php echo $name ?></h1>
			<p>Quản lý đơn hàng đã hoàn thành hoặc đã hủy</p>
		</div>
	</div>
	<div style="display: flex; gap: 10px;">
		<a href="view_receipt.php?id=<?php echo $id ?>" class="btn-add-new" style="background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);">
			<i class="fas fa-hourglass-half"></i>
			Đơn chưa xử lý
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
			<th>NGƯỜI NHẬN</th>
			<th>NGƯỜI ĐẶT</th>
			<th>TRẠNG THÁI</th>
			<th>TỔNG TIỀN</th>
			<th>CHI TIẾT</th>
		</tr>
	</thead>
	<tbody>

	<?php foreach ($query_sql_command_select as $each_receipt) : ?>
	<?php if ($each_receipt['status'] == 3 || $each_receipt['status'] == 5 || $each_receipt['status'] == 7 ) { ?>
	<tr>
		<td><span class="badge-id">#<?php echo $each_receipt['id'] ?></span></td>
		<td>
			<i class="fas fa-clock" style="color: #718096; margin-right: 5px;"></i>
			<?php echo $each_receipt['order_time'] ?>
		</td>
		<td>
			<div style="line-height: 1.6;">
				<strong><?php echo $each_receipt['receiver_name'] ?></strong><br>
				<i class="fas fa-phone" style="color: #718096; margin-right: 5px;"></i><?php echo $each_receipt['receiver_phone'] ?><br>
				<i class="fas fa-map-marker-alt" style="color: #718096; margin-right: 5px;"></i><?php echo $each_receipt['receiver_address'] ?>
			</div>
		</td>
		<td>
			<div class="customer-info">
				<i class="fas fa-user-circle customer-avatar"></i>
				<span><?php echo $each_receipt['customer_name'] ?></span>
			</div>
		</td>
		<td>
			<?php 
			switch ($each_receipt['status']) {
				case 3:
					echo '<span class="badge-id" style="background: #fed7d7; color: #c53030;"><i class="fas fa-ban"></i> Shop đã hủy</span>';
					break;							
				case 7:
					echo '<span class="badge-id" style="background: #feebc8; color: #c05621;"><i class="fas fa-user-times"></i> Khách hủy</span>';
					break;
				case 5:
					echo '<span class="badge-id" style="background: #c6f6d5; color: #22543d;"><i class="fas fa-check-circle"></i> Hoàn thành</span>';
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