<?php 
require '../check_admin_login.php';
require '../connect_database.php';

// Get order status statistics
$sql_command_select = "
    SELECT 
        status,
        COUNT(*) as count,
        CASE 
            WHEN status = 0 THEN 'Chờ xử lý'
            WHEN status = 2 THEN 'Chờ xác nhận'
            WHEN status = 3 THEN 'Shop đã hủy'
            WHEN status = 4 THEN 'Đang giao hàng'
            WHEN status = 5 THEN 'Hoàn thành'
            WHEN status = 7 THEN 'Khách hủy'
            ELSE 'Khác'
        END as status_name,
        CASE 
            WHEN status = 0 THEN '#ffc107'
            WHEN status = 2 THEN '#fd7e14'
            WHEN status = 3 THEN '#dc3545'
            WHEN status = 4 THEN '#0dcaf0'
            WHEN status = 5 THEN '#198754'
            WHEN status = 7 THEN '#6c757d'
            ELSE '#6f42c1'
        END as color
    FROM receipts 
    WHERE status IN (0, 2, 3, 4, 5, 7)
    GROUP BY status, status_name, color
    ORDER BY status
";

$query_sql_command_select = mysqli_query($connect_database, $sql_command_select);
$data = [];

while ($row = mysqli_fetch_assoc($query_sql_command_select)) {
    $data[] = [
        'name' => $row['status_name'],
        'y' => (int)$row['count'],
        'color' => $row['color']
    ];
}

// If no data, return empty array
if (empty($data)) {
    $data = [
        [
            'name' => 'Chưa có đơn hàng',
            'y' => 1,
            'color' => '#e9ecef'
        ]
    ];
}

mysqli_close($connect_database);
echo json_encode($data);
?>
