<?php 
require '../check_super_admin_login.php';
require '../connect_database.php';
if ( empty($_GET['page']) ) {
	$index_page = 1;
} else {
	$index_page = $_GET['page'];	
}

// lấy ra tổng số hoạt động
$sql_select_count_activity = "SELECT count(*) FROM activities";
$count_activities = mysqli_fetch_array(mysqli_query($connect_database, $sql_select_count_activity))['count(*)'] ;
// số hoạt động trên 1 trang
$activities_per_page = 14;
// số trang skip khi chuyển trang
$activities_skipped = ( $index_page - 1 ) * $activities_per_page;
//lấy ra tổng số trang
$pages = ceil($count_activities / $activities_per_page);

$sql_select_activity = "
	SELECT activities.*, admins.name as 'admin_name'
	FROM activities 
	LEFT JOIN admins on admins.id = activities.admin_id
	ORDER BY id DESC
	LIMIT $activities_per_page OFFSET $activities_skipped
";
$query_sql_select_activity = mysqli_query($connect_database, $sql_select_activity);

// Page configuration
$page_title = 'Lịch sử hoạt động - Admin Panel';
$page_heading = 'Lịch sử hoạt động';
require '../header.php';
?>

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-history"></i>
		</div>
		<div class="page-info">
			<h1>Lịch sử Hoạt động</h1>
			<p>Theo dõi tất cả hoạt động của quản trị viên</p>
		</div>
	</div>
</div>

<table class="table">
	<thead>
		<tr>
			<th>MÃ</th>
			<th>NGƯỜI THAO TÁC</th>
			<th>HÀNH ĐỘNG</th>
			<th>ĐỐI TƯỢNG</th>
			<th>TÊN ĐỐI TƯỢNG</th>
			<th>THỜI GIAN</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($query_sql_select_activity as $each_activity): ?>
		<tr>
			<td><span class="badge-id">#<?php echo $each_activity['id'] ?></span></td>
			<td>
				<div class="customer-info">
					<i class="fas fa-user-circle customer-avatar"></i>
					<span><?php echo $each_activity['admin_name'] ?></span>
				</div>
			</td>
			<td>
				<span class="badge-id" style="background: #e2e8f0; color: #4a5568;">
					<?php echo 'đã ' . $each_activity['activity'] ?>
				</span>
			</td>
			<td><?php echo $each_activity['object'] ?></td>
			<td><?php echo $each_activity['object_name'] ?></td>
			<td>
				<i class="fas fa-clock" style="color: #718096; margin-right: 5px;"></i>
				<?php echo $each_activity['time'] ?>
			</td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>

<div class="pagination">
	<?php for ($page_num = 1; $page_num <= $pages; $page_num++) { ?>
		<a href="?page=<?php echo $page_num?>" 
		   class="<?php echo ($page_num == $index_page) ? 'active' : '' ?>">
			<?php echo $page_num ?>
		</a>
	<?php } ?>
</div>

<?php require '../footer.php'; ?>