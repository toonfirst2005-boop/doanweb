<?php
// Helper function to update session name from database
if (!empty($_SESSION['id'])) {
    require_once 'admin/connect_database.php';
    $customer_id = $_SESSION['id'];
    $sql_customer = "SELECT name FROM customers WHERE id = '$customer_id'";
    $result_customer = mysqli_query($connect_database, $sql_customer);
    if ($result_customer && mysqli_num_rows($result_customer) > 0) {
        $customer = mysqli_fetch_array($result_customer);
        $_SESSION['name'] = $customer['name'];
    }
    mysqli_close($connect_database);
}
?>
