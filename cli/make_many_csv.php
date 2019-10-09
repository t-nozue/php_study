<?php
require_once dirname(__FILE__, 2) . '/bootstrap.php';

// make 128 csv
define('CSV_COUNT', 128);
// make 1,000 line csv
define('LINE_COUNT', 1000);
define('CSV_FILE_PATH', DATA_DIR . '/test_%03d.csv');

file_exists(DATA_DIR) || mkdir(DATA_DIR, 0755, true);
for ($i = 0; $i < CSV_COUNT; $i++) {
    $csv_file_path = sprintf(CSV_FILE_PATH, $i);
    $handle = fopen($csv_file_path, 'w');
    if ($handle == false) {
        echo Logger::error('Failed to open file: ' . $csv_file_path);
        exit;
    }

    for ($j = 0; $j < LINE_COUNT; $j++) {
        $csv_data = [($j + 1), 'a', 'b', 'c'];
        fputcsv($handle, $csv_data);
    }
    fclose($handle);
}
