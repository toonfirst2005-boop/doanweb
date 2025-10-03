<?php 
require '../check_super_admin_login.php';
require '../connect_database.php';
if ( empty($_GET['page']) ) {
	$index_page = 1;
} else {
	$index_page = $_GET['page'];	
}

if ( isset($_GET['search']) ) {
	$content_search = $_GET['search'];
} else {
	$content_search = '';
}

// lấy ra tổng số hoạt động
$sql_select_count_admins = "SELECT count(*) FROM admins WHERE name LIKE '%$content_search%' AND status = 1 ";
$count_admins = mysqli_fetch_array(mysqli_query($connect_database, $sql_select_count_admins))['count(*)'] ;
// số hoạt động trên 1 trang
$admins_per_page = 14;
// số trang skip khi chuyển trang
$admins_skipped = ( $index_page - 1 ) * $admins_per_page;
//lấy ra tổng số trang
$pages = ceil($count_admins / $admins_per_page);

$sql_select_admins = "
	SELECT admins.*, IFNULL(MAX(activities.time), 'Chưa hoạt động lần nào') as 'last_time'
	FROM admins
	LEFT JOIN activities ON admins.id = activities.admin_id
	WHERE admins.name LIKE '%$content_search%' AND admins.status = 1
	GROUP BY admins.id
	LIMIT $admins_per_page OFFSET $admins_skipped
";

$query_sql_select_admins = mysqli_query($connect_database, $sql_select_admins);

// Page configuration
$page_title = 'Quản lý Quản trị viên - Admin Panel';
$show_search = true;
$search_placeholder = 'Nhập tên quản trị viên...';
require '../header.php';
?>

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-user-shield"></i>
		</div>
		<div class="page-info">
			<h1>Quản lý Quản trị viên</h1>
			<p>Quản lý nhân viên và quản trị viên hệ thống</p>
		</div>
	</div>
	<a href="form_insert_admin.php" class="btn-add-new">
		<i class="fas fa-user-plus"></i>
		Thêm Admin
	</a>
</div>

<div class="search-bar-modern">
	<form method="GET" class="search-form-modern">
		<i class="fas fa-search search-icon"></i>
		<input type="search" name="search" placeholder="Nhập tên quản trị viên..." value="<?php echo $content_search ?>">
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
			<th>TÊN QUẢN TRỊ VIÊN</th>
			<th>EMAIL</th>
			<th>CHỨC VỤ</th>
			<th>LẦN CUỐI HOẠT ĐỘNG</th>
			<th>THAO TÁC</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($query_sql_select_admins as $each_admin): ?>
		<tr>
			<td><span class="badge-id">#<?php echo $each_admin['id'] ?></span></td>
			<td>
				<div class="customer-info">
					<i class="fas fa-user-circle customer-avatar"></i>
					<span><?php echo $each_admin['name'] ?></span>
				</div>
			</td>
			<td>
				<i class="fas fa-envelope" style="color: #718096; margin-right: 5px;"></i>
				<?php echo $each_admin['email'] ?>
			</td>
			<td>
				<?php if ( $each_admin['level'] == 1 ) { ?>
					<span class="badge-money" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
						<i class="fas fa-crown"></i> Quản lý
					</span>
				<?php } else { ?>
					<span class="badge-id" style="background: #e2e8f0; color: #4a5568;">
						<i class="fas fa-user"></i> Nhân viên
					</span>
				<?php } ?>
			</td>
			<td>
				<i class="fas fa-clock" style="color: #718096; margin-right: 5px;"></i>
				<?php echo $each_admin['last_time'] ?>
			</td>
			<td>
				<div class="action-buttons">
					<a href="detail_admin.php?id=<?php echo $each_admin['id'] ?>" class="btn-action btn-view" title="Xem chi tiết">
						<i class="fas fa-eye"></i>
					</a>
					<a href="view_activities.php?id=<?php echo $each_admin['id'] ?>" class="btn-action btn-edit" title="Xem lịch sử">
						<i class="fas fa-history"></i>
					</a>
					<?php if ( $each_admin['level'] != 1 ) { ?>
					<a href="kick_admin.php?id=<?php echo $each_admin['id'] ?>" class="btn-action btn-delete" title="Sa thải" onclick="return confirm('Bạn có chắc muốn sa thải quản trị viên này?')">
						<i class="fas fa-user-times"></i>
					</a>
					<?php } ?>
				</div>
			</td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
	
	<div class="pagination">
		<?php for ($page_num = 1; $page_num <= $pages; $page_num++) { ?>
			<a href="?page=<?php echo $page_num?>&search=<?php echo $content_search ?>" 
			   class="<?php echo ($page_num == $index_page) ? 'active' : '' ?>">
				<?php echo $page_num ?>
			</a>
		<?php } ?>
	</div>

<?php require '../footer.php'; ?>