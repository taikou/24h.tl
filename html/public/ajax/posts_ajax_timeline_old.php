<?php
/* Url */
	$urlStatus = URL_BASE.$getPosts[$i]['username'].'/status/'.$getPosts[$i]['id'];
		
	//<---- DELETE POST
    if( $_SESSION['authenticated'] == $getPosts[$i]['user'] ) {
		$removePost = ' <li><a data-message="'.$_SESSION['LANG']['delete_post'].'" data-confirm="'.$_SESSION['LANG']['confirm'].'" class="trash" data="'.$getPosts[$i]['id'].'" data-token="'.$getPosts[$i]['token_id'].'"> <span class="fa fa-trash myicon-right"></span>  '.$_SESSION['LANG']['trash'].'</a></li>';
	} else {
		$removePost = null;
	}
	//<---- VERIFIED
	if( $getPosts[$i]['type_account'] == 1 ) {
		$verified = ' <i class="fa fa-check-circle verified verified-min showTooltip" title="'.$_SESSION["LANG"]["verified"].'" data-toggle="tooltip" data-placement="right"></i>';
	} else {
		$verified = null;
	}


	if( $getPosts[$i]['repost_of_id'] != 0  ) {
		$repostUser = '<p style="font-size: 13px; color: #999; font-style: italic;">
								<img style="vertical-align: middle;" src="'.URL_BASE.'public/img/repost-ico.png" /> Reposted by '.$nameUser.'
								</p>';
	} else {
			$repostUser = null;
	}
	$activeRepost = $obj->checkRepost( $getPosts[$i]['id'], $_SESSION['authenticated'] );
	
	//============ REPOST SESSION CURRENT
	if( $activeRepost == 1  ) {
		$spanRepost   = ' repostedSpan';
		$textRepost   = $_SESSION['LANG']['reposted'];
	} else  {
		$spanRepost   = null;
		$textRepost   = $_SESSION['LANG']['repost'];
	}

	 if( isset( $_SESSION['authenticated'] ) && $getPosts[$i]['user'] !=  $_SESSION['authenticated'] ): 
	 $repostIcon = '<li><a data-rep="'.$_SESSION['LANG']['repost'].'" data-rep-active="'. $_SESSION['LANG']['reposted'].'" class="repost_button repostIcon '.$spanRepost.'" data="'.$getPosts[$i]['id'].'" data-token="'.$getPosts[$i]['token_id'].'">
		   			   <span class="fa fa-retweet myicon-right"></span> 
		   					'.$textRepost.'
	   					</a></li>';
	 endif;
			
	$widthPhoto = _Function::getWidth( '../../upload/'.$getPosts[$i]['photo'] ); 
				
	if( $widthPhoto >= 600 ) {
		$thumbPic = 'thumb/600-600-';
	} else  {
		$thumbPic = null;
	}
	
	$url_slideshare = preg_replace('#^https?:#', '', rtrim($getPosts[$i]['doc_url'],'/'));
	
	//==== PHOTO	
	if( $getPosts[$i]['photo'] != ''  ) {
		$media = "<a data-view='".$_SESSION['LANG']['details']." &rarr;' data-url=".$urlStatus." class='galeryAjax cboxElement link-img' href='".URL_BASE."upload/".$getPosts[$i]['photo']."'><img class='photoPost img-responsive' src='".URL_BASE.$thumbPic."upload/".$getPosts[$i]['photo']."'></a>";
	}
	
	//==== VIDEO VIMEO
	else if( $getPosts[$i]['video_site'] == 'vimeo'  ) {
		$media = '<div class="embed-responsive embed-responsive-4by3"> <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/'.$getPosts[$i]['video_code'].'" width="450" height="360" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';
	}

	//==== VIDEO YOUTUBE
	else if( $getPosts[$i]['video_site'] == 'youtube'  ) {
		$media = '<div class="embed-responsive embed-responsive-4by3"> <iframe class="embed-responsive-item" height="360" src="https://www.youtube.com/embed/'.$getPosts[$i]['video_code'].'" allowfullscreen></iframe></div>';
	}
	
	//==== DailyMotion
	else if( $getPosts[$i]['video_site'] == 'dailymotion'  ) {
		$media = '<iframe class="embed-responsive-item" height="360" scrolling="no" src="'.$getPosts[$i]['video_url'].'"></iframe>';
	}
	
	//==== Screenr 
	else if( $getPosts[$i]['video_site'] == 'screenr'  ) {
		$media = '<iframe class="embed-responsive-item" height="360" scrolling="no" src="'.$getPosts[$i]['video_url'].'"></iframe>';
	}
	
	//==== dotsub 
	else if( $getPosts[$i]['video_site'] == 'dotsub'  ) {
		$media = '<iframe class="embed-responsive-item" height="360" scrolling="no" src="'.$getPosts[$i]['video_url'].'"></iframe>';
	}

	//==== hulu 
	else if( $getPosts[$i]['video_site'] == 'hulu'  ) {
		$media = '<iframe class="embed-responsive-item" height="360" scrolling="no" src="'.$getPosts[$i]['video_url'].'"></iframe>';
	}
	
	//==== blip 
	else if( $getPosts[$i]['video_site'] == 'blip'  ) {
		$media = '<iframe class="embed-responsive-item" height="360" scrolling="no" src="'.$getPosts[$i]['video_url'].'"></iframe>';
	}
	
	//==== SlideShare 
	else if( $getPosts[$i]['doc_site'] == 'slideshare'  ) {
		$media = '<iframe class="embed-responsive-item" height="360" scrolling="no" src="'.$url_slideshare.'"></iframe>';
	}

	//==== SOUNDCLOUD
	else if( $getPosts[$i]['url_soundcloud'] != ''  ) {
		$media = '<iframe class="embed-responsive-item" height="120" scrolling="no" src="https://w.soundcloud.com/player/?url='.$getPosts[$i]['url_soundcloud'].'"></iframe>';
	} else {
		$media = null;
	}
	
	//<- thumbnail
	if( $getPosts[$i]['url_thumbnail'] != '' ) {
		$thumbnail = '<div class="pull-left margin-zero thumb-url-load">
		                       	<img src="'.URL_BASE.'image_url.php?url='.base64_encode( $getPosts[$i]['url_thumbnail'] ).'" alt="Image" onerror="$(this).hide();" class="media-object thumbnail_url img-responsive">
		                    </div>';
	} else {
		$thumbnail = null;
	}
	//<- Title
	if( $getPosts[$i]['url_title'] != '' ) {
		$_title = $getPosts[$i]['url_title'];
	} else {
		$_title = 'Untitled';
	}

//<- url_description
	if( $getPosts[$i]['url_description'] != '' ) {
		$_desc = '<span class="block-sm color-font text-overflow">'.$getPosts[$i]['url_description'].'</span>';
	} else {
		$_desc = null;
	}
	
	if( $getPosts[$i]['url_host'] != '' ) {
		$urlExpand = '<div class="row list-media-mv margin-zero">
				  	<div class="col-sm-12 padding-zero">
				  		
					<!-- ******** Media ****** -->  
		    	  	<a href="'.$getPosts[$i]['url'].'" style="border-bottom: 2px solid #0088E2 !important;" target="_blank" class="media li-group list-group-ms links-sm">
		                   '.$thumbnail.'
		                    <div class="media-body clearfix text-overflow padding-tp padding-left-group">
		                       <strong class="media-heading">
		                       	'.$_title.'
		                       	</strong>
		                       <p class="text-col">
		                       	  	'.$_desc.'
		                          <small class="color-font-link text-uppercase text-link">'.$getPosts[$i]['url_host'].'</small>
		                    </p></div><!-- media-body -->
		        	 </a><!-- ******** Media ****** -->
				</div><!-- col-sm-6 -->
			</div>';
		} else {
			$urlExpand = null;
		}
		
if( $getPosts[$i]['geolocation'] != '' ) {
		$geolocation = '<div class="container-geo">
					  		<i class="fa fa-map-marker ico-geo myicon-right"></i> 
					  		
					  		<a class="text-geo" target="_blank" href="https://www.google.com/maps/place/'.$getPosts[$i]['geolocation'].'">
					  			'.$getPosts[$i]['geolocation'].'
					  			</a>
					  	</div>';
		} else {
			$geolocation = null;
		}
	
$_array[] = '<li class="panel panel-default hoverList panel-posts margin-zero li-group" data="'.$idPost.'"> 
	<div class="panel-heading timeline-title">
			  	<div class="pull-left col-avatar">
			  		<a class="openModal" href="'.URL_BASE.$getPosts[$i]['username'].'" data-id="'.$_idUser.'">
			  			<img src="'.URL_BASE.'thumb/90-90-public/avatar/'.$getPosts[$i]['avatar'].'" alt="Avatar" class="media-object img-rounded" width="45"></a>
			  	</div>
			  	<div class="text-user-timeline">
			  		<strong>
			  			<a href="'.URL_BASE.$getPosts[$i]['username'].'" data-id="'.$_idUser.'" class="username-posts openModal">
			  				'.stripslashes( $getPosts[$i]['name'] ).' '.$verified.'
			  				</a>
			  			</strong> 
			  		@'.$getPosts[$i]['username'].'
			  	</div>
			  	
				'.$geolocation.'
			  	
				<a href="'.URL_BASE.$getPosts[$i]['username'].'/status/'.$getPosts[$i]['id'].'" class="date-status" title="'.date('d/m/Y', strtotime( $getPosts[$i]['date'] )).'">
			  		<span class="small sm-font sm-date timestamp timeAgo" data="'.date('c', strtotime( $getPosts[$i]['date'] ) ).'"></span>
			  	</a>
			  
			  </div><!-- panel-heading -->
			  <div class="panel-body">
			  <p class="p-text">
			   '._Function::checkText( $getPosts[$i]['post_details'] ).'
			  </p><!-- End p-text -->
			  '.$repostUser.'
			  '.$urlExpand.'
			  '. $media .'
			  	<ul class="list-inline margin-bottom-zero list-options">
				<li><a data-expand="'.$_SESSION['LANG']['expand'].'" data-hide="'.$_SESSION['LANG']['hide'].'" class="expand getData" data="'.$getPosts[$i]['id'].'" data-token="'.$getPosts[$i]['token_id'].'">
		  			<span class="textEx">'.$_SESSION['LANG']['expand'].'</span>
		  			</a>
		  		</li>
		  		<li>
		  	<a data-fav="'.$_SESSION['LANG']['favorite'].'" data-fav-active="'.$_SESSION['LANG']['favorited'].'"class="favorite favoriteIcon '.$classFav.'" '.$spanFav.' data="'.$getPosts[$i]['id'].'" data-token="'.$getPosts[$i]['token_id'].'">
		  		<span class="fa fa-thumbs-o-up myicon-right"></span> 
		  		'.$_SESSION['LANG']['favorite'].'
		  		</a>
		  	</li>
		  	'.$repostIcon.'
				'.$removePost.'
			  	</ul>
			  </div><!-- End panel body -->
			  
			  <div class="panel-footer content-ajax details-post">
			  	<div class="media">
			  	
				<div class="container-media grid-media"></div>
				
				<span href="#" class="pull-left">
	                <img alt="" src="'.URL_BASE.'thumb/80-80-public/avatar/'.$infoUser->avatar.'" class="media-object img-rounded" width="40">
	            </span>
				<div class="media-body">
	            <form action="" method="post" accept-charset="UTF-8" id="form_reply_post">	
	            	<textarea class="form-control textarea-text" name="reply_post" id="reply_post">@'.$getPosts[$i]['username'].' </textarea>
	                <input type="hidden" name="id_reply" id="id_reply" value="'.$getPosts[$i]['id'].'">
		   			<input type="hidden" name="token_reply" id="token_reply" value="'.$getPosts[$i]['token_id'].'">
		   					
	                <div class="help-block">
	                	<button id="button_reply" disabled="disabled" style="opacity: 0.5; cursor: default;" type="submit" class="btn btn-info btn-xs btn-border">
	                		'.$_SESSION['LANG']['reply'].'
	                	</button>
	                </div>
	                </form>
	            </div><!-- media-body -->
			  	</div><!-- End media -->
			  </div><!-- End panel-footer -->
			 </li>';
 ?>