<?php
/*
Plugin Name: AH-Card
Plugin URI: https://github.com/andreherberth/ahcard
Description: Creates an unique card number for PRO members based on role. 
Version: 0.1.5
Author: André Herberth
Author URI: http://fiverr.com/andreherberth
*/

/* 
First we initialize the plugin updater.
Credits for the code: https://github.com/YahnisElsts/plugin-update-checker
License: MIT License (https://en.wikipedia.org/wiki/MIT_License)
*/

require '3rdparty/updater/plugin-update-checker.php';
$className = PucFactory::getLatestClassVersion('PucGitHubChecker');
$myUpdateChecker = new $className(
    'https://github.com/andreherberth/ah-card',
    __FILE__,
    'master'
);

/* Then we load the init script. All code will reside there.  */
require 'init.php';
//Registering the activation hook. Installs the plugin. 

register_activation_hook( __FILE__, 'ah_card_activate' );
?>