<?php

/****************************************
 * 
 *  Author : Miguel Vasquez
 *  File   : pagesController.php
 *  Class pagesController
 * 
 *  This class has the function of controlling the following pages:
 *  * Help 
 *  * Advertising
 *  * Terms
 *  * Privacy
 *  * About
 *  * Profile - "Settings"
 *  * Settings
 *  * Password
 *  * Design
 *  * Messages
 *  * Recover Pass
 *  * Discover
 *  * Connect
 *  * Activity
 * 
 **************************************/


use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;
 
class pagesController extends Controller
{
	//<-- * INDEX ERROR * --->
    public function index() {
    	header ('HTTP/1.0 404 Not Found');
	    include 'public/error/404.php'; // SHOW ERROR 404
    }
	
	public function pagesStatic( $page ) {
    	$output                    = $this->loadModel('User');
		$this->_view->getAllPages  = $output->getAllPages();
		$this->_view->pagesGeneral = $output->getAllPagesGeneral();
		
		foreach ( $this->_view->getAllPages as $key) {
			 $loop[] = $key['url']; 
		}
		if( !in_array( $page , $loop ) ) {
			$this->redirect( null );
		}
		
		//<--- INFO USER SESSION ACTIVE
		$this->_view->infoSession  = $output->infoUser( $_SESSION['authenticated'] );
		$this->_view->settings     = $output->getSettings();
		$this->_view->notiMsg      = $output->notificationsMessages();
		$this->_view->notiIntera   = $output->notificationsInteractions();
		
		// Count All posts
		$this->_view->dataPosts = $output->countAllPost( $_SESSION['authenticated'] );
		// Count All Followers
		$this->_view->dataFollowers = $output->countAllFollowers( $_SESSION['authenticated'] );
		// Count All Following
		$this->_view->dataFollowing = $output->countAllFollowing( $_SESSION['authenticated'] );
		
		//<--- WHO TO FOLLOWER
	    $this->_view->whoToFollow  = $output->whoToFollow( $_SESSION['authenticated'] );
	   
	   //<--- TRENDING TOPIC
	   $this->_view->trending = $output->getTrendsTopic();
	   
    	$out                = $this->loadModel( 'Pages' );
		$this->_view->data  = $out->getPage( $page );
		$this->_view->res   = count( $this->_view->data  );
        $this->_view->title = $this->_view->data->title;
		/* Show Views */
        $this->_view->render('static', null );
    }
    
    public function api() {
		if(isset($_GET['post']) and $_GET['post']){
			$api_post=trim($_GET['post']);
			if(strlen($api_post)>140)$api_post=substr($api_post,0,140);
			$_SESSION['api_post']=$api_post;
			header('Location: /');
			exit;
		}
    	$output                    = $this->loadModel('User');
		$this->_view->pagesGeneral = $output->getAllPagesGeneral();
		
		
		//<--- INFO USER SESSION ACTIVE
		$this->_view->infoSession  = $output->infoUser( $_SESSION['authenticated'] );
		$this->_view->settings     = $output->getSettings();
		$this->_view->notiMsg      = $output->notificationsMessages();
		$this->_view->notiIntera   = $output->notificationsInteractions();
		
		// Count All posts
		$this->_view->dataPosts = $output->countAllPost( $_SESSION['authenticated'] );
		// Count All Followers
		$this->_view->dataFollowers = $output->countAllFollowers( $_SESSION['authenticated'] );
		// Count All Following
		$this->_view->dataFollowing = $output->countAllFollowing( $_SESSION['authenticated'] );
		
		//<--- WHO TO FOLLOWER
	    $this->_view->whoToFollow  = $output->whoToFollow( $_SESSION['authenticated'] );
	   
	   //<--- TRENDING TOPIC
	   $this->_view->trending = $output->getTrendsTopic();
	   
    	$out                = $this->loadModel( 'Pages' );
        $this->_view->title = 'API';
		
		/* GET USER DATA */
		$this->_view->totalPostDisplay = 15; //<<<-- Total posts to display
		$this->_view->userGET = $_GET['user'];
		$this->_view->postGET = $_GET['post'];
		$this->_view->data  = $output->getUserId( $this->_view->userGET );
		$this->_view->info  = $output->infoUserApi(  $this->_view->data[0]['id'] );
		
		/* POSTS */
		//<----- ALL POST INDEX PAGE
		   $this->_view->posts = $output->postApi(
		   'WHERE user = '.$this->_view->data[0]['id'].'
			&& status = "1"
			&& status_general = "1"
&& date>=(now() - interval 1 day) 
			', 'LIMIT '.($this->_view->totalPostDisplay)
		   );
		
		if( isset( $this->_view->userGET ) ) {
			/* Show Views */
            $this->_view->render('api_json', null );
		} else {
			/* Show Views */
        $this->_view->render('api', null );
		}
		
    }
	
	public function profile() {
		  $output                   = $this->loadModel('User');
		  $this->_view->settings    = $output->getSettings();
		  $this->_view->notiMsg     = $output->notificationsMessages();
		  $this->_view->notiIntera  = $output->notificationsInteractions();
		  $this->_view->pagesGeneral = $output->getAllPagesGeneral();
		  
		  // Count All posts
		$this->_view->dataPosts = $output->countAllPost( $_SESSION['authenticated'] );
		// Count All Followers
		$this->_view->dataFollowers = $output->countAllFollowers( $_SESSION['authenticated'] );
		// Count All Following
		$this->_view->dataFollowing = $output->countAllFollowing( $_SESSION['authenticated'] );
		
		  //<--- INFO USER SESSION ACTIVE
		  $this->_view->infoSession = $output->infoUser( $_SESSION['authenticated'] );
		  //<--- TRENDING TOPIC
		  $this->_view->trending    = $output->getTrendsTopic();
		  $this->_view->module      = 'profile';
		   
		 $this->_view->title = $_SESSION['LANG']['profile'];
		 /* Show Views */
		 $this->_view->render('settings', null );
	}
	
	public function settings() {
		 $output                   = $this->loadModel('User');
		 $this->_view->settings    = $output->getSettings();
	     $this->_view->notiMsg     = $output->notificationsMessages();
		 $this->_view->notiIntera  = $output->notificationsInteractions();
		 $this->_view->pagesGeneral = $output->getAllPagesGeneral();
		 
		 // Count All posts
		$this->_view->dataPosts = $output->countAllPost( $_SESSION['authenticated'] );
		// Count All Followers
		$this->_view->dataFollowers = $output->countAllFollowers( $_SESSION['authenticated'] );
		// Count All Following
		$this->_view->dataFollowing = $output->countAllFollowing( $_SESSION['authenticated'] );
		
		 //<--- INFO USER SESSION ACTIVE
		 $this->_view->infoSession = $output->infoUser( $_SESSION['authenticated'] );
		 //<--- TRENDING TOPIC
		 $this->_view->trending    = $output->getTrendsTopic();
		 //<--- countries
		 $this->_view->countries   = $output->countries();
		 $this->_view->module      = 'settings';
		   
		 $this->_view->title = $_SESSION['LANG']['settings'];
		 /* Show Views */
		 $this->_view->render('settings', null );
	}
	
	public function password() {
	  $output                   = $this->loadModel('User');
	  $this->_view->settings    = $output->getSettings();
	  $this->_view->notiMsg     = $output->notificationsMessages();
	  $this->_view->notiIntera  = $output->notificationsInteractions();
	  $this->_view->pagesGeneral = $output->getAllPagesGeneral();
	  
	  // Count All posts
		$this->_view->dataPosts = $output->countAllPost( $_SESSION['authenticated'] );
		// Count All Followers
		$this->_view->dataFollowers = $output->countAllFollowers( $_SESSION['authenticated'] );
		// Count All Following
		$this->_view->dataFollowing = $output->countAllFollowing( $_SESSION['authenticated'] );
		
	  //<--- INFO USER SESSION ACTIVE
	  $this->_view->infoSession = $output->infoUser( $_SESSION['authenticated'] );
	  //<--- TRENDING TOPIC
	  $this->_view->trending    = $output->getTrendsTopic();
	  //<--- pass
	  $this->_view->module      = 'password';
	   
	 $this->_view->title = $_SESSION['LANG']['password'];
	 /* Show Views */
	 $this->_view->render('settings', null );
	}
	
	public function design() {
	  $output                   = $this->loadModel('User');
	  $this->_view->settings    = $output->getSettings();
	  $this->_view->notiMsg     = $output->notificationsMessages();
	  $this->_view->notiIntera  = $output->notificationsInteractions();
	  $this->_view->pagesGeneral = $output->getAllPagesGeneral();
	  
	  // Count All posts
		$this->_view->dataPosts = $output->countAllPost( $_SESSION['authenticated'] );
		// Count All Followers
		$this->_view->dataFollowers = $output->countAllFollowers( $_SESSION['authenticated'] );
		// Count All Following
		$this->_view->dataFollowing = $output->countAllFollowing( $_SESSION['authenticated'] );
		
      //<--- INFO USER SESSION ACTIVE
	  $this->_view->infoSession = $output->infoUser( $_SESSION['authenticated'] );
	  //<--- TRENDING TOPIC
	  $this->_view->trending    = $output->getTrendsTopic();
	  //<--- design
	  $this->_view->module      = 'design';
	   
	 $this->_view->title = $_SESSION['LANG']['design'];
	 /* Show Views */
	 $this->_view->render('settings', null );
	}
	
	public function messages() {
	 $output                   = $this->loadModel('User');
	//<--- INFO USER SESSION ACTIVE
	 $this->_view->infoSession = $output->infoUser( $_SESSION['authenticated'] );
	 $this->_view->settings    = $output->getSettings();
	 $this->_view->notiMsg         = $output->notificationsMessages();
     $this->_view->notiIntera      = $output->notificationsInteractions();
	 $this->_view->pagesGeneral = $output->getAllPagesGeneral();
	 
	 // Count All posts
		$this->_view->dataPosts = $output->countAllPost( $_SESSION['authenticated'] );
		// Count All Followers
		$this->_view->dataFollowers = $output->countAllFollowers( $_SESSION['authenticated'] );
		// Count All Following
		$this->_view->dataFollowing = $output->countAllFollowing( $_SESSION['authenticated'] );
		
	 //<--- TRENDING TOPIC
	 $this->_view->trending    = $output->getTrendsTopic();
	 
	 //<<<<-- Messages --->>>>
	 $this->_view->messages    = $output->getMessages( $_SESSION['authenticated'] );
	 $this->_view->countMgs    = count( $this->_view->messages  );
	 
	 $this->_view->title  =  $_SESSION['LANG']['messages'];
	 //<--- msg
	 $this->_view->module = 'messages';
	/* Show Views */
     $this->_view->render('settings', null );
	}
	
	public function recover() {
	 $output                 = $this->loadModel( 'Pages' );
	 $this->_view->codeValid = $output->getCodePass( $_GET['c'] ) ? 1 : 0;
	 /* Show Views */
	 $this->_view->render('recover', null );
	}
	
	public function discover() {
		$output                  = $this->loadModel('User');
		$this->_view->settings   = $output->getSettings();
		$this->_view->notiMsg    = $output->notificationsMessages();
		$this->_view->notiIntera = $output->notificationsInteractions();
		$this->_view->pagesGeneral = $output->getAllPagesGeneral();
		
		$sort = $_GET['sort'];
		
		$sortDefaults = array( 
				    	'photos', 
				    	'music', 
				    	'videos', 
				    	'links', 
				    	'games'
		);
		
		if( in_array( $sort, $sortDefaults ) ) {
			switch( $sort ) {
					case 'photos':
						$this->_view->titleSort      = $_SESSION['LANG']['photos'].' - ';
						$this->_view->query_sql      = ' && P.photo != ""';
						$this->_view->query_sql_ajax = 'photos';
						break;
						
					case 'music':
						$this->_view->titleSort      = $_SESSION['LANG']['music'].' - ';
						$this->_view->query_sql      = ' && P.url_soundcloud != ""';
						$this->_view->query_sql_ajax = 'music';
						break;
						
					case 'videos':
						$this->_view->titleSort      = $_SESSION['LANG']['videos'].' - ';
						$this->_view->query_sql      = ' && P.video_url != ""';
						$this->_view->query_sql_ajax = 'videos';
						break;
						
					case 'links':
						$this->_view->titleSort      = $_SESSION['LANG']['links'].' - ';
						$this->_view->query_sql      = ' && P.url_host != ""';
						$this->_view->query_sql_ajax = 'links';
						break;
				}
		} else {
			$this->_view->titleSort      = null;
			$this->_view->query_sql      = null;
			$this->_view->query_sql_ajax = null;
		}
		
		// Count All posts
		$this->_view->dataPosts = $output->countAllPost( $_SESSION['authenticated'] );
		// Count All Followers
		$this->_view->dataFollowers = $output->countAllFollowers( $_SESSION['authenticated'] );
		// Count All Following
		$this->_view->dataFollowing = $output->countAllFollowing( $_SESSION['authenticated'] );
		
		//<----- ALL POST INDEX PAGE
	   $this->_view->posts     = $output->discover( 
	   'WHERE P.user != '. $_SESSION['authenticated'] .' 
	   && U.status = "active" 
	   && P.status = "1" 
	   && U.mode = "1" 
	   && F.id IS NULL'.$this->_view->query_sql, 
	   null , 
	   $_SESSION['authenticated']
	   );
		     
		//<--- INFO USER SESSION ACTIVE
	   $this->_view->infoSession = $output->infoUser( $_SESSION['authenticated'] );
	   //<--- TRENDING TOPIC
	   $this->_view->trending    = $output->getTrendsTopic();
		 
	   //<--- WHO TO FOLLOWER
	   $this->_view->whoToFollow = $output->whoToFollow( $_SESSION['authenticated'] );
	   
		   
	   $this->_view->title = $this->_view->titleSort.$_SESSION['LANG']['discover'].' ';
	   /* Show Views */
	   $this->_view->render('discover', null );
	}

	
	
	public function interactions() {
		$output                   = $this->loadModel('User');
		$this->_view->settings    = $output->getSettings();
		$this->_view->notiMsg     = $output->notificationsMessages();
		$this->_view->interaViews = $output->interactionsViews();
		$this->_view->pagesGeneral = $output->getAllPagesGeneral();
		
		// Count All posts
		$this->_view->dataPosts = $output->countAllPost( $_SESSION['authenticated'] );
		// Count All Followers
		$this->_view->dataFollowers = $output->countAllFollowers( $_SESSION['authenticated'] );
		// Count All Following
		$this->_view->dataFollowing = $output->countAllFollowing( $_SESSION['authenticated'] );
		
		//<--- INFO USER SESSION ACTIVE
	   $this->_view->infoSession = $output->infoUser( $_SESSION['authenticated'] );
	   
		//<----- ALL POST INDEX PAGE
//	   $this->_view->data = $output->getInteractions( 
//	   'U.status = "active" ', 
//	   null , 
//	   $_SESSION['authenticated']
//	   );
		     
	   //<--- TRENDING TOPIC
	   $this->_view->trending = $output->getTrendsTopic();
		 
	   //<--- WHO TO FOLLOWER
	   $this->_view->whoToFollow = $output->whoToFollow( $_SESSION['authenticated'] );
	   
	   $this->_view->title = $_SESSION['LANG']['interactions'];
	   $this->_view->_file = 'ajax.interactions.php';
	   /* Show Views */
	   $this->_view->render('interactions', null );
	}

	public function oauth() {
		
		$oauth_provider = $_GET['oauth_provider'];
		
		if( $oauth_provider == '' ) {
	   	 header('location: '.URL_BASE );
		 exit;
	   } 
		
		if( $oauth_provider == 'twitter' ) {
			$twitteroauth = new TwitterOAuth(YOUR_CONSUMER_KEY, YOUR_CONSUMER_SECRET);

			// Requesting authentication tokens, the parameter is the URL we will be redirected to
			$request_token = $twitteroauth->getRequestToken(URL_BASE.'get_data_twitter/');
			// Saving them into the session
			
			$_SESSION['oauth_token']        = $request_token['oauth_token'];
			$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
			
			// If everything goes well..
			if ( $twitteroauth->http_code == 200) {
				
			    // Let's generate the URL and redirect
			    $url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
			    header('Location: ' . $url);
				
			} else {
			error_log(print_r($twitteroauth,1));
			    // It's a bad idea to kill the script, but we've got to know when there's an error.
			    die($_SESSION['LANG']['error']);
			}
		}//<--- End IF
		
		
		if( $oauth_provider == 'facebook' ){
			
			require_once 'oauth/Facebook/autoload.php';
			
			// init app with app id and secret
			FacebookSession::setDefaultApplication( APP_ID, APP_SECRET );
			// login helper with redirect_uri
			    $helper = new FacebookRedirectLoginHelper(URL_BASE.'oauth/?oauth_provider=facebook' );
			try {
			  $session = $helper->getSessionFromRedirect();
			} catch( FacebookRequestException $ex ) {
			  // When Facebook returns an error
			} catch( Exception $ex ) {
			  // When validation fails or other local issues
			}
			// see if we have a session
			if ( isset( $session ) ) {
				
				$output  = $this->loadModel('User');
				
			  // graph api request for user data
			  $request = new FacebookRequest( $session, 'GET', '/me' );
			  $response = $request->execute();
			  // get response
			  $graphObject = $response->getGraphObject();
			  $fbid        = $graphObject->getProperty('id');              // To Get Facebook ID
			  $fbfullname  = $graphObject->getProperty('name'); // To Get Facebook full name
			  $femail      = $graphObject->getProperty('email');    // To Get Facebook email ID
			  
			  $userdata = $output->checkUser($fbid, 'facebook', $fbid, $fbfullname, null, $femail, null, null );
				if( !empty( $userdata ) ){
		    	
				$_SESSION['authenticated'] = $userdata['id'];
				$_SESSION['username']      = $userdata['username'];
				$_SESSION['lang_user']     = $userdata['language'];

		        header('Location: '.URL_BASE.'');
		        } else {
		        	echo 'error';
		        }
	
			} else {
			  $loginUrl = $helper->getLoginUrl(array('scope' => 'email', 'public_profile'));
			 header("Location: ".$loginUrl);
			}
		}
		
	}//<<-- End Method
	
	public function get_data_twitter() {
		if($_GET['useapp']){
//			header('Location: tl24h://.https://24h.tl/get_data_twitter/?oauth_verifier='.$_GET['oauth_verifier']);
//			exit;
		}
		if ( !empty( $_GET['oauth_verifier'] ) 
			&& !empty($_SESSION['oauth_token'] ) 
			&& !empty($_SESSION['oauth_token_secret'] )
		) {
			$output  = $this->loadModel('User');
			
		    // We've got everything we need
		    $twitteroauth = new TwitterOAuth( YOUR_CONSUMER_KEY, YOUR_CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret'] );
		
		// Let's request the access token
		    $access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']);
		// Save it in a session var
		    $_SESSION['access_token'] = $access_token;
		// Let's get the user's info
		    $user_info = $twitteroauth->get('account/verify_credentials');
			
		    if ( isset( $user_info->error ) ) {
		    	
		        // Something's wrong, go back to square 1  
		       header('Location: '.URL_BASE.'oauth/?oauth_provider=twitter');
			   
		    } else {
				$twitter_otoken        = $access_token['oauth_token'];
				$twitter_otoken_secret = $access_token['oauth_token_secret'];
				$email                 = '';
		        $uid                   = $user_info->id;
		        $username              = $user_info->screen_name;
				$name                  = $user_info->name;
				$location              = $user_info->location;
		        
		        $userdata = $output->checkUser($uid, 'twitter', $username, $name, $location, $email, $twitter_otoken, $twitter_otoken_secret );
		   
		    if( !empty( $userdata ) ){
		    	
				$_SESSION['authenticated'] = $userdata['id'];
				$_SESSION['username']      = $userdata['username'];
				$_SESSION['lang_user']     = $userdata['language'];

				if($userdata['is_newuser']){
					$url_newuser='?act=newuser';
				}
		        header('Location: '.URL_BASE.$url_newuser);
		        } else {
		        	 echo $_SESSION['LANG']['error'];
		        }
		    }
		} else {
		    // Something's missing, go back to square 1
		    header('Location: '.URL_BASE.'oauth/?oauth_provider=twitter');
		}
	}//<<-- End Method


}//<<<<<<<<<-- * END CLASS * -->>>>>>>>>>>>>

?>
