<?php 
/*----------------------------------------------
 *  SHOW NUMBER NOTIFICATIONS IN BROWSER ( 1 )
 * --------------------------------------------
 */
if( $this->notiMsg->total != 0 &&  $this->notiIntera->total != 0 ) {
	$totalNotifications = '('.( $this->notiMsg->total + $this->notiIntera->total ).') ';
	$totalNotify        = ( $this->notiMsg->total + $this->notiIntera->total );
} else if( $this->notiMsg->total == 0 &&  $this->notiIntera->total != 0  ) {
	$totalNotifications = '('.$this->notiIntera->total.') ';
	$totalNotify = $this->notiIntera->total;
} else if ( $this->notiMsg->total != 0 &&  $this->notiIntera->total == 0 ) {
	$totalNotifications = '('.$this->notiMsg->total.') ';
	$totalNotify = $this->notiMsg->total;
} else {
	$totalNotifications = null;
	$totalNotify = null;
}
$thisurl='https://'.$_SERVER["HTTP_HOST"] . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="<?=$_SESSION['lang']?>">
  <head>
    <meta charset="utf-8">
    <base href="<?php echo URL_BASE; ?>" target="_top" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta name="description" content="<?php echo DESCRIPTION_SITE; ?>" />
	<meta name="keywords" content="<?php echo KEYWORDS_SITE; ?>" />
   <meta name="apple-mobile-web-app-capable" content="yes" />
   <link rel="apple-touch-icon-precomposed" href="<?php echo URL_BASE; ?>public/img/24h_icon.png" />
    <link rel="icon" href="<?php echo URL_BASE; ?>public/img/24h_icon.png">
	
   <? /*
   <meta name="apple-itunes-app" content="app-id=1392021806">
   <meta name="google-play-app" content="app-id=com.decoo.tl24h">
   <link rel="stylesheet" href="public/smartbanner/smart-app-banner.css?4" type="text/css" media="screen">
   <link rel="apple-touch-icon" href="<?php echo URL_BASE; ?>public/img/24h_icon.png" />
   <link rel="android-touch-icon" href="<?php echo URL_BASE; ?>public/img/24h_icon.png" />
    */ ?>
    <? if(!preg_match('/24h_timeline/',$_SERVER['HTTP_USER_AGENT'])){ ?>
	<meta name="smartbanner:title" content="24h.timeline">
	<meta name="smartbanner:author" content="Decoo,inc.">
	<meta name="smartbanner:price" content="FREE">
	<meta name="smartbanner:price-suffix-apple" content=" - On the App Store">
	<meta name="smartbanner:price-suffix-google" content=" - In Google Play">
	<meta name="smartbanner:icon-apple" content="<?php echo URL_BASE; ?>public/img/24h_icon.png">
	<meta name="smartbanner:icon-google" content="<?php echo URL_BASE; ?>public/img/24h_icon.png">
	<meta name="smartbanner:button" content="VIEW">
	<meta name="smartbanner:button-url-apple" content="https://24h.tl/?app">
	<meta name="smartbanner:button-url-google" content="https://24h.tl/?app">
	<meta name="smartbanner:enabled-platforms" content="android,ios">
   <meta name="smartbanner:disable-positioning" content="true">
    <link rel="stylesheet" href="/public/smartbanner/smartbanner.css?2">
	<script src="public/smartbanner/smartbanner.min.js"></script>
	<? } ?>
	
 <?php foreach ( _Function :: arrayLang() as $key => $value ) : ?>
   <link rel="alternate" hreflang="<?=$value?>" href="<?=$thisurl.(strpos($thisurl,'?')?'&':'?')?>lang=<?=$value?>">
 <?php endforeach;  ?>
    <title><?php echo $totalNotifications; if( isset( $this->title ) ) : echo _Function::spaces(strip_tags( $this->title )).' - '; endif; ?><?php echo SITE_NAME; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo $_layoutParams['root_css']; ?>bootstrap.css?19" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo $_layoutParams['root_css']; ?>main.css?21" rel="stylesheet">
    
     <!-- FLAT UI CSS -->
    <link href="<?php echo $_layoutParams['root_css']; ?>flat-ui.css?15" rel="stylesheet">
    
    <!-- FONT Awesome CSS -->
    <link href="<?php echo $_layoutParams['root_css']; ?>font-awesome.min.css" rel="stylesheet">
    
    <!-- IcoMoon CSS -->
    <link href="<?php echo $_layoutParams['root_css']; ?>icomoon.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="<?php echo $_layoutParams['root_js']; ?>ie-emulation-modes-warning.js"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?php echo $_layoutParams['root_js']; ?>ie10-viewport-bug-workaround.js"></script>
    
    
    <link href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">

    <link href="/public/reactions/assets/css/style.css?9" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/smartphoto@latest/css/smartphoto.min.css">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <style type="text/css">
    	
    	.navbar-inverse:not(.navbar-user-ui) {
    		background-color: <?php echo $this->settings->bg_navbar; ?>;
    	}
    	.navbar-inverse .navbar-nav > li > a {
    		color: <?php echo $this->settings->color_link_navbar; ?>;
    	}
    	i.verified {
    		color: <?php echo $this->settings->color_icons_verified; ?>;
    	}
		
		body {
			-moz-user-select: none; /* Firefox */
			-ms-user-select: none; /* Internet Explorer */
			-khtml-user-select: none; /* KHTML browsers (e.g. Konqueror) */
			-webkit-user-select: none; /* Chrome, Safari, and Opera */
			-webkit-touch-callout: none; /* Disable Android and iOS callouts*/
		}
    	
    </style>
    
<?php if( Session::get( 'authenticated' ) ): ?>
<script type="text/javascript">
var scrool_top_icon = "up";

//<----- Notifications
function Notifications() {	
	
	 var _title = '<?php if( isset( $this->title ) ) : echo addslashes(  _Function::spaces( strip_tags( $this->title ) ) ).' - '; endif; ?><?php echo addslashes( SITE_NAME ); ?>';
	 console.time('cache');
	 $.get("public/ajax/notifications.php", function( data ) {	
		if ( data ) {
			//* Messages */
			if( data.messages != 0 ) {
				
				var totalMsg = data.messages;
				
				$('#noti_msg').fadeIn().html(data.messages);
			} else {
				$('#noti_msg').fadeOut().html('');
				
				if(  data.interactions == 0 ) {
					 $('title').html( _title );
				}
			}
			
			//* Interactions */
			if( data.interactions != 0 ) {
				
				var totalIntera = data.interactions;
				$('#noti_connect').fadeIn().html(data.interactions);
			}
			
			//* Error */
			if( data.error == 1 ) {
				window.location.reload();
			}
			
			var totalGlobal = parseInt( totalMsg ) + parseInt( totalIntera );
		
		if( data.interactions != 0 && data.messages != 0 ) {
		    $('title').html( "("+ totalGlobal + ") " + _title );
		  } else if( data.interactions != 0 && data.messages == 0 ) {
		    $('title').html( "("+ data.interactions + ") " + _title );
		  } else if( data.interactions == 0 && data.messages != 0 ) {
		    $('title').html( "("+ data.messages + ") " + _title );
		  } 
		
		}//<-- DATA
	     	
		},'json');
		
		console.timeEnd('cache'); 
}//End Function TimeLine
	
timer = setInterval("Notifications()", 10000);
        </script>
            <?php endif; ?>
            
<script type="text/javascript">
    
    // AllLoaded
    var AllLoaded = "<?php echo $_SESSION['LANG']['all_loaded']; ?>";
    // ReadMore
    var ReadMore = "<?php echo $_SESSION['LANG']['read_more']; ?>";
    var ReadLess = "<?php echo $_SESSION['LANG']['read_less']; ?>";
    
    <?php if( !Session::get( 'authenticated' ) ): ?>
    // Mail Verify True or False
    var emailVerify = <?php echo EMAIL_VERIFY; ?>;
    <?php endif; ?>
</script>


<style>
.smartphoto-count{
	display: none;
}
.smartphoto-header{
	margin-top:60px;
}
</style>