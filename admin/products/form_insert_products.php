<?php 
require '../check_admin_login.php';
require '../connect_database.php';

$sql_select_manufacturees = "select * from manufacturers";
$query_sql_select_manufacturees = mysqli_query($connect_database, $sql_select_manufacturees);
mysqli_close($connect_database);

// Page configuration
$page_title = 'Thêm Sản phẩm - Admin Panel';
$page_heading = 'Thêm Sản phẩm';
require '../header.php';
?>

<link rel="stylesheet" href="../style_modern_form.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="bootstrap-tagsinput-latest/dist/bootstrap-tagsinput.css">

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-box"></i>
		</div>
		<div class="page-info">
			<h1>Thêm sản phẩm mới</h1>
			<p>Thêm sản phẩm mới vào hệ thống</p>
		</div>
	</div>
	<a href="index_products.php" class="btn-add-new" style="background: linear-gradient(135deg, #718096 0%, #4a5568 100%);">
		<i class="fas fa-arrow-left"></i>
		Quay lại
	</a>
</div>

<?php require '../validate.php' ?>

<div class="form-container">
	<form method="post" action="process_insert_products.php" enctype="multipart/form-data" id="form-insert-products">
		<div class="form-grid">
			<div class="form-group">
				<label class="form-label">
					<i class="fas fa-tag"></i>
					Tên sản phẩm <span class="required">*</span>
				</label>
				<input type="text" name="name" class="form-input" placeholder="Nhập tên sản phẩm">
			</div>

			<div class="form-group">
				<label class="form-label">
					<i class="fas fa-dollar-sign"></i>
					Giá thành <span class="required">*</span>
				</label>
				<div class="currency-input-wrapper">
					<input type="text" name="price" id="price-input" class="form-input" placeholder="Nhập giá sản phẩm">
					<span class="currency-symbol">₫</span>
				</div>
				<p class="form-hint">Nhập số tiền, hệ thống sẽ tự động định dạng</p>
			</div>

			<div class="form-group full-width">
				<label class="form-label">
					<i class="fas fa-align-left"></i>
					Mô tả
				</label>
				<textarea name="description" class="form-textarea" placeholder="Nhập mô tả sản phẩm"></textarea>
			</div>

			<div class="form-group">
				<label class="form-label">
					<i class="fas fa-industry"></i>
					Nhà sản xuất <span class="required">*</span>
				</label>
				<select name="manufacturer_id" class="form-select">
					<option value="">-- Chọn nhà sản xuất --</option>
					<?php foreach ($query_sql_select_manufacturees as $array_manufacturers): ?>
						<option value="<?php echo $array_manufacturers['id'] ?>">
							<?php echo $array_manufacturers['name'] ?>
						</option>
					<?php endforeach ?>
				</select>
			</div>

			<div class="form-group">
				<label class="form-label">
					<i class="fas fa-image"></i>
					Hình ảnh <span class="required">*</span>
				</label>
				<input type="file" name="image" id="image-input" class="form-file-input" accept=".jpg,.jpeg,.png,.gif,.bmp,.webp,.svg,.tiff,.tif,.ico,.heic,.heif">
				<label for="image-input" class="form-file-label">
					<i class="fas fa-cloud-upload-alt"></i>
					<span>Chọn hình ảnh</span>
				</label>
				<p class="form-hint">Hỗ trợ: JPG, JPEG, PNG, GIF, BMP, WEBP, SVG, TIFF, ICO, HEIC, HEIF (Tối đa 5MB)</p>
			</div>

			<div class="form-group full-width">
				<label class="form-label">
					<i class="fas fa-tags"></i>
					Thể loại (Tùy chọn)
				</label>
				<input type="text" name="types_name" id="type" class="form-input" placeholder="Nhập thể loại (phân cách bằng dấu phẩy)">
				<p class="form-hint">Không bắt buộc. Nhập nhiều thể loại, phân cách bằng dấu phẩy</p>
			</div>
		</div>

		<div class="form-actions">
			<button type="submit" class="btn-submit">
				<i class="fas fa-save"></i>
				Lưu sản phẩm
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
<script src="bootstrap-tagsinput-latest/dist/bootstrap-tagsinput.js"></script>
<script src="typeahead.bundle.js"></script>
<script src="form_insert_products.js"></script>
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
	
	// Remove all non-numeric characters except decimal point
	value = value.replace(/[^\d]/g, '');
	
	// Format with thousand separators
	if (value) {
		value = parseInt(value).toLocaleString('vi-VN');
	}
	
	e.target.value = value;
});

// Remove formatting before submit
document.getElementById('form-insert-products').addEventListener('submit', function(e) {
	const priceValue = priceInput.value.replace(/[^\d]/g, '');
	priceInput.value = priceValue;
});
</script>

<?php require '../footer.php'; ?>