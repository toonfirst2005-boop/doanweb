<?php 
require '../check_super_admin_login.php';
require '../connect_database.php';

$content_search = '';

if (isset($_GET['search'])) {
	$content_search = $_GET['search'];	
}

if (isset($_GET['page'])) {
	$index_page = $_GET['page'];
} else {
	$index_page = 1 ;
}

//lấy ra tổng số nhà sản xuất
$sql_command_count_manufacturers = "select count(*) from manufacturers where name like '%$content_search%'";
$query_sql_command_count_manufacturers = mysqli_query($connect_database, $sql_command_count_manufacturers);
$count_manufacturers = mysqli_fetch_array($query_sql_command_count_manufacturers)['count(*)'];

//tổng số nhà sản xuất trên 1 trang => 4
$manufacturers_per_page = 4;

//tổng số trang
$count_pages = ceil($count_manufacturers / $manufacturers_per_page);

//số nhà sx bỏ qua trên 1 trang

$count_skip_manufacturers = ($index_page - 1 ) * $manufacturers_per_page;

$sql_command_select = "select * from manufacturers where name like '%$content_search%' limit $manufacturers_per_page offset $count_skip_manufacturers";
$query_sql_command_select = mysqli_query($connect_database, $sql_command_select);

$page_title = 'Quản lý Nhà sản xuất - Admin Panel';
$page_heading = 'Nhà sản xuất';
require '../header.php';
?>

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-industry"></i>
		</div>
		<div class="page-info">
			<h1>Nhà sản xuất</h1>
			<p>Quản lý thông tin các nhà sản xuất sản phẩm</p>
		</div>
	</div>
	<a href="form_insert_manufacturers.php" class="btn-add-new">
		<i class="fas fa-plus"></i>
		Thêm nhà sản xuất
	</a>
</div>

<div class="search-box-modern">
	<form method="GET" class="search-form-modern">
		<div class="search-input-wrapper">
			<i class="fas fa-search"></i>
			<input type="search" name="search" placeholder="Tìm kiếm theo tên nhà sản xuất..." value="<?php echo $content_search ?>">
		</div>
		<button type="submit" class="btn-search-modern">
			<i class="fas fa-search"></i>
			Tìm kiếm
		</button>
	</form>
</div>

<?php require '../validate.php' ?>

<table class="table">
	<thead>
		<tr>
			<th>MÃ</th>
			<th>TÊN NHÀ SẢN XUẤT</th>
			<th>SỐ ĐIỆN THOẠI</th>
			<th>ĐỊA CHỈ</th>
			<th>HÌNH ẢNH</th>
			<th>THAO TÁC</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($query_sql_command_select as $array_manufacturers): ?>
		<tr>
			<td><span class="badge-id">#<?php echo $array_manufacturers['id'] ?></span></td>
			<td>
				<div class="manufacturer-name">
					<i class="fas fa-industry" style="color: #667eea; margin-right: 8px;"></i>
					<strong><?php echo $array_manufacturers['name'] ?></strong>
				</div>
			</td>
			<td>
				<i class="fas fa-phone" style="color: #718096; margin-right: 5px;"></i>
				<?php echo $array_manufacturers['phone'] ?>
			</td>
			<td>
				<i class="fas fa-map-marker-alt" style="color: #718096; margin-right: 5px;"></i>
				<?php echo $array_manufacturers['address'] ?>
			</td>
			<td>
				<div class="manufacturer-image">
					<img src="<?php echo $array_manufacturers['image'] ?>" alt="<?php echo $array_manufacturers['name'] ?>">
				</div>
			</td>
			<td>
				<div class="action-buttons">
					<a href="detail_manufacturer.php?id=<?php echo $array_manufacturers['id'] ?>" class="btn-action btn-view" title="Xem chi tiết">
						<i class="fas fa-eye"></i>
					</a>
					<a href="form_update_manufacturers.php?id=<?php echo $array_manufacturers['id'] ?>" class="btn-action btn-edit" title="Chỉnh sửa">
						<i class="fas fa-edit"></i>
					</a>
					<a href="process_delete_manufacturers.php?id=<?php echo $array_manufacturers['id'] ?>" class="btn-action btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa nhà sản xuất này?')">
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
		<a href="?page=<?php echo $page_num?>&search=<?php echo $content_search ?>" 
		   class="<?php echo ($page_num == $index_page) ? 'active' : '' ?>">
			<?php echo $page_num ?>
		</a>
	<?php } ?>
</div>

<style>
/* Search Box Modern */
.search-box-modern {
	background: white;
	padding: 20px;
	border-radius: 12px;
	margin-bottom: 20px;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.search-form-modern {
	display: flex;
	gap: 15px;
	align-items: center;
}

.search-input-wrapper {
	flex: 1;
	position: relative;
}

.search-input-wrapper i {
	position: absolute;
	right: 15px;
	top: 50%;
	transform: translateY(-50%);
	color: #718096;
	font-size: 16px;
	pointer-events: none;
}

.search-input-wrapper input {
	width: 100%;
	padding: 12px 45px 12px 15px;
	border: 2px solid #e2e8f0;
	border-radius: 10px;
	font-size: 15px;
	transition: all 0.3s;
}

.search-input-wrapper input:focus {
	outline: none;
	border-color: #667eea;
	box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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

.btn-search-modern i {
	font-size: 16px;
}

/* Manufacturer Styles */
.manufacturer-name {
	display: flex;
	align-items: center;
	font-size: 15px;
}

.manufacturer-image {
	width: 80px;
	height: 80px;
	border-radius: 12px;
	overflow: hidden;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
	margin: 0 auto;
}

.manufacturer-image img {
	width: 100%;
	height: 100%;
	object-fit: cover;
}
</style>

<?php require '../footer.php'; ?>