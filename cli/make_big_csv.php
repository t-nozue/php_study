<?php
require_once dirname(__FILE__, 2) . '/bootstrap.php';

// make 10,000 line csv

define('LINE_COUNT', 10 * 1000);
define('CSV_FILE_PATH', DATA_DIR . '/big.csv');

file_exists(DATA_DIR) || mkdir(DATA_DIR, 0755, true);
$handle = fopen(CSV_FILE_PATH, 'w');
if ($handle == false) {
    echo Logger::error('Failed to open file: ' . CSV_FILE_PATH);
    exit;
}

for ($i = 0; $i < LINE_COUNT; $i++) {
    $csv_data = [($i + 1), 'a', 'b', 'c'];
    fputcsv($handle, $csv_data);
}
fclose($handle);
