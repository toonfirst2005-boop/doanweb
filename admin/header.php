<!DOCTYPE html>
<html>
<head>
	<title><?php echo isset($page_title) ? $page_title : 'Admin Panel'; ?></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../style_admin_modern.css">
	<link rel="stylesheet" href="../style_menu.css">
	<link rel="stylesheet" href="../style_validate1.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	<?php if(isset($extra_css)) echo $extra_css; ?>
</head>
<body>
<?php require '../menu.php'; ?>

<div class="main-content">
	<div class="top">
		<div class="search">
			<?php if(isset($show_search) && $show_search): ?>
				<form class="form_search" method="GET">
					<label style="color: #718096; font-weight: 600;">Tìm kiếm</label>
					<input type="search" name="search" placeholder="<?php echo isset($search_placeholder) ? $search_placeholder : 'Nhập từ khóa...'; ?>" value="<?php echo isset($content_search) ? $content_search : ''; ?>">
					<button type="submit">
						<i class="fas fa-search"></i>
					</button>
				</form>
			<?php else: ?>
				<h2 style="margin: 0; color: var(--text-dark); font-size: 24px; font-weight: 700;">
					<?php echo isset($page_heading) ? $page_heading : 'Admin Panel'; ?>
				</h2>
			<?php endif; ?>
		</div>

		<div class="login">
			<i class="fas fa-user-circle" style="color: var(--primary-purple); font-size: 20px;"></i>
			<span>Xin chào <?php echo $_SESSION['name'] ?></span>
		</div>
	</div>

	<?php require '../validate.php' ?>
	
	<div class="bot">
