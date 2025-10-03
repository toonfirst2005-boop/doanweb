<?php require '../check_admin_login.php'; ?>
<!DOCTYPE html>
<html>
<head>
	<title>Dashboard - Admin Panel</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../style_admin_modern.css">
	<link rel="stylesheet" href="../style_menu.css">
	<link rel="stylesheet" href="../style_validate1.css">
	<link rel="stylesheet" type="text/css" href="style_chart.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body> 
<?php require '../menu.php'; ?>

<div class="main-content">
	<div class="top">
		<div class="search">
			<h2 style="margin: 0; color: var(--text-dark); font-size: 24px; font-weight: 700;">Dashboard</h2>
		</div>

		<div class="login">
			<i class="fas fa-user-circle" style="color: var(--primary-purple); font-size: 20px;"></i>
			<span>Xin chào <?php echo $_SESSION['name'] ?></span>
		</div>
	</div>

	<?php require '../validate.php' ?>
	
	<div class="bot">
		<div class="header">
			<h1>TRANG CHỦ</h1>
			<p>Tổng quan thống kê hệ thống</p>
		</div>

		<div class="dashboard-grid">
			<div class="chart-card">
				<div id="container"></div>
			</div>
			<div class="chart-card">
				<div id="container_1"></div>
			</div>
			<div class="chart-card">
				<div id="container_2"></div>
			</div>
			<div class="chart-card">
				<div id="container_3"></div>
			</div>
		</div>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/cylinder.js"></script>
<script src="script.js"></script>
</body>
</html>