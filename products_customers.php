<?php
require 'admin/connect_database.php';

$sql = "SELECT * FROM products WHERE (is_deleted IS NULL OR is_deleted = 0)";
$result = mysqli_query($connect_database, $sql);
?>

<style>
.product-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 35px;
    padding: 60px 40px;
    max-width: 1400px;
    margin: 0 auto;
    background: white;
    min-height: 50vh;
}

.product-item {
    background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 24px;
    padding: 0;
    text-align: center;
    box-shadow: 0 8px 30px rgba(0,0,0,0.06);
    transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
    border: none;
    position: relative;
    overflow: hidden;
}

.product-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(145deg, 
        rgba(255, 182, 193, 0.15) 0%, 
        rgba(255, 218, 185, 0.15) 35%,
        rgba(173, 216, 230, 0.15) 70%,
        rgba(221, 160, 221, 0.15) 100%);
    opacity: 0;
    transition: opacity 0.5s ease;
    z-index: 0;
    border-radius: 24px;
}

.product-item:hover::before {
    opacity: 1;
}

.product-item:hover {
    transform: translateY(-12px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.12);
}

.product-item img {
    width: 100%;
    height: 280px;
    object-fit: cover;
    border-radius: 24px 24px 0 0;
    transition: transform 0.5s ease;
    position: relative;
    z-index: 1;
}

.product-item:hover img {
    transform: scale(1.05);
}

.product-content {
    padding: 25px 20px;
    position: relative;
    z-index: 1;
}

.product-item h3 {
    font-size: 19px;
    margin: 0 0 12px;
    color: #1a1a1a;
    font-weight: 600;
    line-height: 1.5;
    min-height: 58px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 1;
    letter-spacing: -0.3px;
}

.product-item .price {
    font-weight: 700;
    font-size: 24px;
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 12px 0 20px;
    position: relative;
    z-index: 1;
}

.product-item .button-add-to-cart {
    display: inline-block;
    padding: 14px 36px;
    background: linear-gradient(135deg, #ff6b9d 0%, #c44569 100%);
    color: #fff;
    border: none;
    border-radius: 50px;
    text-decoration: none;
    cursor: pointer;
    font-weight: 600;
    font-size: 15px;
    transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
    box-shadow: 0 8px 25px rgba(255, 107, 157, 0.35);
    position: relative;
    z-index: 1;
    letter-spacing: 0.3px;
    overflow: hidden;
}

.product-item .button-add-to-cart::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #c44569 0%, #ff6b9d 100%);
    transition: left 0.4s ease;
    z-index: -1;
}

.product-item .button-add-to-cart:hover::before {
    left: 0;
}

.product-item .button-add-to-cart:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(255, 107, 157, 0.5);
}

.product-item .button-add-to-cart:active {
    transform: translateY(-1px);
}

/* Soft pastel color variants for cards */
.product-item:nth-child(4n+1) {
    background: linear-gradient(145deg, #fff5f7 0%, #ffffff 100%);
}

.product-item:nth-child(4n+2) {
    background: linear-gradient(145deg, #f0f9ff 0%, #ffffff 100%);
}

.product-item:nth-child(4n+3) {
    background: linear-gradient(145deg, #fef3f0 0%, #ffffff 100%);
}

.product-item:nth-child(4n+4) {
    background: linear-gradient(145deg, #f5f0ff 0%, #ffffff 100%);
}

@media (max-width: 1200px) {
    .product-container {
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 25px;
        padding: 40px 25px;
    }
}

@media (max-width: 768px) {
    .product-container {
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
        padding: 30px 15px;
    }
    
    .product-item img {
        height: 220px;
    }
    
    .product-item h3 {
        font-size: 16px;
        min-height: 48px;
    }
    
    .product-item .price {
        font-size: 20px;
    }
    
    .product-item .button-add-to-cart {
        padding: 12px 28px;
        font-size: 14px;
    }
}
</style>
<?php
$img_path = "/banhang/admin/products/";
?>
<div class="product-container">
    <?php while ($row = mysqli_fetch_array($result)) { ?>
        <div class="product-item">
            <img src="<?php echo $img_path . $row['image']; ?>" alt="<?php echo $row['name']; ?>">
            <div class="product-content">
                <h3><?php echo $row['name']; ?></h3>
                <p class="price"><?php echo number_format($row['price'], 0, ',', '.'); ?>₫</p>
                <button class="button-add-to-cart" data-id="<?php echo $row['id']; ?>">Thêm vào giỏ</button>
            </div>
        </div>
    <?php } ?>
</div>

