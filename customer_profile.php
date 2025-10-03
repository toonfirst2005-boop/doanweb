<?php 
session_start();

if (empty($_SESSION['id'])) {
	header('location:form_sign_in.php');
	exit;
}

require 'admin/connect_database.php';

// Get customer info
$customer_id = $_SESSION['id'];
$sql_customer = "SELECT * FROM customers WHERE id = '$customer_id'";
$result_customer = mysqli_query($connect_database, $sql_customer);
$customer = mysqli_fetch_array($result_customer);

// Update session name to match database
$_SESSION['name'] = $customer['name'];

// Get customer orders
$sql_orders = "SELECT * FROM receipts WHERE customer_id = '$customer_id' ORDER BY order_time DESC";
$result_orders = mysqli_query($connect_database, $sql_orders);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Thông tin tài khoản - ShopModern</title>
	<link rel="stylesheet" href="style_index_customers.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	<style>
		.profile-container {
			max-width: 1200px;
			margin: 40px auto;
			padding: 20px;
		}

		.profile-header {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: white;
			padding: 40px;
			border-radius: 20px;
			margin-bottom: 30px;
			box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
		}

		.profile-header h1 {
			margin: 0 0 10px 0;
			font-size: 32px;
		}

		.profile-header p {
			margin: 0;
			opacity: 0.9;
		}

		.info-card {
			background: white;
			padding: 30px;
			border-radius: 16px;
			box-shadow: 0 4px 20px rgba(0,0,0,0.08);
			margin-bottom: 30px;
		}

		.info-card h2 {
			color: #333;
			margin: 0 0 20px 0;
			font-size: 24px;
			border-bottom: 2px solid #f0f0f0;
			padding-bottom: 10px;
		}

		.info-grid {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
			gap: 20px;
		}

		.info-item {
			display: flex;
			flex-direction: column;
			gap: 5px;
		}

		.info-label {
			color: #666;
			font-size: 14px;
			font-weight: 500;
		}

		.info-value {
			color: #333;
			font-size: 16px;
			font-weight: 600;
		}

		.orders-table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 20px;
		}

		.orders-table th {
			background: #f8f9fa;
			padding: 15px;
			text-align: left;
			font-weight: 600;
			color: #333;
			border-bottom: 2px solid #dee2e6;
		}

		.orders-table td {
			padding: 15px;
			border-bottom: 1px solid #f0f0f0;
		}

		.orders-table tr:hover {
			background: #f8f9fa;
		}

		.status-badge {
			display: inline-block;
			padding: 6px 12px;
			border-radius: 20px;
			font-size: 13px;
			font-weight: 600;
		}

		.status-pending {
			background: #fff3cd;
			color: #856404;
		}

		.status-processing {
			background: #cfe2ff;
			color: #084298;
		}

		.status-completed {
			background: #d1e7dd;
			color: #0f5132;
		}

		.status-cancelled {
			background: #f8d7da;
			color: #842029;
		}

		.no-orders {
			text-align: center;
			padding: 40px;
			color: #999;
		}

		.back-btn {
			display: inline-block;
			padding: 12px 24px;
			background: linear-gradient(135deg, #ff6b9d 0%, #c44569 100%);
			color: white;
			text-decoration: none;
			border-radius: 25px;
			font-weight: 600;
			margin-bottom: 20px;
			transition: all 0.3s ease;
		}

		.back-btn:hover {
			transform: translateY(-2px);
			box-shadow: 0 5px 15px rgba(255, 107, 157, 0.4);
			color: white;
		}

		.progress-container {
			width: 100%;
			height: 6px;
			background: #f0f0f0;
			border-radius: 3px;
			margin-top: 8px;
			overflow: hidden;
		}

		.progress-bar {
			height: 100%;
			border-radius: 3px;
			transition: width 0.3s ease;
		}

		.status-pending .progress-bar {
			background: linear-gradient(90deg, #ffc107, #ff8f00);
		}

		.status-processing .progress-bar {
			background: linear-gradient(90deg, #007bff, #0056b3);
		}

		.status-completed .progress-bar {
			background: linear-gradient(90deg, #28a745, #1e7e34);
		}

		.status-cancelled .progress-bar {
			background: linear-gradient(90deg, #dc3545, #c82333);
		}

		.status-with-progress {
			display: flex;
			flex-direction: column;
			gap: 5px;
		}

		.refresh-btn {
			background: #007bff;
			color: white;
			border: none;
			padding: 8px 16px;
			border-radius: 20px;
			cursor: pointer;
			font-size: 12px;
			transition: all 0.3s ease;
			margin-left: 10px;
		}

		.refresh-btn:hover {
			background: #0056b3;
			transform: scale(1.05);
		}

		.refresh-btn:disabled {
			background: #6c757d;
			cursor: not-allowed;
			transform: none;
		}

		.notification {
			position: fixed;
			top: 20px;
			right: 20px;
			padding: 15px 20px;
			border-radius: 10px;
			color: white;
			font-weight: 600;
			z-index: 1000;
			transform: translateX(400px);
			transition: transform 0.3s ease;
		}

		.notification.show {
			transform: translateX(0);
		}

		.notification.success {
			background: #28a745;
		}

		.notification.info {
			background: #17a2b8;
		}

		.order-detail {
			cursor: pointer;
			transition: background 0.2s ease;
		}

		.order-detail:hover {
			background: #f8f9fa !important;
		}
	</style>
</head>
<body>
	<?php require 'menu_customers.php'; ?>

	<div class="profile-container">
		<a href="index_customers.php" class="back-btn">← Quay lại trang chủ</a>

		<div class="profile-header">
			<h1>Xin chào, <?php echo $customer['name']; ?>!</h1>
			<p>Quản lý thông tin tài khoản và đơn hàng của bạn</p>
		</div>

		<div class="info-card">
			<h2>📋 Thông tin cá nhân</h2>
			<div class="info-grid">
				<div class="info-item">
					<span class="info-label">Họ và tên</span>
					<span class="info-value"><?php echo $customer['name']; ?></span>
				</div>
				<div class="info-item">
					<span class="info-label">Email</span>
					<span class="info-value"><?php echo $customer['email']; ?></span>
				</div>
				<div class="info-item">
					<span class="info-label">Số điện thoại</span>
					<span class="info-value"><?php echo $customer['phone']; ?></span>
				</div>
				<div class="info-item">
					<span class="info-label">Giới tính</span>
					<span class="info-value"><?php echo $customer['gender']; ?></span>
				</div>
				<div class="info-item">
					<span class="info-label">Ngày sinh</span>
					<span class="info-value"><?php echo date('d/m/Y', strtotime($customer['dob'])); ?></span>
				</div>
				<div class="info-item">
					<span class="info-label">Địa chỉ</span>
					<span class="info-value"><?php echo $customer['address']; ?></span>
				</div>
			</div>
		</div>

		<div class="info-card">
			<h2>📦 Đơn hàng của tôi
				<button class="refresh-btn" onclick="refreshOrderStatus()" id="refreshBtn">
					<i class="fas fa-sync-alt"></i> Làm mới
				</button>
			</h2>
			<?php if (mysqli_num_rows($result_orders) > 0) { ?>
				<table class="orders-table">
					<thead>
						<tr>
							<th>Mã đơn</th>
							<th>Ngày đặt</th>
							<th>Tổng tiền</th>
							<th>Trạng thái</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($order = mysqli_fetch_array($result_orders)) { 
							// Determine status class based on database status codes
							$status_class = 'status-pending';
							$status_text = 'Chờ xử lý';
							$progress = 10;
							$icon = 'fas fa-clock';
							
							switch($order['status']) {
								case 0:
									$status_class = 'status-pending';
									$status_text = 'Chờ xử lý';
									$progress = 10;
									$icon = 'fas fa-clock';
									break;
								case 2:
									$status_class = 'status-pending';
									$status_text = 'Chờ xác nhận';
									$progress = 25;
									$icon = 'fas fa-hourglass-half';
									break;
								case 3:
									$status_class = 'status-cancelled';
									$status_text = 'Shop đã hủy';
									$progress = 0;
									$icon = 'fas fa-ban';
									break;
								case 4:
									$status_class = 'status-processing';
									$status_text = 'Đang giao hàng';
									$progress = 75;
									$icon = 'fas fa-shipping-fast';
									break;
								case 5:
									$status_class = 'status-completed';
									$status_text = 'Hoàn thành';
									$progress = 100;
									$icon = 'fas fa-check-circle';
									break;
								case 7:
									$status_class = 'status-cancelled';
									$status_text = 'Khách hủy';
									$progress = 0;
									$icon = 'fas fa-user-times';
									break;
								default:
									$status_class = 'status-pending';
									$status_text = 'Chờ xử lý';
									$progress = 10;
									$icon = 'fas fa-clock';
									break;
							}
						?>
						<tr class="order-detail" data-order-id="<?php echo $order['id']; ?>">
							<td>#<?php echo $order['id']; ?></td>
							<td><?php echo date('d/m/Y H:i', strtotime($order['order_time'])); ?></td>
							<td><strong><?php echo number_format($order['total_price'], 0, ',', '.'); ?>₫</strong></td>
							<td>
								<div class="status-with-progress">
									<span class="status-badge <?php echo $status_class; ?>">
										<i class="<?php echo $icon; ?>"></i> <?php echo $status_text; ?>
									</span>
									<?php if ($progress > 0) { ?>
									<div class="progress-container">
										<div class="progress-bar" style="width: <?php echo $progress; ?>%"></div>
									</div>
									<?php } ?>
								</div>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } else { ?>
				<div class="no-orders">
					<p>Bạn chưa có đơn hàng nào</p>
					<a href="index_customers.php#products" class="back-btn" style="margin-top: 20px;">Mua sắm ngay</a>
				</div>
			<?php } ?>
		</div>
	</div>

	<!-- Notification container -->
	<div id="notification" class="notification"></div>

	<?php require 'footer_customers.php'; ?>

	<script>
		let refreshInterval;
		let updateCheckInterval;
		let isRefreshing = false;
		let lastCheckTime = new Date().toISOString().slice(0, 19).replace('T', ' ');

		// Auto refresh every 30 seconds
		function startAutoRefresh() {
			refreshInterval = setInterval(() => {
				refreshOrderStatus(true); // Silent refresh
			}, 30000);
		}

		// Check for updates every 10 seconds
		function startUpdateCheck() {
			updateCheckInterval = setInterval(() => {
				checkForUpdates();
			}, 10000);
		}

		function stopAutoRefresh() {
			if (refreshInterval) {
				clearInterval(refreshInterval);
			}
			if (updateCheckInterval) {
				clearInterval(updateCheckInterval);
			}
		}

		function checkForUpdates() {
			fetch(`check_order_updates.php?last_check=${encodeURIComponent(lastCheckTime)}`)
				.then(response => response.json())
				.then(data => {
					if (data.success && data.updates.length > 0) {
						// Show notifications for updates
						data.updates.forEach(update => {
							const message = `Đơn hàng #${update.order_id} đã chuyển từ "${update.old_status.text}" thành "${update.new_status.text}"`;
							showNotification(message, 'success');
						});
						
						// Update last check time
						lastCheckTime = data.current_time;
						
						// Refresh order table
						refreshOrderStatus(true);
					}
				})
				.catch(error => {
					console.error('Error checking updates:', error);
				});
		}

		function showNotification(message, type = 'info') {
			const notification = document.getElementById('notification');
			notification.textContent = message;
			notification.className = `notification ${type} show`;
			
			setTimeout(() => {
				notification.classList.remove('show');
			}, 3000);
		}

		function refreshOrderStatus(silent = false) {
			if (isRefreshing) return;
			
			isRefreshing = true;
			const refreshBtn = document.getElementById('refreshBtn');
			const icon = refreshBtn.querySelector('i');
			
			refreshBtn.disabled = true;
			icon.classList.add('fa-spin');
			
			if (!silent) {
				showNotification('Đang cập nhật trạng thái đơn hàng...', 'info');
			}

			fetch('get_order_status.php')
				.then(response => response.json())
				.then(data => {
					if (data.success) {
						updateOrderTable(data.orders, silent);
						if (!silent) {
							showNotification('Đã cập nhật trạng thái đơn hàng!', 'success');
						}
					} else {
						if (!silent) {
							showNotification('Có lỗi khi cập nhật!', 'error');
						}
					}
				})
				.catch(error => {
					console.error('Error:', error);
					if (!silent) {
						showNotification('Có lỗi khi cập nhật!', 'error');
					}
				})
				.finally(() => {
					isRefreshing = false;
					refreshBtn.disabled = false;
					icon.classList.remove('fa-spin');
				});
		}

		function updateOrderTable(orders, silent = false) {
			const tbody = document.querySelector('.orders-table tbody');
			if (!tbody) return;

			let hasChanges = false;
			
			orders.forEach(order => {
				const row = document.querySelector(`tr[data-order-id="${order.id}"]`);
				if (row) {
					const statusCell = row.querySelector('td:last-child');
					const currentStatus = statusCell.querySelector('.status-badge').textContent.trim();
					const newStatus = order.status_info.text;
					
					if (currentStatus !== newStatus) {
						hasChanges = true;
						
						// Update status badge
						const statusHtml = `
							<div class="status-with-progress">
								<span class="status-badge ${order.status_info.class}">
									<i class="${order.status_info.icon}"></i> ${order.status_info.text}
								</span>
								${order.status_info.progress > 0 ? `
									<div class="progress-container">
										<div class="progress-bar" style="width: ${order.status_info.progress}%"></div>
									</div>
								` : ''}
							</div>
						`;
						
						statusCell.innerHTML = statusHtml;
						
						// Add animation effect
						row.style.backgroundColor = '#e8f5e8';
						setTimeout(() => {
							row.style.backgroundColor = '';
						}, 2000);
					}
				}
			});
			
			if (hasChanges && !silent) {
				showNotification('Có cập nhật trạng thái đơn hàng mới!', 'success');
			}
		}

		// Start auto refresh when page loads
		document.addEventListener('DOMContentLoaded', function() {
			startAutoRefresh();
			startUpdateCheck();
			
			// Stop auto refresh when user leaves the page
			window.addEventListener('beforeunload', stopAutoRefresh);
			
			// Resume auto refresh when user comes back to the page
			document.addEventListener('visibilitychange', function() {
				if (document.hidden) {
					stopAutoRefresh();
				} else {
					startAutoRefresh();
					startUpdateCheck();
					refreshOrderStatus(true); // Silent refresh when coming back
				}
			});
		});
	</script>
</body>
</html>
