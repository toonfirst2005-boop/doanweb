	<?php session_start(); ?>

	<!DOCTYPE html>
	<html>
	<head>
		<title>Shop Online - Trang chủ</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="style_index_customers.css">
		<link rel="stylesheet" href="admin/style_validate.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
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
				$featured_sql = "SELECT * FROM products ORDER BY id DESC LIMIT 1";
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
	<?php require 'footer_customers.php' ?>


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		$(".button-add-to-cart").click(function() {
			var id = $(this).data("id");
			
			$.ajax({
				url: 'process_add_to_cart.php',
				dataType: 'html',
				data: {id},
			})
			.done(function(data) {
				if ( data == 1 ) { 
					console.log("Thanh cong")
				} else {
					console.log(data)
				}
				
			})
		})


	})
	</script>


	</body>
	</html>