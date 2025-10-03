<?php
echo "<h2>PHP Upload Settings</h2>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Setting</th><th>Value</th></tr>";
echo "<tr><td>upload_max_filesize</td><td>" . ini_get('upload_max_filesize') . "</td></tr>";
echo "<tr><td>post_max_size</td><td>" . ini_get('post_max_size') . "</td></tr>";
echo "<tr><td>max_file_uploads</td><td>" . ini_get('max_file_uploads') . "</td></tr>";
echo "<tr><td>memory_limit</td><td>" . ini_get('memory_limit') . "</td></tr>";
echo "<tr><td>max_execution_time</td><td>" . ini_get('max_execution_time') . " seconds</td></tr>";
echo "<tr><td>file_uploads</td><td>" . (ini_get('file_uploads') ? 'Enabled' : 'Disabled') . "</td></tr>";
echo "<tr><td>upload_tmp_dir</td><td>" . (ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : 'Default') . "</td></tr>";
echo "</table>";

echo "<h2>Test Upload Form</h2>";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])) {
    echo "<h3>Upload Result:</h3>";
    echo "<pre>";
    print_r($_FILES['test_file']);
    echo "</pre>";
    
    if ($_FILES['test_file']['error'] === UPLOAD_ERR_OK) {
        echo "<p style='color: green;'>✓ Upload successful!</p>";
    } else {
        echo "<p style='color: red;'>✗ Upload failed with error code: " . $_FILES['test_file']['error'] . "</p>";
    }
}
?>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="test_file" accept="image/*">
    <button type="submit">Test Upload</button>
</form>
