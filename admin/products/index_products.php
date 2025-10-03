<?php 
require '../check_admin_login.php';
require '../connect_database.php';

// Kiểm tra và thêm cột is_deleted nếu chưa có
$sql_check_column = "SHOW COLUMNS FROM products LIKE 'is_deleted'";
$result_check_column = mysqli_query($connect_database, $sql_check_column);
if (mysqli_num_rows($result_check_column) == 0) {
    // Thêm cột is_deleted nếu chưa có
    $sql_add_column = "ALTER TABLE products ADD COLUMN is_deleted TINYINT(1) DEFAULT 0 COMMENT 'Trạng thái: 0=Đang bán, 1=Đã ngừng bán'";
    mysqli_query($connect_database, $sql_add_column);
}

//kiểm tra filter trạng thái sản phẩm
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'active';
$status_condition = '';
if ($status_filter == 'active') {
	$status_condition = " AND (products.is_deleted IS NULL OR products.is_deleted = 0)";
} elseif ($status_filter == 'inactive') {
	$status_condition = " AND products.is_deleted = 1";
}
// 'all' không thêm điều kiện

if (isset($_GET['search'])) {
	$content_search = $_GET['search'];
} else {
	$content_search = '';
}

if (isset($_GET['page'])) {
	$index_page = $_GET['page'];
} else {
	$index_page = 1;
}


//lấy ra tổng số sản phẩm theo filter
$sql_command_count_products = "select count(*) from products where name like '%$content_search%' $status_condition";
$query_sql_command_count_products = mysqli_query($connect_database, $sql_command_count_products);
$count_products = mysqli_fetch_array($query_sql_command_count_products)['count(*)'];


//lấy ra số bài trên 1 trang
$products_per_page = 4;


//lấy ra tổng số trang
$count_pages = ceil($count_products / $products_per_page);

//lấy ra số trang bỏ qua
$count_skip_products = ($index_page - 1) * $products_per_page;


$sql_command_select = "select products.*, manufacturers.name as 'manufacturers_name' from products join manufacturers on manufacturers.id = products.manufacturer_id where products.name like '%$content_search%' $status_condition limit $products_per_page offset $count_skip_products ";
$query_sql_command_select = mysqli_query($connect_database, $sql_command_select);
mysqli_close($connect_database);

// Page configuration
$page_title = 'Quản lý Sản phẩm - Admin Panel';
$page_heading = 'Sản phẩm';
require '../header.php';
?>

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-box"></i>
		</div>
		<div class="page-info">
			<h1>Quản lý Sản phẩm</h1>
			<p>Quản lý danh sách sản phẩm trong hệ thống</p>
		</div>
	</div>
	<a href="form_insert_products.php" class="btn-add-new">
		<i class="fas fa-plus"></i>
		Thêm sản phẩm mới
	</a>
</div>

<div class="search-bar-modern">
	<form method="GET" class="search-form-modern">
		<i class="fas fa-search search-icon"></i>
		<input type="search" name="search" placeholder="Tìm kiếm sản phẩm..." value="<?php echo $content_search ?>">
		<div class="status-filter-wrapper">
			<select name="status" class="status-filter">
				<option value="active" <?php echo $status_filter == 'active' ? 'selected' : '' ?>>
					🟢 Đang bán
				</option>
				<option value="inactive" <?php echo $status_filter == 'inactive' ? 'selected' : '' ?>>
					🔴 Đã ngừng bán
				</option>
				<option value="all" <?php echo $status_filter == 'all' ? 'selected' : '' ?>>
					📋 Tất cả
				</option>
			</select>
		</div>
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
			<th>TÊN SẢN PHẨM</th>
			<th>TRẠNG THÁI</th>
			<th>GIÁ</th>
			<th>HÌNH ẢNH</th>
			<th>NHÀ SẢN XUẤT</th>
			<th>THAO TÁC</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($query_sql_command_select as $array_products): ?>
		<tr>
			<td><span class="badge-id">#<?php echo $array_products['id'] ?></span></td>
			<td><?php echo $array_products['name'] ?></td>
			<td>
				<?php 
					$is_deleted = isset($array_products['is_deleted']) ? $array_products['is_deleted'] : 0;
				?>
				<?php if ($is_deleted): ?>
					<span class="status-badge status-inactive">
						<i class="fas fa-pause-circle"></i>
						Đã ngừng bán
					</span>
				<?php else: ?>
					<span class="status-badge status-active">
						<i class="fas fa-check-circle"></i>
						Đang bán
					</span>
				<?php endif; ?>
			</td>
			<td><span class="badge-money"><?php echo number_format($array_products['price'], 0, ',', '.') ?>₫</span></td>
			<td>
				<img src="<?php echo $array_products['image'] ?>" height="60px" style="border-radius: 8px; object-fit: cover;">
			</td>
			<td><?php echo $array_products['manufacturers_name'] ?></td>
			<td>
				<div class="action-buttons">
					<a href="detail_product.php?id=<?php echo $array_products['id'] ?>" class="btn-action btn-view" title="Xem chi tiết">
						<i class="fas fa-eye"></i>
					</a>
					<a href="form_update_products.php?id=<?php echo $array_products['id'] ?>" class="btn-action btn-edit" title="Chỉnh sửa">
						<i class="fas fa-edit"></i>
					</a>
					<a href="process_delete_products.php?id=<?php echo $array_products['id'] ?>" class="btn-action btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa?')">
						<i class="fas fa-trash"></i>
					</a>
				</div>
			</td>
		</tr>
		<?php endforeach ?>
	</tbody>
	</table>

	<div class="pagination">
		<?php for ($page_num = 1; $page_num <= $count_pages; $page_num++) { ?>
			<a href="?page=<?php echo $page_num?>&search=<?php echo $content_search ?>&status=<?php echo $status_filter ?>" 
			   class="<?php echo ($page_num == $index_page) ? 'active' : '' ?>">
				<?php echo $page_num ?>
			</a>
		<?php } ?>
	</div>

<style>
/* Search Form Modern */
.search-form-modern {
	display: flex;
	gap: 15px;
	align-items: center;
	background: white;
	padding: 20px;
	border-radius: 12px;
	margin-bottom: 20px;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.search-form-modern input {
	flex: 1;
	padding: 12px 45px 12px 15px;
	border: 2px solid #e2e8f0;
	border-radius: 10px;
	font-size: 15px;
	transition: all 0.3s;
}

.search-form-modern input:focus {
	outline: none;
	border-color: #667eea;
	box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Status Filter */
.status-filter-wrapper {
	position: relative;
}

.status-filter {
	padding: 12px 40px 12px 15px;
	border: 2px solid #e2e8f0;
	border-radius: 10px;
	font-size: 15px;
	background: white;
	cursor: pointer;
	transition: all 0.3s;
	min-width: 150px;
	appearance: none;
	background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 9 4 4 4-4'/%3e%3c/svg%3e");
	background-position: right 12px center;
	background-repeat: no-repeat;
	background-size: 16px;
}

.status-filter:focus {
	outline: none;
	border-color: #667eea;
	box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Status Badges */
.status-badge {
	padding: 6px 12px;
	border-radius: 20px;
	font-size: 13px;
	font-weight: 600;
	display: inline-flex;
	align-items: center;
	gap: 6px;
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

.btn-search-modern {
	padding: 12px 24px;
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	color: white;
	border: none;
	border-radius: 10px;
	font-size: 15px;
	font-weight: 600;
	cursor: pointer;
	display: flex;
	align-items: center;
	gap: 8px;
	transition: all 0.3s;
	box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
}

.btn-search-modern:hover {
	transform: translateY(-2px);
	box-shadow: 0 6px 12px rgba(102, 126, 234, 0.4);
}
</style>

<?php require '../footer.php'; ?>
