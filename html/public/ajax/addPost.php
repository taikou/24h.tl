<?php
session_start();
error_reporting(0);
if ( 
		isset ( $_POST['add_post'] ) 
		&& !empty( $_POST['add_post'] ) 
		|| isset ( $_POST['photoId'] ) 
		&& !empty( $_POST['photoId'] ) 
) {
  if ( isset ( $_SESSION['authenticated'] ) ) {
		/*
		 * --------------------------
		 *   Require/Include Files
		 * -------------------------
		 */
		require_once('../../class_ajax_request/classAjax.php');
		include_once('../../application/functions.php'); 
		include_once('../../application/DataConfig.php');
		/*
		 * ----------------------------
		 * Instance Class
		 * ----------------------------
		 */
		$obj      = new AjaxRequest();
		$infoUser = $obj->infoUserLive( $_SESSION['authenticated'] );
		$admin    = $obj->getSettings();
		
		/*
		 * ---------------------------------------------------------
		 * Photo Data and Root
		 * --------------------------------------------------------
		 */
		$path                     = "../../tmp/";
		$rootUpload               = '../../upload/';
		$photoID                  = $_POST['photoId'];
		$_POST['_geolocation']    = trim( strip_tags( $_POST['_geolocation'] ) );
		$_POST['url']             = '';
		$_POST['url_thumbnail']   = '';
		$_POST['url_title']       = '';
		$_POST['url_description'] = '';
		$_POST['url_host']        = '';
		
		/*
		 * ---------------------------------------------------------
		 * Getting the first URL of publication
		 * --------------------------------------------------------
		 */
		 
		 if( $photoID == '' ) {
		 	
			//preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $_POST['add_post'], $_matches);
			preg_match_all('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', $_POST['add_post'], $_matches);

				 foreach ( $_matches as $_key ) {
					$_key = array_unique(  $_key );
				}
				$_numUrls = count( $_matches[1] );
				
				$firstURL = $_matches[0][0];
			
			if( !empty( $firstURL ) ) {
			   $urlMedia = _Function::expand_link( $firstURL );
		    } else {
		    	$urlMedia = NULL;
		    }
		 } else {
		 	$urlMedia = '';
		 }

		/*
		 * ---------------------------------------------------------
		 * SoundCloud
		 * --------------------------------------------------------
		 */
		$isValidUrlSoundCloud = _Function :: isValidSoundCloudURL( $urlMedia ) ? 1 : 0;
		$dataSoundCloud       = _Function :: isValidSoundCloudURL( $urlMedia );
		
		
		/*
		 * ---------------------------------------------------------
		 * Youtube 
		 * --------------------------------------------------------
		 */
		$isValidYoutube    = _Function::isValidYoutubeURL( $urlMedia ) ? 1: 0; // 1 Valid 0 Not Valid
		$dataVideoYoutube  = _Function::isValidYoutubeURL( $urlMedia ); 
		$idVideoYoutube    = _Function::getYoutubeId( $urlMedia );
		//print_r($dataVideoYoutube);
		/*
		 * ---------------------------------------------------------
		 * Vimeo
		 * --------------------------------------------------------
		 */
		$isValidVimeoURL   = _Function::isValidVimeoURL( $urlMedia ) ? 1 : 0; // 1 Valid 0 Not Valid
		$dataVideoVimeo    = _Function::isValidVimeoURL( $urlMedia ); 
	
	
		/*
		 * ---------------------------------------------------------
		 * Dailymotion
		 * --------------------------------------------------------
		 */
		 $isValidDailymotionURL = _Function::isValidDailymotionURL( $urlMedia ) ? 1 : 0;
		 $dataDailymotion       = _Function::isValidDailymotionURL( $urlMedia );
		 
		 /*
		 * ---------------------------------------------------------
		 * Screenr 
		 * --------------------------------------------------------
		 */
		 $isValidScreenrURL = _Function::isValidScreenrURL( $urlMedia ) ? 1 : 0;
		 $dataScreenr       = _Function::isValidScreenrURL( $urlMedia );
		
		/*
		 * ---------------------------------------------------------
		 * Dotsub 
		 * --------------------------------------------------------
		 */
		 $isValidDotsubURL = _Function::isValidDotsubURL( $urlMedia ) ? 1 : 0;
		 $dataDotsub       = _Function::isValidDotsubURL( $urlMedia );
		 
		 /*
		 * ---------------------------------------------------------
		 * Hulu 
		 * --------------------------------------------------------
		 */
		 $isValidHuluURL = _Function::isValidHuluURL( $urlMedia ) ? 1 : 0;
		 $dataHulu       = _Function::isValidHuluURL( $urlMedia );
		 
		 /*
		 * ---------------------------------------------------------
		 * SlideShare 
		 * --------------------------------------------------------
		 */
		 $isValidSlideShareURL = _Function::isValidSlideShareURL( $urlMedia ) ? 1 : 0;
		 $dataSlideShare       = _Function::isValidSlideShareURL( $urlMedia );
		 
		 /*
		 * ---------------------------------------------------------
		 * Blip
		 * --------------------------------------------------------
		 */
		 $isValidBlipURL = _Function::isValidBlipURL( $urlMedia ) ? 1 : 0;
		 $dataBlip       = _Function::isValidBlipURL( $urlMedia );
		 
		 if( $isValidSlideShareURL === 1 ) {
		 	
			$htmlSlideShare = $dataSlideShare->{'html'}; 
	
			$xpath    = new DOMXPath(@DOMDocument::loadHTML($htmlSlideShare));
			$urlSlideShare = $xpath->evaluate("string(//iframe/@src)");
			$urlSlideShare = str_replace( '\\' , '', $urlSlideShare);
			$urlSlideShare = str_replace( '"' , '', $urlSlideShare);
	
			$_POST['doc_site']      = strtolower( $dataSlideShare->{'provider_name'});
			$_POST['doc_url']       = $urlSlideShare;
			
		 } else {
		 	$_POST['doc_site']      = '';
			$_POST['doc_url']       = '';
		 }
		
		
		/*
		 *--------------------
		 * SOUNDCLOUD
		 * if soundCloud is true
		 * @$_POST['photoId'] == false
		 * @$_POST['video'] == false
		 *-------------------- 
		 */		 
		 if( $isValidUrlSoundCloud === 1 ) {
		 	$_POST['video']         = '';
			$_POST['song']          = $urlMedia;
			$_POST['song_title']    = $dataSoundCloud->{'title'}.' (SoundCloud)';
			$_POST['thumbnail_song'] = preg_replace( '/(\-t500x500|\-t120x120+)/', '-large', $dataSoundCloud->{'thumbnail_url'} );
			
			
			/* DELETE PHOTO UPLOAD */
            chmod( $rootUpload.$photoID, 0777 );
			 if ( file_exists( $path.$photoID ) && $photoID != '' ) {
					unlink( $path.$photoID );
					
				}//<--- IF FILE EXISTS
				
				$_POST['photoId'] = '';
		 }//<<-- if valid
		    
		if( $isValidUrlSoundCloud === 0 ) {
			$_POST['song_title'] = '';
			$_POST['song'] = '';
			$_POST['thumbnail_song'] = '';
		}

		$error             = 0;
		$pos_details_r_n   = preg_replace('/(?:(?:\r\n|\r|\n)\s*){2}/s', "\r\n\r\n", $_POST['add_post']); 
		$pos_details_clean = trim($pos_details_r_n,"\r\n");
						
		$_POST['token_id'] = _Function::idHash( $_SESSION['authenticated'] );
		$pos_details       = _Function::checkText( $pos_details_clean );
		$_POST['add_post'] = _Function::checkTextDb( $_POST['add_post'] );
		
		
		/*
		 * -------------------------------------------
		 * If is greater than the default character 
		 * -------------------------------------------
		 */
		if( mb_strlen( $_POST['add_post'], 'utf8' ) > $admin->post_length  ) {
			$_POST['add_post'] = _Function::cropStringLimit( $_POST['add_post'], $admin->post_length );
			
		}
		
		/*
		 * -------------------------------------------
		 *                isValidYoutube
		 * -------------------------------------------
		 */
//		print_r($dataVideoYoutube);
		if( $isValidYoutube ==  1 && $_POST['photoId'] == '' ) {
			$dataVideo                = $dataVideoYoutube->{'title'}.' (Youtube) ';
			$_POST['video_code']      = $idVideoYoutube;
			$_POST['video_title']     = $dataVideoYoutube->{'title'}.' (Youtube) ';
			$_POST['video_site']      = 'youtube';
			$_POST['video_url']       =  _Function::bitLyUrl( 'https://www.youtube.com/watch?v='.$idVideoYoutube.'' );
			$_POST['video_thumbnail'] = 'https://img.youtube.com/vi/'.$idVideoYoutube.'/0.jpg';
		} else if( $isValidYoutube ==  1 && $_POST['photoId'] != '' ) {
			$_POST['video_code']      = '';
			$_POST['video_title']     = '';
			$_POST['video_site']      = '';		
			$_POST['video_url']       = 'https://youtu.be/'.$idVideoYoutube.'';
			$_POST['video_thumbnail'] = '';
		
		}
		/*
		 * -------------------------------------------
		 *                isValidVimeoURL
		 * -------------------------------------------
		 */
		else if( $isValidVimeoURL ==  1 && $_POST['photoId'] == '' ) {
			$dataVideo                = $dataVideoVimeo->{'title'}.' (Vimeo) ';
			$typeMedia                = $_SESSION['LANG']['video'];
			$icon                     = '<i class="video_img_sm icons"></i>';
			$_POST['video_code']      = $dataVideoVimeo->{'video_id'};
			$_POST['video_title']     = $dataVideoVimeo->{'title'}.' (Vimeo) ';
			$_POST['video_site']      = 'vimeo';
			$_POST['video_url']       = _Function::bitLyUrl( 'http://vimeo.com/'.$dataVideoVimeo->{'video_id'}.'' );
			$_POST['video_thumbnail'] = preg_replace( '/(\_640|\_1280+)/', '_200', $dataVideoVimeo->{'thumbnail_url'} );
			
		}  else if ( $isValidVimeoURL ==  1 && $_POST['photoId'] != '' ) {
			$_POST['video_code']      = '';
			$_POST['video_title']     = '';
			$_POST['video_site']      = '';	
			$_POST['video_url']       = 'http://vimeo.com/'.$dataVideoVimeo->{'video_id'}.'';
			$_POST['video_thumbnail'] = '';
			
		} else if( $isValidDailymotionURL === 1 ) {
		 	
			$htmlDailymotion = $dataDailymotion->{'html'}; 
	
			$xpath    = new DOMXPath(@DOMDocument::loadHTML($htmlDailymotion));
			$urlDaily = $xpath->evaluate("string(//iframe/@src)");
			$urlDaily = str_replace( '\\' , '', $urlDaily);
			$urlDaily = str_replace( '"' , '', $urlDaily);
			
			$urlSecure = preg_replace("/^http:/i", "https:", $urlDaily);
	
			$_POST['video_code']      = '';
			$_POST['video_title']     = '';
			$_POST['video_site']      = strtolower( $dataDailymotion->{'provider_name'});
			$_POST['video_url']       = $urlSecure;
			$_POST['video_thumbnail'] = $dataDailymotion->{'thumbnail_url'};
			
		 } 
		 //<--------------  ** End Dailymotion ** -----> 
		 
		 else if( $isValidScreenrURL === 1 ) {
		 	
			$htmlScreenr = $dataScreenr->{'html'}; 
	
			$xpath    = new DOMXPath(@DOMDocument::loadHTML($htmlScreenr));
			$urlScreenr = $xpath->evaluate("string(//iframe/@src)");
			$urlScreenr = str_replace( '\\' , '', $urlScreenr);
			$urlScreenr = str_replace( '"' , '', $urlScreenr);
	
			$_POST['video_code']      = '';
			$_POST['video_title']     = '';
			$_POST['video_site']      = strtolower( $dataScreenr->{'provider_name'});
			$_POST['video_url']       = $urlScreenr;
			$_POST['video_thumbnail'] = $dataScreenr->{'thumbnail_url'};
			
		 } 
		 //<--------------  ** End Screenr ** -----> 
		 
		 else if( $isValidDotsubURL === 1 ) {
		 	
			$htmlDotsub = $dataDotsub->{'html'}; 
	
			$xpath    = new DOMXPath(@DOMDocument::loadHTML($htmlDotsub));
			$urlDotsub = $xpath->evaluate("string(//iframe/@src)");
			$urlDotsub = str_replace( '\\' , '', $urlDotsub);
			$urlDotsub = str_replace( '"' , '', $urlDotsub);
			
			$urlSecure = preg_replace("/^http:/i", "https:", $urlDotsub);
	
			$_POST['video_code']      = '';
			$_POST['video_title']     = '';
			$_POST['video_site']      = strtolower( $dataDotsub->{'provider_name'});
			$_POST['video_url']       = $urlSecure;
			$_POST['video_thumbnail'] = $dataDotsub->{'thumbnail_url'};
			
		 } 
		 //<--------------  ** End Dotsub ** -----> 
		 
		 else if( $isValidHuluURL === 1 ) {
		 	
			$htmlHulu= $dataHulu->{'html'}; 
	
			$xpath    = new DOMXPath(@DOMDocument::loadHTML($htmlHulu));
			$urlHulu = $xpath->evaluate("string(//iframe/@src)");
			$urlHulu = str_replace( '\\' , '', $urlHulu);
			$urlHulu = str_replace( '"' , '', $urlHulu);
	
			$_POST['video_code']      = '';
			$_POST['video_title']     = '';
			$_POST['video_site']      = strtolower( $dataHulu->{'provider_name'});
			$_POST['video_url']       = $urlHulu;
			$_POST['video_thumbnail'] = $dataHulu->{'thumbnail_url'};
			
		 } 
		 //<--------------  ** End Hulu ** ----->
		 
		 else if( $isValidBlipURL === 1 ) {
		 	
			$htmlBlip = $dataBlip->{'html'}; 
	
			$xpath    = new DOMXPath(@DOMDocument::loadHTML($htmlBlip));
			$urlBlip = $xpath->evaluate("string(//iframe/@src)");
			$urlBlip = str_replace( '\\' , '', $urlBlip);
			$urlBlip = str_replace( '"' , '', $urlBlip);
	
			$_POST['video_code']      = '';
			$_POST['video_title']     = '';
			$_POST['video_site']      = strtolower( $dataBlip->{'provider_name'});
			$_POST['video_url']       = $urlBlip;
			$_POST['video_thumbnail'] = $dataBlip->{'thumbnail_url'};
			
		 } 
		 //<--------------  ** End Blip ** ----->
		  else  {
			$_POST['video_code']      = '';
			$_POST['video_title']     = '';
			$_POST['video_site']      = '';
			$_POST['video_url']       = '';
			$_POST['video_thumbnail'] = '';
		}
		
		
		if( $isValidYoutube ===  0 
			&& $isValidVimeoURL === 0 
			&& $isValidUrlSoundCloud === 0 
			&& $isValidDailymotionURL === 0
			&& $isValidScreenrURL === 0
			&& $isValidDotsubURL === 0
			&& $isValidHuluURL === 0
			&& $isValidSlideShareURL === 0
			&& $isValidBlipURL === 0
			&& mb_strlen( trim( $_POST['add_post'] ), 'utf8' ) == 0
			&& $_POST['photoId'] == '' 
		) {
			$error = 1;
			return false;
		}
if( 
			$urlMedia != ''
			&& $isValidYoutube ===  0 
			&& $isValidVimeoURL === 0 
			&& $isValidUrlSoundCloud === 0 
			&& $isValidDailymotionURL === 0
			&& $isValidScreenrURL === 0
			&& $isValidDotsubURL === 0
			&& $isValidHuluURL === 0
			&& $isValidSlideShareURL === 0
			&& $isValidBlipURL === 0
			
		) {

		$html = _Function::file_get_contents_curl($urlMedia);
		
		$_parse = parse_url( $urlMedia );
		$_host  = $_parse['host'];
		
		//parsing begins here:
		$doc = new DOMDocument();
		@$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
		$nodes = $doc->getElementsByTagName('title');
		
		//get and display what you need:
		$title = $nodes->item(0)->nodeValue;
		
		$metas = $doc->getElementsByTagName('meta');
		
		for ($i = 0; $i < $metas->length; ++$i) {
			
		    $meta = $metas->item($i);
		    if( $meta->getAttribute('name') == 'description') {
		        $description = $meta->getAttribute('content');
		        	
			}//<-- name
			
			if( empty( $description ) ) {
				if( $meta->getAttribute('property') == 'og:description') {
		        $description = $meta->getAttribute('content');
		        	
				}//<-- name
			}
			
			if($meta->getAttribute('property') == 'og:image') {
				if(filter_var($meta->getAttribute('content'), FILTER_VALIDATE_URL)) {
					$image = $meta->getAttribute('content');
				} 
				
		 }//<-- property
		}//<-- For  
		
		if( empty( $description ) ) {
			$description = '';
		}
		if( empty( $image ) ) {
			$image = '';
		}
		
		$_POST['url'] = $urlMedia;
		$_POST['url_thumbnail'] = $image;
		$_POST['url_title'] = strip_tags( $title );
		$_POST['url_description'] = strip_tags( $description );
		$_POST['url_host'] = $_host;
		
	}//<-End If

	
	if( !empty( $_POST['photoId'] ) ) {
		/* Get Width of Image upload */
		$widthPhoto = _Function::getWidth( URL_BASE.'upload/'.$_POST['photoId'] ); 
				
		if( $widthPhoto > 600 ) {
			$thumbPic = 'thumb/600-440-';
		} else  {
			$thumbPic = null;
		}
	}
		/*
		 * ---------------------------------------------
		 *    If everything is OK publication insert
		 * --------------------------------------------
		 */	
		$response = $obj->insertPost();
		if( $infoUser->type_account == 1 ) {
		 $verified = ' <i class="fa fa-check-circle verified verified-min showTooltip" title="'.$_SESSION["LANG"]["verified"].'" data-toggle="tooltip" data-placement="right"></i>'; 
	} else {
		 $verified = null; 
	}
	
		if( !empty( $response ) ) {
			
			//==================================================//
			//=            * COPY FOLDER UPLOAD /         *    =//		
			//==================================================//
		 	chmod( $rootUpload.$photoID, 0777 );
			 if ( file_exists( $path.$photoID ) && $photoID != '' ) {
					copy( $path.$photoID, $rootUpload.$photoID );
					unlink( $path.$photoID );
					
				}//<--- IF FILE EXISTS
				
				/* Url */
				$urlStatus = URL_BASE.$infoUser->username.'/status/'.$response;
if(1){  
				if($_POST['auto_tweet']!='on')$_SESSION['auto_tweet_disable']=true;
				if($infoUser->oauth_provider=='twitter' and $infoUser->twitter_oauth_token!='' and $infoUser->twitter_oauth_token_secret!='' and $_POST['auto_tweet']=='on'){
//	error_log(print_r($infoUser,1));					
					//自動ツイート
					include_once('../../oauth/twitter/twitteroauth.php');
					$twitteroauth = new TwitterOAuth( YOUR_CONSUMER_KEY, YOUR_CONSUMER_SECRET, $infoUser->twitter_oauth_token, $infoUser->twitter_oauth_token_secret );
//	error_log(YOUR_CONSUMER_KEY.' : '.YOUR_CONSUMER_SECRET.' : '.$infoUser->twitter_oauth_token.' : '.$infoUser->twitter_oauth_token_secret);
					$url='statuses/update';
					//本文が10文字以上なら頭5文字もツイートする
					if( mb_strlen( $_POST['add_post'], 'utf8' ) >= 10  ) {
						$tweet_text = _Function::cropStringLimit( $_POST['add_post'], 5 ).'…  →';
					}else{
						$tweet_text='';
					}
					$param=array('status'=>$tweet_text.$urlStatus.' '.$_SESSION['LANG']['share_hashtags']);
//	error_log(print_r($param,1));
					$res=$twitteroauth->post($url,$param);
//	error_log(print_r($res,1));
				}
	
}
           ?>
           <li class="panel panel-default hoverList panel-posts li-group margin-zero " data="<?php echo $response; ?>">
					  <div class="panel-heading timeline-title">
					  	
					  	<div class="pull-left col-avatar">
					  		<a class="openModal" href="<?php echo URL_BASE.$infoUser->username; ?>" data-id="<?php echo $_SESSION['authenticated']; ?>">
					  			<img src="<?php echo URL_BASE.'thumb/90-90-public/avatar/'.$infoUser->avatar; ?>" alt="Avatar" class="media-object img-rounded" width="45"></a>
					  	</div>
					  	
					  	
					  	<div class="text-user-timeline">
					  		<strong>
					  			<a href="<?php echo URL_BASE.$infoUser->username; ?>" data-id="<?php echo $_SESSION['authenticated']; ?>" class="username-posts openModal">
					  				<?php echo stripslashes( $infoUser->name ).$verified; ?>
					  				</a>
					  			</strong> 
					  		@<?php echo $infoUser->username; ?>
					  	</div>
					  	
				  	<?php /* 位置情報表示を非表示
				  	<?php if( $_POST['_geolocation'] != '' ) : ?>
				  	<div class="container-geo">
				  		<i class="fa fa-map-marker ico-geo myicon-right"></i> 
				  		
				  		<a class="text-geo" target="_blank" href="https://www.google.com/maps/place/<?php echo $_POST['_geolocation']; ?>">
				  			<?php echo $_POST['_geolocation']; ?>
				  			</a>
				  	</div>
				  	<?php endif; ?>
				  	*/ ?>
					  	
					  <a href="<?php echo $urlStatus; ?>" class="date-status" title="<?php echo date('d/m/Y', time()); ?>">
					  	<span class="small sm-font sm-date timestamp timeAgo" data="<?php echo date('c', time()); ?>"></span>
					  </a>
					  	
					  	
					  </div><!-- panel-heading -->
					  	
					  <div class="panel-body">
					  	
					  	<?php
					  	
					  	 if( $pos_details != '' || $_POST['video'] != '' ) : ?>
					  	<p class="p-text">
					  		<?php  
			   				 	/* POST DETAILS */
			   				 	if( $pos_details != '' ) {
			   				 		echo $pos_details.' ';
			   				 	}
								/* DATA VIDEO */
			   				 	if( isset( $_POST['video'] ) 
				   				 	&& $_POST['video'] != '' 
				   				 	&& $isValidYoutube ==  1 
				   				 	|| isset( $_POST['video'] ) 
				   				 	&& $_POST['video'] != '' 
				   				 	&& $isValidVimeoURL == 1
								) {
									echo ' '.$dataVideo;
								}
								?> 
							</p>
					    <?php endif; ?>
			 <!-- ******* MEDIA ******** -->
			 
<?php
	//Class
	$mediaGet = $obj->getMedia_2($response);
			
	$widthPhoto = _Function::getWidth( '../../upload/'.$mediaGet[0]['photo'] ); 
				
	if( $widthPhoto >= 600 ) {
		$thumbPic = 'thumb/600-600-';
	} else  {
		$thumbPic = null;
	}
	
	$url_slideshare = preg_replace('#^https?:#', '', rtrim($mediaGet[0]['doc_url'],'/'));
	
	//==== PHOTO	
	if( $mediaGet[0]['photo'] != ''  ) {
		$media = "<a data-view='".$_SESSION['LANG']['details']." &rarr;' data-url=".$urlStatus." class='galeryAjax cboxElementxx link-img' href='".URL_BASE."upload/".$mediaGet[0]['photo']."'><img class='photoPost img-responsive' src='".URL_BASE.$thumbPic."upload/".$mediaGet[0]['photo']."'></a>";
	}
	
	//==== VIDEO VIMEO
	else if( $mediaGet[0]['video_site'] == 'vimeo'  ) {
		$media = '<div class="embed-responsive embed-responsive-4by3"> <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/'.$mediaGet[0]['video_code'].'" width="450" height="360" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';
	}

	//==== VIDEO YOUTUBE
	else if( $mediaGet[0]['video_site'] == 'youtube'  ) {
		$media = '<div class="embed-responsive embed-responsive-4by3"> <iframe class="embed-responsive-item" height="360" src="https://www.youtube.com/embed/'.$mediaGet[0]['video_code'].'" allowfullscreen></iframe></div>';
	}
	
	//==== DailyMotion
	else if( $mediaGet[0]['video_site'] == 'dailymotion'  ) {
		$media = '<iframe class="embed-responsive-item" height="360" scrolling="no" src="'.$mediaGet[0]['video_url'].'"></iframe>';
	}
	
	//==== Screenr 
	else if( $mediaGet[0]['video_site'] == 'screenr'  ) {
		$media = '<iframe class="embed-responsive-item" height="360" scrolling="no" src="'.$mediaGet[0]['video_url'].'"></iframe>';
	}
	//==== Dotsub 
	else if( $mediaGet[0]['video_site'] == 'dotsub'  ) {
		$media = '<iframe class="embed-responsive-item" height="360" scrolling="no" src="'.$mediaGet[0]['video_url'].'"></iframe>';
	}

	//==== Hulu 
	else if( $mediaGet[0]['video_site'] == 'hulu'  ) {
		$media = '<iframe class="embed-responsive-item" height="360" scrolling="no" src="'.$mediaGet[0]['video_url'].'"></iframe>';
	}
	
	//==== Blip 
	else if( $mediaGet[0]['video_site'] == 'blip'  ) {
		$media = '<iframe class="embed-responsive-item" height="360" scrolling="no" src="'.$mediaGet[0]['video_url'].'"></iframe>';
	}
	
	//==== slideshare 
	else if( $mediaGet[0]['doc_site'] == 'slideshare'  ) {
		$media = '<iframe class="embed-responsive-item" height="360" scrolling="no" src="'.$url_slideshare.'"></iframe>';
	}
	
	//==== SOUNDCLOUD
	else if( $mediaGet[0]['url_soundcloud'] != ''  ) {
		$media = '<iframe class="embed-responsive-item" height="120" scrolling="no" src="https://w.soundcloud.com/player/?url='.$mediaGet[0]['url_soundcloud'].'"></iframe>';
	} 
	 else {
		$media = null;
	}
	
	echo $media; 
	
	if( $mediaGet[0]['url_host'] != '' ) {
	?>
	
	<div class="row list-media-mv margin-zero">
				  	<div class="col-sm-12 padding-zero">
				  		
					<!-- ******** Media ****** -->  
		    	  	<a href="<?php echo $mediaGet[0]['url']; ?>" style="border-bottom: 2px solid #0088E2 !important;" target="_blank" class="media li-group list-group-ms links-sm">
		                   
		                   <?php if( $mediaGet[0]['url_thumbnail'] != '' ) { ?>
		                    <div class="pull-left margin-zero thumb-url-load">
		                       	<img src="<?php echo URL_BASE.'image_url.php?url='.base64_encode( $mediaGet[0]['url_thumbnail'] ); ?>" onerror="$(this).hide();" alt="Image" class="media-object img-responsive thumbnail_url">
		                    </div>
		                    <?php } ?>
		                    
		                    <div class="media-body clearfix text-overflow padding-tp padding-left-group">
		                       <strong class="media-heading">
		                       	<?php if( $mediaGet[0]['url_title'] != '') { ?>
		                       		<?php echo $mediaGet[0]['url_title']; 
		                       		} else {
		                       			echo 'Untitled';
		                       		} ?>
		                       	</strong>
		                       <p class="text-col">
		                       	  	<?php if( $mediaGet[0]['url_description'] != '') { ?>
		                       	  <span class="block-sm color-font text-overflow"><?php echo $mediaGet[0]['url_description']; ?></span>
		                           <?php } ?>
		                          <small class="color-font-link text-uppercase text-link"><?php echo $mediaGet[0]['url_host']; ?></small>
		                    </p></div><!-- media-body -->
		        	 </a><!-- ******** Media ****** -->
				</div><!-- col-sm-6 -->
			</div>
			
			<?php } ?>
	
	  	<!-- list -->
	  	<ul class="list-inline margin-bottom-zero list-options">
				  		
				  		<li><a data-expand="<?php echo $_SESSION['LANG']['expand']; ?>" data-hide="<?php echo $_SESSION['LANG']['hide']; ?>" class="expand getData" data="<?php echo $response; ?>" data-token="<?php echo $_POST['token_id']; ?>">
				  			<span class="textEx"><?php echo $_SESSION['LANG']['expand']; ?></span>
				  			</a>
				  		</li>
					  
					  <li>
					  	<a data-fav="<?php echo $_SESSION['LANG']['favorite']; ?>" data-fav-active="<?php echo $_SESSION['LANG']['favorited']; ?>"class="favorite favoriteIcon" data="<?php echo $response; ?>" data-token="<?php echo $_POST['token_id']; ?>">
					  		<span class="fa fa-thumbs-o-up myicon-right"></span> 
					  		<?php echo $_SESSION['LANG']['favorite']; ?>
					  		</a>
					  	</li>
					  	
					  <li><a data-message="<?php echo $_SESSION['LANG']['delete_post']; ?>" data-confirm="<?php echo $_SESSION['LANG']['confirm']; ?>" class="trash" data-image="<?php echo $photoID; ?>" data="<?php echo $response; ?>" data-token="<?php echo $_POST['token_id']; ?>">
					  	<span class="fa fa-trash myicon-right"></span> 
					  	<?php echo $_SESSION['LANG']['trash']; ?>
					  	  </a>
					  	</li>
					 
					</ul><!-- ./list -->
				    
				  </div><!-- panel-body -->
				  
	<div class="panel-footer content-ajax details-post">
	
	<div class="container-media grid-media"></div>
	
			<div class="media">
				
	            <span href="#" class="pull-left">
	                <img alt="" src="<?php echo URL_BASE.'thumb/80-80-public/avatar/'.$infoUser->avatar; ?>" class="media-object img-circle" width="40">
	            </span>
	            
	            <div class="media-body">
	            <form action="" method="post" accept-charset="UTF-8" id="form_reply_post">	
	            	<textarea class="form-control textarea-text" name="reply_post" id="reply_post"></textarea>
	                <input type="hidden" name="id_reply" id="id_reply" value="<?php echo $response; ?>">
		   			<input type="hidden" name="token_reply" id="token_reply" value="<?php echo $_POST['token_id']; ?>">
		   					
	                <div class="help-block">
	                	<button id="button_reply" disabled="disabled" style="opacity: 0.5; cursor: default;" type="submit" class="btn btn-info btn-xs btn-border">
	                		<?php echo $_SESSION['LANG']['reply']; ?>
	                	</button>
	                </div>
	                </form>
	            </div><!-- media-body -->
	        </div><!-- media -->
						
	</div><!-- panel-footer -->

</li><!-- End Li --> 
	<?php  } else {
      	chmod( $path.$photoID, 0777 );
		 if ( file_exists( $path.$photoID ) && $photoID != '' ) {
			 	
			 unlink( $path.$photoID );
		 }
      }
   } else {
		echo '<script type="text/javascript">	
					$(document).ready(function(){
						window.location.reload();
			         });// END READY 
         </script>';
	}
}//<------ IF PARENT

 ?>