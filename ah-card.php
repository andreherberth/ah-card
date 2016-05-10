<?php
/*
Plugin Name: Ah-Card
*/

/* 
First we initialize the plugin updater.
Credits for the code: https://github.com/YahnisElsts/plugin-update-checker
License: MIT License (https://en.wikipedia.org/wiki/MIT_License)
*/

require '3rdparty/updater/plugin-update-checker.php';
$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://repo.ourwp.net/ah-card/metadata.json',
    __FILE__
);

/* Then we load the init script. All code will reside there.  */
require 'init.php';

//Activating Activate function!


/*

Note on coding convention used.

Variables are prefixed ah_c_variablename

Functions and classes use ah_card_function/classname.

*/
?>