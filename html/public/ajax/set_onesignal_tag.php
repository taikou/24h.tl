<?php
session_start();
error_reporting(0);

$post=$_POST;
//error_log(print_r($post,1));
$url='https://onesignal.com/api/v1/players/'.$post['oneSignalUserId'];

$data['app_id']='8af3f38a-d025-42e7-a06d-a0ac4d56ea2b';
$data['tags']=array('user_id'=>$_SESSION['authenticated']);

$data=json_encode($data);

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_HEADER, false); 
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
$response = curl_exec($ch); 
//error_log('url:'.$url);
//error_log('post:'.print_r($post,1));
//error_log('os_res:'.($response));
curl_close($ch); 


$_SESSION['onesignal']=$post;