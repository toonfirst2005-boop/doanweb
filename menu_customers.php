<nav class="navbar">
	<div class="nav-container">
		<div class="nav-logo">
			<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
				<line x1="3" y1="6" x2="21" y2="6"></line>
				<path d="M16 10a4 4 0 0 1-8 0"></path>
			</svg>
			<span class="logo-text">ShopModern</span>
		</div>
		
		<ul class="nav-menu">
			<li><a href="index_customers.php" class="nav-link">Trang chủ</a></li>
			<li><a href="#products" class="nav-link">Sản phẩm</a></li>
			<li><a href="#" class="nav-link">Tính năng</a></li>
			<li><a href="#footer" class="nav-link">Về chúng tôi</a></li>
		</ul>

		<div class="nav-actions">
			<?php if (empty($_SESSION['id'])) { ?>
				<a href="form_sign_in.php" class="btn-outline">Đăng nhập</a>
			<?php } else { ?>
				<div class="user-welcome" onclick="window.location.href='customer_profile.php'" style="cursor: pointer;">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
						<circle cx="12" cy="7" r="4"></circle>
					</svg>
					<span><?php echo $_SESSION['name'] ?></span>
				</div>
				<a href="view_cart.php" class="cart-icon">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<circle cx="9" cy="21" r="1"></circle>
						<circle cx="20" cy="21" r="1"></circle>
						<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
					</svg>
					<span class="cart-badge" id="cart-count">
						<?php 
						$cart_count = 0;
						if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
							foreach ($_SESSION['cart'] as $item) {
								$cart_count += $item['quantity'];
							}
						}
						echo $cart_count;
						?>
					</span>
				</a>
				<a href="process_sign_out.php" class="btn-outline">Đăng xuất</a>
			<?php } ?>
		</div>
	</div>
</nav>
