<?php
session_start();
error_reporting(0);
if(  isset ( $_POST['offset'] ) && isset ( $_POST['number'] ) ) {
 if ( isset ( $_SESSION['authenticated'] ) ) {
   if( isset( $_POST ) && $_SERVER['REQUEST_METHOD'] == "POST" ) {
   	
   	/*
	 * ---------------------------------------
	 *   Valid $offset && Valid $postnumbers
	 * ---------------------------------------
	 */
	$offset                 = is_numeric($_POST['offset']) ? $_POST['offset'] : die();
	$postnumbers            = is_numeric($_POST['number']) ? $_POST['number'] : die();
	
	
	/*
	 * ---------------------------------------
	 *   Query > ID || Query < ID
	 * ---------------------------------------
	 */
	if( $_POST['query'] == 1 ) {
		$query = '<';
	} else {
		$query = '>';
	}
	
	/*
	 * --------------------------
	 *   Require/Include Files
	 * -------------------------
	 */
	require_once('../../class_ajax_request/classAjax.php');
	include_once('../../application/functions.php'); 
	include_once('../../application/DataConfig.php');	
	
	/*
	 * ----------------------
	 *   Instance Class
	 * ----------------------
	 */
	$obj         = new AjaxRequest();
	$infoUser    = $obj->infoUserLive( $_SESSION['authenticated'] );
	
//	$response    = $obj->getInteractions('U.status = "active" && I.id '.$query.' '.$offset .' ', 'LIMIT '.$postnumbers, $_SESSION['authenticated'] );
	$response    = $obj->getInteractions(' I.id '.$query.' '.$offset .' ', 'LIMIT '.$postnumbers, $_SESSION['authenticated'] );
	
	$countPosts = count( $response );
   		 if( $countPosts != 0 ) : 
			 foreach ( $response as $key ) {
			 	
				$_idUser = $key['user_id'];
				 
				//============ VERIFIED
				if( $key['type_account'] == '1' ) {
					$verified = ' <i class="fa fa-check-circle verified verified-min showTooltip" title="'.$_SESSION["LANG"]["verified"].'" data-toggle="tooltip" data-placement="right"></i>';
				} else {
					$verified = null;
				}
				if(strlen($key['type'])>1){
					list($key['type'],$reaction_type)=explode('-',$key['type']);
				}else{
					$reaction_type='like';
				}
				switch( $key['type'] ) {
					case 1:
						$action          = $_SESSION['LANG']['followed_you'];
						$icoDefault      = '<i class="glyphicon glyphicon-plus size-icon-popover ico-btn-followed"></i><i class="icon-user3 myicon-right ico-btn-followed"></i>';
						$linkDestination = false;
						$idTag           = null;
						break;
					case 2:
						$action          = $_SESSION['LANG']['reposted_post'];
						$icoDefault      = '<i class="fa fa-retweet myicon-right ico-btn-repost"></i>';
						$reply           = null;
						$linkDestination = true;
						$idTag           = null;
						break;
					case 3:
						$action          = $_SESSION['LANG']['favorited_post'];
//							$icoDefault      = '<i class="fa fa-thumbs-o-up myicon-right ico-btn-favorite"></i>';
						$icoDefault      = '<img src="/public/reactions/assets/img/'.$reaction_type.'.png" style="width:20px;" />';
						$linkDestination = true;
						$idTag           = null;
						break;
					case 4:
						$action          = $_SESSION['LANG']['commented_post'];
						$icoDefault      = '<i class="fa fa-comment myicon-right ico-btn-reply"></i>';
						$linkDestination = true;
						$idTag           = '#reply-status-wrap';
						break;
					case 5:
						$action          = '<strong style="color: #333; font-weight: bold;">@</strong>'.$_SESSION['LANG']['mentions'].'';
						$icoDefault      = null;
						$linkDestination = true;
						$idTag           = null;
						break;
					case 6:
						$action          = '<strong style="color: #FF7000; font-weight: bold;">@</strong>'.$_SESSION['LANG']['mentions_in_replies'].'';
						$icoDefault      = null;
						$linkDestination = true;
						$idTag           = '#reply-status-wrap';
						break;
				}
				
				/* Url */
				$urlStatus = URL_BASE.$key['p_username'].'/status/'.$key['id'];
				
				?>
				<!-- POSTS -->
				
				<!-- ******* media li-group ********  -->
				<li class="media li-group list-group-item border-group hoverList" data="<?php echo $key['idInteraction']; ?>">
				  	<a class="pull-left openModal" href="<?php echo URL_BASE.$key['username'] ?>" data-id="<?php echo $_idUser ?>">
				    	<img class="media-object img-circle" src="<?php echo URL_BASE.'thumb/80-80-public/avatar/'.$key['avatar']; ?>" width="40">
				  	</a>
				  	<div class="media-body">
						<div class="pull-right small">
							<span class="timestamp timeAgo" data="<?php echo date('c', strtotime( $key['date'] ) ); ?>"></span>
							</div>
						<h6 class="media-heading"><a href="<?php echo URL_BASE.$key['username'] ?>" data-id="<?php echo $_idUser ?>" class="username-title openModal"><strong><?php echo stripslashes( $key['name'] ).$verified; ?></strong>
						</a> <small style="color: #999;">@<?php echo $key['username']; ?></small>
						</h6>
				    	<p class="list-grid-block p-text">
				    		<?php echo $icoDefault.' '.$action; ?>

				    		<?php if( $linkDestination == true ): ?>
			   				<p class="margin-zero">
			   					
			   					<?php echo _Function::checkText( $key['post_details'] ); ?>
			   					<?php if( $key['video_site'] == '' && $key['post_details'] == '' && $key['url_soundcloud'] == ''  ): ?>
			   						<a data-view="<?php echo $_SESSION['LANG']['details']; ?> &rarr;" data-url="<?php echo $urlStatus; ?>" class="linkImage galeryAjax cboxElementxx link-img" href="<?php echo URL_BASE.'upload/'.$key['photo']; ?>" rel="lightbox">
			   							<?php echo 'pic.thumb/'.$key['photo']; ?>
			   							</a>
			   						<?php endif; ?>
			   				</p><!-- end tag p -->
			   				
			   				<a style="float: left; font-size: 12px; margin: 0 7px 0 0;" href="<?php echo URL_BASE.$key['p_username'].'/status/'.$key['id'].$idTag; ?>">
			   					<span class="textEx"><?php echo $_SESSION['LANG']['got_to_post']; ?> &raquo;</span>
			   				</a>
			   			<?php endif; //<--- End $linkDestination == true ?>
				    	</p>
				    	
		     </div><!-- media-body -->
	    </li><!-- ******* media li-group ********  -->
<?php } endif; 
     }//<-- SESSION
  }//<-- if token id
}//<-- ISSET
?>