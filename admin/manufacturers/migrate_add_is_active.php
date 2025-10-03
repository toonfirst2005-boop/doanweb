<?php
/**
 * Migration script để thêm cột is_active vào bảng manufacturers
 * Chạy file này một lần để cập nhật database
 */

require '../check_super_admin_login.php';
require '../connect_database.php';

echo "<h2>Migration: Thêm cột is_active vào bảng manufacturers</h2>";

// Kiểm tra cột is_active có tồn tại không
$sql_check_column = "SHOW COLUMNS FROM manufacturers LIKE 'is_active'";
$result_check_column = mysqli_query($connect_database, $sql_check_column);

if (mysqli_num_rows($result_check_column) == 0) {
    echo "<p>Đang thêm cột is_active...</p>";
    
    // Thêm cột is_active
    $sql_add_column = "ALTER TABLE manufacturers ADD COLUMN is_active TINYINT(1) DEFAULT 1 COMMENT 'Trạng thái hợp tác: 1=Đang hợp tác, 0=Ngừng hợp tác'";
    $result = mysqli_query($connect_database, $sql_add_column);
    
    if ($result) {
        echo "<p style='color: green;'>✅ Thêm cột is_active thành công!</p>";
        
        // Cập nhật tất cả nhà sản xuất hiện có thành trạng thái "đang hợp tác"
        $sql_update = "UPDATE manufacturers SET is_active = 1 WHERE is_active IS NULL";
        $result_update = mysqli_query($connect_database, $sql_update);
        
        if ($result_update) {
            $affected_rows = mysqli_affected_rows($connect_database);
            echo "<p style='color: green;'>✅ Cập nhật trạng thái cho $affected_rows nhà sản xuất thành công!</p>";
        }
        
        echo "<h3>Cấu trúc mới của bảng manufacturers:</h3>";
        $sql_describe = "DESCRIBE manufacturers";
        $result_describe = mysqli_query($connect_database, $sql_describe);
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = mysqli_fetch_array($result_describe)) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
    } else {
        $error = mysqli_error($connect_database);
        echo "<p style='color: red;'>❌ Lỗi khi thêm cột: $error</p>";
    }
} else {
    echo "<p style='color: blue;'>ℹ️ Cột is_active đã tồn tại!</p>";
    
    // Hiển thị thông tin cột hiện có
    $column_info = mysqli_fetch_array($result_check_column);
    echo "<p>Thông tin cột hiện có:</p>";
    echo "<ul>";
    echo "<li><strong>Field:</strong> " . $column_info['Field'] . "</li>";
    echo "<li><strong>Type:</strong> " . $column_info['Type'] . "</li>";
    echo "<li><strong>Null:</strong> " . $column_info['Null'] . "</li>";
    echo "<li><strong>Default:</strong> " . $column_info['Default'] . "</li>";
    echo "</ul>";
}

mysqli_close($connect_database);

echo "<br><p><a href='index_manufacturers.php'>← Quay lại danh sách nhà sản xuất</a></p>";
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background: #f5f5f5;
}

h2 {
    color: #333;
    border-bottom: 2px solid #667eea;
    padding-bottom: 10px;
}

p {
    background: white;
    padding: 10px;
    border-radius: 5px;
    margin: 10px 0;
}

table {
    background: white;
    border-radius: 5px;
    overflow: hidden;
    margin: 10px 0;
}

th {
    background: #667eea;
    color: white;
    padding: 10px;
}

td {
    padding: 8px 10px;
}

tr:nth-child(even) {
    background: #f9f9f9;
}
</style>

