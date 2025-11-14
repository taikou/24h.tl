<?php
session_start();
error_reporting(0);
if ( 
	isset( $_POST['id'] ) 
	&& !empty( $_POST['id'] ) 
	&& isset( $_POST['action'] ) 
	&& !empty( $_POST['action'] )
) {
  if ( isset( $_SESSION['authenticated'] ) ) {
	  require_once('../../class_ajax_request/classAjax.php');
	  $obj = new AjaxRequest();
	  if($_POST['action']=='remove'){
		  $obj->removeStaticPost($_SESSION['authenticated'],$_POST['id']);
		  die('REMOVED');
	  }elseif($_POST['action']=='set'){
		  $ret=$obj->setStaticPost($_SESSION['authenticated'],$_POST['id']);
		  if($ret){
			  die('SET');
		  }else{
			  die('NO_MORE_SET');
		  }
		  
	  }
  }
}

?>