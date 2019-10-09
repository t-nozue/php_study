<?php
class Logger
{
    /**
     * log level
     */
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

    /**
     * __construct function
     *
     * @param string $file_path
     */
    public function __construct($file_path = '')
    {
        if (!empty($file_path)) {
            $handle = fopen($file_path, 'w+');

            if ($handle) {
                $this->handle = $handle;
                $this->file_path = $file_path;
                return;
            } else {
                trigger_error(sprintf('Failed to open log file: %s', $file_path));
                return;
            }
        }

        file_exists(LOG_DIR) || mkdir(LOG_DIR, 0755, true);
        $this->file_path = sprintf('%s/app_%s.log', LOG_DIR, Util::getDateString('Ymd', 'now'));
        $this->handle = fopen($this->file_path, 'a+');

        if ($this->handle == false) {
            trigger_error(sprintf('Failed to open log file: %s', $this->file_path));
        }
    }

    /**
     * out function
     *
     * @param string $message
     * @param string $level
     * @return string|boolean
     */
    public function out($message = null, $level = self::LEVEL_INFO)
    {
        $level = mb_strtoupper($level);
        if (is_null($message) || !is_string($message) || !in_array($level, $this->level_list)) {
            return false;
        }

        $now = Util::getDateString('Y-m-d H:i:s', 'now');
        $log_message = sprintf('[%s] [%s] %s', $level, $now, $message) . PHP_EOL;
        fwrite($this->handle, $log_message);
        return $log_message;
    }

    /**
     * close function
     *
     * @return boolean
     */
    public function close()
    {
        return fclose($this->handle);
    }

    /**
     * getLogger function
     *
     * @param string $file_path
     * @return Logger
     */
    public static function getLogger($file_path = '')
    {
        static $instance;
        if (empty($instance)) {
            $instance = new Logger($file_path);
        }
        return $instance;
    }

    /**
     * debug function
     *
     * @param string $message
     * @return string|boolean
     */
    public static function debug($message)
    {
        $logger = self::getLogger();
        return $logger->out($message, self::LEVEL_DEBUG);
    }

    /**
     * info function
     *
     * @param string $message
     * @return string|boolean
     */
    public static function info($message)
    {
        $logger = self::getLogger();
        return $logger->out($message, self::LEVEL_INFO);
    }

    /**
     * warn function
     *
     * @param string $message
     * @return string|boolean
     */
    public static function warn($message)
    {
        $logger = self::getLogger();
        return $logger->out($message, self::LEVEL_WARN);
    }

    /**
     * error function
     *
     * @param string $message
     * @return string|boolean
     */
    public static function error($message)
    {
        $logger = self::getLogger();
        return $logger->out($message, self::LEVEL_ERROR);
    }

    /**
     * fatal function
     *
     * @param string $message
     * @return string|boolean
     */
    public static function fatal($message)
    {
        $logger = self::getLogger();
        return $logger->out($message, self::LEVEL_FATAL);
    }
}
