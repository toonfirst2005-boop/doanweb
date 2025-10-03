<?php 
require '../check_admin_login.php';
require '../connect_database.php';

if (empty($_GET['id'])){
	$_SESSION['error'] = 'Chưa nhập id sản phẩm cần sửa';
	header('location:index_products.php');
	exit;
}

$id = $_GET['id'];

if ( isset($_GET['type_id']) ) {
	$type_id = $_GET['type_id'];
}
//kiểm tra id nhập vào có đúng
$sql_command_select = "select * from products where id = '$id' ";
$query_sql_command_select = mysqli_query($connect_database, $sql_command_select);
$array_products = mysqli_fetch_array($query_sql_command_select);
$check = mysqli_num_rows($query_sql_command_select);
if ( $check !== 1 ) {
	$_SESSION['error'] = 'Sai id sẩn phẩm';
	header('location:index_products.php');
	exit();
}


$sql_command_select_products = "select * from products where id = '$id' ";
$query_sql_command_select_products = mysqli_query($connect_database, $sql_command_select_products);

$sql_command_select_manufacturers = "select * from manufacturers";
$query_sql_command_select_manufacturers = mysqli_query($connect_database, $sql_command_select_manufacturers);

$array_products = mysqli_fetch_array($query_sql_command_select_products);
mysqli_close($connect_database);

// Page configuration
$page_title = 'Sửa Sản phẩm - Admin Panel';
$page_heading = 'Sửa Sản phẩm';
require '../header.php';
?>

<link rel="stylesheet" href="../style_modern_form.css">

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-edit"></i>
		</div>
		<div class="page-info">
			<h1>Chỉnh sửa sản phẩm</h1>
			<p>Cập nhật thông tin sản phẩm</p>
		</div>
	</div>
	<a href="index_products.php" class="btn-add-new" style="background: linear-gradient(135deg, #718096 0%, #4a5568 100%);">
		<i class="fas fa-arrow-left"></i>
		Quay lại
	</a>
</div>

<?php require '../validate.php' ?>

<div class="form-container">
	<form method="post" action="process_update_products.php" enctype="multipart/form-data" id="form-update-products">
		<input type="hidden" name="id" value="<?php echo $array_products['id'] ?>">
		<?php if ( isset($_GET['type_id']) ) { ?>
			<input type="hidden" name="type_id" value="<?php echo $type_id; ?>">
		<?php } ?>
		<input type="hidden" name="image_old" value="<?php echo $array_products['image'] ?>">
		
		<div class="form-grid">
			<div class="form-group">
				<label class="form-label">
					<i class="fas fa-tag"></i>
					Tên sản phẩm <span class="required">*</span>
				</label>
				<input type="text" name="name" class="form-input" value="<?php echo $array_products['name'] ?>" placeholder="Nhập tên sản phẩm">
			</div>

			<div class="form-group">
				<label class="form-label">
					<i class="fas fa-dollar-sign"></i>
					Giá thành <span class="required">*</span>
				</label>
				<div class="currency-input-wrapper">
					<input type="text" name="price" id="price-input" class="form-input" value="<?php echo number_format($array_products['price'], 0, ',', '.') ?>" placeholder="Nhập giá sản phẩm">
					<span class="currency-symbol">₫</span>
				</div>
				<p class="form-hint">Nhập số tiền, hệ thống sẽ tự động định dạng</p>
			</div>

			<div class="form-group full-width">
				<label class="form-label">
					<i class="fas fa-align-left"></i>
					Mô tả
				</label>
				<textarea name="description" class="form-textarea" placeholder="Nhập mô tả sản phẩm"><?php echo $array_products['description'] ?></textarea>
			</div>

			<div class="form-group">
				<label class="form-label">
					<i class="fas fa-industry"></i>
					Nhà sản xuất <span class="required">*</span>
				</label>
				<select name="manufacturer_id" class="form-select">
					<?php foreach ($query_sql_command_select_manufacturers as $array_manufacturers): ?>
						<option 
							value="<?php echo $array_manufacturers['id'] ?>"
							<?php if($array_products['manufacturer_id'] == $array_manufacturers['id']) { ?>selected<?php } ?>
						>
							<?php echo $array_manufacturers['name'] ?>
						</option>
					<?php endforeach ?>
				</select>
			</div>

			<div class="form-group">
				<label class="form-label">
					<i class="fas fa-image"></i>
					Đổi hình ảnh
				</label>
				<input type="file" name="image_new" id="image-input" class="form-file-input" accept=".jpg,.jpeg,.png,.gif,.bmp,.webp,.svg,.tiff,.tif,.ico,.heic,.heif">
				<label for="image-input" class="form-file-label">
					<i class="fas fa-cloud-upload-alt"></i>
					<span>Chọn hình ảnh mới</span>
				</label>
				<p class="form-hint">Để trống nếu giữ nguyên hình ảnh cũ. Hỗ trợ: JPG, PNG, GIF, BMP, WEBP, SVG, TIFF, ICO, HEIC, HEIF (Tối đa 5MB)</p>
			</div>

			<div class="form-group full-width">
				<label class="form-label">
					<i class="fas fa-image"></i>
					Hình ảnh hiện tại
				</label>
				<div class="image-preview">
					<img src="<?php echo $array_products['image'] ?>" alt="Product Image">
				</div>
			</div>
		</div>

		<div class="form-actions">
			<button type="submit" class="btn-submit">
				<i class="fas fa-save"></i>
				Cập nhật sản phẩm
			</button>
			<a href="index_products.php" class="btn-cancel">
				<i class="fas fa-times"></i>
				Hủy
			</a>
		</div>
	</form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script src="form_update_products.js"></script>
<script>
// Preview image
document.getElementById('image-input').addEventListener('change', function(e) {
	const file = e.target.files[0];
	if (file) {
		document.querySelector('.form-file-label span').textContent = file.name;
	}
});

// Currency input formatting
const priceInput = document.getElementById('price-input');

priceInput.addEventListener('input', function(e) {
	let value = e.target.value;
	
	// Remove all non-numeric characters
	value = value.replace(/[^\d]/g, '');
	
	// Format with thousand separators
	if (value) {
		value = parseInt(value).toLocaleString('vi-VN');
	}
	
	e.target.value = value;
});

// Remove formatting before submit
document.getElementById('form-update-products').addEventListener('submit', function(e) {
	const priceValue = priceInput.value.replace(/[^\d]/g, '');
	priceInput.value = priceValue;
});
</script>

<?php require '../footer.php'; ?>