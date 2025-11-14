<?php 
/*
 * ----------------------------------------
 * Class thumbnail
 * ---------------------------------------
 */
class thumbnail 
    { 
        private $image; 
        private $size_x; 
        private $size_y; 
		
        function thumbnail( $url ) 
        {
        	 $this->image=$url;
		} 
        function size( $size_x, $size_y ) 
        {
        	 $this->size_x = $size_x;
        	 $this->size_y = $size_y;
		} 
        function show() 
        {
        	if ( file_exists( $this->image ) )
		  {
 	            $imageinfo = getimagesize ( $this->image ); 
				$x = $imageinfo[0]; 
				$y = $imageinfo[1]; 
		  		$resize_x = $this->size_x / $x; 
				$resize_y = $this->size_y / $y; 
				if ( $resize_x < $resize_y ) 
				$resize = $resize_x; 
				else $resize = $resize_y; 
//	error_log('x:'.$x.' y:'.$y.' resize:'.$resize);				
					
				if($imageinfo[2] == 1){
					$hash=md5($this->image);
					$filename='/home/homepage/webs/24h.tl/html/public/anigif/anigif_'.$this->size_x.'x'.$this->size_y.'_'.$hash.'.gif';
					if(0 and file_exists($filename)){
						$imagedata=file_get_contents($filename);
						header("Content-type: image/gif");
						echo $imagedata;
						exit;
					}else{
						//GIFの場合はアニメかどうかのチェック
						$image = new Imagick();
						$fp=fopen($this->image,'rb');
						$image->readImageFile($fp);
						if($image->getNumberImages() > 1){
							//アニメGIFの場合の処理
	//		error_log('animegif!');
							$image->setFirstIterator();
							$image=$image->coalesceImages();
							do{
								$image->scaleImage($x*$resize,$y*$resize);
							}while($image->nextImage());
							$image->setFirstIterator();
	//						$image = $image->optimizeImageLayers();
							$image = $image->deconstructImages();
							header("Content-type: image/gif");
						//	$tmpfile='/dev/shm/'.uniqid().'.gif';
							$image->writeImages($filename,true);
							$imagedata=file_get_contents($filename);
						//	unlink($tmpfile);
							echo $imagedata;
						}
					}
					$image->clear();
				}else{
				
					header("Content-type: image/jpeg"); 


					if ( $imageinfo[2] == 1 )    $original_image=imagecreatefromgif($this->image); 
					if ( $imageinfo[2] == 2 )    $original_image=imagecreatefromjpeg($this->image); 
					if ( $imageinfo[2] == 3 )    $original_image=imagecreatefrompng($this->image); 
					if ( $imageinfo[2] > 3 )        die('Formato de imagen no soportado'); 

					

					$im    = imagecreatetruecolor( ceil ( $x * $resize ), ceil( $y * $resize ) );
					imagefill( $im, 0, 0, imagecolorallocate( $im, 255, 255, 255 ) );
					imagealphablending( $im, TRUE );
					imagecopyresampled( $im, $original_image,0,0,0,0, ceil( $x * $resize ), ceil( $y * $resize ), $x, $y ); 

					imagejpeg( $im, NULL, 90 ); 
					imagedestroy( $im ); 
				}
	         }//====== IF
	         
	         else 
			{
				header ('HTTP/1.1 404 Not Found');
				error_log('not found:'.$this->image);
				die('Error 404'); // ERROR 404
			}
        } 
    } 
?>