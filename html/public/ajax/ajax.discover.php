<?php
session_start();
error_reporting(0);
if( 
		isset ( $_POST['offset']) 
		&& isset ( $_POST['number'])	
)
{
if( !isset ( $_SESSION['authenticated'] ))$_SESSION['authenticated']=0;
if ( isset ( $_SESSION['authenticated'] ) )
{
  if( isset( $_POST ) && $_SERVER['REQUEST_METHOD'] == "POST" )
   {
   	
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
	if( $_POST['query'] == 1 )
	{
		$query = '<';
	}
	else 
	{
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
	
	$sortDefaults = array( 
				    	'photos', 
				    	'music', 
				    	'videos', 
				    	'links', 
				    	'games'
		);

if( in_array( $_POST['_userId'], $sortDefaults ) ) {
	switch( $_POST['_userId'] ) {
					case 'photos':
						$query_sql_sort      = ' && P.photo != ""';
						break;
					case 'music':
						$query_sql_sort      = ' && P.url_soundcloud != ""';
						break;
					case 'videos':
						$query_sql_sort      = ' && P.video_url != ""';
						break;
					case 'links':
						$query_sql_sort      = ' && P.url_host != ""';
						break;
				}
		} else {
			$query_sql_sort      = null;
		}
	
	/*
	 * ----------------------
	 *   Instance Class
	 * ----------------------
	 */
	$obj              = new AjaxRequest();
	$infoSessioUsr    = $obj->infoUserLive( $_SESSION['authenticated'] );
	$response         = $obj->discover(
	'WHERE P.user != '. $_SESSION['authenticated'] .' 
		   && U.status = "active" 
		   && P.status = "1" 
&& P.date>=(utc_timestamp() - interval 1 day)	  
&& B.id IS NULL 
&& U.ban_status = 0		   
		   && U.mode = "1" 
		   && P.id '.$query.' '.$offset .' 
		   && P.repost_of_id = "0"
		   && F.id IS NULL '.$query_sql_sort.'
		   GROUP BY P.id ORDER BY P.id DESC', 
		   'LIMIT '.$postnumbers,
		    $_SESSION['authenticated'] );
	
	?>
	<?php $countPosts = count( $response );
   		 if( $countPosts != 0 ) : 
			 foreach ( $response as $key ) {
			 	
				$idPost  = $key['id'];
				$_idUser = $key['user_id'];

				//============ VERIFIED
				if( $key['type_account'] == '1' ) {
					$verified = ' <i class="fa fa-check-circle verified verified-min showTooltip" title="'.$_SESSION["LANG"]["verified"].'" data-toggle="tooltip" data-placement="right"></i>';
				} else {
					$verified = null;
				}
				//============ FAVORITES
				if( $key['favoriteUser'] == 1 ) {
					$classFav     = 'favorited';
					$spanFav      = ' title="'.$_SESSION['LANG']['trash'].' '.$_SESSION['LANG']['favorite'].'"';
					$spanAbsolute = '<span class="add_fav"></span>';
					$textFav      = $_SESSION['LANG']['favorited'];
				} else  {
					$classFav     = null;
					$spanFav      = null;
					$spanAbsolute = null;
					$textFav      = $_SESSION['LANG']['favorite'];
				}
				
				/*
				 * -------------------------------------------------
				 *      If the picture is larger than 440 pixels, 
				 *      show the thumbnail
				 * -------------------------------------------------
				 */
				$widthPhoto = _Function::getWidth( '../../upload/'.$key['photo'] ); 
				
				if( $widthPhoto >= 600 ) {
					$thumbPic = 'thumb/600-600-';
				} else  {
					$thumbPic = null;
				}
				
				/* Url */
				$urlStatus = URL_BASE.$key['username'].'/status/'.$key['id'];
				
				$activeRepost = $obj->checkRepost( $key['id'], $_SESSION['authenticated'] );
				
				//============ REPOST SESSION CURRENT
				if( $activeRepost == 1  ) {
					$spanRepost   = ' repostedSpan';
					$textRepost   = $_SESSION['LANG']['reposted'];
				} else  {
					$spanRepost   = null;
					$textRepost   = $_SESSION['LANG']['repost'];
				}
				
				/*
				 * -------------------------------------
				 *  POST DETAILS / EXPAND / FAVS ETC
				 * -------------------------------------
				 */
				include( 'post_details.php' );
				
			}//<--- Foreach
 endif; //<--- != 0
     }//<-- SESSION
  }//<-- if token id
}//<-- ISSET
?>