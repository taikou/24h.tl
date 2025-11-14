<?php
/*
 * -------------------------------------
 * Miguel Vasquez
 * DataConfig.php
 * @Class Controller
 * 
 * -------------------------------------
 */
/*
 * PARAMS DEFAULT
 * define('SITE_NAME', $settings->title ); 
 * Title from AdminSettings
 * 
 */
date_default_timezone_set('UTC'); 
 
$config = Config::singleton();
define('SITE_NAME', $settings->title ); //<-- SITE NAME EG: SOCIAL
define('DESCRIPTION_SITE', $settings->description ); //<-- SITE DESCRIPTION
define('NEW_REGISTRATIONS', $settings->new_registrations ); //<-- News registrations
define('EMAIL_VERIFY', $settings->email_verification ); //<--EMAIL_VERIFICATION
define('KEYWORDS_SITE', $settings->keywords ); //<-- SITE KEYWORDS
$config->set( 'BACKGROUND_INDEX', $settings->bg_index );
define( 'MAX_LENGTH', $settings->post_length );

/* EMAIL */
define( 'EMAIL_ACTIVE_ACCOUNT', 'activate@'.$_SERVER['SERVER_NAME'].'' );
define( 'EMAIL_PASS_RECOVER', 'recover@'.$_SERVER['SERVER_NAME'].'' );
define( 'EMAIL_NOTIFICATIONS', 'notifications@'.$_SERVER['SERVER_NAME'].'' );

/* PHPMAILER DATA SMTP */
define( 'SMTP_HOST', 'localhost' ); //Specify main and backup SMTP servers - smtp1.example.com;smtp2.example.com
define( 'SMTP_USERNAME', '' ); //SMTP username
define( 'SMTP_PASSWORD', '' ); //SMTP password
define( 'SMTP_PORT', 587 ); //TCP port to connect to

/* BIT URL PARAMS */
define( 'BIT_URL_USER', 'o_4tvvh0atu0' );
define( 'BIT_URL_KEY', 'R_7abbe12cf37846cfb678578a69f3e631' );

//<--- *  NOT MODIFIED * --->
define('DEFAULT_CONTROLLER', 'index');
define('DEFAULT_LAYOUT', 'default');
/*
 * FOLDERS/FILES ROOT
 * 
 * */
$config->set('controllersFolder', 'controllers/');
$config->set('modelsFolder', 'models/');
$config->set('viewsFolder', 'views/');
$config->set('tmpFolder', 'tmp/');
$config->set('avatarFolder', 'avatar/');
$config->set('photosListingFolder', 'photos-listing/');
/*
 * PARAMS
 * 
 * */

$config->set( 'time', date( 'Y-m-d G:i:s', time() ) );

/*
 * TWITTER KEY
 * 
 */
define('YOUR_CONSUMER_KEY', 'enKpW2b45QGGlmqVu9UaY1aT3'); //Consumer Key (API Key)
define('YOUR_CONSUMER_SECRET', 'rlVjXUbQ5fnkjcd1GvY1UhVzI7egJxScidhwwLbhfHlbn6mhsY'); //Consumer Secret (API Secret)

/*
 * FACEBOOK KEY
 * 
 */
define('APP_ID', '');//(API ID)
define('APP_SECRET', '');//(API Secret)

//Decoo追加
const DEFAULT_AVATARS=['bear.png','bird.png','butterfly.png','carp.png','cat.png','chicken.png','crab.png','dolphin.png','duck.png','dyno.png','elephant.png','fish.png','fox.png','frog.png','giraffe.png','gorilla.png','horse.png','kangaroo.png','lion.png','mouse.png','otter.png','panda.png','parrot.png','pelican.png','penguin.png','pig.png','rabbit.png','sea_lion.png','seahorse.png','shark.png','snake.png','swan.png','t_rex.png','turtle.png','whale.png','wolf.png'];

?>
