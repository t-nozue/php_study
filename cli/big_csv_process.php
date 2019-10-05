<?php
require_once dirname(__FILE__, 2) . '/bootstrap.php';

// TODO: WIP
$time_manager = new ProcessTimeManager();
$time_manager->start();

Logger::debug('test');

$time_manager->end();
