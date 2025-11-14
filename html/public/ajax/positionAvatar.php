<?php
session_start();
error_reporting(0);
require('../../class_ajax_request/classAjax.php');
include('../../application/functions.php'); 
include('../../application/DataConfig.php');
$session_id  = $_SESSION['authenticated']; //$session id
$path        = "../../tmp/";
$path_avatar = "../avatar/";
$obj         = new AjaxRequest();
$infoUser    = $obj->infoUserLive( $_SESSION['authenticated'] );
$imgOld      = $path_avatar.$infoUser->avatar;
$imgOldLarge = $path_avatar.'large_'.$infoUser->avatar;


if ( isset( $session_id ) 
&& isset ( $_POST['_avatarPosition'] )
) {

$ext         = pathinfo( $imgOldLarge );
$random      = _Function::randomString( 5, FALSE, TRUE, FALSE );
$strRandom   = strtolower( $infoUser->username )."_".$session_id."".$random;
$photo_post  = $strRandom.".".strtolower ( $ext['extension'] );
$exp         = explode( ' ', $_POST['_avatarPosition']);
$posX        = trim( $exp[0], 'px' );	
$posY        = trim( $exp[1], 'px' );		

			
									
	/* 200x200 px */
	$_size = 200;
	$file = $path_avatar.$imgOldLarge;
	
	list( $width, $height, $imageType  ) = getimagesize( $file );
	$imageType = image_type_to_mime_type($imageType);
	$newImage = imagecreatetruecolor( $_size, $_size);
	
	switch($imageType) {
	case "image/gif":
		$source=imagecreatefromgif($file); 
		break;
    case "image/pjpeg":
	case "image/jpeg":
	case "image/jpg":
		$source=imagecreatefromjpeg($file); 
		break;
    case "image/png":
	case "image/x-png":
		$source=imagecreatefrompng($file); 
		imagefill( $newImage, 0, 0, imagecolorallocate( $newImage, 255, 255, 255 ) );
		imagealphablending( $newImage, TRUE );
		break;
}

	if ( $width > $height ) {
	    $new_height = $_size;
	    $new_width = ( $width / $height ) * $new_height;
	
	    $x = ( $width - $height ) / 2;
	    $y = 0;
	} else {
	    $new_width = $_size;
	    $new_height = ( $height / $width ) * $new_width;
	    
		//($height-$width)/2
	    $y = ( $height - $width ) / 2;
	    $x = 0;
	}

    //imagecopyresampled( $newImage, $source, $posX, $posY, $posXzero, $posYzero, $new_width, $new_height, $width, $height ); 
    imagecopyresampled( $newImage, $source, $posX, $posY, 0, 0, $new_width, $new_height, $width, $height );
	
    switch($imageType) {
	case "image/gif":
		$file = $path_avatar.$photo_post;
  		imagegif($newImage,$file); 
		break;
  	case "image/pjpeg":
	case "image/jpeg":
	case "image/jpg":
		$file = $path_avatar.$photo_post;
  		imagejpeg($newImage,$file,90); 
		break;
	case "image/png":
	case "image/x-png":
		$file = $path_avatar.$photo_post;
		imagepng($newImage,$file);  
		break;
     }
    imagedestroy( $source );  
	
	
	//<=//   PHOTO LARGE     =//>
	$photo_post_id = $photo_post;
	
	rename($imgOldLarge, $path_avatar.'large_'.$strRandom.".".strtolower ( $ext['extension'] ));
	
	//<<<--- * UPDATE DB * -->>>
$res = $obj->uploadAvatarPosition( $photo_post_id );

if( $res == 1 )	{
	
	//<<<-- Delete old image -->>>/
	if ( file_exists( $imgOld ) 
		&& !in_array(basename($imgOld),DEFAULT_AVATARS)
		&& $photo_post_id ) {
			
		unlink( $imgOld );
	}//<--- IF FILE EXISTS #1
	
	echo json_encode( array ( 'output' => $_SESSION['LANG']['saved_successfully'], 'error' => 0, 'photo' => $photo_post ) ); 
	
} else {
	 echo json_encode( array ( 'output' => $_SESSION['LANG']['error'], 'error' => 1 ) ); 
}						

// SESSION ACTIVE	
} else {
	echo json_encode( array ( 'output' => $_SESSION['LANG']['error'], 'error' => 1, 'reload' => 1 ) );
	exit;
}
?>