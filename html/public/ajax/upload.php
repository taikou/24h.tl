<?php
ini_set('memory_limit', '-1');
session_start();
error_reporting(0);
include('../../application/functions.php'); 
$session_id  = $_SESSION['authenticated']; //$session id
$path        = "../../tmp/";
$path_upload = "../../upload/";

if ( isset( $session_id ) ) 
{
	$valid_formats = array("jpg", "JPG", "jpeg","png","x-png","gif","pjpeg");
	$_valid_formats = array("image/pjpeg", "image/jpeg", "image/jpg","image/png","image/x-png","image/gif");
	
	if( isset( $_POST ) && $_SERVER['REQUEST_METHOD'] == "POST")
		{
			$name = $_FILES['photo_upload']['name'];
			$size = $_FILES['photo_upload']['size'];
			$typeMime = $_FILES['photo_upload']['type'];
			$imgInfo  = getimagesize( $_FILES['photo_upload']['tmp_name'] );
			
			if( strlen( $name ) )
				{
					$ext = pathinfo( $name );
//	error_log('ext:'.print_r($ext,1).' mime:'.$imgInfo['mime'].' tmpname:'.$_FILES['photo_upload']['tmp_name'].'   '.print_r($_FILES,1));
					if( in_array( $ext['extension'], $valid_formats) && in_array( $typeMime , $_valid_formats) && in_array( $imgInfo['mime'], $_valid_formats) )
					{
					if( $size < ( 3000 * 3000 ) )
						{
							if($ext['extension']=='gif'){
								$image = new Imagick();
								$image->readImage($_FILES['photo_upload']['tmp_name']);
								if($image->getNumberImages() > 1){
									$is_animegif=true;
								}
							}
							
							$photo_post  = $session_id.""._Function::randomString( 14, FALSE, TRUE, FALSE ).".".strtolower ( $ext['extension'] );
							$tmp = $_FILES['photo_upload']['tmp_name'];
							if( move_uploaded_file( $tmp, $path.$photo_post ) ) {
										
									//=============== px =================//
									$width  = _Function::getWidth( $path.$photo_post );
									$height = _Function::getHeight( $path.$photo_post );
									$exif_datas = exif_read_data($path.$photo_post);
									if($exif_datas["Orientation"]==6 or $exif_datas["Orientation"]==8){
										$max_width = '900';

										if( $height < $width ) {
											$max_width = '600';
										}

										if ( $height > $max_width ) {
											$scale = $max_width / $height;
										} else {
											$scale = 1;
										}
									}else{


										$max_width = '900';

										if( $width < $height ) {
											$max_width = '600';
										}

										if ( $width > $max_width ) {
											$scale = $max_width / $width;
										} else {
											$scale = 1;
										}
									}
									if($is_animegif){													
										$image->setFirstIterator();
										$image=$image->coalesceImages();
										do{
											$image->scaleImage($width*$scale,$height*$scale);
										}while($image->nextImage());
										$image->setFirstIterator();
										$image = $image->deconstructImages();
										$image->writeImages($path.$photo_post,true);
										$uploaded = true;
										$image->clear();

									}else{
										$uploaded = _Function::resizeImage( $path.$photo_post, $width, $height, $scale, $path.$photo_post );
									}

									//<=//   PHOTO LARGE     =//>
									$photo_post_id = $photo_post;

									
				echo json_encode( array ( 'output' => '', 'error' => 0, 'photo' => $photo_post ) ); 
//<--- move_uploaded_file 
} else {
   	 echo json_encode( array ( 'output' => '<div class="alert alert-danger btn-sm">'. $_SESSION['LANG']['error'] .'</div>', 'error' => 1 ) );
  }
		//Size	
		}  else {
			 echo json_encode( array ( 'output' => '<div class="alert alert-danger btn-sm">'. $_SESSION['LANG']['max_size_9'] .'</div>', 'error' => 1 ) );	
		}
// Extension 
} else {
	 echo json_encode( array ( 'output' => '<div class="alert alert-danger btn-sm">'. $_SESSION['LANG']['formats_available'] .'</div>', 'error' => 1 ) );
 }
// StreLen 
} else {
	 echo json_encode( array ( 'output' => '<div class="alert alert-danger btn-sm">'. $_SESSION['LANG']['please_select_image'] .'</div>', 'error' => 1 ) );
		exit; 
}
 //Isset Post
 }
// SESSION ACTIVE 
} else {
	echo json_encode( array ( 'output' => '<div class="alert alert-danger btn-sm">Please login to perform this Action</div>', 'error' => 1 ) );
	exit;
}
?>