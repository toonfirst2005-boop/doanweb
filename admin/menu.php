<div class="l-navbar" id="navbar">
	<nav class="nav">
		<div class="nav__brand">
			<div class="nav__toggle" id="nav-toggle">
				<i class="fas fa-bars"></i>
			</div>
			<a href="../root/index.php" class="nav__logo">Admin Panel</a>
		</div>
		
		<div class="nav__list">
			<a href="../root/index.php" class="nav__link" data-tooltip="Dashboard">
				<i class="fas fa-chart-line nav__icon"></i>
				<span class="nav__name">Dashboard</span>
			</a>
			
			<a href="../products/index_products.php" class="nav__link" data-tooltip="Sản phẩm">
				<i class="fas fa-box nav__icon"></i>
				<span class="nav__name">Sản phẩm</span>
			</a>

			<a href="../manufacturers/index_manufacturers.php" class="nav__link" data-tooltip="Nhà sản xuất">
				<i class="fas fa-industry nav__icon"></i>
				<span class="nav__name">Nhà sản xuất</span>
			</a>

			<a href="../receipts/index.php" class="nav__link" data-tooltip="Đơn hàng">
				<i class="fas fa-receipt nav__icon"></i>
				<span class="nav__name">Đơn hàng</span>
			</a>

			<a href="../customers/index.php" class="nav__link" data-tooltip="Khách hàng">
				<i class="fas fa-users nav__icon"></i>
				<span class="nav__name">Khách hàng</span>
			</a>

			<a href="../admins/index.php" class="nav__link" data-tooltip="Quản trị viên">
				<i class="fas fa-user-shield nav__icon"></i>
				<span class="nav__name">Quản trị viên</span>
			</a>

			<a href="../process_log_out_admin.php" class="nav__link" data-tooltip="Đăng xuất">
				<i class="fas fa-sign-out-alt nav__icon"></i>
				<span class="nav__name">Đăng xuất</span>
			</a>
		</div>
	</nav>
</div>

<script type="text/javascript">
	// Toggle sidebar expand/collapse
	const toggle = document.getElementById('nav-toggle');
	const navbar = document.getElementById('navbar');
	
	if(toggle && navbar){
		toggle.addEventListener('click', () => {
			navbar.classList.toggle('expander');
		});
	}

	// Active link highlighting
	const currentPath = window.location.pathname;
	const linkColor = document.querySelectorAll('.nav__link');
	
	linkColor.forEach(link => {
		// Check if link href matches current path
		if(link.getAttribute('href') && currentPath.includes(link.getAttribute('href').replace('../', ''))){
			link.classList.add('active');
		}
		
		// Add click handler for active state
		link.addEventListener('click', function(e){
			// Only prevent default if it's not a real link
			if(!this.getAttribute('href') || this.getAttribute('href') === '#'){
				e.preventDefault();
			}
			linkColor.forEach(l => l.classList.remove('active'));
			this.classList.add('active');
		});
	});
</script>