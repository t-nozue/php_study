<?php
class Logger
{
    const LEVEL_DEBUG = 'DEBUG';
    const LEVEL_INFO = 'INFO';
    const LEVEL_WARN = 'WARN';
    const LEVEL_ERROR = 'ERROR';
    const LEVEL_FATAL = 'FATAL';

    private $file_path;
    private $handle;
    private $level_list = [
        self::LEVEL_DEBUG,
        self::LEVEL_INFO,
        self::LEVEL_WARN,
        self::LEVEL_ERROR,
        self::LEVEL_FATAL,
    ];

    public function __construct($file_path = '')
    {
        if (!empty($file_path)) {
            $handle = fopen($file_path, 'w+');

            if ($handle) {
                $this->handle = $handle;
                $this->file_path = $file_path;
                return;
            } else {
                trigger_error(sprintf('log file is not open. file: %s', $file_path));
                return;
            }
        }

        file_exists(LOG_DIR) || mkdir(LOG_DIR, 0755, true);
        $this->file_path = sprintf('%s/app_%s.log', LOG_DIR, Util::getDateString('Ymd', 'now'));
        $this->handle = fopen($this->file_path, 'a+');

        if ($this->handle == false) {
            trigger_error(sprintf('log file is not open. file: %s', $this->file_path));
        }
    }

    public function out($message = null, $level = self::LEVEL_INFO)
    {
        $level = mb_strtoupper($level);
        if (is_null($message) || !is_string($message) || !in_array($level, $this->level_list)) {
            return;
        }

        $now = Util::getDateString('Y-m-d H:i:s', 'now');
        fwrite($this->handle, sprintf('[%s] [%s] %s', $level, $now, $message) . PHP_EOL);
        return;
    }

    public function close()
    {
        return fclose($this->handle);
    }

    static public function getLogger($file_path = '')
    {
        static $instance;
        if (empty($instance)) {
            $instance = new Logger($file_path);
        }
        return $instance;
    }

    static public function debug($message)
    {
        $logger = self::getLogger();
        $logger->out($message, self::LEVEL_DEBUG);
    }

    static public function info($message)
    {
        $logger = self::getLogger();
        $logger->out($message, self::LEVEL_INFO);
    }

    static public function warn($message)
    {
        $logger = self::getLogger();
        $logger->out($message, self::LEVEL_WARN);
    }

    static public function error($message)
    {
        $logger = self::getLogger();
        $logger->out($message, self::LEVEL_ERROR);
    }

    static public function fatal($message)
    {
        $logger = self::getLogger();
        $logger->out($message, self::LEVEL_FATAL);
    }
}
