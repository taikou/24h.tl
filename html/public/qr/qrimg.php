<?php
session_start();
require_once('../../class_ajax_request/classAjax.php');
include_once('../../application/functions.php'); 
//include_once('../../application/DataConfig.php');	

if($_SESSION['authenticated']){
	$db=new SPDO();
	$sql="SELECT * FROM users WHERE id='".$_SESSION['authenticated']."' LIMIT 1";
	$uinfo=$db->query($sql)->fetch();

	$username=$uinfo['username'];
	$avatar_file='/home/homepage/webs/24h.tl/html/public/avatar/'.$uinfo['avatar'];
//	error_log($avatar_file);
}else{
	$username='?from=qr';
	$avatar_file='/home/homepage/webs/24h.tl/html/public/img/24h_icon_1bit.png';
}
$text='https://24h.tl/'.$username;
$cache_qrbase='/home/homepage/webs/24h.tl/html/cache/qr_base/'.$username.'.png';

if(!file_exists($cache_qrbase)){
	$url='https://qrcode-monkey.p.mashape.com/qr/custom';
	$header=array('X-Mashape-Key: qWlecCFBcpmshFTDk4PGpeKVD7q1p1GJBucjsnAwCUmbMLmF0Z');
	$data='
	{"data":"'.$text.'","config":{"body":"circle","eye":"frame5","eyeBall":"ball11","erf1":["fh"],
	"erf2":[],
	"erf3":["fv","fh"],
	"brf1":["fh"],
	"brf2":[],
	"brf3":["fv","fh"],"bodyColor":"#0277BD","bgColor":"#FFFFFF","eye1Color":"#075685","eye2Color":"#075685","eye3Color":"#075685","eyeBall1Color":"#0277BD","eyeBall2Color":"#0277BD","eyeBall3Color":"#0277BD","gradientColor1":"#F6407A","gradientColor2":"#5381F8","gradientType":"linear","gradientOnEyes":true,"logo":"#qrcodemonkey"},"size":600,"download":false,"file":"png"}
	';
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_HEADER, false); 
	curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	$response = curl_exec($ch); 
	//error_log('url:'.$url);
	//error_log('post:'.print_r($post,1));
	//error_log('os_res:'.($response));

	$qr_data=$response;
	$img_qr=imagecreatefromstring($qr_data);
	imagepng($img_qr,$cache_qrbase);
	curl_close($ch);
}else{
	$img_qr=imagecreatefrompng($cache_qrbase);
}

//error_log(imagesx($img_qr).'x'.imagesy($img_qr));
switch(substr($avatar_file,-4)){
	case '.gif':
		$img_avatar=imagecreatefromgif($avatar_file);
		break;
	case '.png':
		$img_avatar=imagecreatefrompng($avatar_file);
		break;
	default:
		$img_avatar=imagecreatefromjpeg($avatar_file);
		break;
}
$avatar_width=imagesx($img_avatar);
$avatar_height=imagesy($img_avatar);

$img_avatar_back=imagecreatetruecolor(220,220);
$background_color = imagecolorallocate($img_avatar_back, 255, 255, 255); //white
imagefill($img_avatar_back,0,0,$background_color);

imagecopyresampled($img_avatar_back, $img_avatar, 10,10,0,0,200,200,$avatar_width,$avatar_height);

imagecopyresampled($img_qr,$img_avatar_back,241,241,0,0,220,220,220,220);

//imagepng($img_qr);

$img_output=imagecreatetruecolor(693,740);
$background_color = imagecolorallocate($img_output, 255, 255, 255); //white
imagefill($img_output,0,0,$background_color);

$img_logo=imagecreatefrompng('/home/homepage/webs/24h.tl/html/public/img/logo.png');

$font     = '/home/homepage/webs/kikiho.net/html/labo/kabemimi/laouib.ttf';// path
$fontSize=20;
$box    = imagettfbbox($fontSize, 0, $font, $text);
//error_log(print_r($box,1));
$txtwidth=$box[2] - $box[6] + 10;
$txtheight=$box[3] - $box[7] + 10;
$txtimg    = imagecreatetruecolor($txtwidth,$txtheight );
$color  = imagecolorallocate($txtimg, 48, 48, 48);
$backgroundColor = imagecolorallocate($txtimg, 255, 255, 255);

imagefill($txtimg, 0, 0, $backgroundColor);
imagettftext($txtimg, $fontSize, 0, 0, ($txtheight-10), $color, $font, $text);

imagecopyresampled($img_output, $img_qr, 0,0,0,0,693,693,693,693);

imagecopyresampled($img_output, $txtimg, 30,690,0,0,$txtwidth,$txtheight,$txtwidth,$txtheight);

imagecopyresampled($img_output, $img_logo, 420,680,0,0,264,42,264,42);

header('Content-Type: image/png');
imagepng($img_output);

?>