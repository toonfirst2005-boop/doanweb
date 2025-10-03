<?php 
require '../check_admin_login.php';
require '../connect_database.php';

if (isset($_GET['index'])) {
	$i = $_GET['index'];
} else {
	$i = 1;
}


//lấy ra tổng số hóa đơn
$sql_command_select_receipts = "select count(*) from receipts where status = 0 or status = 2 or status = 4";
$query_sql_command_select_receipts = mysqli_query($connect_database, $sql_command_select_receipts);
$count_receipts = mysqli_fetch_array($query_sql_command_select_receipts)['count(*)'];

//lấy ra số hóa đơn trên 1 trang
$receipts_on_page = 5;


//lấy ra số trang
$count_pages = ceil ($count_receipts / $receipts_on_page);

//lấy ra số trang bỏ qua theo thú tự trang
$skip_receipts_page = ( $i - 1 ) * $receipts_on_page;


$sql_command_select = "SELECT receipts.*, customers.name as 'customer_name', customers.email as 'customer_email', customers.phone as 'customer_phone' 
from receipts
JOIN customers on customers.id = receipts.customer_id
WHERE receipts.status = 0 OR receipts.status = 2 OR receipts.status = 4
ORDER BY receipts.order_time desc
limit $receipts_on_page offset $skip_receipts_page";

$query_sql_command_select = mysqli_query($connect_database, $sql_command_select);

$page_title = 'Quản lý Đơn hàng - Admin Panel';
$page_heading = 'Đơn hàng';
require '../header.php';
?>

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-shopping-cart"></i>
		</div>
		<div class="page-info">
			<h1>Quản lý Đơn hàng</h1>
			<p>Quản lý đơn hàng chờ xử lý và đang giao</p>
		</div>
	</div>
	<a href="view_receipts_finished.php" class="btn-add-new" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);">
		<i class="fas fa-check-circle"></i>
		Xem đơn hàng đã xử lý
	</a>
</div>
<table class="table">
	<thead>
		<tr>
			<th>MÃ</th>
			<th>THỜI GIAN ĐẶT</th>
			<th>NGƯỜI NHẬN</th>
			<th>KHÁCH HÀNG</th>
			<th>TRẠNG THÁI</th>
			<th>TỔNG TIỀN</th>
			<th>CHI TIẾT</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($query_sql_command_select as $each_receipt) : ?>
		<tr>
		<td><span class="badge-id">#<?php echo $each_receipt['id'] ?></span></td>
		<td>
			<i class="fas fa-clock" style="color: #718096; margin-right: 5px;"></i>
			<?php echo date('d/m/Y H:i', strtotime($each_receipt['order_time'])) ?>
		</td>
		<td>
			<div style="line-height: 1.6;">
				<strong><?php echo $each_receipt['receiver_name'] ?></strong><br>
				<i class="fas fa-phone" style="color: #718096; margin-right: 5px;"></i><?php echo $each_receipt['receiver_phone'] ?><br>
				<i class="fas fa-map-marker-alt" style="color: #718096; margin-right: 5px;"></i><?php echo $each_receipt['receiver_address'] ?>
			</div>
		</td>
		<td>
			<div class="customer-info">
				<i class="fas fa-user-circle customer-avatar"></i>
				<span><?php echo $each_receipt['customer_name'] ?></span>
			</div>
		</td>
		<td>
			<?php 
			switch ($each_receipt['status']) {
				case 0:
					echo '<span class="badge-id" style="background: #fff3cd; color: #856404;"><i class="fas fa-clock"></i> Chờ xử lý</span>';
					break;
				case 2:
					echo '<span class="badge-id" style="background: #fed7d7; color: #c53030;"><i class="fas fa-hourglass-half"></i> Chờ xác nhận</span>';
					break;							
				case 4:
					echo '<span class="badge-id" style="background: #bee3f8; color: #2c5282;"><i class="fas fa-shipping-fast"></i> Đang giao hàng</span>';
					break;
			}
			?>
	</td>
	<td><span class="badge-money"><?php echo number_format($each_receipt['total_price'], 0, ',', '.') ?>₫</span></td>
	<td>
		<button class="btn-action btn-view btn-view-detail" data-id="<?php echo $each_receipt['id'] ?>" data-status="<?php echo $each_receipt['status'] ?>" title="Xem chi tiết">
			<i class="fas fa-eye"></i>
		</button>
	</td>
</tr>
		<?php endforeach ?>
		</tbody>
</table>
<div class="pagination">
	<?php for ($page_num = 1; $page_num <= $count_pages; $page_num++) { ?>
		<a href="?index=<?php echo $page_num?>" 
		   class="<?php echo ($page_num == $i) ? 'active' : '' ?>">
			<?php echo $page_num ?>
		</a>
	<?php } ?>
</div>

<!-- Modal Chi tiết Đơn hàng (phải đặt trước footer) -->
<div id="receiptModal" class="modal" style="display: none;">
	<div class="modal-content" style="max-width: 800px;">
		<div class="modal-header">
			<h2><i class="fas fa-receipt"></i> Chi tiết Đơn hàng</h2>
			<span class="modal-close">&times;</span>
		</div>
		<div class="modal-body" id="modalBody">
			<div class="loading">Đang tải...</div>
		</div>
	</div>
</div>

<script>
// Test script
console.log('=== MODAL SCRIPT LOADED ===');
const buttons = document.querySelectorAll('.btn-view-detail');
console.log('Buttons:', buttons.length);

// Add click event to each button
buttons.forEach(function(btn, index) {
	console.log('Adding listener to button', index);
	btn.addEventListener('click', function() {
		const receiptId = this.dataset.id;
		const status = this.dataset.status;
		console.log('Button clicked! ID:', receiptId, 'Status:', status);
		
		const modal = document.getElementById('receiptModal');
		const modalBody = document.getElementById('modalBody');
		
		if (modal) {
			modal.style.display = 'block';
			modalBody.innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div>';
			console.log('Modal opened, loading data...');
			
			// Fetch receipt details
			fetch('get_receipt_detail.php?id=' + receiptId)
				.then(function(response) {
					console.log('Response status:', response.status);
					return response.json();
				})
				.then(function(data) {
					console.log('Data received:', data);
					if (data.error) {
						modalBody.innerHTML = '<div style="color: red; padding: 20px; text-align: center;">' + data.error + '</div>';
					} else {
						displayReceiptDetail(data, status);
					}
				})
				.catch(function(error) {
					console.error('Error:', error);
					modalBody.innerHTML = '<div style="color: red; padding: 20px; text-align: center;">Lỗi khi tải dữ liệu: ' + error.message + '</div>';
				});
		} else {
			console.error('Modal not found!');
		}
	});
});

// Close modal
const closeBtn = document.querySelector('.modal-close');
if (closeBtn) {
	closeBtn.addEventListener('click', function() {
		document.getElementById('receiptModal').style.display = 'none';
	});
}

// Close when click outside
window.addEventListener('click', function(event) {
	const modal = document.getElementById('receiptModal');
	if (event.target == modal) {
		modal.style.display = 'none';
	}
});

// Display receipt detail function
function displayReceiptDetail(data, status) {
	const receipt = data.receipt;
	const products = data.products;
	const modalBody = document.getElementById('modalBody');
	
	let statusBadge = '';
	let statusActions = '';
	
	if (status == 0) {
		statusBadge = '<span class="badge-id" style="background: #fff3cd; color: #856404;"><i class="fas fa-clock"></i> Chờ xử lý</span>';
		statusActions = 
			'<div class="status-actions">' +
				'<div class="status-dropdown">' +
					'<button class="btn-status-change" onclick="toggleStatusMenu(' + receipt.id + ')">' +
						'<i class="fas fa-exchange-alt"></i> Chuyển trạng thái' +
					'</button>' +
					'<div class="status-menu" id="status-menu-' + receipt.id + '">' +
						'<div class="status-option status-approve" onclick="updateStatus(' + receipt.id + ', 2)">' +
							'<i class="fas fa-check-circle"></i>' +
							'<div>' +
								'<div class="status-title">Xác nhận đơn</div>' +
								'<div class="status-desc">Chuyển sang chờ xác nhận</div>' +
							'</div>' +
						'</div>' +
						'<div class="status-option status-cancel" onclick="updateStatus(' + receipt.id + ', 3)">' +
							'<i class="fas fa-times-circle"></i>' +
							'<div>' +
								'<div class="status-title">Hủy đơn</div>' +
								'<div class="status-desc">Hủy đơn hàng này</div>' +
							'</div>' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>';
	} else if (status == 2) {
		statusBadge = '<span class="badge-id" style="background: #fed7d7; color: #c53030;"><i class="fas fa-hourglass-half"></i> Chờ xác nhận</span>';
		statusActions = 
			'<div class="status-actions">' +
				'<div class="status-dropdown">' +
					'<button class="btn-status-change" onclick="toggleStatusMenu(' + receipt.id + ')">' +
						'<i class="fas fa-exchange-alt"></i> Chuyển trạng thái' +
					'</button>' +
					'<div class="status-menu" id="status-menu-' + receipt.id + '">' +
						'<div class="status-option status-approve" onclick="updateStatus(' + receipt.id + ', 4)">' +
							'<i class="fas fa-check-circle"></i>' +
							'<div>' +
								'<div class="status-title">Xác nhận đơn</div>' +
								'<div class="status-desc">Xác nhận và chuyển sang giao hàng</div>' +
							'</div>' +
						'</div>' +
						'<div class="status-option status-cancel" onclick="updateStatus(' + receipt.id + ', 3)">' +
							'<i class="fas fa-times-circle"></i>' +
							'<div>' +
								'<div class="status-title">Hủy đơn</div>' +
								'<div class="status-desc">Hủy đơn hàng này</div>' +
							'</div>' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>';
	} else if (status == 4) {
		statusBadge = '<span class="badge-id" style="background: #bee3f8; color: #2c5282;"><i class="fas fa-shipping-fast"></i> Đang giao hàng</span>';
		statusActions = 
			'<div class="status-actions">' +
				'<div class="status-dropdown">' +
					'<button class="btn-status-change" onclick="toggleStatusMenu(' + receipt.id + ')">' +
						'<i class="fas fa-exchange-alt"></i> Chuyển trạng thái' +
					'</button>' +
					'<div class="status-menu" id="status-menu-' + receipt.id + '">' +
						'<div class="status-option status-complete" onclick="updateStatus(' + receipt.id + ', 5)">' +
							'<i class="fas fa-check-double"></i>' +
							'<div>' +
								'<div class="status-title">Hoàn thành</div>' +
								'<div class="status-desc">Xác nhận giao hàng thành công</div>' +
							'</div>' +
						'</div>' +
						'<div class="status-option status-cancel" onclick="updateStatus(' + receipt.id + ', 3)">' +
							'<i class="fas fa-times-circle"></i>' +
							'<div>' +
								'<div class="status-title">Hủy đơn</div>' +
								'<div class="status-desc">Hủy đơn hàng này</div>' +
							'</div>' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>';
	}
	
	let productsHTML = '';
	products.forEach(function(product) {
		productsHTML += 
			'<div class="product-item">' +
				'<div class="product-image">' +
					'<img src="../' + product.product_image + '" alt="' + product.product_name + '">' +
				'</div>' +
				'<div class="product-info">' +
					'<div class="product-name">' + product.product_name + '</div>' +
					'<div class="product-details">' +
						'<span class="product-price">' + parseInt(product.price).toLocaleString('vi-VN') + '₫</span>' +
						'<span class="product-quantity">x' + product.quantity + '</span>' +
					'</div>' +
				'</div>' +
				'<div class="product-total">' + (product.quantity * product.price).toLocaleString('vi-VN') + '₫</div>' +
			'</div>';
	});
	
	modalBody.innerHTML = 
		'<div class="process-container">' +
			'<h3 class="process-title"><i class="fas fa-route"></i> Quy trình xử lý đơn hàng</h3>' +
			'<div class="process-timeline">' +
				'<div class="process-step ' + (status >= 0 ? 'completed' : '') + '">' +
					'<div class="step-card">' +
						'<div class="step-icon-wrapper">' +
							'<i class="fas fa-plus-circle"></i>' +
						'</div>' +
						'<div class="step-info">' +
							'<div class="step-title">Đơn hàng mới</div>' +
							'<div class="step-description">Khách hàng đã đặt hàng</div>' +
						'</div>' +
					'</div>' +
					'<div class="step-connector ' + (status >= 2 ? 'completed' : '') + '"></div>' +
				'</div>' +
				'<div class="process-step ' + (status >= 2 ? 'completed' : '') + '">' +
					'<div class="step-card">' +
						'<div class="step-icon-wrapper">' +
							'<i class="fas fa-clock"></i>' +
						'</div>' +
						'<div class="step-info">' +
							'<div class="step-title">Chờ xác nhận</div>' +
							'<div class="step-description">Đang xem xét đơn hàng</div>' +
						'</div>' +
					'</div>' +
					'<div class="step-connector ' + (status >= 4 ? 'completed' : '') + '"></div>' +
				'</div>' +
				'<div class="process-step ' + (status >= 4 ? 'completed' : '') + '">' +
					'<div class="step-card">' +
						'<div class="step-icon-wrapper">' +
							'<i class="fas fa-check-circle"></i>' +
						'</div>' +
						'<div class="step-info">' +
							'<div class="step-title">Đã xác nhận</div>' +
							'<div class="step-description">Đơn hàng đã được xác nhận</div>' +
						'</div>' +
					'</div>' +
					'<div class="step-connector ' + (status >= 4 ? 'completed' : '') + '"></div>' +
				'</div>' +
				'<div class="process-step ' + (status >= 4 ? 'completed' : '') + '">' +
					'<div class="step-card">' +
						'<div class="step-icon-wrapper">' +
							'<i class="fas fa-shipping-fast"></i>' +
						'</div>' +
						'<div class="step-info">' +
							'<div class="step-title">Đang giao hàng</div>' +
							'<div class="step-description">Đơn hàng đang được vận chuyển</div>' +
						'</div>' +
					'</div>' +
					'<div class="step-connector ' + (status >= 5 ? 'completed' : '') + '"></div>' +
				'</div>' +
				'<div class="process-step ' + (status >= 5 ? 'completed' : '') + '">' +
					'<div class="step-card">' +
						'<div class="step-icon-wrapper">' +
							'<i class="fas fa-box-open"></i>' +
						'</div>' +
						'<div class="step-info">' +
							'<div class="step-title">Đã giao hàng</div>' +
							'<div class="step-description">Giao hàng thành công</div>' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>' +
		'</div>' +
		'<div class="modal-actions">' + statusActions +
		'<button onclick="document.getElementById(\'receiptModal\').style.display=\'none\'" class="btn-close-modal">' +
		'<i class="fas fa-times-circle"></i> Đóng</button></div>';
}

function toggleStatusMenu(id) {
	const menu = document.getElementById('status-menu-' + id);
	const allMenus = document.querySelectorAll('.status-menu');
	
	// Close all other menus
	allMenus.forEach(function(m) {
		if (m.id !== 'status-menu-' + id) {
			m.classList.remove('show');
		}
	});
	
	// Toggle current menu
	menu.classList.toggle('show');
	
	// Close menu when clicking outside
	setTimeout(function() {
		document.addEventListener('click', function closeMenu(e) {
			if (!e.target.closest('.status-dropdown')) {
				menu.classList.remove('show');
				document.removeEventListener('click', closeMenu);
			}
		});
	}, 100);
}

function updateStatus(id, status) {
	let confirmMessage = '';
	if (status == 3) {
		confirmMessage = 'Bạn có chắc muốn hủy đơn hàng này?';
	} else if (status == 4) {
		confirmMessage = 'Xác nhận đơn hàng và chuyển sang đang giao hàng?';
	} else if (status == 5) {
		confirmMessage = 'Xác nhận đơn hàng đã giao thành công?';
	}
	
	if (confirmMessage && !confirm(confirmMessage)) {
		return;
	}
	
	window.location.href = 'update_receipt.php?id=' + id + '&status=' + status;
}

function switchTab(event, tabId) {
	// Hide all tabs
	const tabPanes = document.querySelectorAll('.tab-pane');
	tabPanes.forEach(function(pane) {
		pane.classList.remove('active');
	});
	
	// Remove active from all buttons
	const tabBtns = document.querySelectorAll('.tab-btn');
	tabBtns.forEach(function(btn) {
		btn.classList.remove('active');
	});
	
	// Show selected tab
	document.getElementById(tabId).classList.add('active');
	event.currentTarget.classList.add('active');
}
</script>

<style>
.modal {
	position: fixed;
	z-index: 1000;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	background-color: rgba(0,0,0,0.5);
	animation: fadeIn 0.3s;
}

@keyframes fadeIn {
	from { opacity: 0; }
	to { opacity: 1; }
}

.modal-content {
	background-color: #fefefe;
	margin: 5% auto;
	border-radius: 12px;
	width: 90%;
	max-height: 85vh;
	overflow-y: auto;
	animation: slideDown 0.3s;
}

@keyframes slideDown {
	from {
		transform: translateY(-50px);
		opacity: 0;
	}
	to {
		transform: translateY(0);
		opacity: 1;
	}
}

.modal-header {
	padding: 20px 30px;
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	color: white;
	border-radius: 12px 12px 0 0;
	display: flex;
	justify-content: space-between;
	align-items: center;
}

.modal-header h2 {
	margin: 0;
	font-size: 24px;
}

.modal-close {
	font-size: 28px;
	font-weight: bold;
	cursor: pointer;
	transition: 0.3s;
}

.modal-close:hover {
	transform: scale(1.2);
}

.modal-body {
	padding: 30px;
}

.loading {
	text-align: center;
	padding: 40px;
	font-size: 18px;
	color: #718096;
}

/* Tabs */
.tabs-container {
	margin: 0;
}

.tabs-header {
	display: flex;
	gap: 10px;
	border-bottom: 2px solid #e2e8f0;
	margin-bottom: 20px;
}

.tab-btn {
	padding: 12px 24px;
	border: none;
	background: transparent;
	color: #718096;
	font-size: 16px;
	font-weight: 500;
	cursor: pointer;
	border-bottom: 3px solid transparent;
	transition: all 0.3s;
	display: flex;
	align-items: center;
	gap: 8px;
}

.tab-btn:hover {
	color: #667eea;
	background: rgba(102, 126, 234, 0.05);
}

.tab-btn.active {
	color: #667eea;
	border-bottom-color: #667eea;
	background: rgba(102, 126, 234, 0.1);
}

.tab-btn i {
	font-size: 18px;
}

.tabs-content {
	padding: 10px 0;
}

.tab-pane {
	display: none;
	animation: fadeIn 0.3s;
}

.tab-pane.active {
	display: block;
}

@keyframes fadeIn {
	from { opacity: 0; transform: translateY(10px); }
	to { opacity: 1; transform: translateY(0); }
}

/* Process Timeline */
.process-container {
	padding: 0;
}

.process-title {
	color: #2d3748;
	font-size: 22px;
	font-weight: 700;
	margin: 0 0 40px 0;
	display: flex;
	align-items: center;
	gap: 12px;
	padding-bottom: 20px;
	border-bottom: 2px solid #e2e8f0;
}

.process-title i {
	color: #667eea;
	font-size: 24px;
}

.process-timeline {
	display: flex;
	justify-content: space-between;
	gap: 15px;
	padding: 20px 0;
	flex-wrap: wrap;
}

.process-step {
	flex: 1;
	min-width: 160px;
	display: flex;
	flex-direction: column;
	align-items: center;
	position: relative;
}

.step-card {
	background: #f7fafc;
	border: 2px solid #e2e8f0;
	border-radius: 16px;
	padding: 20px 15px;
	width: 100%;
	height: 140px;
	max-width: 160px;
	min-height: 140px;
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	text-align: center;
	transition: all 0.3s;
	position: relative;
	z-index: 2;
}

.process-step.completed .step-card {
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	border-color: #667eea;
	box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
	transform: translateY(-5px);
}

.step-icon-wrapper {
	width: 50px;
	height: 50px;
	border-radius: 50%;
	background: white;
	display: flex;
	align-items: center;
	justify-content: center;
	margin-bottom: 10px;
	transition: all 0.3s;
}

.step-icon-wrapper i {
	font-size: 22px;
	color: #cbd5e0;
	transition: all 0.3s;
}

.process-step.completed .step-icon-wrapper {
	background: white;
	box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
}

.process-step.completed .step-icon-wrapper i {
	color: #667eea;
}

.step-info {
	width: 100%;
}

.step-title {
	font-weight: 700;
	font-size: 13px;
	color: #2d3748;
	margin-bottom: 5px;
	transition: all 0.3s;
	line-height: 1.2;
}

.process-step.completed .step-title {
	color: white;
}

.step-description {
	font-size: 11px;
	color: #718096;
	line-height: 1.3;
	transition: all 0.3s;
}

.process-step.completed .step-description {
	color: rgba(255, 255, 255, 0.9);
}

.step-connector {
	position: absolute;
	top: 70px;
	left: calc(50% + 80px);
	width: calc(100% - 160px);
	height: 3px;
	background: #e2e8f0;
	transition: all 0.3s;
	z-index: 1;
}

.step-connector.completed {
	background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.process-step:last-child .step-connector {
	display: none;
}

/* Responsive Design */
@media (max-width: 768px) {
	.process-timeline {
		flex-direction: column;
		gap: 20px;
		align-items: center;
	}
	
	.process-step {
		width: 100%;
		max-width: 300px;
	}
	
	.step-card {
		max-width: 100%;
		height: auto;
		min-height: 100px;
	}
	
	.step-connector {
		display: none !important;
	}
	
	.modal-content {
		width: 95%;
		margin: 2% auto;
	}
}

/* Info Grid */
.info-grid {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 25px;
}

/* Info Cards */
.info-card {
	background: linear-gradient(135deg, #ffffff 0%, #f7fafc 100%);
	padding: 25px;
	border-radius: 16px;
	border: 1px solid #e2e8f0;
	box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
	position: relative;
	overflow: hidden;
}

.info-card::before {
	content: '';
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	height: 4px;
	background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.info-card.gradient-blue::before {
	background: linear-gradient(90deg, #4299e1 0%, #667eea 100%);
}

.info-card.gradient-purple::before {
	background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.card-icon {
	position: absolute;
	top: 20px;
	right: 20px;
	font-size: 60px;
	color: rgba(102, 126, 234, 0.1);
}

.info-card h3 {
	color: #2d3748;
	margin: 0 0 25px 0;
	font-size: 18px;
	font-weight: 700;
	display: flex;
	align-items: center;
	gap: 10px;
}

.info-card h3 i {
	color: #667eea;
}

.info-item {
	display: flex;
	align-items: flex-start;
	gap: 15px;
	padding: 15px 0;
	border-bottom: 1px solid #e2e8f0;
}

.info-item:last-child {
	border-bottom: none;
}

.item-icon {
	width: 40px;
	height: 40px;
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	border-radius: 10px;
	display: flex;
	align-items: center;
	justify-content: center;
	color: white;
	font-size: 18px;
	flex-shrink: 0;
}

.item-content {
	flex: 1;
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.info-item .label {
	color: #718096;
	font-size: 13px;
	font-weight: 500;
	text-transform: uppercase;
	letter-spacing: 0.5px;
}

.info-item .value {
	color: #2d3748;
	font-weight: 600;
	font-size: 15px;
}

.order-id {
	color: #667eea !important;
	font-family: monospace;
	font-size: 16px !important;
}

.price-highlight {
	color: #48bb78 !important;
	font-size: 24px !important;
	font-weight: 700 !important;
}

.total-price {
	background: linear-gradient(135deg, #f0fff4 0%, #c6f6d5 100%);
	border-radius: 12px;
	padding: 15px !important;
	margin-top: 10px;
	border: 2px solid #48bb78 !important;
}

.total-price .item-icon {
	background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
}

/* Products List */
.products-list {
	display: flex;
	flex-direction: column;
	gap: 15px;
}

.product-item {
	display: flex;
	align-items: center;
	gap: 20px;
	padding: 15px;
	background: #f7fafc;
	border-radius: 12px;
	border: 1px solid #e2e8f0;
	transition: all 0.3s;
}

.product-item:hover {
	background: #edf2f7;
	box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
	transform: translateY(-2px);
}

.product-image {
	width: 80px;
	height: 80px;
	border-radius: 12px;
	overflow: hidden;
	flex-shrink: 0;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.product-image img {
	width: 100%;
	height: 100%;
	object-fit: cover;
}

.product-info {
	flex: 1;
}

.product-name {
	font-size: 16px;
	font-weight: 600;
	color: #2d3748;
	margin-bottom: 8px;
}

.product-details {
	display: flex;
	gap: 15px;
	align-items: center;
}

.product-price {
	color: #718096;
	font-size: 14px;
}

.product-quantity {
	background: #667eea;
	color: white;
	padding: 4px 12px;
	border-radius: 20px;
	font-size: 13px;
	font-weight: 600;
}

.product-total {
	font-size: 18px;
	font-weight: 700;
	color: #48bb78;
}

/* Total Section */
.total-section {
	margin-top: 25px;
	padding: 20px;
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	border-radius: 12px;
	display: flex;
	justify-content: space-between;
	align-items: center;
	color: white;
}

.total-label {
	font-size: 18px;
	font-weight: 600;
}

.total-value {
	font-size: 28px;
	font-weight: 700;
}

/* Modal Actions */
.modal-actions {
	display: flex;
	gap: 10px;
	margin-top: 25px;
	justify-content: center;
	padding-top: 25px;
	border-top: 2px solid #e2e8f0;
}

.btn-close-modal {
	background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
	color: white;
	border: none;
	padding: 12px 28px;
	border-radius: 10px;
	font-size: 15px;
	font-weight: 600;
	cursor: pointer;
	display: flex;
	align-items: center;
	gap: 10px;
	transition: all 0.3s;
	box-shadow: 0 4px 6px rgba(245, 101, 101, 0.3);
}

.btn-close-modal:hover {
	background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
	transform: translateY(-2px);
	box-shadow: 0 6px 12px rgba(245, 101, 101, 0.4);
}

.btn-close-modal i {
	font-size: 16px;
}

/* Status Dropdown */
.status-actions {
	display: flex;
	gap: 10px;
	align-items: center;
}

.status-dropdown {
	position: relative;
}

.btn-status-change {
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	color: white;
	border: none;
	padding: 12px 24px;
	border-radius: 10px;
	font-size: 15px;
	font-weight: 600;
	cursor: pointer;
	display: flex;
	align-items: center;
	gap: 10px;
	transition: all 0.3s;
	box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
}

.btn-status-change:hover {
	transform: translateY(-2px);
	box-shadow: 0 6px 12px rgba(102, 126, 234, 0.4);
}

.btn-status-change i {
	font-size: 16px;
}

.status-menu {
	position: absolute;
	top: calc(100% + 10px);
	left: 0;
	background: white;
	border-radius: 12px;
	box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
	min-width: 280px;
	opacity: 0;
	visibility: hidden;
	transform: translateY(-10px);
	transition: all 0.3s;
	z-index: 1000;
	overflow: hidden;
}

.status-menu.show {
	opacity: 1;
	visibility: visible;
	transform: translateY(0);
}

.status-option {
	display: flex;
	align-items: center;
	gap: 15px;
	padding: 15px 20px;
	cursor: pointer;
	transition: all 0.3s;
	border-bottom: 1px solid #e2e8f0;
}

.status-option:last-child {
	border-bottom: none;
}

.status-option:hover {
	background: #f7fafc;
}

.status-option i {
	font-size: 24px;
	width: 40px;
	height: 40px;
	display: flex;
	align-items: center;
	justify-content: center;
	border-radius: 10px;
	flex-shrink: 0;
}

.status-approve i {
	background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
	color: white;
}

.status-complete i {
	background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
	color: white;
}

.status-cancel i {
	background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
	color: white;
}

.status-option:hover.status-approve {
	background: linear-gradient(135deg, #f0fff4 0%, #c6f6d5 100%);
}

.status-option:hover.status-complete {
	background: linear-gradient(135deg, #ebf8ff 0%, #bee3f8 100%);
}

.status-option:hover.status-cancel {
	background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
}

.status-title {
	font-weight: 600;
	font-size: 15px;
	color: #2d3748;
	margin-bottom: 3px;
}

.status-desc {
	font-size: 13px;
	color: #718096;
}
</style>

<script>
console.log('Script loaded');

document.addEventListener('DOMContentLoaded', function() {
	console.log('DOM loaded');
	
	// Modal functionality
	const modal = document.getElementById('receiptModal');
	const modalBody = document.getElementById('modalBody');
	const closeBtn = document.querySelector('.modal-close');

	console.log('Modal:', modal);
	console.log('ModalBody:', modalBody);
	console.log('CloseBtn:', closeBtn);

	if (!modal || !modalBody || !closeBtn) {
		console.error('Modal elements not found');
		return;
	}

	// Close modal
	closeBtn.onclick = function() {
		modal.style.display = 'none';
	}

	window.onclick = function(event) {
		if (event.target == modal) {
			modal.style.display = 'none';
		}
	}

	// View detail buttons
	const detailButtons = document.querySelectorAll('.btn-view-detail');
	console.log('Found ' + detailButtons.length + ' detail buttons');
	
	detailButtons.forEach(btn => {
	btn.addEventListener('click', function() {
		const receiptId = this.dataset.id;
		const status = this.dataset.status;
		
		modal.style.display = 'block';
		modalBody.innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div>';
		
		// Fetch receipt details
		fetch('get_receipt_detail.php?id=' + receiptId)
		.then(response => {
			console.log('Response status:', response.status);
			if (response.status !== 200) {
				modalBody.innerHTML = '<div class="error" style="color: red; padding: 20px;">Lỗi khi tải dữ liệu: ' + response.statusText + '</div>';
				return;
			}
			return response.json();
		})
		.then(data => {
			console.log('Data received:', data);
			if (data.error) {
				modalBody.innerHTML = '<div class="error" style="color: red; padding: 20px;">' + data.error + '</div>';
				return;
			}
			
			displayReceiptDetail(data, status);
		})
		.catch(error => {
			console.error('Error:', error);
			modalBody.innerHTML = '<div class="error" style="color: red; padding: 20px;">Lỗi khi tải dữ liệu: ' + error.message + '</div>';
		});
	});

	function updateStatus(id, status) {
		if (status == 3 && !confirm('Bạn có chắc muốn hủy đơn hàng này?')) {
			return;
		}
		
		window.location.href = 'update_receipt.php?id=' + id + '&status=' + status;
	}

	function displayReceiptDetail(data, status) {
		const receipt = data.receipt;
		const products = data.products;
		
		let statusBadge = '';
		let statusActions = '';
		
		if (status == 0) {
			statusBadge = '<span class="badge-id" style="background: #fff3cd; color: #856404;"><i class="fas fa-clock"></i> Chờ xử lý</span>';
			statusActions = `
				<button onclick="updateStatus(${receipt.id}, 2)" class="btn-action" style="background: #17a2b8; color: white;">
					<i class="fas fa-check"></i> Xác nhận đơn
				</button>
				<button onclick="updateStatus(${receipt.id}, 3)" class="btn-action btn-delete">
					<i class="fas fa-times"></i> Hủy đơn hàng
				</button>
			`;
		} else if (status == 2) {
			statusBadge = '<span class="badge-id" style="background: #fed7d7; color: #c53030;"><i class="fas fa-hourglass-half"></i> Chờ xác nhận</span>';
			statusActions = `
				<button onclick="updateStatus(${receipt.id}, 4)" class="btn-action" style="background: #48bb78; color: white;">
					<i class="fas fa-check"></i> Duyệt đơn hàng
				</button>
				<button onclick="updateStatus(${receipt.id}, 3)" class="btn-action btn-delete">
					<i class="fas fa-times"></i> Hủy đơn hàng
				</button>
			`;
		} else if (status == 4) {
			statusBadge = '<span class="badge-id" style="background: #bee3f8; color: #2c5282;"><i class="fas fa-shipping-fast"></i> Đang giao hàng</span>';
			statusActions = `
				<button onclick="updateStatus(${receipt.id}, 5)" class="btn-action" style="background: #4299e1; color: white;">
					<i class="fas fa-check-circle"></i> Hoàn thành
				</button>
				<button onclick="updateStatus(${receipt.id}, 3)" class="btn-action btn-delete">
					<i class="fas fa-times"></i> Hủy đơn hàng
				</button>
			`;
		}
		
		let productsHTML = '';
		products.forEach(product => {
			productsHTML += `
				<tr>
					<td>
						<div style="display: flex; align-items: center; gap: 10px;">
							<img src="../${product.product_image}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
							<span>${product.product_name}</span>
						</div>
					</td>
					<td>${product.quantity}</td>
					<td>${parseInt(product.price).toLocaleString('vi-VN')}₫</td>
					<td><strong>${(product.quantity * product.price).toLocaleString('vi-VN')}₫</strong></td>
				</tr>
			`;
		});
		
		modalBody.innerHTML = `
			<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
				<div>
					<h3 style="color: #667eea; margin-bottom: 15px;"><i class="fas fa-user"></i> Thông tin người nhận</h3>
					<p><strong>Tên:</strong> ${receipt.receiver_name}</p>
					<p><strong>SĐT:</strong> ${receipt.receiver_phone}</p>
					<p><strong>Địa chỉ:</strong> ${receipt.receiver_address}</p>
				</div>
				<div>
					<h3 style="color: #667eea; margin-bottom: 15px;"><i class="fas fa-info-circle"></i> Thông tin đơn hàng</h3>
					<p><strong>Mã đơn:</strong> #${receipt.id}</p>
					<p><strong>Thời gian:</strong> ${receipt.order_time}</p>
					<p><strong>Trạng thái:</strong> ${statusBadge}</p>
				</div>
			</div>
			
			<h3 style="color: #667eea; margin-bottom: 15px;"><i class="fas fa-shopping-cart"></i> Sản phẩm</h3>
			<table class="table">
				<thead>
					<tr>
						<th>Sản phẩm</th>
						<th>Số lượng</th>
						<th>Đơn giá</th>
						<th>Thành tiền</th>
					</tr>
				</thead>
				<tbody>
					${productsHTML}
				</tbody>
			</table>
			
			<div style="text-align: right; margin-top: 20px; padding-top: 20px; border-top: 2px solid #e2e8f0;">
				<h3 style="color: #667eea;">Tổng tiền: <span style="color: #48bb78;">${parseInt(receipt.total_price).toLocaleString('vi-VN')}₫</span></h3>
			</div>
			
			<div style="display: flex; gap: 10px; margin-top: 20px; justify-content: center;">
				${statusActions}
				<button onclick="document.getElementById('receiptModal').style.display='none'" class="btn-action" style="background: #718096; color: white;">
					<i class="fas fa-times"></i> Đóng
				</button>
			</div>
		`;
	}
}); // End DOMContentLoaded
</script>
<?php
require '../footer.php'; 
?>