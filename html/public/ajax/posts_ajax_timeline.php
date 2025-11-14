<?php
	$key=$getPosts[$i];
	$urlStatus = URL_BASE.$getPosts[$i]['username'].'/status/'.$getPosts[$i]['id'];

	ob_start();
	include('post_details.php');
	$_array[]=ob_get_clean();
?>