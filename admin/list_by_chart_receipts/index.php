<?php 
require '../check_super_admin_login.php';

// Page configuration
$page_title = 'Thống kê Hóa đơn - Admin Panel';
$page_heading = 'Thống kê Hóa đơn';
require '../header.php';
?>

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-chart-pie"></i>
		</div>
		<div class="page-info">
			<h1>Thống kê Hóa đơn</h1>
			<p>Theo dõi hóa đơn theo trạng thái</p>
		</div>
	</div>
</div>

<div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px;">
	Thống kê theo
	<select>
		<option disabled selected>Chọn cách thống kê</option>
		<option class="option_day_to_day" value = "1">Ngày chỉ định tới ngày chỉ định</option>
		<option class="option_days_ago" value="2">Số ngày gần đây (tối đa 30)</option>
		<option class="option_month" value="3">Tháng</option>
	</select>
	<br>
	<form>
		<input type="date"  id = "input_day_to_day_1" hidden>
		<br>
		<input type="date"  id = "input_day_to_day_2" hidden>
		<p id = "tips" hidden>Chọn xong rồi click chuột ra ngoài để thấy biểu đồ</p>
		<input type="number"  id = "input_days_ago" hidden>
		<input type="month"  id = "input_month" hidden>
	</form>
	
	<figure class="highcharts-figure">
		<div id="container"></div>
	</figure>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="chart.js"></script>

<?php require '../footer.php'; ?>