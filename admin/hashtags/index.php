<?php 
require '../check_admin_login.php';
require '../connect_database.php';
if ( isset($_GET['page']) ) {
	$index_page = $_GET['page'];
} else {
	$index_page = 1;
}

if ( isset($_GET['search']) ) {
	$content_search = $_GET['search'];
} else {
	$content_search = '';
}

$sql_count_hashtags = "SELECT count(*) FROM types WHERE name LIKE '%$content_search%'";
$count_hashtags = mysqli_fetch_array(mysqli_query($connect_database, $sql_count_hashtags))['count(*)'] ;
$limit_hashtags_per_page = 7;
$count_pages = ceil($count_hashtags / $limit_hashtags_per_page );
$hashtags_skip_by_page = ( $index_page - 1 ) * $limit_hashtags_per_page;


$sql_select = "
	SELECT * FROM types 
	WHERE name LIKE '%$content_search%'
	ORDER BY id DESC
	LIMIT $limit_hashtags_per_page
	OFFSET $hashtags_skip_by_page
";
$query_sql_select = mysqli_query($connect_database, $sql_select);

// Page configuration
$page_title = 'Quản lý Thẻ - Admin Panel';
$show_search = true;
$search_placeholder = 'Nhập tên thẻ...';
require '../header.php';
?>

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-tags"></i>
		</div>
		<div class="page-info">
			<h1>Quản lý Gắn thẻ</h1>
			<p>Quản lý thẻ và phân loại sản phẩm</p>
		</div>
	</div>
	<a href="form_insert_hashtag.php" class="btn-add-new">
		<i class="fas fa-plus"></i>
		Thêm thẻ mới
	</a>
</div>

<div class="search-bar-modern">
	<form method="GET" class="search-form-modern">
		<i class="fas fa-search search-icon"></i>
		<input type="search" name="search" placeholder="Nhập tên thẻ..." value="<?php echo $content_search ?>">
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
			<th>TÊN THẺ</th>
			<th>THAO TÁC</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($query_sql_select as $each_type) { ?>
		<tr>
			<td><span class="badge-id">#<?php echo $each_type['id'] ?></span></td>
			<td>
				<div class="customer-info">
					<i class="fas fa-tag customer-avatar" style="color: #667eea;"></i>
					<span><?php echo $each_type['name'] ?></span>
				</div>
			</td>
			<td>
				<div class="action-buttons">
					<a href="products_linked_hashtag.php?id=<?php echo $each_type['id'] ?>" class="btn-action btn-view" title="Xem sản phẩm">
						<i class="fas fa-box"></i>
					</a>
					<a href="index_insert_products_to_hashtag.php?type_id=<?php echo $each_type['id']?>" class="btn-action" style="background: #48bb78; color: white;" title="Thêm sản phẩm">
						<i class="fas fa-plus"></i>
					</a>
					<a href="form_change_name.php?id=<?php echo $each_type['id'] ?>" class="btn-action btn-edit" title="Đổi tên">
						<i class="fas fa-edit"></i>
					</a>
					<a href="delete_type.php?id=<?php echo $each_type['id'] ?>" class="btn-action btn-delete" title="Xóa thẻ" onclick="return confirm('Bạn có chắc muốn xóa thẻ này?')">
						<i class="fas fa-trash"></i>
					</a>
				</div>
			</td>
		</tr>
		<?php } ?>
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

<?php require '../footer.php'; ?>