<!-- POSTS -->
<?php 
	$panel_old=((!$is_static and (strtotime($key['date'])<(time()-3600*24)))?'panel-old':'');
	
	$FavsByType=$obj->getAllFavoritesByType($idPost);
	switch($key['fa_status']){
		case 1:
			$classReaction='dw_reaction_like'; $textFav='Like'; break;
		case 2:
			$classReaction='dw_reaction_love'; $textFav='Love'; break;
		case 3:
			$classReaction='dw_reaction_haha'; $textFav='Haha'; break;
		case 4:
			$classReaction='dw_reaction_wow'; $textFav='WoW'; break;
		case 5:
			$classReaction='dw_reaction_sad'; $textFav='Sad'; break;
		case 6:
			$classReaction='dw_reaction_angry'; $textFav='Angry'; break;
		default:
			$classReaction='';$textFav='Like'; break;
	}
	
?>
<li class="panel panel-default <?=$panel_old?> hoverList panel-posts margin-zero li-group" data="<?php echo $idPost; ?>" <?=($is_static?'id="staticpost_'.$idPost.'"':'')?>>
	<?php echo $spanAbsolutexxxx; ?>
	<div class="panel-heading timeline-title">
					  	
					  	<div class="pull-left col-avatar">
					  		<a class="openModal" href="<?php echo URL_BASE.$key['username']; ?>" data-id="<?php echo $_idUser; ?>">
					  			<img src="<?php echo URL_BASE.'thumb/90-90-public/avatar/'.$key['avatar']; ?>" alt="Avatar" class="media-object img-rounded" width="45"></a>
					  	</div>
					  	
					  	
					  	<div class="text-user-timeline">
					  		<strong>
					  			<a href="<?php echo URL_BASE.$key['username']; ?>" data-id="<?php echo $_idUser; ?>" class="username-posts openModal">
					  				<?php echo stripslashes( $key['name'] ).$verified; ?>
					  				</a>
					  			</strong> 
					  		@<?php echo $key['username']; ?>
					  	</div>
					  	
					  	<?php if( $key['geolocation'] != '' ) : ?>
					  	<div class="container-geo">
					  		<i class="fa fa-map-marker ico-geo myicon-right"></i> 
					  		
					  		<a class="text-geo" target="_blank" href="https://www.google.com/maps/place/<?php echo $key['geolocation']; ?>">
					  			<?php echo $key['geolocation']; ?>
					  			</a>
					  	</div>
					  	<?php endif; ?>
					  	
					  	<a href="<?php echo URL_BASE.$key['username'].'/status/'.$key['id']; ?>" class="date-status" title="<?php echo date('d/m/Y', strtotime( $key['date'] )); ?>">
					  		<span class="small sm-font sm-date timestamp timeAgo" data="<?php echo date('c', strtotime( $key['date'] )); ?>"></span>
					  	</a>
					  	
					  	<!-- 固定 (他人）-->
						<? if($is_static){ ?>
							<span class="fa fa-thumb-tack myicon-right"></span>
						<? } ?>
					  	
					  </div><!-- panel-heading -->
					  
	<div class="panel-body">
		
		<?php if( $key['post_details'] != '' || $key['video_site'] != '' ) : ?>	
		<p class="p-text" style="z-index:-1;" onclick="location.href='/<?=$key['username']?>/status/<?=$idPost?>';">
			
  		<?php  
		 	/*<------- * POST DETAILS * -------->*/
			echo _Function::checkText( $key['post_details'] ); ?> 
		</p>
		<?php endif; ?>
		
		<?php if( $key['repost_of_id'] != 0 ) { ?>
				<p style="font-size: 13px; color: #999; font-style: italic;">
					<img style="vertical-align: middle;" src="<?php echo URL_BASE; ?>public/img/repost-ico.png" /> <?php echo $_SESSION['LANG']['reposted_by']; ?> <?php echo $nameUser; ?>
					</p>
			<?php } ?>
			
			<!-- ******* MEDIA ******** -->
			<?php
			$widthPhoto = _Function::getWidth( '../../upload/'.$key['photo'] ); 
			
				if( $widthPhoto >= 600  ) {
					$thumbPic = 'thumb/600-600-';
				} else  {
					$thumbPic = null;
				}
				
				$url_slideshare = preg_replace('#^https?:#', '', rtrim($key['doc_url'],'/'));
	//==== PHOTO	
	if( $key['photo'] != ''  ) {
//		$media = "<a data-view='".$_SESSION['LANG']['details']." &rarr;' data-url='".$urlStatus."' class='galeryAjax cboxElementxx link-img' href='".URL_BASE."upload/".$key['photo']."'><img class='photoPost img-responsive' src='".URL_BASE.$thumbPic."upload/".$key['photo']."'></a>";
		$media = "<a class='photoPostContainer link-img smartphoto' href='".URL_BASE."upload/".$key['photo']."' data-caption='' data-id='".$key['photo']."' data-group='".$key['photo']."' ><img class='photoPost img-responsive' src='".URL_BASE.$thumbPic."upload/".$key['photo']."'></a>";

	}
	
	//==== VIDEO VIMEO
	else if( $key['video_site'] == 'vimeo'  ) {
		$media = '<div class="embed-responsive embed-responsive-4by3"> <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/'.$key['video_code'].'" width="450" height="360" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';
	}

	//==== VIDEO YOUTUBE
	else if( $key['video_site'] == 'youtube'  ) {
		$media = '<div class="embed-responsive embed-responsive-4by3"> <iframe class="embed-responsive-item" height="360" src="https://www.youtube.com/embed/'.$key['video_code'].'" allowfullscreen></iframe></div>';
	}

	//==== DailyMotion
	else if( $key['video_site'] == 'dailymotion'  ) {
		$media = '<iframe class="embed-responsive-item" height="360" scrolling="no" src="'.$key['video_url'].'"></iframe>';
	}
	
	//==== Screenr 
	else if( $key['video_site'] == 'screenr'  ) {
		$media = '<iframe class="embed-responsive-item" height="360" scrolling="no" src="'.$key['video_url'].'"></iframe>';
	}
	
	//==== dotsub 
	else if( $key['video_site'] == 'dotsub'  ) {
		$media = '<iframe class="embed-responsive-item" height="360" scrolling="no" src="'.$key['video_url'].'"></iframe>';
	}
	
	//==== hulu 
	else if( $key['video_site'] == 'hulu'  ) {
		$media = '<iframe class="embed-responsive-item" height="360" scrolling="no" src="'.$key['video_url'].'"></iframe>';
	}

	//==== blip 
	else if( $key['video_site'] == 'blip'  ) {
		$media = '<iframe class="embed-responsive-item" height="360" scrolling="no" src="'.$key['video_url'].'"></iframe>';
	}

	//==== SlideShare 
	else if( $key['doc_site'] == 'slideshare'  ) {
		$media = '<iframe class="embed-responsive-item" height="360" scrolling="no" src="'.$url_slideshare.'"></iframe>';
	}
	
	//==== SOUNDCLOUD
	else if( $key['url_soundcloud'] != ''  ) {
		$media = '<iframe class="embed-responsive-item" height="120" scrolling="no" src="https://w.soundcloud.com/player/?url='.$key['url_soundcloud'].'"></iframe>';
	} else {
		$media = null;
	}

	echo $media;
	
	if( $key['url_host'] != '' ) {
		
	?>
			<div class="row list-media-mv margin-zero">
				  	<div class="col-sm-12 padding-zero">
				  		
					<!-- ******** Media ****** -->  
		    	  	<a href="<?php echo $key['url']; ?>" style="border-bottom: 2px solid #0088E2 !important;" target="_blank" class="media li-group list-group-ms links-sm">
		                   
		                   <?php if( $key['url_thumbnail'] != '' ) { ?>
		                    <div class="pull-left margin-zero thumb-url-load">
		                       	<img src="<?php echo URL_BASE.'image_url.php?url='.base64_encode( $key['url_thumbnail'] ); ?>" onerror="$(this).hide();" alt="Image" class="media-object thumbnail_url img-responsive">
		                    </div>
		                    <?php } ?>
		                    
		                    <div class="media-body clearfix text-overflow padding-tp padding-left-group">
		                       <strong class="media-heading">
		                       	<?php if( $key['url_title'] != '') { ?>
		                       		<?php echo $key['url_title']; 
		                       		 } else {
		                       			echo 'Untitled';
		                       		} ?>
		                       	</strong>
		                       <p class="text-col">
		                       	  	<?php if( $key['url_description'] != '') { ?>
		                       	  <span class="block-sm color-font text-overflow"><?php echo $key['url_description']; ?></span>
		                           <?php } ?>
		                          <small class="color-font-link text-uppercase text-link"><?php echo $key['url_host']; ?></small>
		                    </p></div><!-- media-body -->
		        	 </a><!-- ******** Media ****** -->
				</div><!-- col-sm-6 -->
			</div>
			
			<?php } ?>

			

		<!-- reactions -->
			<?php if( isset( $_SESSION['authenticated'] ) ): ?>												
<div>
	<div class="dw-reactions-count">
	<? if($FavsByType[1]>0){ ?><span class="dw-reaction-count dw-reaction-count-like"><strong><?=$FavsByType[1]?></strong></span><? } ?>
	<? if($FavsByType[2]>0){ ?><span class="dw-reaction-count dw-reaction-count-love"><strong><?=$FavsByType[2]?></strong></span><? } ?>
	<? if($FavsByType[3]>0){ ?><span class="dw-reaction-count dw-reaction-count-haha"><strong><?=$FavsByType[3]?></strong></span><? } ?>
	<? if($FavsByType[4]>0){ ?><span class="dw-reaction-count dw-reaction-count-wow"><strong><?=$FavsByType[4]?></strong></span><? } ?>
	<? if($FavsByType[5]>0){ ?><span class="dw-reaction-count dw-reaction-count-sad"><strong><?=$FavsByType[5]?></strong></span><? } ?>
	<? if($FavsByType[6]>0){ ?><span class="dw-reaction-count dw-reaction-count-angry"><strong><?=$FavsByType[6]?></strong></span><? } ?>

	</div>
</div>				
		<?php endif; ?>												

		<!-- list -->
	  	<ul class="list-inline margin-bottom-zero list-options" style="margin-top: -14px;">	

<li class="dw-reactions dw-reactions-post-<?=$idPost?>" data-type="<?=($classFav?'unvote':'vote')?>" data-token="<?=$key['token_id']?>" data-post="<?=$idPost?>">
	<div class="dw-reactions-button" style="user-select: none;">
		<span class="dw-reactions-main-button  <?=$classReaction?>"><?=$textFav?></span>
		<div class="dw-reactions-box">
			<span class="dw-reaction dw-reaction-like"><strong>Like</strong></span>
			<span class="dw-reaction dw-reaction-love"><strong>Love</strong></span>
			<span class="dw-reaction dw-reaction-haha"><strong>Haha</strong></span>
			<span class="dw-reaction dw-reaction-wow"><strong>Wow</strong></span>
			<span class="dw-reaction dw-reaction-sad"><strong>Sad</strong></span>
			<span class="dw-reaction dw-reaction-angry"><strong>Angry</strong></span>
		</div>
	</div>
</li>				
									
			<!-- EXPAND -->
			<li><a data-expand="<?php echo $_SESSION['LANG']['expand']; ?>" data-hide="<?php echo $_SESSION['LANG']['hide']; ?>" class="expand getData" data="<?php echo $key['id']; ?>" data-token="<?php echo $key['token_id']; ?>">
	  			<span style="font-size: 12px; color:#999; font-weight: bold;">
	  			<span class="fa fa-comment myicon-right"></span><?php echo $_SESSION['LANG']['expand']; ?><?=($key['comment_num']>0?'('.$key['comment_num'].')':'')?></span>
	  			</a>
	  		</li>
<? /*				  		
			<?php if( isset( $_SESSION['authenticated'] ) ): ?>

				
												
			<!-- FAVORITES -->
			<li>
		  	<a data-fav="<?php echo $_SESSION['LANG']['favorite']; ?>" data-fav-active="<?php echo $_SESSION['LANG']['favorited']; ?>"class="favorite favoriteIcon <?php echo $classFav; ?>" <?php echo $spanFav; ?> data="<?php echo $key['id']; ?>" data-token="<?php echo $key['token_id']; ?>">
		  		<span class="fa fa-thumbs-o-up myicon-right"></span> 
		  		<?php echo $textFav; ?>
		  		</a>
		  	</li>
		  	<?php endif; ?>
	*/ ?>		
			<!-- REPOST -->
			<?php if( ( $_SESSION['authenticated'] ) && $key['user'] !=  $_SESSION['authenticated'] ): ?>
			<li>
		  	<a data-fav="<?php echo $_SESSION['LANG']['repost']; ?>" data-rep-active="<?php echo $_SESSION['LANG']['reposted']; ?>"class="repost_button repostIcon <?php echo $spanRepost; ?>" data="<?php echo $key['id']; ?>" data-token="<?php echo $key['token_id']; ?>"  style="color:#999; font-weight: bold;font-size: 13px;">
		  		<span class="fa fa-retweet myicon-right"></span><?php echo $textRepost; ?></a>
		  	</li>
			<?php endif; ?>
			
			<!-- シェア -->
			<? if(preg_match('/24h_timeline/',$_SERVER['HTTP_USER_AGENT'])){ //app ?>
			<li style="margin-left: 0px;">
		  	<a href="gonative://share/sharePage?url=<?=URL_BASE.$key['username'].'/status/'.$key['id']?>"  style="font-size: 12px;color:#999; font-weight: bold;"><span class="fa fa-share myicon-right"></span><?=$_SESSION['LANG']['share']?></a>　
			</li>
			<? }else{ //web ?>
			<li style="margin-left: 5px;">
		  	<a target="_blank" href="http://twitter.com/share?url=<?=URL_BASE.$key['username'].'/status/'.$key['id']?>&text=<?=rawurlencode($_SESSION['LANG']['share_hashtags'])?>" style="color:lightskyblue;"><i style="font-size: 16px;" class="ion-social-twitter"></i></a>　
			</li><li>
   	   		<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?=rawurlencode(URL_BASE.$key['username'].'/status/'.$key['id'])?>" style="color: #0D57CE;"><i style="font-size: 16px;" class="ion-social-facebook"></i></a>　
			</li><li>
    		<a target="_blank" href="http://line.me/R/msg/text/?<?=rawurlencode(URL_BASE.$key['username'].'/status/'.$key['id'].' '.$_SESSION['LANG']['share_hashtags'])?>" style="color: #0ECF19;"><i style="font-size: 16px;" class="ion-ios-chatbubble"></i></a>
			</li>
			<? } ?>
			
		</ul><!-- ./list -->
			
			<?php if( $key['user'] == $_SESSION['authenticated'] ): ?>
			<!-- 固定 (自分)-->
	  	<ul class="list-inline margin-bottom-zero list-options">	
			
			<li style="margin-left: 0px;"><a data-message="<?php echo $_SESSION['LANG']['static_post']; ?>"  class="staticPost favoriteIcon <?=$classStatic?>"  data="<?php echo $key['id']; ?>" data-action="<?=$dataActionStatic?>" data-token="<?php echo $key['token_id']; ?>">
		  	<span class="fa fa-thumb-tack myicon-right"></span><?php echo $_SESSION['LANG'][($is_static?'remove_':'').'static_post']; ?>
		  	  </a>
		  	</li>
			<!-- TRASH -->
			<li style="margin-left: 0px;"><a data-message="<?php echo $_SESSION['LANG']['delete_post']; ?>" data-confirm="<?php echo $_SESSION['LANG']['confirm']; ?>" class="trash" data-image="<?php echo $key['photo']; ?>" data="<?php echo $key['id']; ?>" data-token="<?php echo $key['token_id']; ?>">
		  	<span class="fa fa-trash myicon-right"></span> 
		  	<?php echo $_SESSION['LANG']['trash']; ?>
		  	  </a>
		  	</li>
		  	</ul>
			<?php endif; ?>
	</div><!-- panel-body -->

	<div class="panel-footer content-ajax details-post">
	
	
	<div class="container-media grid-media"></div>
	
	<?php if( ( $_SESSION['authenticated'] ) ): ?>
	<div class="media">
				
	            <span href="#" class="pull-left">
	                <img alt="" src="<?php echo URL_BASE.'thumb/80-80-public/avatar/'.$infoSessioUsr->avatar; ?>" class="media-object img-circle" width="40">
	            </span>
	            
	            <div class="media-body">
	            <form action="" method="post" accept-charset="UTF-8" id="form_reply_post">	
	            	<textarea class="form-control textarea-text" name="reply_post" id="reply_post">@<?php echo $key['username']; ?> </textarea>
	                <input type="hidden" name="id_reply" id="id_reply" value="<?php echo $key['id']; ?>">
		   			<input type="hidden" name="token_reply" id="token_reply" value="<?php echo $key['token_id']; ?>">
		   					
	                <div class="help-block">
	                	<button id="button_reply" disabled="disabled" style="opacity: 0.5; cursor: default;" type="submit" class="btn btn-info btn-xs btn-border">
	                		<?php echo $_SESSION['LANG']['reply']; ?>
	                	</button>
	                </div>
	            </form>
	            </div><!-- media-body -->
	        </div><!-- media -->
	<?php endif; ?>
  </div><!-- panel-footer -->
</li>