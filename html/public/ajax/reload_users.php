<?php
session_start();
error_reporting(0);

if ( isset( $_SESSION['authenticated'] ) ) {
	if( $_SERVER['REQUEST_METHOD'] == "POST" ) {
		
	/*
	 * --------------------------
	 *   Require File
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
	$obj            = new AjaxRequest();
	$whoToFollow    = $obj->whoToFollow( $_SESSION['authenticated'] );
	
	foreach ( $whoToFollow as $key ) {
   				//============ VERIFIED
				if( $key['type_account'] == '1' ) {
					$verfied = ' <i class="fa fa-check-circle verified verified-min showTooltip" title="'.$_SESSION["LANG"]["verified"].'" data-toggle="tooltip" data-placement="right"></i>';
				} else {
					$verfied = null;
				}
   			 ?>
   			 <div class="media li-group padding-sm">
                    <div class="pull-left">
                       <a href="<?php echo URL_BASE.$key['username']; ?>" <?php echo URL_BASE.$key['username']; ?>" data-id="<?php echo $key['id'] ?>" class="openModal">
                       	<img src="<?php echo URL_BASE."thumb/100-100-public/avatar/".$key['avatar']; ?>" alt="Image" class="media-object img-rounded" width="50" />
                       </a>
                    </div>
                    <div class="media-body clearfix text-overflow">
                       <strong class="media-heading">
                       		<a href="<?php echo URL_BASE.$key['username']; ?>" data-id="<?php echo $key['id'] ?>" class="openModal link-follow"><?php echo stripslashes( $key['name'] ); ?> <?php echo $verfied; ?></a>
                       	</strong>
                       	
                       <p class="text-col" style="margin-top: 5px;">
                          <button type="button" class="btn btn-default btn-border btn-xs whofollow" data-follow="<?php echo $_SESSION['LANG']['follow']; ?>" data-following="<?php echo $_SESSION['LANG']['following']; ?>" data-username="<?php echo $key['username']; ?>" data-id="<?php echo _Function::randomString( 10, FALSE, TRUE, FALSE ).'-'.$key['id']; ?>">
                          	<i class="glyphicon glyphicon-plus size-icon-popover"></i><i class="icon-user3 myicon-right"></i> 
                          	<?php echo $_SESSION['LANG']['follow']; ?> @<?php echo $key['username']; ?></a>
                          	</button>
                       </p>
               </div><!-- media-body -->
         </div><!-- Media -->
         
   			<?php }//<---FOREACH 

   }//<-- POST ISSET
else {
	echo '[Error Request]';
}
}//<-- SESSION
?>