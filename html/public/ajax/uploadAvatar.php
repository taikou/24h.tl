<?php
ini_set( 'memory_limit', '-1' );
session_start();
error_reporting( 0 );
require( '../../class_ajax_request/classAjax.php' );
include( '../../application/functions.php' );
include( '../../application/DataConfig.php' );
$session_id = $_SESSION[ 'authenticated' ]; //$session id
$path = "../../tmp/";
$path_avatar = "../avatar/";
$obj = new AjaxRequest();
$infoUser = $obj->infoUserLive( $_SESSION[ 'authenticated' ] );
$imgOld = $path_avatar . $infoUser->avatar;
$imgOldLarge = $path_avatar . 'large_' . $infoUser->avatar;

//error_log('start');
if ( isset( $session_id ) ) {
	$valid_formats = array( "jpg", "JPG", "jpeg", "png", "x-png", "gif", "pjpeg" );
	if ( isset( $_POST ) && $_SERVER[ 'REQUEST_METHOD' ] == "POST" ) {
		$name = $_FILES[ 'photo' ][ 'name' ];
		$size = $_FILES[ 'photo' ][ 'size' ];

		if ( strlen( $name ) ) {
			$ext = pathinfo( $name );
			if ( in_array( $ext[ 'extension' ], $valid_formats ) ) {
				if ( $size < ( 2250 * 2250 ) ) {
					if($ext['extension']=='gif'){
						$image = new Imagick();
						$image->readImage($_FILES['photo']['tmp_name']);
						if($image->getNumberImages() > 1){
							$is_animegif=true;
						}
					}
					$random = _Function::randomString( 5, FALSE, TRUE, FALSE );
					$photo_post = strtolower( $infoUser->username ) . "_" . $session_id . "" . $random . "." . strtolower( $ext[ 'extension' ] );
					$photo_large = 'large_' . strtolower( $infoUser->username ) . "_" . $session_id . "" . $random . "." . strtolower( $ext[ 'extension' ] );
					$tmp = $_FILES[ 'photo' ][ 'tmp_name' ];

					/* Get Width and Height */
					$dimensionsImage = getimagesize( $tmp );
					$widthImage = $dimensionsImage[ 0 ];
					$heightImage = $dimensionsImage[ 1 ];

					/* Width and Height */
					if ( $widthImage >= 400 && $heightImage >= 400 ) {
						if ( move_uploaded_file( $tmp, $path . $photo_large ) ) {

							//=============== px =================//
							$width = _Function::getWidth( $path . $photo_large );
							$height = _Function::getHeight( $path . $photo_large );
							$max_width = '800';

							if ( $width < $height ) {
								$max_width = '400';
							}

							if ( $width > $max_width ) {
								$scale = $max_width / $width;
							} else {
								$scale = 1;

							}
							if($is_animegif){													
								$image->setFirstIterator();
								$image=$image->coalesceImages();
								do{
									$image->scaleImage($width*$scale,$height*$scale);
								}while($image->nextImage());
								$image->setFirstIterator();

								$image = $image->deconstructImages();
				//				$image = $image->optimizeImageLayers();
								$image->writeImages($path.$photo_large,true);
								$uploaded = true;
				//				$image->clear();

							}else{
								$uploaded = _Function::resizeImage( $path . $photo_large, $width, $height, $scale, $path . $photo_large );
							}
							
							/* 200x200 px */
							$_size = 400;
							$file = $path . $photo_large;
							list( $width, $height, $imageType ) = getimagesize( $file );
							$imageType = image_type_to_mime_type( $imageType );
							$newImage = imagecreatetruecolor( $_size, $_size );

							switch ( $imageType ) {
								case "image/gif":
									if(!$is_animegif){
										$source = imagecreatefromgif( $file );
									}
									break;
								case "image/pjpeg":
								case "image/jpeg":
								case "image/jpg":
									$source = imagecreatefromjpeg( $file );
									break;
								case "image/png":
								case "image/x-png":
									$source = imagecreatefrompng( $file );
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
							
							if($is_animegif){
								$image->readImage($file);
								$image->setFirstIterator();
								$image=$image->coalesceImages();
								do{
									$image->scaleImage($_size,$_size);
								}while($image->nextImage());
								$image->setFirstIterator();
								$image = $image->deconstructImages();
								
							}else{
								imagecopyresampled( $newImage, $source, 0, 0, $x, $y, $new_width, $new_height, $width, $height );
							}
							
							switch ( $imageType ) {
								case "image/gif":
									$file = $path . $photo_post;
									if($is_animegif){
										$image->writeImages($file,true);
									}else{
										imagegif( $newImage, $file );
									}
									break;
								case "image/pjpeg":
								case "image/jpeg":
								case "image/jpg":
									$file = $path . $photo_post;
									imagejpeg( $newImage, $file, 90 );
									break;
								case "image/png":
								case "image/x-png":
									$file = $path . $photo_post;
									imagepng( $newImage, $file );
									break;
							}
							imagedestroy( $source );
							$image=null;

							//<=//   PHOTO LARGE     =//>
							$photo_post_id = $photo_post;

							//==================================================//
							//=            * COPY FOLDER AVATAR /         *    =//		
							//==================================================//
							if ( file_exists( $path . $photo_post ) && isset( $photo_post_id ) ) {

								/* 62x62x */
								copy( $path . $photo_post, $path_avatar . $photo_post );
								unlink( $path . $photo_post );

								/* Large Image */
								copy( $path . $photo_large, $path_avatar . $photo_large );
								unlink( $path . $photo_large );

							} //<--- IF FILE EXISTS	#2

							//<<<-- Delete old image -->>>/
							if ( file_exists( $imgOld ) &&
								!in_array( basename( $imgOld ), DEFAULT_AVATARS ) &&
								$photo_post_id ) {

								unlink( $imgOld );
							} //<--- IF FILE EXISTS #1

							if ( file_exists( $imgOldLarge ) ) {

								unlink( $imgOldLarge );
							} //<--- IF FILE EXISTS #1

							//<<<--- * UPDATE DB * -->>>
							$obj->uploadAvatar( $photo_post_id );

							echo json_encode( array( 'output' => '', 'error' => 0, 'photo' => $photo_post ) );
							//<--- move_uploaded_file
						} else {
							echo json_encode( array( 'output' => $_SESSION[ 'LANG' ][ 'error' ], 'error' => 1 ) );
						}
						//<--- Width && Height
					} else {
						echo json_encode( array( 'output' => $_SESSION[ 'LANG' ][ 'width_height_min_avatar' ], 'error' => 1 ) );
					}
					// size
				} else {
					echo json_encode( array( 'output' => $_SESSION[ 'LANG' ][ 'max_size_5' ], 'error' => 1 ) );
				}

				// Formats Images								
			} else {
				echo json_encode( array( 'output' => $_SESSION[ 'LANG' ][ 'formats_available' ], 'error' => 1 ) );
			}
			//Error		
		} else {
			echo json_encode( array( 'output' => $_SESSION[ 'LANG' ][ 'please_select_image' ], 'error' => 1 ) );
			exit;
		}
	} // ISSET
	// SESSION ACTIVE	
} else {
	echo json_encode( array( 'output' => $_SESSION[ 'LANG' ][ 'error' ], 'error' => 1, 'reload' => 1 ) );
	exit;
}
?>