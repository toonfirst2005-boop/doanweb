<?php 
require '../check_admin_login.php';
require '../connect_database.php';

// Get top selling products in last 30 days
$sql_command_select = "
    SELECT 
        p.name as product_name,
        SUM(rd.quantity) as total_sold,
        p.price,
        (SUM(rd.quantity) * p.price) as total_revenue
    FROM receipt_detail rd
    JOIN products p ON p.id = rd.product_id
    JOIN receipts r ON r.id = rd.receipt_id
    WHERE DATE(r.order_time) >= (CURDATE() - INTERVAL 30 DAY)
    AND r.status IN (4, 5)  -- Only completed and shipped orders
    GROUP BY p.id, p.name, p.price
    ORDER BY total_sold DESC
    LIMIT 10
";

$query_sql_command_select = mysqli_query($connect_database, $sql_command_select);
$data = [];

$colors = [
    '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7',
    '#DDA0DD', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E9'
];

$colorIndex = 0;

while ($row = mysqli_fetch_assoc($query_sql_command_select)) {
    $data[] = [
        'name' => $row['product_name'],
        'y' => (int)$row['total_sold'],
        'color' => $colors[$colorIndex % count($colors)],
        'revenue' => (float)$row['total_revenue']
    ];
    $colorIndex++;
}

// If no data, return sample data
if (empty($data)) {
    $data = [
        [
            'name' => 'Chưa có dữ liệu',
            'y' => 1,
            'color' => '#e9ecef'
        ]
    ];
}

mysqli_close($connect_database);
echo json_encode($data);
?>

