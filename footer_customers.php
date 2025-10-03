<footer class="bot" id="footer">
	<div class="footer-content">
		<div class="footer-section">
			<h3>ShopModern</h3>
			<p>Nền tảng mua sắm trực tuyến hàng đầu Việt Nam. Mang đến trải nghiệm mua sắm tuyệt vời với hàng ngàn sản phẩm chất lượng.</p>
			<div class="social-links">
				<a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
				<a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
				<a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
				<a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
				<a href="#" class="social-icon"><i class="fab fa-tiktok"></i></a>
			</div>
		</div>
		<div class="footer-section">
			<h4>Về chúng tôi</h4>
			<ul>
				<li><a href="#">Giới thiệu</a></li>
				<li><a href="#">Tuyển dụng</a></li>
				<li><a href="#">Chính sách bảo mật</a></li>
			</ul>
		</div>
		<div class="footer-section">
			<h4>Hỗ trợ khách hàng</h4>
			<ul>
				<li><a href="#">Trung tâm trợ giúp</a></li>
				<li><a href="#">Hướng dẫn mua hàng</a></li>
				<li><a href="#">Chính sách đổi trả</a></li>
				<li><a href="#">Liên hệ</a></li>
			</ul>
		</div>
		<div class="footer-section">
			<h4>Liên hệ</h4>
			<ul>
				<li>📧 Email: support@shopmodern.vn</li>
				<li>📞 Hotline: 1900 xxxx</li>
				<li>📍 Địa chỉ: Hà Nội, Việt Nam</li>
			</ul>
		</div>
	</div>
	<div class="footer-bottom">
		<p>&copy; 2025 ShopModern. All Rights Reserved. Designed by DucNguyen</p>
	</div>
</footer>

<style>
.bot {
	background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
	color: white;
	padding: 60px 40px 20px;
}

.footer-content {
	max-width: 1400px;
	margin: 0 auto;
	display: grid;
	grid-template-columns: 2fr 1fr 1fr 1fr;
	gap: 40px;
	margin-bottom: 40px;
}

.footer-section h3 {
	font-size: 24px;
	margin-bottom: 15px;
	background: linear-gradient(135deg, #ff6b9d 0%, #c44569 100%);
	-webkit-background-clip: text;
	-webkit-text-fill-color: transparent;
	background-clip: text;
}

.footer-section h4 {
	font-size: 18px;
	margin-bottom: 15px;
	color: #fff;
	font-weight: 600;
}

.footer-section p {
	color: rgba(255, 255, 255, 0.85);
	line-height: 1.6;
	font-size: 14px;
	margin-bottom: 20px;
}

.social-links {
	display: flex;
	gap: 12px;
	margin-top: 20px;
}

.social-icon {
	width: 40px;
	height: 40px;
	border-radius: 50%;
	background: rgba(255, 255, 255, 0.1);
	display: flex;
	align-items: center;
	justify-content: center;
	color: white;
	text-decoration: none;
	transition: all 0.3s ease;
	font-size: 18px;
}

.social-icon:hover {
	background: linear-gradient(135deg, #ff6b9d 0%, #c44569 100%);
	transform: translateY(-3px);
	box-shadow: 0 5px 15px rgba(255, 107, 157, 0.4);
}

.footer-section ul {
	list-style: none;
	padding: 0;
	margin: 0;
}

.footer-section ul li {
	margin-bottom: 10px;
}

.footer-section ul li {
	color: rgba(255, 255, 255, 0.85);
	font-size: 14px;
}

.footer-section ul li a {
	color: rgba(255, 255, 255, 0.85);
	text-decoration: none;
	font-size: 14px;
	transition: color 0.3s ease;
}

.footer-section ul li a:hover {
	color: #ff6b9d;
}

.footer-bottom {
	text-align: center;
	padding-top: 20px;
	border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.footer-bottom p {
	margin: 0;
	font-size: 14px;
	color: rgba(255, 255, 255, 0.5);
}

@media (max-width: 768px) {
	.footer-content {
		grid-template-columns: 1fr;
		gap: 30px;
	}
	
	.bot {
		padding: 40px 20px 20px;
	}
}
</style>