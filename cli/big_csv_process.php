<?php
require_once dirname(__FILE__, 2) . '/bootstrap.php';

class BigCsvProcessor
{
    const CSV_FILE_PATH = DATA_DIR . '/big.csv';
    const CHILD_PROCESS_COUNT = 4;

    /**
     * execute function
     *
     * @return void
     */
    public function execute()
    {
        // read csv file
        $handle = fopen(self::CSV_FILE_PATH, 'r');
        if ($handle == false) {
            echo Logger::error('Failed to open file: ' . self::CSV_FILE_PATH);
            return;
        }

        // fork process
        while (!feof($handle)) {
            $pid_list = [];
            for ($i = 0; $i < self::CHILD_PROCESS_COUNT; $i++) {
                $csv_row = fgetcsv($handle);
                $pid = pcntl_fork();
                if ($pid == -1) {
                    echo Logger::error('Failed to fork process.');
                    break;
                } elseif ($pid) {
                    // parent process
                    $pid_list[] = $pid;
                } else {
                    // child process
                    $this->childProcess($csv_row);
                    exit;
                }
            }
            foreach ($pid_list as $pid) {
                pcntl_waitpid($pid, $status);
                unset($pid_list[$pid]);
            }
        }

        // close csv file
        fclose($handle);
    }

    /**
     * childProcess function
     *
     * @param array $csv_row
     * @return void
     */
    private function childProcess($csv_row)
    {
        // TODO: do something

        // TODO: debug log
        Logger::debug('process line: ' . $csv_row[0]);
    }
}

function main()
{
    $time_manager = new ProcessTimeManager();
    $time_manager->start();

    // main process
    $big_csv_processor = new BigCsvProcessor();
    $big_csv_processor->execute();

    $time_manager->end();
}
main();
