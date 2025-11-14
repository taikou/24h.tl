<?php

/****************************************
 * 
 *  Author : Miguel Vasquez
 *  File   : userModel.php
 *  Class UserModel
 *  
 *  
 *  This class has the function get data, 
 *  which are called since the controller "userController", 
 *  and others controllers
 *  
 **************************************/
 
class UserModel extends ModelBase
{
	public function getSettings() {
    	/*
		 * --------------------------
		 * Get Settings from Admin
		 * --------------------------
		 */
        $post = $this->db->query("
        SELECT
        title,
        description,
        keywords,
        message_length,
        post_length,
        ad,
        bg_navbar,
        color_link_navbar,
        color_icons_verified
        FROM admin_settings ");
        return $post->fetch( PDO::FETCH_OBJ );
    }
	
	public function infoUser( $usr ) {
   	   /*
		 * --------------------------------------------
		 * Get info user, name, username, etc..
		 * -------------------------------------------
		 */
   	   if ( isset( $usr ) ) {
			$sql = "
			SELECT 
			U.id,
			U.username,
			U.name,
			U.date,
			U.location,
			U.country,
			U.email,
			U.website,
			U.bio,
			U.avatar,
			U.type_account,
			U.mode,
			U.status, 
			U.messages_private,
			U.email_notification_follow,
			U.email_notification_msg,
			U.push_notification_follow,
			U.push_notification_msg,
			U.push_notification_reaction,
			U.push_notification_comment,
			U.push_notification_mention,
			U.language,
			U.oauth_uid,
			U.oauth_provider,
			U.twitter_oauth_token,
			U.twitter_oauth_token_secret,
			U.follow_pass,
			U.sns_instagram_id,
			U.sns_instagram_verified,
			U.sns_twitter_id,
			U.sns_twitter_verified,
			U.afili_cid,
			U.afili_sendresult_time,
			P.bg,
			P.bg_position,
			P.bg_attachment,
			P.color_link,
			P.bg_color,
			P.cover_image,
			P.cover_position
			FROM users U
			LEFT JOIN profile_design P ON U.id = P.user 
			LEFT JOIN posts PO ON U.id = PO.user && PO.status = '1' && PO.status_general = '1'
			WHERE U.id = ?
			GROUP BY U.id
			";
			
			$data = $this->db->prepare( $sql );
			
			if ( $data->execute( array( $usr ) ) ) {
				
				return $data->fetch( PDO::FETCH_OBJ );
				$this->db = null;
			}
			
		}// END ISSET
    }//<--- * END FUNCTION *-->
    
    public function countries() {
		/*
		 * ----------------
		 * Get countries..
		 * ----------------
		 */
		$sql = $this->db->prepare( "SELECT short, country FROM countries ORDER BY country" );
		if( $sql->execute() )
			{
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
	}
    
    public function getAllPosts( $_where, $_limit, $session ) {
			/*
			 * -----------------------------
			 * Get all Post in "Index page"
			 * $_where : Condition
			 * $_limit : Limit of post
			 * $_session: Current Session
			 * -----------------------------
			 */
			$sql = $this->db->prepare( "
			SELECT 
			COUNT(DISTINCT FA.id ) favoriteUser,
			P.id
			FROM posts P 
			LEFT JOIN users U ON P.user = U.id
			LEFT JOIN posts RP ON P.repost_of_id = RP.id
			LEFT JOIN users U2 ON RP.user = U2.id
			LEFT JOIN favorites  FA ON P.id = FA.id_favorite && FA.id_usr = :id && FA.status = '1'
	        ".$_where."
			ORDER BY P.id DESC
			". $_limit ."
			" );
			
			//return $sql;
			if( $sql->execute( array( ':id' => $session ) ) )
			{
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
			//$this->db = null;
			
	}//<--- * END FUNCTION *-->
	
	public function getStaticPost($user_id){
		$sql="SELECT * FROM user_static_posts WHERE user_id='".mysql_escape_string($user_id)."' LIMIT 5;";
		$rs=$this->db->query($sql)->fetchAll();
		if(count($rs)>0){
			foreach($rs as $r){
				$static_ids[]=$r['post_id'];
			}
			return $static_ids;
		}else{
			return array();
		}
	}

	
	
	public function discover( $_where, $_limit, $session ) {
			 /*
			 * --------------------------------
			 * Get all Post in "Discover Page"
			 * $_where : Condition
			 * $_limit : Limit of post
			 * $_session: Current Session
			 * --------------------------------
			 */
			$sql = $this->db->prepare( "
			SELECT 
			COUNT(DISTINCT FA.id ) favoriteUser,
			COUNT(DISTINCT F.id ) followActive,
			COUNT(DISTINCT B.id) blockUser,
			P.id,
			P.token_id,
			P.post_details,
			P.photo,
			P.video_code,
			P.video_title,
			P.video_site,
			P.video_url,
			P.user,
			P.date,
			U.name,
			U.username,
			U.avatar,
			U.type_account
			FROM posts P
			LEFT JOIN users U ON P.user = U.id
			LEFT JOIN followers F ON P.user = F.following && F.follower = :id  && F.status = '1'
			LEFT JOIN favorites  FA ON P.id = FA.id_favorite && FA.id_usr = :id && FA.status = '1'
			LEFT JOIN block_user B ON U.id = B.user && B.user_blocked = :id && B.status = '1'
			". $_where ." && U.status = 'active' && P.status = '1' && B.id IS NULL && P.repost_of_id = '0'
			GROUP BY P.id
			ORDER BY P.date DESC
			". $_limit ."
			" );
			
			//return $sql;
			if( $sql->execute( array( ':id' => $session ) ) ){
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
			//$this->db = null;
			
	}//<--- * END FUNCTION *-->
	
	public function connect( $_where, $_limit, $session ) {
			/*
			 * ------------------------------------
			 * Get all Mentions in "Connect page"
			 * $_where : Condition
			 * $_limit : Limit of post
			 * $_session: Current Session
			 * ------------------------------------
			 */
			$sql = $this->db->prepare( "
			SELECT 
			COUNT(DISTINCT FA.id ) favoriteUser,
			P.id,
			P.token_id,
			P.post_details,
			P.photo,
			P.video_code,
			P.video_title,
			P.video_site,
			P.video_url,
			P.user,
			P.date,
			U.id user_id,
			U.name,
			U.username,
			U.avatar,
			U.type_account,
			U.mode
			FROM posts P
			LEFT JOIN users U ON P.user = U.id
			LEFT JOIN favorites  FA ON P.id = FA.id_favorite && FA.id_usr = :id && FA.status = '1'
			". $_where ."
			GROUP BY P.id
			ORDER BY P.id DESC
			". $_limit ."
			" );
			
			if( $sql->execute(  array( ':id' => $session )  ) )
			{
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
			
	}//<--- * END FUNCTION *-->
	
	public function getInteractions( $where, $limit, $_id ) {
			/*
			 * -----------------------------
			 * Get All interactions
			 * $where : Condition
			 * $limit : Limit media
			 * ---------------------------
			 */
			$sql = $this->db->prepare( "
			SELECT 
			I.type,
			P.id,
			P.token_id,
			P.post_details,
			P.video_url,
			P.user,
			P.date,
			U.id user_id,
			U.name,
			U.username,
			U.avatar,
			U.type_account,
			U.mode,
			US.username p_username
			FROM interactions I 
			LEFT JOIN users U ON I.autor = U.id
			LEFT JOIN posts P ON I.target = P.id
			LEFT JOIN reply R ON I.target = R.post 
			&& R.user = U.id 
			&& R.post = P.id 
			&& R.status = '1'
			LEFT JOIN followers F ON F.follower = U.id
			LEFT JOIN users US ON P.user = US.id
			LEFT JOIN favorites FA ON I.target = FA.id_favorite
		 	WHERE I.destination = :id && I.autor != :id && ".$where." && I.trash = '0'
		 	GROUP BY I.id DESC
		 	ORDER BY I.id DESC
			".$limit."
			" );
			
			if( $sql->execute(  array( ':id' => $_id  )  ) ) {
				
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
	}//<--- * END FUNCTION *-->
	
	public function getPostsFavs( $_where, $_limit, $session ) {
			/*
			 * -----------------------------
			 * Get Post Favorites
			 * $_where : Condition
			 * $_limit : Limit of post
			 * $_session: Current Session
			 * -----------------------------
			 */
			
			$sql = "SELECT COUNT(id) as totalPostsFavs FROM favorites WHERE id_favorite IN (SELECT id FROM posts WHERE user = '".$session."' and status='1') and status='1'";
//			error_log($sql);
//			return false;
//			$sql=$this->db->prepare($sql,$session);
/*
			$sql = $this->db->prepare( "
			SELECT 
			COUNT( DISTINCT P.id ) totalPostsFavs
			FROM posts P
			LEFT JOIN users U ON P.user = U.id
			LEFT JOIN favorites F ON P.id = F.id_favorite
			". $_where ." && U.status = 'active' && P.status = '1' && F.status = '1'
			" );
*/			
			if( $sql2=$this->db->query($sql) ) {
				return  $sql2->fetch( PDO::FETCH_OBJ );
			}
			
	}//<--- * END FUNCTION *-->
    
    public function whoToFollow( $_id ) {
			/*
			 * -----------------------------
			 * "who To Follow" : 
			 * suggestion who to follow
			 * $id : Current user "Session ID"
			 * -----------------------------
			 */
			$sql = $this->db->prepare( "
			SELECT 
			COUNT(DISTINCT B.id) blockUser,
			U.id,
			U.name,
			U.username,
			U.avatar,
			U.type_account
			FROM users U
			LEFT JOIN followers F ON U.id = F.following && F.follower = :id && F.status = '1'
			LEFT JOIN block_user B ON U.id = B.user && B.user_blocked = :id && B.status = '1'
			WHERE U.id <> :id && F.id IS NULL && U.status = 'active' && B.id IS NULL
			&& U.avatar REGEXP '_[0-9]{1,10}'
			GROUP BY U.id
			ORDER BY rand(". time() . " * " . time() .")
			LIMIT 3
			" );
			
			//return $sql;
			if( $sql->execute( array( ':id' => $_id ) ) )
			{
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
			$this->db = null;
			
	}//<--- * END FUNCTION *-->
	
  	public function getUserId( $usr ) {
   		/*
		 * -----------------------------
		 * Get user by username 
		 * $usr : Username
		 * http://yousite.com/UserName
		 * -----------------------------
		 */
   	   if ( isset( $usr ) )
		{
			$sql = "
			SELECT 
			id,
			name,
			date,
			username
			FROM users
			WHERE username = :username && status = 'active'
			";
			
			$data = $this->db->prepare( $sql );
			
			if ( $data->execute( array( ':username' => $usr ) ) )
			{
				return $data->fetchall();
				$this->db = null;
			}
			
		}// END ISSET
    }//<--- * END FUNCTION *-->

	
	public function getTrendsTopic() {
		/*
		 * -----------------------------
		 * Get Trends Topic
		 * trends over the last 5 days
		 * Limit 10
		 * -----------------------------
		 */
		$time = date( 'Y-m-d G:i:s', time() );
		$sql = $this->db->prepare( "
		SELECT COUNT( DISTINCT user ) total, 
		trends FROM trends_topic 
		WHERE 
		DATE(time_stamp) <= DATE('".$time."') AND 
        DATE(time_stamp) >= DATE('".$time."' - INTERVAL 5 DAY ) && trends != ''
		GROUP BY trends
		ORDER BY total DESC 
		LIMIT 10" );
		$sql->execute();
		return $sql->fetchall( PDO::FETCH_OBJ );

	}
	
	public function checkFollow( $follower, $following ) {
		/*
		 * ----------------------------------------------
		 * Namely follow up status
		 * ----------------------------------------------
		 */
		$follower  = (int)$follower;
		$following = (int)$following;
		
		$query = $this->db->prepare("
		SELECT status FROM followers WHERE follower = ? &&  following = ? ");
		$query->execute( array( $follower, $following ) );
		return $query->fetch( PDO::FETCH_OBJ );
		$this->db = null;
	}
	
	public function checkRepost( $id, $user ) {
		/*
		 * ----------------------------------------------
		 * Repost of User
		 * ----------------------------------------------
		 */
		$id    = (int)$id;
		
		$sql = $this->db->prepare( "SELECT id FROM posts WHERE repost_of_id = ? && user = ? && status = '1' " );
		$sql->execute( array( $id, $user ) );
		$response = $sql->fetch();
		
		if( !empty( $response ) ) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getRepostUser( $_id ) {
			/*
			 * ------------------------
			 * Get Repost
			 * $_id : ID Publication/Post
			 * ----------------------
			 */
			$sql = $this->db->prepare( "
			SELECT 
			COUNT( DISTINCT id ) totalRepost
			FROM posts
			WHERE repost_of_id = :id && status = '1'
			ORDER BY id ASC
			" );
			
			if( $sql->execute(  array( ':id' => $_id  )  ) )
			{
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
	}//<--- * END FUNCTION *-->
		public function checkUserBlock( $user, $blocked ) {
		/*
		 * ----------------------------------------------
		 * Know whether a user has been blocked
		 * ----------------------------------------------
		 */
		$user    = (int)$user;
		$blocked = (int)$blocked;
		
		$query = $this->db->prepare("
		SELECT id, status FROM block_user WHERE user = ? &&  user_blocked = ? ");
		$query->execute( array( $user, $blocked ) );
		return $query->fetchall();
		$this->db = null;
	}
	
	public function getPhotosVideos( $_id ) {
			/*
			 * ----------------------------------------------
			 * Get photos show Profile users
			 * $_id : ID User
			 * ----------------------------------------------
			 */
			$sql = $this->db->prepare( "
			SELECT 
			U.username,
			P.id,
			P.photo,
			P.post_details,
			P.date,
			P.video_code,
			P.video_url,
			P.video_site,
			P.video_thumbnail,
			P.video_title,
			P.url_soundcloud,
			P.title_soundcloud,
			P.thumbnail_song
			FROM posts P
			LEFT JOIN users U ON P.user = U.id
			WHERE P.user = :id && P.photo != '' && P.status = '1' && repost_of_id = 0
			|| P.user = :id && P.status = '1' && P.video_code != '' && repost_of_id = 0
			|| P.user = :id && P.status = '1' && P.video_url != '' && repost_of_id = 0
			|| P.user = :id && P.status = '1' && P.url_soundcloud != '' && repost_of_id = 0
			ORDER BY P.id DESC
			LIMIT 12
			" );
			if( $sql->execute(  array( ':id' => $_id  )  ) )
			{
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
	}//<--- * END FUNCTION *-->
	
	public function getAllMedia( $_id, $where, $limit ) {
			/*
			 * -----------------------------
			 * Get All media
			 * $_id : ID User
			 * $where : Condition
			 * $limit : Limit media
			 * ---------------------------
			 */
			$sql = $this->db->prepare( "
			SELECT 
			P.id,
			P.photo,
			P.post_details,
			P.date,
			P.video_code,
			P.video_url
			FROM posts P
			WHERE P.user = :id && P.repost_of_id = '0' && P.status = '1' && P.photo != '' ".$where."
&& P.date>=(utc_timestamp() - interval 1 day)			
			|| P.user = :id && P.repost_of_id = '0' && P.status = '1' && P.video_code != '' ".$where."
&& P.date>=(utc_timestamp() - interval 1 day)			
			|| P.user = :id && P.status = '1' && P.url_soundcloud != '' && repost_of_id = 0 ".$where."
&& P.date>=(utc_timestamp() - interval 1 day)			
			ORDER BY P.id DESC
			".$limit."
			" );
			if( $sql->execute(  array( ':id' => $_id  )  ) )
			{
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
	}//<--- * END FUNCTION *-->
    
    public function getFollowers( $_where, $_limit, $session ) {
			/*
			 * -----------------------------
			 * Get Followers
			 * $_where : Condition
			 * $_limit : Limit of post
			 * $_session: Current Session
			 * -----------------------------
			 */
			$sql = $this->db->prepare( "
			SELECT 
			COUNT(DISTINCT FO.id ) followActive,
			U.id,
			U.username,
			U.name,
			U.avatar,
			U.type_account,
			U.bio
			FROM users U
			LEFT JOIN followers F ON U.id = F.follower
			LEFT JOIN followers  FO ON U.id = F.following && FO.follower = :id && FO.status = '1' 
			". $_where ." && U.status = 'active' && F.status = '1'
			GROUP BY U.id
			ORDER BY F.id DESC
			". $_limit ."
			" );
			
			if( $sql->execute(  array( ':id' => $session )  ) )
			{
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
			
	}//<--- * END FUNCTION *-->
	
	public function getFollowing( $_where, $_limit, $session ) {
			/*
			 * -----------------------------
			 * Get Following
			 * $_where : Condition
			 * $_limit : Limit of post
			 * $_session: Current Session
			 * -----------------------------
			 */
			$sql = $this->db->prepare( "
			SELECT 
			COUNT(DISTINCT FO.id ) followActive,
			U.id,
			U.username,
			U.name,
			U.avatar,
			U.type_account,
			U.bio
			FROM users U
			LEFT JOIN followers F ON U.id = F.following
			LEFT JOIN followers  FO ON U.id = FO.follower && FO.following = :id && FO.status = '1'
			". $_where ." && U.status = 'active' && F.status = '1'
			GROUP BY U.id
			ORDER BY F.id DESC
			". $_limit ."
			" );
			
			if( $sql->execute(  array( ':id' => $session )  ) ) {
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
	}//<--- * END FUNCTION *-->
	
	public function getStatus( $user, $status ) {
			/*
			 * -----------------------------
			 * Get Status
			 * $user   : Username of user
			 * $status : ID status
			 * -----------------------------
			 */	
			$sql = $this->db->prepare( "
			SELECT 
			COUNT(DISTINCT FA.id ) favoriteUser,
			P.id,
			P.token_id,
			P.post_details,
			P.photo,
			P.video_code,
			P.video_title,
			P.video_site,
			P.video_url,
			P.user,
			P.date _date,
			P.url_soundcloud,
			P.title_soundcloud,
			P.geolocation,
			P.doc_site,
			P.doc_url,
			P.url,
			P.url_thumbnail,
			P.url_title,
			P.url_description,
			P.url_host,
			U.id user_id,
			U.name,
			U.username,
			U.avatar,
			U.type_account,
			U.mode,
			U.status,
			PR.bg,
			PR.bg_position,
			PR.bg_attachment,
			PR.color_link,
			PR.bg_color,
			FA.status fa_status
			FROM posts P
			LEFT JOIN users U ON P.user = U.id
			LEFT JOIN profile_design PR ON U.id = PR.user
			LEFT JOIN followers F ON P.user = F.following
			LEFT JOIN favorites  FA ON P.id = FA.id_favorite && FA.id_usr = :session && FA.status = '1'
			WHERE U.username = :user && P.id = :id && U.status = 'active' && P.status = '1'
			GROUP BY P.id
			ORDER BY P.date DESC
			" );
			
			if( $sql->execute( array( ':user' => $user, ':id' => $status, ':session' => $_SESSION['authenticated'] ) ) )
			{
				return  $sql->fetch( PDO::FETCH_OBJ );
			}
			//$this->db = null;
			
	}//<--- * END FUNCTION *-->

	public function getAllFavoritesByType( $_id ) {
			/*
			 * ------------------------
			 * Get Favorites
			 * $_id : ID Publication/Post
			 * ----------------------
			 */
			$sql = $this->db->prepare( "
			SELECT 
			F.status as type, count(*) as num
			FROM users U
			LEFT JOIN favorites F ON U.id = F.id_usr && F.status <> '0'
			LEFT JOIN posts P ON F.id_favorite = P.id
			WHERE P.id = :id
			GROUP BY F.status
			" );
			
			if( $sql->execute(  array( ':id' => $_id  )  ) )
			{
				$rs=$sql->fetchAll( PDO::FETCH_ASSOC );
				if(count($rs)>0){
					foreach($rs as $r){
						$ret[$r['type']]=$r['num'];
					}
					return $ret;
				}
			}
			return false;
	}//<--- * END FUNCTION *-->
	
	public function getFavorites( $_id ) {
			/*
			 * ------------------------
			 * Get Favorites
			 * $_id : ID Publication/Post
			 * ----------------------
			 */
			$sql = $this->db->prepare( "
			SELECT 
			U.id,
			U.username,
			U.name,
			U.avatar
			FROM users U
			LEFT JOIN favorites F ON U.id = F.id_usr && F.status = '1'
			LEFT JOIN posts P ON F.id_favorite = P.id
			WHERE P.id = :id
			ORDER BY F.date DESC
			LIMIT 20
			" );
			
			if( $sql->execute(  array( ':id' => $_id  )  ) )
			{
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
	}//<--- * END FUNCTION *-->
	
	public function getReply( $_id ) {
			/*
			 * ------------------------
			 * Get Replys
			 * $_id : ID Publication/Post
			 * ----------------------
			 */
			$sql = $this->db->prepare( "
			SELECT 
			U.id,
			U.username,
			U.name,
			U.avatar,
			U.type_account,
			R.id idReply,
			R.reply,
			R.date
			FROM users U
			LEFT JOIN reply R ON U.id = R.user && R.status = '1'
			LEFT JOIN posts P ON R.post = P.id
			LEFT JOIN block_user B ON B.user IN (".$_SESSION['authenticated'].",R.user) && B.user_blocked IN (".$_SESSION['authenticated'].",R.user)  && B.status = '1'
			WHERE P.id = :id && R.status = '1' && B.id is NULL
			ORDER BY R.date ASC
			" );
			
			if( $sql->execute(  array( ':id' => $_id  )  ) )
			{
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
	}//<--- * END FUNCTION *-->
	
	public function getMessages( $_id ) {
		/*
		 * ----------------------------------------
		 * Get messages private
		 * $_id : ID User
		 * ---------------------------------------
		 */	
		$sql = $this->db->prepare( "
		SELECT 
	    mm.recipient_id,
	    m.*,
	    U.id,
		U.username,
		U.name,
		U.type_account,
		U.avatar,
		U2.id id_2,
		U2.username username2,
		U2.name name2,
		U2.type_account type_account2,
		U2.avatar avatar2
		FROM (
		  SELECT
		  `from`,
		  `to`,
		    `from` + `to` - 1 as recipient_id,
		    Max(id) as id
		  FROM
		    messages
		  WHERE
		    `from` = :id && remove_from = '1'  ||
		    `to` = :id && remove_from = '1'
		  GROUP BY
		    `from` + `to` - 1
		  ) mm
		    INNER JOIN
		  messages m
		    On
		  mm.id = m.id
		  LEFT JOIN users U ON U.id = mm.`to`
		  LEFT JOIN users U2 ON U2.id = mm.`from` 
		  ORDER BY date DESC
		");
		if( $sql->execute(  array( ':id' => $_id  )  ) )
			{
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
	}//<<<<-- * End * --->>>>
	
	public function getCodeAccount( $code ) {
		/*
		 * -----------------------------
		 * Get Code Account
		 * $code : Code activation
		 * -----------------------------
		 */	
		if ( preg_match( '/[^a-z0-9\_\-]/i',$code ) )
		{
			return false;
		}
		$sql = $this->db->prepare("SELECT id FROM users WHERE activation_code = :code && status = 'pending'");
		$sql->execute( array(  ':code' => $code ) );
		$response = $sql->fetch();
		
		if( !empty( $response ) )
		{
			return true;
		}
		else {
			return false;
		}
	}//<<<-- End
	
	public function activeAccount( $code ) {
		/*
		 * -----------------------------
		 * Activate Account
		 * $code : Code activation
		 * -----------------------------
		 */	
		if ( preg_match( '/[^a-z0-9\_\-]/i',$code ) )
		{
			return false;
		}
		
		$sql = "UPDATE users SET status = 'active' WHERE activation_code = :code && status = 'pending'";
		
		$stmt = $this->db->prepare( $sql );
		$stmt->bindValue( ':code', $code, PDO::PARAM_STR );
		
		if ( $stmt->execute() )
		{
			return true;
		}
		 else {
			 return false;
		 }
		$this->db = null;
		
	}//<<< * End * -->>>>
	
	public function notificationsMessages() {
    	/*
		 * -----------------------
		 * Get all news Messages
		 * ----------------------
		 */
    	$sql = $this->db->prepare("
    	SELECT 
    	COUNT( DISTINCT `from` ) total
		FROM messages 
		WHERE `to` = :id && status = 'new' && remove_from = '1'
		GROUP BY `to`
    	");
    	
    	if ( $sql->execute( array( ':id' => $_SESSION['authenticated'] ) ) ) {
				return $sql->fetch( PDO::FETCH_OBJ );
				$this->db = null;
			}
    }
    
    public function notificationsInteractions() {
    	/*
		 * -----------------------
		 * Get all Interactions
		 * ----------------------
		 */
    	$sql = $this->db->prepare("
    	SELECT 
    	COUNT(DISTINCT id ) total
		FROM interactions 
		WHERE destination = :id && status = '0'
    	");
    	
    	if ( $sql->execute( array( ':id' => $_SESSION['authenticated'] ) ) ) {
				return $sql->fetch( PDO::FETCH_OBJ );
				$this->db = null;
			}
    }
	
	public function interactionsViews() {
		$sql = "UPDATE interactions SET status = '1' WHERE status <> '1' and destination = :id";
		$stmt = $this->db->prepare( $sql );
		$stmt->bindValue( ':id', $_SESSION['authenticated'], PDO::PARAM_INT );
		$stmt->execute();
	}
	
	public function getAllPages() {
    	/*
		 * -------------------------------
		 * get All Pages static, Privacy
		 * Help, etc.
		 * -------------------------------
		 */
        $sql = $this->db->prepare("
        SELECT 
        url
        FROM pages_general 
        ");
		if ( $sql->execute() ) {
			return $sql->fetchall( PDO::FETCH_ASSOC );
		}
    }
	public function getAllPagesGeneral() {
    	/*
		 * -------------------------------
		 * get All Pages static, Privacy
		 * Help, etc.
		 * -------------------------------
		 */
        $sql = $this->db->query("
        SELECT 
        title,
        url
        FROM pages_general 
        ORDER BY title
        ");
		return $sql->fetchall();
    }
	
	//<<<<<<<<<<<<<<<<<<<<<<<< ---------- v2.7 ------------->>>>>>>>>>>>>>>>>>
	
	// Begin Method
	public function getCountryUser( $_ID ) {
		$sql = $this->db->prepare("
		SELECT 
		C.country 
		FROM countries C 
		LEFT JOIN users U ON C.short = U.country
		WHERE U.id = :id && U.country != 'xx'");
		
		if( $sql->execute( array( ':id' => $_ID ) ) ) {
			return $sql->fetch( PDO::FETCH_OBJ );
		}
		$this->db = null;
		
	}//<--- * End Method *-->
	
	
	//<<<<<<<<<<<<<<<<<<<<<--------------- V2.8 --------------->>>>>>>>>>>>>>>>>>>>
	
	public function infoUserApi( $usr ) {
   	   /*
		 * ------------------------------------------------
		 * Get info user, name, username, etc.. for API
		 * ------------------------------------------------
		 */
   	   if ( isset( $usr ) ) {
			$sql = "
			SELECT 
			COUNT( DISTINCT FO.id ) totalFollowers,
			COUNT( DISTINCT FOL.id ) totalFollowing,
			COUNT( DISTINCT FA.id ) totalFavs,
			COUNT( DISTINCT PO.id ) totalPost,
			U.id,
			U.username,
			U.name,
			U.location,
			U.country,
			U.website,
			U.bio,
			U.avatar,
			CONCAT('large_', U.avatar ) avatar_large,
			U.type_account verified,
			P.cover_image,
			CONCAT('large_', P.cover_image ) cover_large
			FROM users U
			LEFT JOIN profile_design P ON U.id = P.user
			LEFT JOIN posts PO ON U.id = PO.user && PO.status = '1' && PO.status_general = '1'
			LEFT JOIN followers FO ON U.id = FO.following && FO.status = '1'
			LEFT JOIN followers FOL ON U.id = FOL.follower && FOL.status = '1'
			LEFT JOIN favorites FA ON U.id = FA.id_usr && FA.status = '1'
			WHERE U.id = ?
			GROUP BY U.id
			";
			
			$data = $this->db->prepare( $sql );
			
			if ( $data->execute( array( $usr ) ) ) {
				
				return $data->fetch( PDO::FETCH_OBJ );
				$this->db = null;
			}
			
		}// END ISSET
    }//<--- * End Method *-->
    
    public function postApi( $_where, $_limit ) {
			 /*
			 * --------------------------------
			 * Get all Post in "Discover Page"
			 * $_where : Condition
			 * $_limit : Limit of post
			 * $_session: Current Session
			 * --------------------------------
			 */
			$sql = $this->db->prepare( "
			SELECT 
			id,
			token_id,
			post_details,
			photo,
			video_code,
			video_title,
			video_site,
			video_url,
			video_thumbnail,
			url_soundcloud,
			title_soundcloud,
			thumbnail_song,
			user,
			date
			FROM posts
			". $_where ." && status = '1' && repost_of_id = '0'
			GROUP BY id
			ORDER BY date DESC
			". $_limit ."
			" );
			
			//return $sql;
			if( $sql->execute() ){
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
			//$this->db = null;
			
	}//<--- * End Method *-->
	
	public function checkUsername( $username ) {
		/*
		 * -----------------------
		 * VERIFIED USERNAME
		 * -----------------------
		 */
		$post = $this->db->prepare("SELECT username FROM users WHERE username = :user");
		$post->execute( array( ':user' => $username ));
		return $post->fetchall();
		$this->db = null;
	}//<--- * End Method *-->
	
	public function signUp() {
   	    /*
		 * ----------------------------------------------
		 * sign up
		 * 1) Verified username, if is available
		 * insert into database
		 * ----------------------------------------------
		 */
   		$verifiedUsername = self :: checkUsername( $_POST['username'] ) ? 1 : 0;
   		
		if( $verifiedUsername == 1 ) {
			return( 'unavailable' );
		}
		
		if( $_POST['email_verify'] == 1 ) {
			$status = 'pending';
		} else if ( $_POST['email_verify'] == 0 ) {
			$status = 'active';
		}
		

		
		$this->db = null;
   }//<-- end Method
   
   public function checkUser($uid, $oauth_provider, $username, $name, $location, $email, $twitter_otoken,$twitter_otoken_secret) {
   	
	 /*
	 * -----------------------
	 * Sign in
	 * -----------------------
	 */
   	 $sql = "
   	 SELECT 
   	 id, 
   	 username,
   	 language
   	 FROM users
   	 WHERE oauth_uid = '".$uid."' && oauth_provider = '".$oauth_provider."' && status = 'active'";

	  $query = $this->db->prepare( $sql );
	 
	  if( $query->execute()) {
	  	
		$data = $query->fetch( PDO::FETCH_ASSOC );
		  
			if( !empty( $data ) ) {
				# User is already registered
				if($twitter_otoken and $twitter_otoken_secret){
					//update twitter oauth_token and secret
					$sql="UPDATE users set twitter_oauth_token='".mysql_escape_string($twitter_otoken)."', twitter_oauth_token_secret='".mysql_escape_string($twitter_otoken_secret)."' WHERE id='".$data['id']."' LIMIT 1";
					$this->db->query($sql);
				}
			} 
			//<--------- * Insert new record * ------>
			else {
		
		$verifiedUsername = self::checkUsername( $username ) ? 1 : 0;
   		
		if( $verifiedUsername == 1 ) {
			$username = $username.$uid;
		} else {
			$username = $username;
		}
		
		if( $location == '') {
			$location = '';
		}
		
		$_dateNow = date( 'Y-m-d G:i:s', time() );
		
		/*
		 * -----------------------
		 * Insert User
		 * -----------------------
		 */
	    $_sql = "
	    INSERT INTO users 
	    VALUES(
	    null,
	    ?,
	    ?,
	    ?,
	    'xx',
	    ?,
	    ?,
	    '".$_dateNow."',
	    '',
	    '',
	    '".DEFAULT_AVATARS[mt_rand(0,count(DEFAULT_AVATARS)-1)]."',
	    ?,
	    '0',
	    '1',
	    'active',
	    '1',
	    '1',
	    '1',
		'1',
		'1',
		'1',
		'1',
		'1',
	    '".$_SESSION['lang']."',
	    ?,
	    ?,
	    ?,
	    ?,
		'',
		'',
	    '',
	    '',
		'',
		'".mysql_escape_string($_SESSION['afili_cid'])."',
		'0',
		0,
		''
		);";
		$password = '';
		$code     = '';
		
		/*if( $oauth_provider == 'twitter' ){
			$email    = $username.'@';
		} else if( $oauth_provider == 'facebook' ){
			$email    = $email;
		}*/
		
		
		 if( $oauth_provider == 'twitter' ){
			$email    = $username.'@';
		} else if( $oauth_provider == 'facebook' && !isset($email )){
			$email    = $username.'@';
		} else if( $oauth_provider == 'facebook' && isset($email) ){
			$email    = $email;
		}
		 
		
		$stmt  = $this->db->prepare( $_sql );
		
		$stmt->bindValue( 1, $username, PDO::PARAM_STR);
		$stmt->bindValue( 2, $name, PDO::PARAM_STR );
		$stmt->bindValue( 3, $location, PDO::PARAM_STR);
		$stmt->bindValue( 4, $password, PDO::PARAM_STR );
		$stmt->bindValue( 5, $email, PDO::PARAM_STR );
		$stmt->bindValue( 6, $code, PDO::PARAM_STR );
		$stmt->bindValue( 7, $uid, PDO::PARAM_STR );
		$stmt->bindValue( 8, $oauth_provider, PDO::PARAM_STR );
		$stmt->bindValue( 9, $twitter_otoken, PDO::PARAM_STR );		
		$stmt->bindValue( 10, $twitter_otoken_secret, PDO::PARAM_STR );
				
		$stmt = $stmt->execute();
		
		/*
		 * -----------------------
		 * User ID inserted
		 * -----------------------
		 */
		$idUsr = $this->db->lastInsertId( $stmt );
		
		if ( $stmt == true ) {
			
			//============================================================//
			//=                * INSERT PROFILE DESIGN *                 =//
			//============================================================//
			$profileDesign = $this->db->prepare("
			INSERT INTO profile_design
			VALUES
			(
			null,
			?,
			'photo_".mt_rand(1,140).".jpg',
			'left',
			'fixed',
			'#0088E2',
			'#000000',
			'',
			'50% 50%'
			)");
			$profileDesign->execute( array( $idUsr ) );
			
			$querySql = "
		   	 SELECT 
		   	 id, 
		   	 username,
		   	 language
		   	 FROM users
		   	 WHERE id = ".$idUsr." && status = 'active'";
			 
			 $_query = $this->db->prepare( $querySql  );
			  if( $_query->execute()) {
	  	
				$data = $_query->fetch( PDO::FETCH_ASSOC );
				$data['is_newuser']=true;
			  }
		  }//<<--- $stmt == true
	    }//<<-- ELSE
	}//<<-- EXECUTE
	  
	  return $data;
	  
	  $this->db = null;
   }//<-- end Method
   
   public function countAllPost( $id ){
   	$sql = "SELECT 
		   	COUNT( DISTINCT id ) 
		   	totalPosts 
		   	FROM  posts 
		   	WHERE user = :id 
		   	&& status =  '1'
		   	&& status_general =  '1'
   	";
	
	$data = $this->db->prepare( $sql );
		if ( $data->execute( array( ':id' => $id ) ) ) {
			
			return $data->fetch( PDO::FETCH_OBJ );
			$this->dbh = null;
		}
   }//<-- end Method
   
   public function countAllFollowers( $id ){
   	$sql = "SELECT 
		   	COUNT( DISTINCT id ) 
		   	totalFollowers 
		   	FROM  followers 
		   	WHERE following = :id 
		   	&& status =  '1'
   	";
	
	$data = $this->db->prepare( $sql );
		if ( $data->execute( array( ':id' => $id ) ) ) {
			
			return $data->fetch( PDO::FETCH_OBJ );
			$this->dbh = null;
		}
   }//<-- End Method
   
   public function countAllFollowing( $id ){
   	$sql = "SELECT 
		   	COUNT( DISTINCT id ) 
		   	totalFollowing 
		   	FROM  followers 
		   	WHERE follower = :id 
		   	&& status =  '1'
   	";
	
	$data = $this->db->prepare( $sql );
		if ( $data->execute( array( ':id' => $id ) ) ) {
			
			return $data->fetch( PDO::FETCH_OBJ );
			$this->dbh = null;
		}
   }//<-- End Method
   
   public function countAllFavorites( $id ){
   	$sql = "SELECT 
		   	COUNT( DISTINCT id ) 
		   	totalFavs 
		   	FROM  favorites 
		   	WHERE id_usr = :id 
		   	&& status =  '1'
   	";
	
	$data = $this->db->prepare( $sql );
		if ( $data->execute( array( ':id' => $id ) ) ) {
			
			return $data->fetch( PDO::FETCH_OBJ );
			$this->dbh = null;
		}
   }//<-- end Method
   
   public function getRecentEmojis(){
   		$sql="SELECT * FROM demoji_history ORDER BY lastuse_at DESC LIMIT 40";
		return $this->db->query($sql)->fetchAll();
   }
	
}//<--- * END CLASS * ---> 

?>
