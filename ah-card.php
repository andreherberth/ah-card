<?php
/*
Plugin Name: Ah-Card
Plugin URI: https://github.com/andreherberth/ahcard
Description: Creates an unique card number for PRO members based on role. 
Version: 0.2
Author: André Herberth
Author URI: http://fiverr.com/andreherberth
*/

/* 
First we initialize the plugin updater.
Credits for the code: https://github.com/YahnisElsts/plugin-update-checker
License: MIT License (https://en.wikipedia.org/wiki/MIT_License)
*/

require '3rdparty/updater/plugin-update-checker.php';
$myUpdateChecker = new $className(
    'https://github.com/andreherberth/ah-card',
    __FILE__,
    'master'
);

/* Then we load the init script. All code will reside there.  */
require 'init.php';
?>