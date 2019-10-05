<?php
require_once dirname(__FILE__) . '/define.php';

function autoLoadClass($classname)
{
    // auto load directories
    $dir_list = [
        'common',
    ];

    foreach ($dir_list as $dir) {
        $path = sprintf('%s/%s/%s.php', dirname(__FILE__), $dir, $classname);
        if (file_exists($path)) {
            require $path;
            return true;
        }
    }
}

spl_autoload_register('autoLoadClass');
