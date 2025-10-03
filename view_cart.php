<?php 
session_start();
require_once 'update_session_name.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - ShopModern</title>
    <link rel="stylesheet" href="style_index_customers.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<?php require 'menu_customers.php' ?>

<div class="cart-container">
    <h1 class="cart-title">
        <i class="fas fa-shopping-cart"></i> Giỏ hàng của bạn
    </h1>

<?php if (empty($cart)) : ?>
    <div class="empty-cart">
        <i class="fas fa-shopping-basket"></i>
        <h2>Giỏ hàng trống</h2>
        <p>Bạn chưa có sản phẩm nào trong giỏ hàng</p>
        <a href="index_customers.php" class="btn-continue-shopping">
            <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
        </a>
    </div>
<?php else: ?>
    <table width="100%" border="1px solid black" cellspacing="0" cellpadding="5">
        <tr>
            <th>Ảnh</th>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Tổng tiền</th>
            <th>Xóa</th>
        </tr>

        <?php foreach ($cart as $id => $array_products) : ?>
            <?php
                $sum = $array_products['price'] * $array_products['quantity'];
                $total += $sum;
            ?>
            <tr>
                <td>
                    <img height="100px" src="admin/products/<?php echo $array_products['image'] ?>">
                </td>
                <td><?php echo $array_products['name'] ?></td>
                <td>
                    <span class="span-price">
                        <?php echo $array_products['price'] ?>    
                    </span>
                </td>
                <td>
                    <button class="button-update-quantity" data-id='<?php echo $id ?>' data-type='decrease'>-</button>
                    <span class="span-quantity"><?php echo $array_products['quantity']; ?></span>
                    <button class="button-update-quantity" data-id='<?php echo $id ?>' data-type='increase'>+</button>
                </td>
                <td>
                    <span class="span-sum">
                        <?php echo $sum ?>
                    </span>
                </td>
                <td>
                    <button class="button-delete" data-id='<?php echo $id ?>'>Xóa</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h1>
        Tổng tiền là 
        <span class="span-total"><?php echo $total ?></span>
    </h1>
<?php endif; ?>


<?php 
// Nếu có đăng nhập mới lấy thông tin khách
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    require 'admin/connect_database.php';
    $sql_command_select = "select * from customers where id = '$id'";
    $query_sql_command_select = mysqli_query($connect_database, $sql_command_select);
    $array_customer = mysqli_fetch_array($query_sql_command_select);
}
?>

<form method="post" action="process_order.php">
    <h2 style="margin-bottom: 20px; color: #1a1a1a; font-size: 24px;">
        <i class="fas fa-shipping-fast" style="color: #ff6b9d;"></i> Thông tin giao hàng
    </h2>
    
    <label><i class="fas fa-user"></i> Tên người nhận</label>
    <input type="text" name="receiver_name" 
        placeholder="Nhập tên người nhận"
        value="<?php echo isset($array_customer['name']) ? htmlspecialchars($array_customer['name']) : '' ?>" required>
    
    <label><i class="fas fa-phone"></i> Số điện thoại người nhận</label>
    <input type="text" name="receiver_phone" 
        placeholder="Nhập số điện thoại"
        value="<?php echo isset($array_customer['phone']) ? htmlspecialchars($array_customer['phone']) : '' ?>" required>
    
    <label><i class="fas fa-map-marker-alt"></i> Địa chỉ người nhận</label>
    <input type="text" name="receiver_address" 
        placeholder="Nhập địa chỉ giao hàng"
        value="<?php echo isset($array_customer['address']) ? htmlspecialchars($array_customer['address']) : '' ?>" required>
    
    <button type="submit">
        <i class="fas fa-check-circle"></i> Đặt hàng ngay
    </button>
</form>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $(".button-update-quantity").click(function(event) {
        var button = $(this)
        var id = button.data('id')
        var type = button.data('type')

        $.ajax({
            url: 'process_update_quantity_in_cart.php',
            type: 'get',
            data: {id,type},
        })
        .done(function() {
            var parent_tr = button.parents('tr')
            var quantity = Number(parent_tr.find('.span-quantity').text())
            var price = Number(parent_tr.find('.span-price').text())
            if ( type == 'increase' ) {
                quantity++;
            } else {
                quantity--;
            }
            if ( quantity == 0 ) {
                parent_tr.remove();
            } else {
                parent_tr.find('.span-quantity').text(quantity)
                var sum = price * quantity
                parent_tr.find('.span-sum').text(sum)
            }
            get_total()
        })
    })

    $(".button-delete").click(function() {
        var button = $(this)
        var id = button.data('id')
        var parent_tr = button.parents('tr')
        $.ajax({
            url: 'process_delete_cart.php',
            type: 'get',
            data: {id},
        })
        .done(function() {
            parent_tr.remove()
            get_total()
        })
    })
}) 

function get_total() {
    var total = 0
    $('.span-sum').each(function() {
        total += Number($(this).text())
    })
    $('.span-total').text(total)
}
</script>

</body>
</html>
