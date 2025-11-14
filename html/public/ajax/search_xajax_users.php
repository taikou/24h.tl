<?php
session_start();
error_reporting(0);
if( 
		isset ( $_POST['offset']) 
		&& isset ( $_POST['number']) 
		&& isset ( $_POST['_userId'] )
	
) {
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
	$obj              = new AjaxRequest();
	$response         = $obj->searchUsers( $_POST['_userId'], ' && U.status = "active" && U.id '.$query.' '.$offset, 'LIMIT '.$postnumbers, $_SESSION['authenticated'] );
    
    $countPosts = count( $response );
   		 if( $countPosts != 0 ) : 
			 foreach ( $response as $key ) {
			 	
				$_idUser = $key['id'];
				 
				 $countryUser = $obj->getCountryUser( $_idUser );
				 
			 	//============ VERIFIED
				if( $key['type_account'] == '1' ) {
					$verified = ' <i class="fa fa-check-circle verified verified-min showTooltip" title="'.$_SESSION["LANG"]["verified"].'" data-toggle="tooltip" data-placement="right"></i>';
				} else {
					$verified = null;
				}
				$checkBlock      = $obj->checkUserBlock( $_SESSION['authenticated'], $key['id'] );
				?>
				<!-- POSTS -->
				<li class="media border-group hoverList li-group list-group-item" data="<?php echo $key['id']; ?>">
					<!-- ******** Media ****** -->  
		    	  	<div class="media li-group">
		                    <div class="pull-left">
		                       <a href="<?php echo URL_BASE.$key['username']; ?>" class="openModal" data-id="<?php echo $_idUser ?>">
		                       	<img src="<?php echo URL_BASE."thumb/200-200-public/avatar/".$key['avatar']; ?>" alt="Avatar" class="media-object img-rounded" width="100">
		                       </a>
		                    </div>
		                    <div class="media-body clearfix text-overflow">
		                       <strong class="media-heading">
		                       		<a href="<?php echo URL_BASE.$key['username']; ?>" data-id="<?php echo $_idUser ?>" class="openModal username-title-2">
		                       			<?php echo stripslashes( $key['name'] ).$verified; ?>
		                       			</a>
		                       		<small style="color: #999;">@<?php echo $key['username']; ?></small>
		                       	</strong>
		                       <p class="text-col">
		                       	
		                       	<?php if( $key['country'] != 'xx' && empty( $key['bio']  ) && $countryUser->country != FALSE ) : ?>
		                       	<small><i class="fa fa-map-marker myicon-right"></i> <?php echo $countryUser->country ?></small>
		                        <?php endif; ?>
		                        
		                        <small><?php echo _Function::checkTextNoLine( $key['bio'] ); ?></small>
		                       </p>
		                       
				              <div class="btn-block">
		                       	
		                      <?php if( $checkBlock[0]['status'] == 0 ): ?>	
                          
	                          <?php if(  isset( $_SESSION['authenticated'] ) && $_SESSION['authenticated'] != $key['id'] && $key['followActive'] == 0 ): ?>
	                          <button data-username="<?php echo $key['username']; ?>" data-id="<?php echo _Function::randomString( 10, FALSE, TRUE, FALSE ).'-'.$key['id']; ?>" data-follow="<?php echo $_SESSION['LANG']['follow']; ?>" data-unfollow="<?php echo $_SESSION['LANG']['unfollow']; ?>" data-following="<?php echo $_SESSION['LANG']['following']; ?>" type="button" class="btn btn-border btn-xs btn-default followBtn" data-original-title="Request send"><i class="icon-user3 myicon-right"></i> <?php echo $_SESSION['LANG']['follow']; ?></button>
	                       	   <?php endif; ?>
	                       	   
	                       	   <?php if(  isset( $_SESSION['authenticated'] ) && $_SESSION['authenticated'] != $key['id'] && $key['followActive'] == 1 ): ?>
	                       	   <button data-username="<?php echo $key['username']; ?>" data-id="<?php echo _Function::randomString( 10, FALSE, TRUE, FALSE ).'-'.$key['id']; ?>" data-follow="<?php echo $_SESSION['LANG']['follow']; ?>" data-unfollow="<?php echo $_SESSION['LANG']['unfollow']; ?>" data-following="<?php echo $_SESSION['LANG']['following']; ?>" type="button" class="btn btn-border btn-xs btn-info follow_active followBtn" data-original-title="Request send"><i class="icon-user3 myicon-right"></i> <?php echo $_SESSION['LANG']['following']; ?></button>
	                       	   <?php endif; ?>
	                       	   
	                       	  <?php endif;//<<-- Blocked ?>
		                       	
		                       </div><!-- btn-block -->
		                       
		                    </div><!-- media-body -->
		        	 </div><!-- ******** Media ****** -->
				</li> <?php }//<<<-- Foreach
		   		endif;  //<<<--- $countPosts != 0
		   		}//<--
}//<-- ISSET
?>