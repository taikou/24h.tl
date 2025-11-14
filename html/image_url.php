<?php 
error_reporting(0);
$url = base64_decode($_GET['url']);
list( $width, $height, $imageType  ) = getimagesize( $url );
//error_log($width.'x'.$height);
$typeMime = image_type_to_mime_type( $imageType  );
header("Content-type: $typeMime");
function LoadJpeg($file,$typeMime, $width, $height) {
	$_size = 400;
	$im2 = imagecreatetruecolor($_size, $_size);
    //detect type and process accordinally
    switch($typeMime){
        case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
            $im = imagecreatefromjpeg($file); //jpeg file
        break;
        case "image/gif":
            $im = imagecreatefromgif($file); //gif file
      break;
       case "image/png":
	   case "image/x-png":
          $im = imagecreatefrompng($file); //png file
          imagefill( $im2, 0, 0, imagecolorallocate( $im2, 255, 255, 255 ) );
		  imagealphablending( $im2, TRUE );
      break;
    default: 
        $im=false;
    break;
    }
//calculate new image dimensions (preserve aspect)
if ( $width > $height ) {
	    $new_height = $_size;
	    $new_width = ( $width / $height ) * $new_height;
	    $x = ( $width - $height ) / 2;
	    $y = 0;
	} else {
	    $new_width = $_size;
	    $new_height = ( $height / $width ) * $new_width;
	    $y = ( $height - $width ) / 2;
	    $x = 0;
	} 
imagecopyresampled ($im2, $im, 0, 0, $x, $y, $new_width, $new_height, $width, $height);
return $im2;
}// ---End Function

$img = LoadJpeg($url,$typeMime,$width,$height);

imagejpeg($img);
imagedestroy($img); 
//imagedestroy($image);
?>