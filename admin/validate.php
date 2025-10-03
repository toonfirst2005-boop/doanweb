<link rel="stylesheet" href="../style_modern_form.css">

<?php if (isset($_SESSION['error'])){ ?>
	<div class="notice-box notice-error" id="notice-error">
		<i class="fas fa-exclamation-circle"></i>
		<div class="notice-box-content">
			<div class="notice-box-title">Lỗi!</div>
			<div class="notice-box-message"><?php echo $_SESSION['error']; ?></div>
		</div>
	</div>
	<?php unset($_SESSION['error']); ?>
<?php } ?>

<?php if (isset($_SESSION['success'])){ ?>
	<div class="notice-box notice-success" id="notice-success">
		<i class="fas fa-check-circle"></i>
		<div class="notice-box-content">
			<div class="notice-box-title">Thành công!</div>
			<div class="notice-box-message"><?php echo $_SESSION['success']; ?></div>
		</div>
	</div>
	<?php unset($_SESSION['success']); ?>
<?php } ?>

<?php if (isset($_SESSION['warning'])){ ?>
	<div class="notice-box notice-warning" id="notice-warning">
		<i class="fas fa-exclamation-triangle"></i>
		<div class="notice-box-content">
			<div class="notice-box-title">Cảnh báo!</div>
			<div class="notice-box-message"><?php echo $_SESSION['warning']; ?></div>
		</div>
	</div>
	<?php unset($_SESSION['warning']); ?>
<?php } ?>

<?php if (isset($_SESSION['info'])){ ?>
	<div class="notice-box notice-info" id="notice-info">
		<i class="fas fa-info-circle"></i>
		<div class="notice-box-content">
			<div class="notice-box-title">Thông tin</div>
			<div class="notice-box-message"><?php echo $_SESSION['info']; ?></div>
		</div>
	</div>
	<?php unset($_SESSION['info']); ?>
<?php } ?>

<script>
// Auto hide notices after 5 seconds
setTimeout(() => {
	const notices = document.querySelectorAll('.notice-box');
	notices.forEach(notice => {
		notice.style.opacity = '0';
		notice.style.transform = 'translateY(-10px)';
		setTimeout(() => notice.remove(), 300);
	});
}, 5000);
</script>