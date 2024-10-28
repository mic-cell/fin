<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = $_POST['data'];
    $file = 'data.txt';

    // Append data to the file
    file_put_contents($file, $data . PHP_EOL, FILE_APPEND);
}
?>
