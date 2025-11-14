<?php
/*
 * DATABASE PARAMS
 * 
 * */
$config = Config::singleton();
$config->set('dbhost', 'localhost');
$config->set('dbname', '24h'); //<-- DATABASE NAME
$config->set('dbuser', 'root'); //<-- USER
$config->set('dbpass', 'abGOyc7OD0NcFd1jbf1jVgdQOsUmDeXk'); //<-- PASS
$config->set('dbchar', 'utf8mb4' ); //<-- NOT MODIFIED

/*
 * DATABASE TABLES  ** PLEASE NOT MODIFIED **
 * 
 * */
define('ADMIN_SETTINGS', 'admin_settings');
define('PAGES_GENERAL', 'pages_general');
/*
 * SITE NAME PATH
 * */
define('URL_BASE', 'https://24h.tl/'); //<-- IMPORTANT: place a backslash at the end

$nowtime=time();
?>
