<?php 
session_start();
require_once 'update_session_name.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>Shop Online - Trang chủ</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style_index_customers.css">
	<link rel="stylesheet" href="admin/style_validate.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<?php require 'admin/validate.php' ?>
<?php require 'menu_customers.php' ?>

<!-- Hero Section -->
<section class="hero-section">
	<div class="hero-container">
		<div class="hero-content">
			<h1 class="hero-title">
				Mua sắm thông minh<br>
				với <span class="text-gradient">ShopModern</span>
			</h1>
			<p class="hero-description">
				Khám phá hàng ngàn sản phẩm chất lượng cao với giá tốt nhất. Giao hàng nhanh chóng, thanh toán an toàn.
			</p>
			<div class="hero-buttons">
				<a href="#products" class="btn-hero-primary">Khám phá ngay</a>
				<a href="#" class="btn-hero-outline">Tìm hiểu thêm</a>
			</div>
		</div>
		<div class="hero-product-card">
			<?php
			require 'admin/connect_database.php';
			$featured_sql = "SELECT * FROM products WHERE (is_deleted IS NULL OR is_deleted = 0) ORDER BY id DESC LIMIT 1";
			$featured_result = mysqli_query($connect_database, $featured_sql);
			$featured = mysqli_fetch_array($featured_result);
			$img_path = "/banhang/admin/products/";
			?>
			<div class="featured-product">
				<img src="<?php echo $img_path . $featured['image']; ?>" alt="<?php echo $featured['name']; ?>">
				<div class="featured-info">
					<h3><?php echo $featured['name']; ?></h3>
					<p class="featured-price"><?php echo number_format($featured['price'], 0, ',', '.'); ?>₫</p>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Products Section -->
<section id="products" class="products-section">
	<?php require 'products_customers.php' ?>
</section>

<?php require 'footer_customers.php' ?>

<!-- Toast Notification -->
<div id="toast-notification" class="toast-notification">
	<div class="toast-icon">✓</div>
	<div class="toast-message">Thành công thêm vào giỏ hàng!</div>
</div>

<style>
.toast-notification {
	position: fixed;
	top: -100px;
	right: 30px;
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	color: white;
	padding: 18px 28px;
	border-radius: 12px;
	box-shadow: 0 10px 40px rgba(102, 126, 234, 0.4);
	display: flex;
	align-items: center;
	gap: 15px;
	z-index: 10000;
	transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
	font-weight: 500;
	font-size: 15px;
}

.toast-notification.show {
	top: 30px;
}

.toast-icon {
	width: 32px;
	height: 32px;
	background: rgba(255, 255, 255, 0.25);
	border-radius: 50%;
	display: flex;
	align-items: center;
	justify-content: center;
	font-size: 20px;
	font-weight: bold;
}
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	// Scroll to top on page load
	window.scrollTo(0, 0);
	
	// Smooth scroll to sections
	$('a[href^="#"]').click(function(e) {
		var target = $(this).attr('href');
		if (target !== '#' && $(target).length) {
			e.preventDefault();
			$('html, body').animate({
				scrollTop: $(target).offset().top - 80
			}, 800);
		}
	});
	
	$(".button-add-to-cart").click(function() {
		var id = $(this).data("id");
		
		$.ajax({
			url: 'process_add_to_cart.php',
			type: 'get',
			dataType: 'html',
			data: {id: id},
		})
		.done(function(data) {
			if (data.trim() == '1') { 
				// Show toast notification
				$('#toast-notification').addClass('show');
				
				// Update cart count
				var currentCount = parseInt($('#cart-count').text()) || 0;
				$('#cart-count').text(currentCount + 1);
				
				// Hide toast after 3 seconds
				setTimeout(function() {
					$('#toast-notification').removeClass('show');
				}, 3000);
			} else {
				alert("Lỗi: " + data);
			}
		})
		.fail(function() {
			alert("Có lỗi xảy ra. Vui lòng thử lại!");
		});
	});
});
</script>
</html>
