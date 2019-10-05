<?php
class ProcessTimeManager
{
    private $start_time;
    private $end_time;

    public function start()
    {
        $this->start_time = microtime(true);
        echo '----- process start. -----' . PHP_EOL;
    }

    public function end()
    {
        if (empty($this->start_time)) {
            Logger::error('process is not started by time manager.');
            return;
        }

        $this->end_time = microtime(true);
        echo sprintf('----- process end. time: %f s', $this->end_time - $this->start_time) . PHP_EOL;
    }
}
