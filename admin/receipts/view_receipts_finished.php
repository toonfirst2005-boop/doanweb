<?php 
require '../check_admin_login.php';
require '../connect_database.php';

if (isset($_GET['index'])) {
	$i = $_GET['index'];
} else {
	$i = 1;
}


//lấy ra tổng số hóa đơn
$sql_command_select_receipts = "select count(*) from receipts where status = 3 or status = 7 or status = 5";
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
WHERE receipts.status in (3, 5, 7)
ORDER BY receipts.order_time desc
limit $receipts_on_page offset $skip_receipts_page";

$query_sql_command_select = mysqli_query($connect_database, $sql_command_select);

$page_title = 'Đơn hàng đã xử lý - Admin Panel';
$page_heading = 'Đơn hàng đã xử lý';
require '../header.php';
?>

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-check-circle"></i>
		</div>
		<div class="page-info">
			<h1>Đơn hàng đã xử lý</h1>
			<p>Quản lý đơn hàng đã hoàn thành hoặc đã hủy</p>
		</div>
	</div>
	<a href="index.php" class="btn-add-new" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
		<i class="fas fa-arrow-left"></i>
		Xem đơn chờ xử lý
	</a>
</div>

<?php require '../validate.php' ?>

<table class="table">
	<thead>
		<tr>
			<th>MÃ</th>
			<th>THỜI GIAN ĐẶT</th>
			<th>NGƯỜI NHẬN</th>
			<th>KHÁCH HÀNG</th>
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
			<a href="detail_receipt.php?id=<?php echo $each_receipt['id'] ?>" class="btn-action btn-view" title="Xem chi tiết">
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
		<a href="?index=<?php echo $page_num?>" 
		   class="<?php echo ($page_num == $i) ? 'active' : '' ?>">
			<?php echo $page_num ?>
		</a>
	<?php } ?>
</div>

<?php require '../footer.php'; ?>