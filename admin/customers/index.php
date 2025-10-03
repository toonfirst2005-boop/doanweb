<?php 
require '../check_super_admin_login.php';
require '../connect_database.php';

if (isset($_GET['search'])) {
	$content_search = $_GET['search'];
} else {
	$content_search = '';
}

if ( empty($_GET['index_page']) ) {
	$index_page = 1;
} else {
	$index_page = $_GET['index_page'];
}


//lấy ra tổng số khách hàng
$sql_count_customers = "SELECT count(*) FROM customers WHERE name LIKE '%$content_search%' ";
$count_customers = mysqli_fetch_array(mysqli_query($connect_database, $sql_count_customers))['count(*)'];
//lấy ra số khách hàng trên 1 trang
$customers_per_page = 14;
//lấy ra số trang
$pages = ceil($count_customers / $customers_per_page);
//lấy ra số khách hàng bỏ qua khi chuyển trang
$customers_skipped = ( $index_page - 1) * $customers_per_page;


$sql_select_customers = "
	SELECT customers.id as 'id', customers.name as 'name', customers.address as 'address', IFNULL(sum(receipts.total_price),0) as 'money', IFNULL(MAX(receipts.order_time), 'Chưa mua lần nào') as 'last_time'
	FROM receipts
	RIGHT JOIN customers ON receipts.customer_id = customers.id
	WHERE name LIKE '%$content_search%'
	GROUP BY customers.id
	LIMIT $customers_per_page
	OFFSET $customers_skipped
";
$query_sql_select_customers = mysqli_query($connect_database, $sql_select_customers);

// Page configuration
$page_title = 'Quản lý Khách hàng - Admin Panel';
$page_heading = 'Khách hàng';
require '../header.php';
?>

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-users"></i>
		</div>
		<div class="page-info">
			<h1>Quản lý Khách hàng</h1>
			<p>Quản lý danh sách khách hàng trong hệ thống</p>
		</div>
	</div>
	<a href="form_insert_customer.php" class="btn-add-new">
		<i class="fas fa-user-plus"></i>
		Thêm khách hàng
	</a>
</div>

<div class="search-bar-modern">
	<form method="GET" class="search-form-modern">
		<i class="fas fa-search search-icon"></i>
		<input type="search" name="search" placeholder="Tìm kiếm khách hàng..." value="<?php echo $content_search ?>">
		<button type="submit" class="btn-search-modern">
			<i class="fas fa-search"></i>
			Tìm kiếm
		</button>
	</form>
</div>
<table class="table">
	<thead>
		<tr>
			<th>MÃ</th>
			<th>TÊN KHÁCH HÀNG</th>
			<th>ĐỊA CHỈ</th>
			<th>LẦN CUỐI MUA HÀNG</th>
			<th>TỔNG CHI TIÊU</th>
			<th>THAO TÁC</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($query_sql_select_customers as $each_customer) { ?>
		<tr>
			<td><span class="badge-id">#<?php echo $each_customer['id'] ?></span></td>
			<td>
				<div class="customer-info">
					<i class="fas fa-user-circle customer-avatar"></i>
					<span><?php echo htmlspecialchars($each_customer['name']) ?></span>
				</div>
			</td>
			<td>
				<i class="fas fa-map-marker-alt" style="color: #718096; margin-right: 5px;"></i>
				<?php echo $each_customer['address'] ? htmlspecialchars($each_customer['address']) : 'Chưa cập nhật' ?>
			</td>
			<td><?php echo htmlspecialchars($each_customer['last_time']) ?></td>
			<td><span class="badge-money"><?php echo number_format($each_customer['money'], 0, ',', '.') ?>₫</span></td>
			<td>
				<div class="action-buttons">
					<a href="detail_customer.php?id=<?php echo $each_customer['id'] ?>" class="btn-action btn-view" title="Xem chi tiết">
						<i class="fas fa-eye"></i>
					</a>
					<a href="form_edit_customer.php?id=<?php echo $each_customer['id'] ?>" class="btn-action btn-edit" title="Chỉnh sửa">
						<i class="fas fa-edit"></i>
					</a>
					<a href="process_delete_customer.php?id=<?php echo $each_customer['id'] ?>" class="btn-action btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa khách hàng <?php echo htmlspecialchars($each_customer['name']) ?>?')">
						<i class="fas fa-trash"></i>
					</a>
				</div>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<div class="pagination">
	<?php for ($page_num = 1; $page_num <= $pages; $page_num++) { ?>
		<a href="?index_page=<?php echo $page_num?>&search=<?php echo $content_search ?>" 
		   class="<?php echo ($page_num == $index_page) ? 'active' : '' ?>">
			<?php echo $page_num ?>
		</a>
	<?php } ?>
</div>

<?php require '../footer.php'; ?>