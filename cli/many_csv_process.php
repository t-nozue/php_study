<?php
require_once dirname(__FILE__, 2) . '/bootstrap.php';

class ManyCsvProcessor
{
    const CSV_FILE_PATH = DATA_DIR . '/test_%03d.csv';
    const CHILD_PROCESS_COUNT = 4;
    const CSV_COUNT = 128;

    /**
     * execute function
     *
     * @return void
     */
    public function execute()
    {
        // fork process
        $csv_index = 0;
        while ($csv_index < self::CSV_COUNT) {
            $pid_list = [];
            for ($i = 0; $i < self::CHILD_PROCESS_COUNT; $i++) {
                $pid = pcntl_fork();
                if ($pid == -1) {
                    echo Logger::error('Failed to fork process.');
                    break;
                } elseif ($pid) {
                    // parent process
                    $pid_list[] = $pid;
                    $csv_index++;
                    if ($csv_index >= self::CSV_COUNT) {
                        break;
                    }
                } else {
                    // child process
                    $this->childProcess($csv_index);
                    exit;
                }
            }
            foreach ($pid_list as $pid) {
                pcntl_waitpid($pid, $status);
                unset($pid_list[$pid]);
            }
        }

    }

    /**
     * childProcess function
     *
     * @param int $csv_index
     * @return void
     */
    private function childProcess($csv_index)
    {
        // read csv file
        $csv_file_path = sprintf(self::CSV_FILE_PATH, $csv_index);
        $handle = fopen($csv_file_path, 'r');
        if ($handle == false) {
            echo Logger::error('Failed to open file: ' . $csv_file_path);
            return;
        }

        // TODO: do something
        while (!feof($handle)) {
            $csv_row = fgetcsv($handle);
        }

        // close csv file
        fclose($handle);

        // TODO: debug log
        Logger::debug(sprintf('Complete process (file: %s)', $csv_file_path));
    }
}

function main()
{
    $time_manager = new ProcessTimeManager();
    $time_manager->start();

    // main process
    $big_csv_processor = new ManyCsvProcessor();
    $big_csv_processor->execute();

    $time_manager->end();
}
main();
