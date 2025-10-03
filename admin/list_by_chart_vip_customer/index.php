<?php 
require '../check_super_admin_login.php';

// Page configuration
$page_title = 'Khách hàng Thân thiết - Admin Panel';
$page_heading = 'Khách hàng Thân thiết';
require '../header.php';
?>

<div class="page-header-modern">
	<div class="page-header-content">
		<div class="page-icon">
			<i class="fas fa-user-friends"></i>
		</div>
		<div class="page-info">
			<h1>Khách hàng Thân thiết</h1>
			<p>Thống kê khách hàng mua hàng nhiều nhất</p>
		</div>
	</div>
</div>

<div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px;">

	<figure class="highcharts-figure">
		<div id="container"></div>
	</figure>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/cylinder.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="chart.js"></script>

<?php require '../footer.php'; ?>