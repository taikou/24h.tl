<?php /* <meta content="charset=UTF-8"?> */ 
//このファイルはUTF-８であることを認識させるための日本語文字列です
require_once '../../application/Config.php';
require_once '../../application/Db.php';
require_once '../../application/SPDO.php';

require_once '../../application/DataConfig.php';

//セッション容量軽減のため、都度読み込む（でもajaxの場合のクリア処理が不完全なので意味ないな・・・）
if( $_SESSION['lang']  && !$_SESSION['LANG'] ) {
    	/* Prefix */
    	$prefix = 'lang_';
		/* Root */
		$root  = '/home/homepage/webs/24h.tl/html/languages';
            $_lang = $_SESSION['lang'];
			$lang =  $root . '/' . $prefix.$_lang.'.php';
			
			if( is_readable( $lang ) ) {
				require_once $lang;
			} else {
				require_once $root . DS . $prefix.'en.php';;
			}
            $_SESSION['lang'] = $_lang;			
}


/****************************************
 * 
 *  Author : Miguel Vasquez
 *  File   : classAjax.php
 *  Class AjaxRequest
 *  This class has the function, insert, edit 
 *  and get data using ajax
 * 
 **************************************/
 
class AjaxRequest
{
	/*
	 * --------------------
	 * @db DateBase
	 * @_dateNow Date now
	 * --------------------
	 */
	protected $db;
	private $_dateNow;
		
	public function __construct() {
		/*
		 * --------------------
		 * Database Connection
		 * --------------------
		 */
		 
		$this->db       = SPDO::singleton();
		$this->_dateNow = date( 'Y-m-d G:i:s', time() );
	}
	
	public function getSettings() {
    	/*
		 * -----------------------
		 * get Settings from Admin
		 * -----------------------
		 */
        $post = $this->db->query("
        SELECT
        title,
        description,
        keywords,
        message_length,
        post_length,
        new_registrations,
        email_verification,
        ad
        FROM admin_settings ");
        return $post->fetch( PDO::FETCH_OBJ );
    }
	
	public function getPhotoPost() {
		/*
		 * --------------------------------------
		 * get photo of post, when delete an post
		 * --------------------------------------
		 */
		
		$query = $this->db->prepare("
		SELECT photo FROM posts WHERE id = :id_post && user = :user");
		$query->execute( array( ':id_post' => $_POST['id'], ':user' => $_SESSION['authenticated'] ) );
		return $query->fetch( PDO::FETCH_OBJ  );
		$this->db = null;
	}
	
	public function infoUserLive( $usr ) {
   	   /*
		 * --------------------------------------------
		 * get info user in live, name, username, etc..
		 * -------------------------------------------
		 */
   	   if ( isset( $usr ) ) {
			$sql = "
			SELECT 
			U.id,
			U.username,
			U.name,
			U.avatar,
			U.email,
			U.type_account,
			U.password,
			U.bio,
			U.mode,
			U.email_notification_follow,
			U.email_notification_msg,
			U.push_notification_follow,
			U.push_notification_msg,
			U.push_notification_reaction,
			U.push_notification_comment,
			U.push_notification_mention,
			U.language,
			U.oauth_provider,
			U.twitter_oauth_token,
			U.twitter_oauth_token_secret,
			P.bg,
			P.bg_position,
			P.bg_attachment,
			P.color_link,
			P.bg_color,
			P.cover_image
			FROM users U
			LEFT JOIN profile_design P ON U.id = P.user 
			WHERE U.id = ?
			";
			
			$data = $this->db->prepare( $sql );
			
			if ( $data->execute( array( $usr ) ) )
			{
				
				return $data->fetch( PDO::FETCH_OBJ );
				$this->db = null;
			}
			
		}// END ISSET
    }//<--- * END FUNCTION *-->
    
    public function verifiedUsername( $username ) {
		/*
		 * -----------------------
		 * VERIFIED USERNAME
		 * -----------------------
		 */
		$post = $this->db->prepare("SELECT id FROM users WHERE username = :user");
		$post->execute( array( ':user' => $username ));
		return $post->fetch( PDO::FETCH_OBJ );
		$this->db = null;
	}
	
    public function checkUser( $id ) {
		 /*
		 * --------------------------------------------
		 * check if the user is valid
		 * @$id ID of user
		 * -------------------------------------------
		 */
		 
		$id = (int)$id;
		$sql = $this->db->prepare( "SELECT id FROM users WHERE id = :id && status = 'active'" );
		$sql->execute( array(  ':id' => $id ) );
		$response = $sql->fetch();
		
		if( !empty( $response ) )
		{
			return true;
		}
		else {
			return false;
		}
	}

    public function getUserInfo( $id ) {
		 /*
		 * --------------------------------------------
		 * check if the user is valid
		 * @$id ID of user
		 * -------------------------------------------
		 */
		 
		$id = (int)$id;
		$sql = $this->db->prepare( "SELECT * FROM users WHERE id = :id " );
		$sql->execute( array(  ':id' => $id ) );
		$response = $sql->fetch();
		
		if( !empty( $response ) )
		{
			return $response;
		}
		else {
			return false;
		}
	}


	public function sendInteraction( $destination, $autor, $target, $type ) {
		/*
		 * --------------------------------------------
		 * Insert user interactions.
		 * @$destination : ID Destiny
		 * @$autor : ID Autor
		 * @$target : Target General
		 * @type : Type of Interaction
		 * -------------------------------------------
		 */
		
		$sql = "
		INSERT INTO interactions
		VALUES(
		null,
		?,
		?,
		?,
		'".$type."',
		'0',
		'". $this->_dateNow ."',
		'0'
		);
		";
		$stmt = $this->db->prepare( $sql );
		$stmt->bindValue( 1, $destination, PDO::PARAM_INT );
		$stmt->bindValue( 2, $autor, PDO::PARAM_INT );
		$stmt->bindValue( 3, $target, PDO::PARAM_INT );
//		$stmt->bindValue( 4, $type, PDO::PARAM_STR );
		$stmt->execute();
	}
	
	public function checkEmail( $mail ) {
		/*
		 * --------------------------------------------
		 * check if the Email is valid
		 * @$mail Email of user
		 * -------------------------------------------
		 */
		$sql = $this->db->prepare( "SELECT id FROM users WHERE email = :mail && status = 'active'" );
		$sql->execute( array(  ':mail' => $mail ) );
		$response = $sql->fetch();
		
		if( !empty( $response ) )
		{
			return true;
		}
		else {
			return false;
		}
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
		SELECT status FROM followers WHERE follower = ? && following = ?");
		$query->execute( array( $follower, $following ) );
		return $query->fetchall();
		$this->db = null;
	}
	
	public function checkReportPost( $user, $reported ) {
		/*
		 * ----------------------------------------------
		 * know whether a publication has been reported 
		 * by the user
		 * ----------------------------------------------
		 */
		$user     = (int)$user;
		$reported = (int)$reported;
		
		$query = $this->db->prepare("
		SELECT id FROM post_reported WHERE user = ? && post_reported = ?");
		$query->execute( array( $user, $reported ) );
		return $query->fetchall();
		$this->db = null;
	}
	
	public function checkReportUser( $user, $reported ) {
		/*
		 * ----------------------------------------------
		 * Know whether a user has been reported
		 * ----------------------------------------------
		 */
		$user     = (int)$user;
		$reported = (int)$reported;
		
		$query = $this->db->prepare("
		SELECT id FROM users_reported WHERE user = ? && id_reported = ?");
		$query->execute( array( $user, $reported ) );
		return $query->fetchall();
		$this->db = null;
	}
	
		public function checkUserBlock( $user, $blocked ) {
		/*
		 * ----------------------------------------------
		 * Know whether a user has been blocked
		 * ----------------------------------------------
		 */
		$user    = (int)$user;
		$blocked = (int)$blocked;
		
		$query = $this->db->prepare("
		SELECT status FROM block_user WHERE user = ? && user_blocked = ?");
		$query->execute( array( $user, $blocked ) );
		return $query->fetchall();
		$this->db = null;
	}
	
	public function isBlockOrBlocked($user_id1,$user_id2){
		$query = $this->db->prepare("
		SELECT id FROM block_user WHERE user IN (:user1,:user2) and user_blocked IN (:user1,:user2) and status='1' LIMIT 1
		");
		$query->execute( array(':user1'=>$user_id1,':user2'=>$user_id2));
		$r=$query->fetch();
		if($r) return true;
		else false;
	}
	
    public function checkPost( $id, $token ) {
		/*
		 * ----------------------------------------------
		 * know if publication exists in the database
		 * ----------------------------------------------
		 */
		$id    = (int)$id;
		
		$sql = $this->db->prepare( "SELECT id FROM posts WHERE id = ? && token_id = ? && status = '1' && repost_of_id = '0' " );
		$sql->execute( array( $id, $token ) );
		$response = $sql->fetch();
		
		if( !empty( $response ) ) {
			return true;
		} else {
			return false;
		}
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
	
	public function getMaxId( $id, $table ) {
		/*
		 * @ $id : NAME ROW ID
		 * @ $table : NAME TABLE
		 * */
		$sql = $this->db->prepare( " SELECT MAX(".$id.") FROM ". $table ." " );
		
		if( $sql->execute( array( $id, $table ) ) ) {
			while( $res = $sql->fetch() ) {
				$maxId = $res[0];
			}
			return $maxId;
			$this->db = null;
		}
	}//<----------- end Method
	
	
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
	}
	
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
		
		
		//メールアドレスが重複した場合、以前のものがdeleteの場合に限りそれを置き換える
		if($_POST['email']){
			$sql="SELECT id,email FROM users WHERE email like :email and status='delete' LIMIT 1";
			$query  = $this->db->prepare( $sql );
			$query->execute(array(':email'=>$_POST['email']));
			$r=$query->fetch(PDO::FETCH_ASSOC);
			if($r['email']===$_POST['email']){
				$replace_email=$r['email'].'_until_'.date('Ymd-H:i:s');
				$sql="UPDATE users SET email=:replace_email WHERE id=:replace_id LIMIT 1";
				$query=$this->db->prepare($sql);
				$r2=$query->execute(array(':replace_email'=>$replace_email,':replace_id'=>$r['id']));
			}
		}
		/*
		 * -----------------------
		 * Insert User
		 * -----------------------
		 */
	    $sql = "
	    INSERT INTO users 
	    VALUES(
	    null,
	    ?,
	    ?,
	    '',
	    'xx',
	    ?,
	    ?,
	    '".$this->_dateNow."',
	    '',
	    '',
	    '".DEFAULT_AVATARS[mt_rand(0,count(DEFAULT_AVATARS)-1)]."',
	    ?,
	    '0',
	    '1',
	    '".$status."',
	    '1',
	    '1',
	    '1',
		'1',
		'1',
		'1',
		'1',
		'1',
	    '".$_SESSION['lang']."',
		'',
		'',
	    '',
	    '',
		'',
		'',
	    0,
	    '',
		0,
		'".mysql_escape_string($_SESSION['afili_cid'])."',
		'0',
		0,
		''
		);";
		$password = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 12]);

		$stmt  = $this->db->prepare( $sql );
		
		$stmt->bindValue( 1, $_POST['username'], PDO::PARAM_STR);
		$stmt->bindValue( 2, $_POST['full_name'], PDO::PARAM_STR );
		$stmt->bindValue( 3, $password, PDO::PARAM_STR );
		$stmt->bindValue( 4, $_POST['email'], PDO::PARAM_STR );
		$stmt->bindValue( 5, $_POST['code'], PDO::PARAM_STR );
		

		$stmt = $stmt->execute();
		
		/*
		 * -----------------------
		 * User ID inserted
		 * -----------------------
		 */
		$idUsr = $this->db->lastInsertId( $stmt );
error_log($stmt);
error_log($sql);
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
			
				if(preg_match('/24h_timeline/',$_SERVER['HTTP_USER_AGENT'])){
					//アプリの場合は自動ログインTOKENを保持
					$token=hash('sha512',$idUsr.'_'.time());
					$time = time() + 60*60*24*10000; //10000日
					setcookie('app_token',$token,$time);
					$sql="INSERT INTO user_apptoken (uid, token) VALUES('".$idUsr."','".$token."') ON DUPLICATE KEY UPDATE token='".$token."', firstlogin_at=now(), lastlogin_at=''";
					$this->db->query($sql);
				}

			
			if( $_POST['email_verify'] == 1 ) {
				return ( 1 );
			} else {
				return $idUsr;
			}
			
			
		}
		$this->db = null;
   }//<-- end Method
   
   public function signOut() {
   		$sql="UPDATE users SET last_sessid='' WHERE id='".$_SESSION['authenticated']."' LIMIT 1";
	   	$this->db->query($sql);
		error_log($sql);
   }
   
   public function signIn() {
   	 /*
	 * -----------------------
	 * Sign in
	 * -----------------------
	 */
	 $user = $_POST['user'];
	 $user_input = $_POST['password'];
	 
	 // パスワードフィールドも取得するように SQL を変更
   	 $sql = "
   	 SELECT 
   	 id, 
   	 username,
   	 language,
   	 password
   	 FROM users
   	 WHERE (username = :user || email = :user) && status = 'active'";
	 
	 $query = $this->db->prepare( $sql );
	  if( $query->execute( array( ':user' => $user ) ) )
		{
			$ret= $query->fetch( PDO::FETCH_ASSOC );
			
			if($ret) {
				// パスワード検証（新しいハッシュと旧 SHA-1 の両方に対応）
				$password_valid = false;
				$needs_upgrade = false;
				
				// 新しい bcrypt ハッシュで検証
				if (password_verify($user_input, $ret['password'])) {
					$password_valid = true;
				}
				// 旧 SHA-1 ハッシュで検証（互換性のため）
				elseif (sha1($user_input) === $ret['password']) {
					$password_valid = true;
					$needs_upgrade = true; // 次回ログイン時に新しいハッシュに更新
				}
				
				if ($password_valid) {
					// 旧パスワードを新しいハッシュに移行
					if ($needs_upgrade) {
						$new_hash = password_hash($user_input, PASSWORD_BCRYPT, ['cost' => 12]);
						$update_sql = "UPDATE users SET password = :new_hash WHERE id = :id LIMIT 1";
						$update_query = $this->db->prepare($update_sql);
						$update_query->execute([
							':new_hash' => $new_hash,
							':id' => $ret['id']
						]);
					}
					
					if(preg_match('/24h_timeline/',$_SERVER['HTTP_USER_AGENT'])){
						//アプリの場合は自動ログインTOKENを保持（こっちの方法は未可動）
						$token=hash('sha512',$ret['id'].'_'.time());
						$time = time() + 60*60*24*30; //30日
						setcookie('app_token',$token,$time, '/', '', true, true); // secure, httpOnly
						$sql="INSERT INTO user_apptoken (uid, token) VALUES('".$ret['id']."','".$token."') ON DUPLICATE KEY UPDATE token='".$token."', firstlogin_at=now(), lastlogin_at=''";
						$this->db->query($sql);
						
						//自動再ログイン用処理(もう一つの方法)
						$sql="UPDATE users SET last_sessid='".session_id()."' WHERE id='".$ret['id']."' LIMIT 1";
						error_log($sql);
						$this->db->query($sql);
						$time = time() + 60*60*24*30; //30日
						setcookie('24h_app_autosessid',session_id(),$time, '/', '', true, true); // secure, httpOnly
						error_log(session_name().','.session_id().','.$time);
					}
					
					// パスワードフィールドを返り値から除外
					unset($ret['password']);
					return $ret;
				}
			}
		}
	  $this->db = null;
	  return false;
	 
   }//<-- end Method
   
   public function loginAdmin() {
   	 /*
	 * -----------------------
	 * Log in Admin
	 * -----------------------
	 */
	 $user = $_POST['usr'];
	 $user_input = $_POST['pass'];
	 
	 // パスワードフィールドも取得するように SQL を変更
   	 $sql = "
   	 SELECT 
   	 id, 
   	 user,
   	 name,
   	 pass
   	 FROM admin
   	 WHERE user = :user && status = 'active'";
	 
	 $query = $this->db->prepare( $sql );
	  if( $query->execute( array( ':user' => $user ) ) )
		{
			$ret = $query->fetch( PDO::FETCH_ASSOC );
			
			if($ret) {
				// パスワード検証（新しいハッシュと旧 SHA-1 の両方に対応）
				$password_valid = false;
				$needs_upgrade = false;
				
				// 新しい bcrypt ハッシュで検証
				if (password_verify($user_input, $ret['pass'])) {
					$password_valid = true;
				}
				// 旧 SHA-1 ハッシュで検証（互換性のため）
				elseif (sha1($user_input) === $ret['pass']) {
					$password_valid = true;
					$needs_upgrade = true;
				}
				
				if ($password_valid) {
					// 旧パスワードを新しいハッシュに移行
					if ($needs_upgrade) {
						$new_hash = password_hash($user_input, PASSWORD_BCRYPT, ['cost' => 12]);
						$update_sql = "UPDATE admin SET pass = :new_hash WHERE id = :id LIMIT 1";
						$update_query = $this->db->prepare($update_sql);
						$update_query->execute([
							':new_hash' => $new_hash,
							':id' => $ret['id']
						]);
					}
					
					// パスワードフィールドを返り値から除外
					unset($ret['pass']);
					return $ret;
				}
			}
		}
	  $this->db = null;
	  return false;
	 
   }//<-- end Method
   
   public function insertPost() {
   	/*
	 * -----------------------
	 * Insert Post
	 * -----------------------
	 */
   	 $sql = "
   	 INSERT INTO posts 
   	 VALUES(
   	 null,
   	 0,
   	 0,
   	 ?,
   	 ?,
   	 ?,
   	 ?,
   	 ?,
   	 ?,
   	 ?,
   	 ?,
   	 '1',
   	 ?,
   	 '".$this->_dateNow."',
   	 '1',
   	 ?,
   	 ?,
   	 ?,
   	 ?,
   	 ?,
   	 ?,
   	 ?,
   	 ?,
   	 ?,
   	 ?,
   	 ?,
	 '0',
	 '0',
	 '0'
   	 );
   	 ";
	 
	    $stmt  = $this->db->prepare( $sql );
		$stmt->bindValue( 1, $_POST['token_id'], PDO::PARAM_STR);
		$stmt->bindValue( 2, $_POST['add_post'], PDO::PARAM_STR );
		$stmt->bindValue( 3, $_POST['photoId'], PDO::PARAM_STR );
		$stmt->bindValue( 4, $_POST['video_code'], PDO::PARAM_STR );
		$stmt->bindValue( 5, $_POST['video_title'], PDO::PARAM_STR );
		$stmt->bindValue( 6, $_POST['video_site'], PDO::PARAM_STR );
		$stmt->bindValue( 7, $_POST['video_url'], PDO::PARAM_STR );
		$stmt->bindValue( 8, $_SESSION['authenticated'], PDO::PARAM_INT );
		$stmt->bindValue( 9, $_POST['video_thumbnail'], PDO::PARAM_STR );
		$stmt->bindValue( 10, $_POST['song'], PDO::PARAM_STR );
		$stmt->bindValue( 11, $_POST['song_title'], PDO::PARAM_STR );
		$stmt->bindValue( 12, $_POST['thumbnail_song'], PDO::PARAM_STR );
		$stmt->bindValue( 13, $_POST['doc_site'], PDO::PARAM_STR );
		$stmt->bindValue( 14, $_POST['doc_url'], PDO::PARAM_STR );
		$stmt->bindValue( 15, $_POST['url'], PDO::PARAM_STR );
		$stmt->bindValue( 16, $_POST['url_thumbnail'], PDO::PARAM_STR );
		$stmt->bindValue( 17, $_POST['url_title'], PDO::PARAM_STR );
		$stmt->bindValue( 18, $_POST['url_description'], PDO::PARAM_STR );
		$stmt->bindValue( 19, $_POST['url_host'], PDO::PARAM_STR );
		$stmt->bindValue( 20, $_POST['_geolocation'], PDO::PARAM_STR );
	//	$stmt->debugDumpParams();
	//	print_r($_POST);
		$stmt = $stmt->execute();
	//	die('aaa');
		/*
		 * -----------------------
		 * Get ID Post
		 * -----------------------
		 */
		$idPost = $this->db->lastInsertId( $stmt );
		
		if ( $stmt == true ) {
			/*
			 * -----------------------------
			 * Insert Hashtag in Database
			 * ----------------------------
			 */
			preg_match_all('~[^{]([#])([^\s#!\"\$\%&\'\(\)\*\+\,\-./\:\;\<\=\>?\[/\/\/\\]\^\`\{\|\}\~]+)~', ' '.$_POST['add_post'], $matches );
			foreach ( $matches as $key ){
			}
			$numHashTag = count( $matches[1] );
			
			for ( $i = 0; $i < $numHashTag; ++$i ) {
				$query = $this->db->prepare("
						INSERT INTO trends_topic
						VALUES
						(
						?,
						?,
						'". $this->_dateNow."'
						)");
						$query->execute( array( $_SESSION['authenticated'], $key[$i] ) );
			}

			/*
			 * -----------------------------
			 * Insert demoji in Database
			 * ----------------------------
			 */
			preg_match_all('~\{[#|%]{1}[0-9a-zA-Z]{5,6}\}~', $_POST['add_post'], $matches );
			foreach ( $matches[0] as $emojicode ){
//error_log($emojicode);
				$sql="	INSERT INTO demoji_history (code, type, count, lastuse_at)
						VALUES
						(
						'".$emojicode."',
						'".(strpos($emojicode,'#')?'0':'1')."',
						1,
						now()
						)
						ON DUPLICATE KEY UPDATE count=(count+1)";
		error_log($sql);
				$query = $this->db->query($sql);
			}


			/*--------------------
			 * SEND NOTIFICATION 
			 * ON MENTIONS
			 * ------------------
			 */
			 
			 $data_post = strtolower( $_POST['add_post'] ); 
			 preg_match_all('~([@])([^\s@!\"\$\%&\'\(\)\*\+\,\-./\:\;\<\=\>?\[/\/\/\\]\^\`\{\|\}\~]+)~', $data_post, $_matches ); 
			 
			 foreach ( $_matches as $_key ) {
				$_key = array_unique(  $_key );
			}
		error_log(print_r($_key,1));
			if(in_array('all',$_key)){
				$sql="SELECT username FROM users, followers WHERE followers.following='".$_SESSION['authenticated']."' and followers.status='1' and users.id=followers.follower";
		error_log($sql);
				$rs=$this->db->query($sql)->fetchAll();
				foreach($rs as $r){
					$followers[]=$r['username'];
				}
				$_key=array_merge($_key,$followers);
				$_key = array_unique(  $_key );
			}
			$_numMentions = count( $_key );
		error_log(print_r($_key,1));
			
			for ( $j = 0; $j < $_numMentions; ++$j ) {
				
				$_key[$j] = strip_tags( $_key[$j] );
				
				if($_key[$j]=='all')continue;
					
				/* Verified Username  */
				 $ckUsername = self :: verifiedUsername( trim( $_key[$j] ) );
				 
				 if( !empty( $ckUsername ) ) {
				 	
					if( $ckUsername->id != $_SESSION['authenticated']  ) {
						/* Send Interaction */
						self :: sendInteraction( $ckUsername->id, $_SESSION['authenticated'], $idPost, 5 );
						
												//PUSH通知
						$fromUser = $this->infoUserLive( $_SESSION['authenticated'] );
						$toUser = $this->infoUserLive( $ckUsername->id );
						if( $ckUsername->id != $_SESSION['authenticated'] and $toUser->push_notification_mention == 1 ) {
							//PUSHで送る
							include_once '../../application/functions.php';
							$user_id=$ckUsername->id;
							$title=$_SESSION['LANG']['push_getmention_title'];
							$body='@'.$fromUser->username.$_SESSION['LANG']['push_getmention_body'];
							$url='https://24h.tl/'.$fromUser->username.'/status/'.$idPost;
							$imgurl='https://24h.tl/public/avatar/'.$fromUser->avatar;
							$res=_Function::send_push($user_id, $title, $body, $url, $imgurl);
						}

					}
				 	
				 }
			}//<---- * END SEND NOTIFICATION ON MENTIONS * ----->

			/*--------------------
			 * Return ID of post
			 * ------------------
			 */
			return $idPost;
		}
		$this->db = null;
   }//<-- end Method
   
   public function deletePost() {
   	/*
	 * -----------------------------
	 * Delete Post
	 * ----------------------------
	 */
   	 $sql = $this->db->prepare( "UPDATE posts SET status = '0', `status_general` =  '0' WHERE id = :_id && token_id = :_token && user = :_user");
   	 $sql->execute( array( ':_id' => $_POST['id'], ':_token' => $_POST['token'], ':_user' => $_POST['user'] ) );
	 
	 if ( $sql->rowCount() != 0 ) {
	 	
			 /* Delete Repost */
			 $_sql = $this->db->prepare( "UPDATE posts SET status = '0', status_general = '0' WHERE repost_of_id = :_id && token_id = :_token");
   	 		 $_sql->execute( array( ':_id' => $_POST['id'], ':_token' => $_POST['token'] ) );
			
			 //<-- DELETE INTERACTIONS
			 $_sqlItera = $this->db->prepare( "UPDATE interactions SET trash = '1' WHERE target = :_id && type != '1'");
   	 		 $_sqlItera->execute( array( ':_id' => $_POST['id'] ) );
		 
		 	//Delete static post
		 	$sql="DELETE FROM user_static_posts WHERE user_id='".mysql_escape_string($_POST['user'])."' and post_id='".mysql_escape_string($_POST['id'])."' LIMIT 1";

		 	$this->db->query($sql);
	
			return( 1 );
		}
		$this->db = null;
   }//<-- end Method
   
   public function favsUser( $where, $_session, $_id ) {
   		/*
		 * -----------------------------
		 * $_session : Current session
		 * $_id      : $id Post
		 * Favorites User
		 * ----------------------------
		 */
		$query = $this->db->prepare("
		SELECT 
		status
		FROM favorites
		WHERE id_usr = ? && 
		id_favorite = ?
		".$where."
		");
		$query->execute( array( $_session, $_id ) );
		return $query->fetchall();
		$this->db = null;
	}
	
   
   public function favorites($type=null) {
   	/*
	 * ----------------------------------------------------------------------------
	 *  Add, Remove Favorites
	 * @$active  :"Check to see if the user has already added prior to Favourites"
	 * @verified : "Check if the publication exists"
	 * ----------------------------------------------------------------------------
	 */
	 
   	$active   = self :: favsUser( null, $_SESSION['authenticated'], $_POST['id'] );
   	$verified = self :: checkPost( $_POST['id'], $_POST['token'] ) ? 1 : 0;

	if($type>=1){
		$type=$type;
	}else{
		$type=1; //通常のLIKE
	}	
	
	switch($type){
		case 1: $reaction_type='3';$push_icon='👍';break;
		case 2: $reaction_type='3-love';$push_icon='😍';break;
		case 3: $reaction_type='3-haha';$push_icon='😁';break;
		case 4: $reaction_type='3-wow';$push_icon='😲';break;
		case 5: $reaction_type='3-sad';$push_icon='😢';break;
		case 6: $reaction_type='3-angry';$push_icon='😡';break;
		default: $reaction_type='3';$push_icon='👍';break;
		
	}
	if( $verified == 1 && empty( $active ) )
	{
		$_idPost  = (int)$_POST['id'];
		$_sql     = $this->db->prepare("SELECT user FROM posts WHERE id = :id");
		$_sql->execute( array(  
							':id' => $_idPost 
							) 
						);
		$response = $_sql->fetch( PDO::FETCH_OBJ );
		
		/** If not exists, insert new record  **/
		$sql = $this->db->prepare("INSERT INTO favorites VALUES( null, ?, ?, '".$type."', '".$this->_dateNow."' );");
	    $sql->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT );
		$sql->bindValue( 2, $_POST['id'], PDO::PARAM_INT );
		$sql->execute();
		if( $sql->rowCount() !=  0 ) {
			
			if( $response->user != $_SESSION['authenticated'] ) {
				/* Send Interaction */
			self :: sendInteraction( $response->user, $_SESSION['authenticated'], $_idPost, $reaction_type );	
			}
			
			 //PUSH通知
			$fromUser = $this->infoUserLive( $_SESSION['authenticated'] );
			$toUser = $this->infoUserLive( $response->user );
//		error_log('-----------------toUser:'.$_POST['id']);
			if( $toUser->push_notification_reaction == 1 ) {
				//PUSHで送る
				include_once '../../application/functions.php';
				$user_id=$response->user;
				$title=$push_icon.$_SESSION['LANG']['push_getreaction_title'];
				$body='@'.$fromUser->username.$_SESSION['LANG']['push_getreaction_body'];
				$url='https://24h.tl/'.$toUser->username.'/status/'.$_POST['id'];
				$imgurl='https://24h.tl/public/avatar/'.$fromUser->avatar;
				$res=_Function::send_push($user_id, $title, $body, $url, $imgurl);
//		error_log('res:'.$res);
			}

			
			return( 1 );
		}
		
	}
	
	if( $verified == 1 && !empty( $active ) && $active[0]['status'] >= 1 )
	{
		/** If exists, update status to Delete/Trash  **/
		$sql = $this->db->prepare("UPDATE favorites SET status = '0' WHERE id_usr = ? && id_favorite = ? ");
	    $sql->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT );
		$sql->bindValue( 2, $_POST['id'], PDO::PARAM_INT );
		$sql->execute();
		if( $sql->rowCount() !=  0 )
		{
			return( 2 );
		}
	}
	else if ( $verified == 1 && !empty( $active ) && $active[0]['status'] == '0' )
		{
			/** If exists and status == Delete/Trash, update status to Active  **/
			$sql = $this->db->prepare("UPDATE favorites SET status = '".$type."' WHERE id_usr = ? && id_favorite = ? ");
		    $sql->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT );
			$sql->bindValue( 2, $_POST['id'], PDO::PARAM_INT );
			$sql->execute();
		if( $sql->rowCount() !=  0 )
		{
			return( 3 );
		}
	}
		return false;
	   	$this->db = null;
		
   }//<-- end
   
   public function follow() {
   	/*
	 * ----------------------------------------------------------------------------
	 * Follow, Unfollow users
	 * @$active  :"Check to see if the user has already follower"
	 * @verified : "Check if the user exists"
	 * ----------------------------------------------------------------------------
	 */
	 
   	$active   = self :: checkFollow( $_SESSION['authenticated'], $_POST['id'] );
//   	$verified = self :: checkUser( $_POST['id'] ) ? 1 : 0;
	$fuinfo=self::getUserInfo($_POST['id']);
	$verified = $fuinfo['status']=='active' ? 1 : 0;
	
	//フォローする場合�?、フォローパスワード�?チェ??�???�??�
	if(empty( $active ) or $active[0]['status'] == '0'){
		if($fuinfo['follow_pass'] and $fuinfo['follow_pass']!=$_POST['follow_pass']){
			return 4; //パスワードう不�?致のためフォローできず
		}
	}
	if( $verified == 1 && empty( $active ) ) {
		
		/** If not exists, insert new record  **/
		$sql = $this->db->prepare("INSERT INTO followers VALUES( null, ?, ?, '1', '".$this->_dateNow."' );");
	    $sql->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT );
		$sql->bindValue( 2, $_POST['id'], PDO::PARAM_INT );
		$sql->execute();
		
		if( $sql->rowCount() !=  0 ) {
			
			/* Send Interaction */
			self :: sendInteraction( $_POST['id'], $_SESSION['authenticated'], $_SESSION['authenticated'], 1 );
			return( 1 );
		}
		
	}
	
	if( $verified == 1 && !empty( $active ) && $active[0]['status'] == '1' ) {
		
		/** If exists, update status to Delete/Trash  **/
		$sql = $this->db->prepare("UPDATE followers SET status = '0' WHERE follower = ? && following = ? ");
	    $sql->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT );
		$sql->bindValue( 2, $_POST['id'], PDO::PARAM_INT );
		$sql->execute();
		
		if( $sql->rowCount() !=  0 ) {
			return( 2 );
		}
	} else if ( $verified == 1 && !empty( $active ) && $active[0]['status'] == '0' ) {
		
			/** If exists and status == Delete/Trash, update status to Active  **/
			$sql = $this->db->prepare("UPDATE followers SET status = '1' WHERE follower = ? && following = ? ");
		    $sql->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT );
			$sql->bindValue( 2, $_POST['id'], PDO::PARAM_INT );
			$sql->execute();
			
		if( $sql->rowCount() !=  0 ) {
			return( 3 );
		}
	}
	return false;
   	$this->db = null;
   }//<-- end
   public function getStaticPosts($user_id, $_limit=5){
	   $sql="SELECT post_id FROM user_static_posts WHERE user_id='".$user_id."' ORDER BY created_at DESC LIMIT ".$_limit;

	   $rs=$this->db->query($sql)->fetchAll();
	   foreach($rs as $r){
		   $ret[]=$r['post_id'];
	   }
	   return $ret;
   }
	
	public function setStaticPost($user_id,$post_id){
		$static_ids=$this->getStaticPosts($user_id);
		if(count($static_ids)>=5){
			return false; // no more add static post
		}else{
			$sql="INSERT INTO user_static_posts (user_id,post_id) VALUES('".mysql_escape_string($user_id)."','".mysql_escape_string($post_id)."')";
			if($this->db->query($sql)){
				return true;
			}
			return false;
		}
	}
	
	public function removeStaticPost($user_id,$post_id){
		$sql="DELETE FROM user_static_posts WHERE user_id='".mysql_escape_string($user_id)."' and post_id='".mysql_escape_string($post_id)."' LIMIT 1";
		$this->db->query($sql);
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
			U.username,
			P.repost_of_id,
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
			P.url_soundcloud,
			P.title_soundcloud,
			P.doc_site,
			P.doc_url,
			P.url,
			P.url_thumbnail,
			P.url_title,
			P.url_description,
			P.url_host,
			P.geolocation,
			P.comment_num,
			P.like_num,
			P.repost_num,
			U.id user_id,
			U.name,
			U.username,
			U.avatar,
			U.type_account,
			U.mode,
			U2.id rp_id_user,
			U2.name rp_name,
			U2.username rp_username,
			U2.avatar rp_avatar,
			U2.type_account rp_type_account,
			U2.mode rp_mode,
			RP.id rp_id,
			RP.user rp_user,
			FA.status fa_status
			FROM posts P 
			LEFT JOIN users U ON P.user = U.id
			LEFT JOIN posts RP ON P.repost_of_id = RP.id
			LEFT JOIN users U2 ON RP.user = U2.id
			LEFT JOIN favorites  FA ON RP.id = FA.id_favorite 
			&& FA.id_usr = :id 
			&& FA.status <> '0' 
			|| P.id = FA.id_favorite 
			&& FA.id_usr = :id 
			&& FA.status <> '0' 
			". $_where ."  
			". $_limit ."
			" );
			
			if( $sql->execute(  array( ':id' => $session )  ) ) {
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
			
	}//<--- * END FUNCTION *-->
	
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
			P.url_soundcloud,
			P.title_soundcloud,
			P.doc_site,
			P.doc_url,
			P.url,
			P.url_thumbnail,
			P.url_title,
			P.url_description,
			P.url_host,
			P.geolocation,
			P.comment_num,
			P.like_num,
			P.repost_num,
			U.id user_id,
			U.name,
			U.username,
			U.avatar,
			U.type_account,
			U.mode
			FROM posts P
			LEFT JOIN users U ON P.user = U.id
			LEFT JOIN followers F ON P.user = F.following && F.follower = :id && F.status <> '0'
			LEFT JOIN favorites  FA ON P.id = FA.id_favorite && FA.id_usr = :id && FA.status <> '0'
			LEFT JOIN block_user B ON  B.user = :id && B.user_blocked = U.id && B.status = '1'
			". $_where ."
			
			". $_limit ."
			" );
			
			if( $sql->execute(  array( ':id' => $session )  ) )
			{
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
			
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
			COUNT( DISTINCT FA.id ) favoriteUser,
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
			P.url_soundcloud,
			P.title_soundcloud,
			U.id user_id,
			U.name,
			U.username,
			U.avatar,
			U.type_account,
			U.mode
			FROM posts P
			LEFT JOIN users U ON P.user = U.id
			LEFT JOIN favorites FA ON P.id = FA.id_favorite && FA.id_usr = :id && FA.status <> '0'
			". $_where ."
			GROUP BY P.id
			ORDER BY P.id DESC
			". $_limit ."
			" );
			
			if( $sql->execute(  
							array( 
							':id' => $session
							)  
							) 
			) {
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
			
	}//<--- * END FUNCTION *-->
	
	public function mentionsInReply( $_where ) {
		    /*
			 * ------------------------------------
			 * Get all Mentions in Reply "Connect page"
			 * $_where : Condition
			 * ------------------------------------
			 */
			$sql = $this->db->prepare( "
			SELECT 
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
			U.mode,
			UR.name r_name,
			UR.username r_username,
			UR.avatar r_avatar,
			UR.type_account r_type_account,
			UR.mode r_mode,
			R.date r_date,
			R.reply
			FROM posts P
			LEFT JOIN users U ON P.user = U.id
			LEFT JOIN reply R ON P.id = R.post && R.status = '1'
			LEFT JOIN users UR ON R.user = UR.id
			". $_where ."
			GROUP BY R.id
			ORDER BY R.id DESC
			" );
			
			if( $sql->execute() ) {
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
			 
			$sql=$this->db->prepare("SELECT * FROM interactions as I WHERE destination = :id and autor != :id and ".$where." and trash = '0' GROUP BY id DESC ORDER BY id DESC ".$limit);
			if( $sql->execute(  array( ':id' => $_id  )  ) ) {
				$Is = $sql->fetchall( PDO::FETCH_ASSOC );
			}
//error_log("SELECT * FROM interactions as I WHERE destination = $_id and autor != $_id and ".$where." and trash = '0' GROUP BY id DESC ORDER BY id DESC ".$limit);
			foreach($Is as $I){
//			error_log('1:'.print_r($I,1)); 
				$I['idInteraction']=$I['id'];
				if(!$U[$I['autor']]){
					$sql="SELECT id as user_id, name, username, avatar, type_account, mode, status FROM users WHERE id='".$I['autor']."' LIMIT 1";
					$U[$I['autor']]=$this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
//			error_log('2:'.print_r($U[$I['autor']],1));
				}
				if($U[$I['autor']]['status']!='active'){
					continue;
				}
				$I=array_merge($I,$U[$I['autor']]);
//			error_log('3:'.print_r($I,1)); 

				if($I['type']!=1){ //フォロー以外�?場合�?対象となる記事�??�??�?�も取?�?
					if(!$P[$I['target']]){
						$sql="SELECT id, token_id, post_details, photo, video_code, video_title, video_site, video_url, user, url_soundcloud, title_soundcloud, status FROM posts WHERE id='".$I['target']."' LIMIT 1";
						$P[$I['target']]=$this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
					}
					if($P[$I['target']]['status']==1){
						$I=array_merge($I,$P[$I['target']]);
					}

					if(!$U[$I['user']]){
						$sql="SELECT id as user_id, name, username, avatar, type_account, mode FROM users WHERE id='".$I['user']."' LIMIT 1";
						$U[$I['user']]=$this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
					}
					$I['p_username']=$U[$I['user']]['username'];
				
				}

				$ret[]=$I;
				
				
			}
			return $ret;
			
			//以下古くて?�??�?�SQL
			$sql = $this->db->prepare( "
			SELECT 
			COUNT(DISTINCT R.id) come,
			I.id idInteraction,
			I.type,
			I.date,
			P.id,
			P.token_id,
			P.post_details,
			P.photo,
			P.video_code,
			P.video_title,
			P.video_site,
			P.video_url,
			P.user,
			P.url_soundcloud,
			P.title_soundcloud,
			P.status,
			U.id user_id,
			U.name,
			U.username,
			U.avatar,
			U.type_account,
			U.mode,
			US.username p_username,
			R.reply
			FROM interactions I 
			LEFT JOIN users U ON I.autor = U.id
			LEFT JOIN posts P ON I.target = P.id && P.status = '1'
			LEFT JOIN reply R ON I.target = R.post 
			&& R.user = U.id 
			&& R.post = P.id 
			&& R.status = '1'
			LEFT JOIN followers F ON F.follower = U.id
			LEFT JOIN users US ON P.user = US.id
			LEFT JOIN favorites FA ON P.id = FA.id_favorite && I.target = FA.id_favorite
		 	WHERE I.destination = :id && I.autor != :id && ".$where." && I.trash = '0'
		 	GROUP BY I.id DESC
		 	ORDER BY I.id DESC
			".$limit."
			" );
			
 			if( $sql->execute(  array( ':id' => $_id  )  ) ) {
//error_log($sql->queryString);				
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
			$sql = $this->db->prepare( "
			SELECT 
			COUNT(DISTINCT FA.id ) favoriteUser,
			P.id,
			P.token_id,
			P.photo,
			P.post_details,
			P.video_code,
			P.video_title,
			P.video_site,
			P.video_url,
			P.user,
			P.date,
			P.url_soundcloud,
			P.title_soundcloud,
			P.doc_site,
			P.doc_url,
			P.url,
			P.url_thumbnail,
			P.url_title,
			P.url_description,
			P.url_host,
			P.geolocation,
			U.id id_user,
			U.name,
			U.username,
			U.avatar,
			U.type_account
			FROM posts P
			LEFT JOIN users U ON P.user = U.id
			LEFT JOIN favorites F ON P.id = F.id_favorite && F.status <> '0'
			LEFT JOIN favorites  FA ON P.id = FA.id_favorite && FA.id_usr = :id && FA.status <> '0'
			". $_where ." && U.status = 'active' && P.status = '1' && F.status <> '0'
			GROUP BY P.id
			ORDER BY F.id DESC
			". $_limit ."
			" );
			
			if( $sql->execute(  array( ':id' => $session )  ) )
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
			F.id id_follow,
			U.id,
			U.username,
			U.name,
			U.avatar,
			U.type_account,
			U.bio
			FROM users U
			LEFT JOIN followers F ON U.id = F.follower
			LEFT JOIN followers  FO ON U.id = FO.following && FO.follower = :id && FO.status = '1'
			". $_where ." && U.status = 'active' && F.status = '1'
			GROUP BY U.id
			ORDER BY F.id DESC
			". $_limit ."
			" );
			
			if( $sql->execute(  array( ':id' => $session )  ) ) {
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
			F.id id_follow,
			U.id,
			U.username,
			U.name,
			U.avatar,
			U.type_account,
			U.bio
			FROM users U
			LEFT JOIN followers F ON U.id = F.following
			LEFT JOIN followers  FO ON U.id = FO.following && FO.follower = :id && FO.status = '1'
			". $_where ." && U.status = 'active' && F.status = '1'
			GROUP BY U.id
			ORDER BY F.id DESC
			". $_limit ."
			" );
			
			if( $sql->execute(  array( ':id' => $session )  ) ) {
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
			
	}//<--- * END FUNCTION *-->
	
	public function getMedia( $_id, $_token ) {
			/*
			 * -------------------------------------------
			 * Get photos / videos of the publications
			 * $_id : ID Publication/Post
			 * $_token : Token unique of Publication/Post
			 * -------------------------------------------
			 */
			$sql = $this->db->prepare( "
			SELECT 
			P.id,
			P.token_id,
			P.photo,
			P.post_details,
			P.video_code,
			P.video_title,
			P.video_site,
			P.video_url,
			P.user,
			P.date,
			P.url_soundcloud,
			P.title_soundcloud,
			U.username
			FROM posts P
			LEFT JOIN users U ON P.user = U.id
			WHERE P.id = :id && P.token_id = :token && P.status = '1'
			" );
			
			if( $sql->execute(  array( ':id' => $_id, ':token' => $_token  )  ) )
			{
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
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
			U.avatar,
			F.status
			FROM users U
			LEFT JOIN favorites F ON U.id = F.id_usr && F.status <> '0'
			LEFT JOIN posts P ON F.id_favorite = P.id
			WHERE P.id = :id
			ORDER BY F.date DESC
			LIMIT 10
			" );
			
			if( $sql->execute(  array( ':id' => $_id  )  ) )
			{
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
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
			LIMIT 5
			" );

			if( $sql->execute(  array( ':id' => $_id  )  ) )
			{
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
	}//<--- * END FUNCTION *-->
	
	public function sendReply() {
		/*
		 * -------------------------------
		 * Send Reply
		 * @checkPost : 
		 * Check if the publication exists
		 * -------------------------------
		 */	
		$checkPost = self :: checkPost( $_POST['id_reply'],  $_POST['token_reply'] ) ? 1 : 0;
		
		if( $checkPost == 1 ) {
		
		/* -----------------
		 * Get ID user Post 
		 * ----------------
		 */
		$_idReply = (int)$_POST['id_reply'];
		$_sql     = $this->db->prepare("SELECT user FROM posts WHERE id = :id");
		$_sql->execute( array(  
							':id' => $_idReply 
							) 
						);
		$response = $_sql->fetch( PDO::FETCH_OBJ );

		if($this->isBlockOrBlocked($response->user,$_SESSION['authenticated'])){
			return false;
		}
		
			$sql = "
			INSERT INTO reply
			VALUES
			(
			null,
			?,
			?,
			?,
			'".$this->_dateNow."',
			'1'
			)";
			
			$stmt = $this->db->prepare( $sql );
			$stmt->bindValue( 1, $_POST['id_reply'], PDO::PARAM_INT );
			$stmt->bindValue( 2, $_SESSION['authenticated'], PDO::PARAM_INT );
			$stmt->bindValue( 3, $_POST['reply_post'], PDO::PARAM_INT );
			$stmt = $stmt->execute();
			
			/* Retrieve ID inserted */
			$id_reply = $this->db->lastInsertId( $stmt );
			
			if( $stmt == true ) {
			
			//postsのcommnet_numをインクリメントす??�?
			$sql = " UPDATE posts SET comment_num=(comment_num+1) WHERE id='".$_POST['id_reply']."' LIMIT 1";
			$this->db->query($sql);
			
			
			//PUSH通知
			if( $response->user != $_SESSION['authenticated'] ) {
				$fromUser = $this->infoUserLive( $_SESSION['authenticated'] );
				$toUser = $this->infoUserLive( $response->user );
				$url='https://24h.tl/'.$toUser->username.'/status/'.$_POST['id_reply'];
				if( $toUser->push_notification_comment == 1 ) {
					//PUSHで送る
					include_once '../../application/functions.php';
					$user_id=$response->user;
					$title=$_SESSION['LANG']['push_getcomment_title'];
					$body='@'.$fromUser->username.$_SESSION['LANG']['push_getcomment_body'];
					$imgurl='https://24h.tl/public/avatar/'.$fromUser->avatar;
					$res=_Function::send_push($user_id, $title, $body, $url, $imgurl);
				}
			}
			
			/*--------------------
			 * SEND NOTIFICATION 
			 * ON MENTIONS
			 * ------------------
			 */
			 
			 $data_post = strtolower( $_POST['reply_post'] ); 
			 preg_match_all('~([@])([^\s@]+)~', $data_post, $_matches ); 
			 
			 foreach ( $_matches as $_key ) {
				$_key = array_unique(  $_key );
			}
			$_numMentions = count( $_matches[1] );
			
			for ( $j = 0; $j < $_numMentions; ++$j ) {
				
				$_key[$j] = strip_tags( $_key[$j] );
				
				/* Verified Username  */
				 $ckUsername = self::verifiedUsername( trim( $_key[$j] ) );
				 
				 if( !empty( $ckUsername ) ) {
				 	
					if( $ckUsername->id != $_SESSION['authenticated'] ) {
						/* Send Interaction */
						self::sendInteraction( $ckUsername->id, $_SESSION['authenticated'], $_POST['id_reply'], 6 );
						
						//PUSH通知
						$fromUser = $this->infoUserLive( $_SESSION['authenticated'] );
						$toUser = $this->infoUserLive( $ckUsername->id );
						if( $ckUsername->id != $response->user and $toUser->push_notification_mention == 1 ) {
							//PUSHで送る
							include_once '../../application/functions.php';
							$user_id=$ckUsername->id;
							$title=$_SESSION['LANG']['push_getmention_title'];
							$body='@'.$fromUser->username.$_SESSION['LANG']['push_getmention_body'];
//							$url='https://24h.tl/'.$toUser->username.'/status/'.$_POST['id_reply'];
							$imgurl='https://24h.tl/public/avatar/'.$fromUser->avatar;
							$res=_Function::send_push($user_id, $title, $body, $url, $imgurl);
						}
					}
				 	
				 }
			}//<---- * END SEND NOTIFICATION ON MENTIONS * ----->
			
		if( $response->user != $_SESSION['authenticated'] ) {
			/* Send Interaction */
			self::sendInteraction( $response->user, $_SESSION['authenticated'], $_POST['id_reply'], 4 );
		}
				
				return $id_reply;
			}
		}
	}//<--- * END FUNCTION *-->	
	
	public function deleteReply() {
   	/*
		 * ------------------------
		 * Delete Reply
		 * ----------------------
		 */	
		 
   	 $sql = $this->db->prepare( "UPDATE reply SET status = '0' WHERE id = :_id && user = :_user && status = '1' ");
   	 $sql->execute( array( ':_id' => $_GET['id'], ':_user' => $_SESSION['authenticated'] ) );
	 
	 if ( $sql->rowCount() != 0 )
		{
			$sql = "UPDATE posts SET comment_num=(comment_num - 1) WHERE id = (SELECT post FROM reply WHERE id='".mysql_escape_string($_GET['id'])."' LIMIT 1) LIMIT 1";
			$this->db->query($sql);
			return( 1 );
		}
	 else {
		 return false;
	 }
		$this->db = null;
   }//<-- end Method
   
   public function reportPost() {
   	/*
	 * ------------------------
	 * Report Post
	 * @$reported : verify if you have not reported
	 * @$verified: Check if the publication exists
	 * ----------------------
	 */	
		 
   	 $reported = self :: checkReportPost( $_SESSION['authenticated'], $_POST['_postId'] ) ? 1 : 0;
   	 $verified = self :: checkPost( $_POST['_postId'], $_POST['_token'] ) ? 1 : 0;
   	 
   	 if( $verified == 1 )
	 {
	 	if( $reported == 0 )
		{
			$sql = $this->db->prepare( "INSERT INTO post_reported VALUES( null, :_user, :_post, '".$this->_dateNow."' );");
   	        $sql->execute( array( ':_user' => $_SESSION['authenticated'], ':_post' => $_POST['_postId'] ) );
	 
			 if ( $sql->rowCount() != 0 )
				{
					return( 1 );
				}
			 else {
				 return false;
			 }
			 $this->db = null;
		}
		/* If the user already reported the publication */
		else {
			return( 3 );
		}
	 	
	 }
	 /* If no exists post */
	 else {
		 	return( 2 );
	 }
   }//<<<-- End Function -->>>
   
   public function reportUser() {
   	 /*
	 * ------------------------
	 * Report User
	 * @$reported : verify if you have not reported
	 * @$verified: Check if the User exists
	 * ----------------------
	 */	
   	 $reported = self::checkReportUser( $_SESSION['authenticated'], $_POST['_userId'] ) ? 1 : 0;
   	 $verified = self::checkUser( $_POST['_userId'] ) ? 1 : 0;
   	 
   	 if( $verified == 1 )
	 {
	 	if( $reported == 0 )
		{
			$sql = $this->db->prepare( "INSERT INTO users_reported VALUES( null, :_user, :_userId, '".$this->_dateNow."' );");
   	        $sql->execute( array( ':_user' => $_SESSION['authenticated'], ':_userId' => $_POST['_userId'] ) );
	 
			 if ( $sql->rowCount() != 0 )
				{
					return( 1 );
				}
			 else {
				 return false;
			 }
			 $this->db = null;
		}
		/* If the user already reported User */
		else {
			return( 3 );
		}
	 	
	 }
	 /* If no exists user */
	 else {
		 	return( 2 );
	 }
   }//<<<-- End Function -->>>
   
   public function blockUser() {
    /*
	 * ------------------------
	 * Block User
	 * @$reported : verify if you have not Blocked
	 * @$verified: Check if the user exists
	 * ----------------------
	 */	
	 
	$reported = self::checkUserBlock( $_SESSION['authenticated'], $_POST['_userId'] );
   	$verified = self::checkUser( $_POST['_userId'] ) ? 1 : 0;

	
	if( $verified == 1 && empty( $reported ) ) {
		$sql = $this->db->prepare( "INSERT INTO block_user VALUES( null, :_user, :_userId, '".$this->_dateNow."', '1' );");
   	        $sql->execute( array( ':_user' => $_SESSION['authenticated'], ':_userId' => $_POST['_userId'] ) );
	 
			 if ( $sql->rowCount() != 0 ) {
			 	
					//================= UPDATE INTERACTIONS  STATUS -> DELETE ==============//
					$updateItera = "UPDATE interactions SET trash = '1' WHERE autor = ? && destination = ?";
					$_execSql   = $this->db->prepare( $updateItera );
					$_execSql->bindValue( 1, $_POST['_userId'], PDO::PARAM_INT );
					$_execSql->bindValue( 2, $_SESSION['authenticated'], PDO::PARAM_INT ); 
					$_execSql->execute();
			
					//================== UPDATE FOLLOWERS TO STATUS TRASH ============//
					$update = "UPDATE followers SET status = '0' 
					WHERE follower = ? && following = ?";
					$exec   = $this->db->prepare( $update );
					$exec->bindValue( 1, $_POST['_userId'], PDO::PARAM_INT ); 
					$exec->bindValue( 2, $_SESSION['authenticated'], PDO::PARAM_INT  );
					$exec->execute();
					
					//================= UPDATE FOLLOWING TO STATUS TRASH ==============//
					$update2 = "UPDATE followers SET status = '0' 
					WHERE follower = ? && following = ?";
					$exe   = $this->db->prepare( $update2 );
					$exe->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT ); 
					$exe->bindValue( 2, $_POST['_userId'], PDO::PARAM_INT  );
					$exe->execute();
					
					//================= UPDATE MESSAGES ==============//
					$updateMsg = "UPDATE messages SET remove_from = '0' 
					WHERE `from` = :user_1 && `to` = :user_2
					|| `to` = :user_1 && `from` = :user_2";
					$_exe   = $this->db->prepare( $updateMsg );
					$_exe->bindValue( ':user_1', $_SESSION['authenticated'], PDO::PARAM_INT ); 
					$_exe->bindValue( ':user_2', $_POST['_userId'], PDO::PARAM_INT  );
					$_exe->execute();
					
					return( 1 );
				}
	}
	
	if( $verified == 1 && !empty( $reported ) && $reported[0]['status'] == '1' ) {
		$sql = $this->db->prepare("UPDATE block_user SET status = '0' WHERE user = ? && user_blocked = ?");
	    $sql->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT );
		$sql->bindValue( 2, $_POST['_userId'], PDO::PARAM_INT );
		$sql->execute();
		
		//ブロックを解除したらフォローもフォロワーも戻るようになってたけど、コメントアウトでそれをやめました
		
		if( $sql->rowCount() !=  0 ) {
		/*
			//================== UPDATE FOLLOWERS TO STATUS ACTIVE "IF EXISTS" ============//
					$update = "UPDATE followers SET status = '1' 
					WHERE follower = ? && following = ?";
					$exec   = $this->db->prepare( $update );
					$exec->bindValue( 1, $_POST['_userId'], PDO::PARAM_INT ); 
					$exec->bindValue( 2, $_SESSION['authenticated'], PDO::PARAM_INT  );
					$exec->execute();
					
					//================= UPDATE FOLLOWING  TO STATUS ACTIVE "IF EXISTS" ==============//
					$update2 = "UPDATE followers SET status = '1' 
					WHERE follower = ? && following = ?";
					$exe   = $this->db->prepare( $update2 );
					$exe->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT ); 
					$exe->bindValue( 2, $_POST['_userId'], PDO::PARAM_INT  );
					$exe->execute();
		*/			
					return( 2 );
		}
		
	} else if ( $verified == 1 && !empty( $reported ) && $reported[0]['status'] == '0' ) {
			$sql = $this->db->prepare("UPDATE block_user SET status = '1' WHERE user = ? && user_blocked = ? ");
		    $sql->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT );
			$sql->bindValue( 2, $_POST['_userId'], PDO::PARAM_INT );
			$sql->execute();
			
		if( $sql->rowCount() !=  0 ) {
			   //================== UPDATE FOLLOWERS TO STATUS TRASH ============//
					$update = "UPDATE followers SET status = '0' 
					WHERE follower = ? && following = ?";
					$exec   = $this->db->prepare( $update );
					$exec->bindValue( 1, $_POST['_userId'], PDO::PARAM_INT ); 
					$exec->bindValue( 2, $_SESSION['authenticated'], PDO::PARAM_INT  );
					$exec->execute();
					
					//================= UPDATE FOLLOWING  TO STATUS TRASH ==============//
					$update2 = "UPDATE followers SET status = '0' 
					WHERE follower = ? && following = ?";
					$exe   = $this->db->prepare( $update2 );
					$exe->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT ); 
					$exe->bindValue( 2, $_POST['_userId'], PDO::PARAM_INT  );
					$exe->execute();
					
					return( 3 );
			
		}
	}
	return false;
   	$this->db = null;
   }//<<<-- End Function -->>>
	
	
	public function updateProfile() {
		/*
		 * ------------------------
		 * Update Name, Location, 
		 * website, Bio in Profile
		 * ----------------------
		 */
		$sql = "UPDATE users SET name = :name, location= :location, website = :web, sns_instagram_id = :sns_instagram_id, sns_instagram_verified = :sns_instagram_verified, sns_twitter_id = :sns_twitter_id, sns_twitter_verified = :sns_twitter_verified, bio = :bio WHERE id = :user";
		$stmt = $this->db->prepare( $sql );
		$stmt->bindValue( ':name', $_POST['name'], PDO::PARAM_STR );
		$stmt->bindValue( ':location', $_POST['location'], PDO::PARAM_STR );
		$stmt->bindValue( ':web', $_POST['website'], PDO::PARAM_STR );
		$stmt->bindValue( ':sns_instagram_id', $_POST['sns_instagram_id'], PDO::PARAM_STR );
		$stmt->bindValue( ':sns_instagram_verified', ($_POST['check_sns_instagram']?1:0) , PDO::PARAM_INT );
		$stmt->bindValue( ':sns_twitter_id', $_POST['sns_twitter_id'], PDO::PARAM_STR );
		$stmt->bindValue( ':sns_twitter_verified', ($_POST['check_sns_twitter']?1:0) , PDO::PARAM_INT );		
		$stmt->bindValue( ':bio', $_POST['bio'], PDO::PARAM_STR );
		$stmt->bindValue( ':user', $_SESSION['authenticated'], PDO::PARAM_INT );
		$stmt->execute();
		
		if ( $stmt->rowCount() != 0 ) {
			return true;
		} else {
			 return false;
		 }
		$this->db = null;
	}
	
	public function updateSettings() {
		/*
		 * ------------------------
		 * Update username, mode, 
		 * country, Bio in Settings
		 * @$verified : Check username availability
		 * ----------------------
		 */
		 
		 if( !empty( $_POST['username'] ) ) {
		 	
		 	$verified = self::checkUsername( $_POST['username'] ) ? 1 : 0;
			/* If the username is not available */
			if( $verified == 1 ) {
				return( 2 );
			}
		 }
		
		
		/* Update if the username is true */
		if( $_POST['username'] != '' && $_POST['email'] == '' ) {
			$sql = "UPDATE users 
			SET username = :username, 
			mode = :mode, 
			country = :country, 
			follow_pass = :follow_pass,
			messages_private = :msg,
			email_notification_follow = :notiFollow,
			email_notification_msg = :notiMsg,
			push_notification_follow = :notiPushFollow,
			push_notification_msg = :notiPushMsg,
			push_notification_reaction = :notiPushReaction,
			push_notification_comment = :notiPushComment,
			push_notification_mention = :notiPushMention,
			language = :lang
			WHERE id = :user";
			$stmt = $this->db->prepare( $sql );
			$stmt->bindValue( ':username', $_POST['username'], PDO::PARAM_STR );
			$stmt->bindValue( ':mode', $_POST['mode'], PDO::PARAM_INT );
			$stmt->bindValue( ':country', $_POST['country'], PDO::PARAM_STR );
			$stmt->bindValue( ':follow_pass', $_POST['follow_pass'], PDO::PARAM_STR );
			$stmt->bindValue( ':user', $_SESSION['authenticated'], PDO::PARAM_INT );
			$stmt->bindValue( ':msg', $_POST['msg_private'], PDO::PARAM_INT );
			$stmt->bindValue( ':notiFollow', $_POST['check_1'], PDO::PARAM_STR );
			$stmt->bindValue( ':notiMsg', $_POST['check_0'], PDO::PARAM_STR );
			$stmt->bindValue( ':notiPushFollow', $_POST['check_3'], PDO::PARAM_STR );
			$stmt->bindValue( ':notiPushMsg', $_POST['check_2'], PDO::PARAM_STR );			
			$stmt->bindValue( ':notiPushReaction', $_POST['check_4'], PDO::PARAM_STR );
			$stmt->bindValue( ':notiPushComment', $_POST['check_5'], PDO::PARAM_STR );			
			$stmt->bindValue( ':notiPushMention', $_POST['check_6'], PDO::PARAM_STR );			
			$stmt->bindValue( ':lang', $_POST['lang'], PDO::PARAM_STR );
			$stmt->execute();
			
			if ( $stmt->rowCount() != 0 ) {
				return( 1 );
			} else {
				 return ( 3 );
			 }
		}
		
		/* Update if the e-mail is true */
		if( $_POST['email'] != '' && $_POST['username'] == '' ) {
		$sql = "UPDATE users 
		SET email= :email,
		mode = :mode, 
		country = :country, 
		follow_pass = :follow_pass,
		messages_private = :msg,
		email_notification_follow = :notiFollow,
		email_notification_msg = :notiMsg,
		push_notification_follow = :notiPushFollow,
		push_notification_msg = :notiPushMsg,
		push_notification_reaction = :notiPushReaction,
		push_notification_comment = :notiPushComment,
		push_notification_mention = :notiPushMention,
		language = :lang
		WHERE id = :user";
		$stmt = $this->db->prepare( $sql );
		$stmt->bindValue( ':email', $_POST['email'], PDO::PARAM_STR );
		$stmt->bindValue( ':mode', $_POST['mode'], PDO::PARAM_INT );
		$stmt->bindValue( ':country', $_POST['country'], PDO::PARAM_STR );
		$stmt->bindValue( ':follow_pass', $_POST['follow_pass'], PDO::PARAM_STR );
		$stmt->bindValue( ':user', $_SESSION['authenticated'], PDO::PARAM_INT );
		$stmt->bindValue( ':msg', $_POST['msg_private'], PDO::PARAM_INT );
		$stmt->bindValue( ':notiFollow', $_POST['check_1'], PDO::PARAM_STR );
		$stmt->bindValue( ':notiMsg', $_POST['check_0'], PDO::PARAM_STR );
		$stmt->bindValue( ':notiPushFollow', $_POST['check_3'], PDO::PARAM_STR );
		$stmt->bindValue( ':notiPushMsg', $_POST['check_2'], PDO::PARAM_STR );			
		$stmt->bindValue( ':notiPushReaction', $_POST['check_4'], PDO::PARAM_STR );
		$stmt->bindValue( ':notiPushComment', $_POST['check_5'], PDO::PARAM_STR );			
		$stmt->bindValue( ':notiPushMention', $_POST['check_6'], PDO::PARAM_STR );			
		$stmt->bindValue( ':lang', $_POST['lang'], PDO::PARAM_STR );
		$stmt->execute();
		
			if ( $stmt->rowCount() != 0 ) {
				return( 1 );
			} else {
				return false;
			 }
	    }
			/* Update if the username and e-mail is true */
			if( $_POST['email'] != '' && $_POST['username'] != '' ) {
				$sql = "UPDATE users 
				SET username = :username, 
				email= :email, 
				mode = :mode, 
				country = :country, 
				follow_pass = :follow_pass,
				messages_private = :msg,
				email_notification_follow = :notiFollow,
				email_notification_msg = :notiMsg,
				push_notification_follow = :notiPushFollow,
				push_notification_msg = :notiPushMsg,
				push_notification_reaction = :notiPushReaction,
				push_notification_comment = :notiPushComment,
				push_notification_mention = :notiPushMention,
				language = :lang
				WHERE id = :user";
				$stmt = $this->db->prepare( $sql );
				$stmt->bindValue( ':username', $_POST['username'], PDO::PARAM_STR );
				$stmt->bindValue( ':email', $_POST['email'], PDO::PARAM_STR );
				$stmt->bindValue( ':mode', $_POST['mode'], PDO::PARAM_INT );
				$stmt->bindValue( ':country', $_POST['country'], PDO::PARAM_STR );
				$stmt->bindValue( ':follow_pass', $_POST['follow_pass'], PDO::PARAM_STR );
				$stmt->bindValue( ':user', $_SESSION['authenticated'], PDO::PARAM_INT );
				$stmt->bindValue( ':msg', $_POST['msg_private'], PDO::PARAM_INT );
				$stmt->bindValue( ':notiFollow', $_POST['check_1'], PDO::PARAM_STR );
				$stmt->bindValue( ':notiMsg', $_POST['check_0'], PDO::PARAM_STR );
				$stmt->bindValue( ':notiPushFollow', $_POST['check_3'], PDO::PARAM_STR );
				$stmt->bindValue( ':notiPushMsg', $_POST['check_2'], PDO::PARAM_STR );			
				$stmt->bindValue( ':notiPushReaction', $_POST['check_4'], PDO::PARAM_STR );
				$stmt->bindValue( ':notiPushComment', $_POST['check_5'], PDO::PARAM_STR );			
				$stmt->bindValue( ':notiPushMention', $_POST['check_6'], PDO::PARAM_STR );			
				$stmt->bindValue( ':lang', $_POST['lang'], PDO::PARAM_STR );
				$stmt->execute();
				
				if ( $stmt->rowCount() != 0 ) {
					return( 1 );
				}  else {
					  return ( 3 );
				 }
			}
			
			/* Update if the user name, e-mail are not defined */
			if( $_POST['email'] == '' && $_POST['username'] == '' ) {
				$sql = "UPDATE users 
				SET mode = :mode, 
				country = :country, 
				follow_pass = :follow_pass,
				messages_private = :msg,
				email_notification_follow = :notiFollow,
				email_notification_msg = :notiMsg,
				push_notification_follow = :notiPushFollow,
				push_notification_msg = :notiPushMsg,
				push_notification_reaction = :notiPushReaction,
				push_notification_comment = :notiPushComment,
				push_notification_mention = :notiPushMention,
				language = :lang
				WHERE id = :user";
				$stmt = $this->db->prepare( $sql );
				$stmt->bindValue( ':mode', $_POST['mode'], PDO::PARAM_INT );
				$stmt->bindValue( ':country', $_POST['country'], PDO::PARAM_STR );
				$stmt->bindValue( ':follow_pass', $_POST['follow_pass'], PDO::PARAM_STR );
				$stmt->bindValue( ':user', $_SESSION['authenticated'], PDO::PARAM_INT );
				$stmt->bindValue( ':msg', $_POST['msg_private'], PDO::PARAM_INT );
				$stmt->bindValue( ':notiFollow', $_POST['check_1'], PDO::PARAM_STR );
				$stmt->bindValue( ':notiMsg', $_POST['check_0'], PDO::PARAM_STR );
				$stmt->bindValue( ':notiPushFollow', $_POST['check_3'], PDO::PARAM_STR );
				$stmt->bindValue( ':notiPushMsg', $_POST['check_2'], PDO::PARAM_STR );			
				$stmt->bindValue( ':notiPushReaction', $_POST['check_4'], PDO::PARAM_STR );
				$stmt->bindValue( ':notiPushComment', $_POST['check_5'], PDO::PARAM_STR );			
				$stmt->bindValue( ':notiPushMention', $_POST['check_6'], PDO::PARAM_STR );			
				$stmt->bindValue( ':lang', $_POST['lang'], PDO::PARAM_STR );
				$stmt->execute();
				
				if ( $stmt->rowCount() != 0 ) {
					return( 1 );
				} else {
					 return ( 3 );
				 }
			}
			
			
		$this->db = null;
	}
	
	public function uploadAvatar( $img ) {
   	    /*
		 * ------------------------
		 * Upload Avatar
		 * $img : New image user
		 * -----------------------
		 */
		$sql  = "UPDATE users SET avatar = ? WHERE id = ?";
		$stmt = $this->db->prepare( $sql );
		$stmt->bindValue( 1, $img, PDO::PARAM_STR );
		$stmt->bindValue( 2, $_SESSION['authenticated'], PDO::PARAM_INT );
		$stmt->execute();
		$this->db = null;
   }
	
	public function uploadAvatarPosition( $img ) {
   	    /*
		 * ------------------------------
		 * Upload Avatar Position [NEW]
		 * $img : New image user
		 * ------------------------------
		 */
		$sql  = "UPDATE users SET avatar = ? WHERE id = ?";
		$stmt = $this->db->prepare( $sql );
		$stmt->bindValue( 1, $img, PDO::PARAM_STR );
		$stmt->bindValue( 2, $_SESSION['authenticated'], PDO::PARAM_INT );
		$stmt->execute();
		
		if ( $stmt->rowCount() != 0 ) {
				return( 1 );
			} else {
			 return false;
		 }
		$this->db = null;
   }
   
   public function uploadCover( $img ) {
   		/*
		 * ------------------------
		 * Upload Cover
		 * $img : New image user
		 * ----------------------
		 */
		$sql  = "UPDATE profile_design SET cover_image = ?, cover_position = '50% 50%' WHERE user = ?";
		$stmt = $this->db->prepare( $sql );
		$stmt->bindValue( 1, $img, PDO::PARAM_STR );
		$stmt->bindValue( 2, $_SESSION['authenticated'], PDO::PARAM_INT );
		$stmt->execute();
		$this->db = null;
   }
   
   public function updateTheme( $img ) {
   		/*
		 * ----------------------------
		 * Upload Theme or Background
		 * $img : New image user
		 * ----------------------------
		 */	
		$sql  = "UPDATE profile_design SET bg = ? WHERE user = ?";
		$stmt = $this->db->prepare( $sql );
		$stmt->bindValue( 1, $img, PDO::PARAM_STR );
		$stmt->bindValue( 2, $_SESSION['authenticated'], PDO::PARAM_INT );
		$stmt->execute();
		$this->db = null;
   }
   
   public function bottomLess() {
   		/*
		 * ------------------------
		 * Delete Background
		 * ----------------------
		 */
		$sql  = "UPDATE profile_design SET bg = null WHERE user = ?";
		$stmt = $this->db->prepare( $sql );
		$stmt->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT );
		$stmt->execute();
		$this->db = null;
   }
   
   public function updateDesignUser() {
   		/*
		 * -----------------------------
		 * Setting: Background Position,
		 * attachment, Color Link,
		 * Color Background
		 * ---------------------------
		 */
		$sql  = "UPDATE 
		profile_design 
		SET 
		bg_position = :pos, 
		bg_attachment = :attachment, 
		color_link = :link,
		bg_color = :color
		WHERE user = :user";
		$stmt = $this->db->prepare( $sql );
		$stmt->bindValue( ':pos', $_POST['pos'], PDO::PARAM_STR );
		$stmt->bindValue( ':attachment', $_POST['mosaic'], PDO::PARAM_STR );
		$stmt->bindValue( ':link', $_POST['link'], PDO::PARAM_STR );
		$stmt->bindValue( ':color', $_POST['bg_color'], PDO::PARAM_STR );
		$stmt->bindValue( ':user', $_SESSION['authenticated'], PDO::PARAM_INT );
		$stmt->execute();
		$this->db = null;
   }
   
   public function getAllMedia( $_id, $where, $limit ) {
			/*
			 * -----------------------------
			 * Get All media
			 * $_id : ID User
			 * $where : Condition
			 * $limit : Limit media
			 * ---------------------------
			 */
			$session = $_SESSION['authenticated'];
			
			$sql = $this->db->prepare( "
			SELECT 
			COUNT(DISTINCT FA.id ) favoriteUser,
			P.id,
			P.token_id,
			P.photo,
			P.post_details,
			P.video_code,
			P.video_title,
			P.video_site,
			P.video_url,
			P.user,
			P.date,
			P.url_soundcloud,
			P.title_soundcloud,
			P.geolocation,
			U.id id_user,
			U.name,
			U.username,
			U.avatar,
			U.type_account
			FROM posts P 
			LEFT JOIN users U ON P.user = U.id
			LEFT JOIN favorites F ON P.id = F.id_favorite && F.status = '1'
			LEFT JOIN favorites  FA ON P.id = FA.id_favorite && FA.id_usr = :id_session && FA.status = '1'
			WHERE P.user = :id && P.repost_of_id = '0' && P.status = '1' && P.photo != '' ".$where." 
			".($_id!=$_SESSION['authenticated']?'&& P.date>=(utc_timestamp() - interval 1 day)':'')."			
			|| P.user = :id && P.repost_of_id = '0' && P.status = '1' && P.video_code != '' ".$where." 
			".($_id!=$_SESSION['authenticated']?'&& P.date>=(utc_timestamp() - interval 1 day)':'')."			
			|| P.user = :id && P.repost_of_id = '0' && P.status = '1' && P.video_url != '' ".$where." 
			".($_id!=$_SESSION['authenticated']?'&& P.date>=(utc_timestamp() - interval 1 day)':'')."			
			|| P.user = :id && P.status = '1' && P.url_soundcloud != '' && repost_of_id = 0 ".$where." 
			".($_id!=$_SESSION['authenticated']?'&& P.date>=(utc_timestamp() - interval 1 day)':'')."			
			GROUP BY P.id
			ORDER BY P.id DESC
			".$limit."
			" );
			
			if( $sql->execute(  array( ':id' => $_id, ':id_session' => $session  )  ) ) {
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
	}//<--- * END FUNCTION *-->
	
	public function sendMessage() {
		/*
		 * ------------------------------------
		 * Send Message Private
		 * @$checkUser : Check if the user exists
		 * ------------------------------------
		 */
		 	
		$checkUser = self :: checkUser( $_POST['id_user'] ) ? 1 : 0;
		
		if( $checkUser == 1 )
		{
			/*-----------------------------------------------
			 * Verify that the recipient not is the sender
			 * ----------------------------------------------
			 */
			if( $_POST['id_user'] != $_SESSION['authenticated'] )
			{
				$sql = "
				INSERT INTO messages
				VALUES
				(
				null,
				?,
				?,
				?,
				'".$this->_dateNow."',
				'new',
				'1'
				)";
				
				$stmt = $this->db->prepare( $sql );
				$stmt->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT );
				$stmt->bindValue( 2, $_POST['id_user'], PDO::PARAM_INT );
				$stmt->bindValue( 3, $_POST['message'], PDO::PARAM_INT );
			    $stmt->execute();
				
				if( $stmt->rowCount() != 0 )
				{
					return true;
				}
				$this->db = null;
		  }//<<<--- * User != From * --->>>
		}//<<<--- * Check * --->>>
		
	}//<<<--- * END FUNCTION *-->>>	
	
	public function sendMessageId() {
		/*
		 * -----------------------------
		 * Send Message Private ID
		 * @$checkUser : Check if the user exists
		 * ---------------------------
		 */
		$checkUser = self :: checkUser( $_POST['id_reply'] ) ? 1 : 0;
		
		if( $checkUser == 1 )
		{
			/*-----------------------------------------------
			 * Verify that the recipient not is the sender
			 * ----------------------------------------------
			 */
			if( $_POST['id_reply'] != $_SESSION['authenticated'] )
			{
				$sql = "
				INSERT INTO messages
				VALUES
				(
				null,
				?,
				?,
				?,
				'".$this->_dateNow."',
				'new',
				'1'
				)";
				
				$stmt = $this->db->prepare( $sql );
				$stmt->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT );
				$stmt->bindValue( 2, $_POST['id_reply'], PDO::PARAM_INT );
				$stmt->bindValue( 3, $_POST['reply_msg'], PDO::PARAM_INT );
			    $stmt = $stmt->execute();
				
				//============ * ID MSG  * ========//
		       $_idMsg = $this->db->lastInsertId( $stmt );
			   
				if( $stmt == true )
				{
					return $_idMsg;
				}
				$this->db = null;
		  }//<<<--- * User != From * --->>>
		}//<<<--- * Check * --->>>
		
	}//<<<--- * END FUNCTION *-->>>	
	
	public function deleteAccount() {
		/*
		 * -----------------------------
		 * Delete Account
		 * ---------------------------
		 */
		 
		$sql = "UPDATE users SET 
		status = 'delete',
		username = '', 
		avatar = 'avatar.png', 
		bio = '' 
		WHERE id = :id && status = 'active'";
		$stmt = $this->db->prepare( $sql );
		$stmt->bindValue( ':id', $_SESSION['authenticated'], PDO::PARAM_INT );
		$stmt->execute();
		
		if( $stmt->rowCount() != 0 ) {
				
			//================= UPDATE INTERACTIONS  STATUS -> DELETE ==============//
			$updateItera = "UPDATE interactions SET trash = '1' WHERE autor = ? || destination = ? ";
			$_execSql   = $this->db->prepare( $updateItera );
			$_execSql->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT ); 
			$_execSql->bindValue( 2, $_SESSION['authenticated'], PDO::PARAM_INT ); 
			$_execSql->execute();
			
		    //================== UPDATE FOLLOWERS STATUS -> DELETE ============//
			$update = "UPDATE followers SET status = '0' WHERE follower = ?";
			$exec   = $this->db->prepare( $update );
			$exec->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT  );
			$exec->execute();
			
			//================= UPDATE FOLLOWING  STATUS -> DELETE ==============//
			$update2 = "UPDATE followers SET status = '0' WHERE following = ?";
			$exe   = $this->db->prepare( $update2 );
			$exe->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT ); 
			$exe->execute();
			
			//================= UPDATE FAVORITES  STATUS -> DELETE ==============//
			$update3 = "UPDATE favorites SET status = '0' WHERE id_usr = ?";
			$_exe   = $this->db->prepare( $update3 );
			$_exe->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT ); 
			$_exe->execute();
			
			//================= UPDATE REPLY  STATUS -> DELETE ==============//
			$update4 = "UPDATE reply SET status = '0' WHERE user = ?";
			$_exec   = $this->db->prepare( $update4 );
			$_exec->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT ); 
			$_exec->execute();
			
			//================= UPDATE REPOST  STATUS -> DELETE ==============//
			$update5 = "UPDATE posts SET status = '0' WHERE user_id = ?";
			$_execQuery  = $this->db->prepare( $update5 );
			$_execQuery->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT );
			$_execQuery->execute();
			
			//================= UPDATE POST  STATUS -> DELETE ==============//
			$update6 = "UPDATE posts SET status = '0' WHERE user = ?";
			$_exec_   = $this->db->prepare( $update6 );
			$_exec_->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT );
			
			if( $_exec_->execute() ) {
		    	
				 /* 
				 *//* Get all photos of Publications/Posts */
			    $querySql = $this->db->prepare("SELECT photo FROM posts WHERE user = ? && photo != '' ");
				
				if( $querySql->execute( array( $_SESSION['authenticated'] ) ) ) {
					
					while( $row = $querySql->fetch( PDO::FETCH_ASSOC ) ) {
						/*
						 * ------------------------------------------
						 * Delete all photos, publications you made.
						 * ------------------------------------------
						 */
						$total = count( $row );
						$root = '../../upload/'.$row['photo'];
						
						if( $total != 0 ) {
							if( file_exists( $root ) ) {
								unlink( $root );
							}
						}
						
					}
				}//<--- * End Delete Photos Posts * --->
		    	
		    }
				
			/*
			 * ----------------------------------------
			 * Delete Cover and Background
			 * ---------------------------------------
			 */
		    $_querySql = $this->db->prepare("SELECT bg, cover_image 
		    FROM profile_design WHERE user = ?");
				
				if( $_querySql->execute( array( $_POST['id'] ) ) ) {
					$defaults  = array( 'photo_0.jpg', 'photo_1.jpg','photo_2.jpg','photo_3.jpg','photo_4.jpg','photo_5.jpg','photo_6.jpg','photo_7.jpg','photo_8.jpg','photo_9.jpg','photo_10.jpg','photo_11.jpg' );
	
					while( $row = $_querySql->fetch( PDO::FETCH_ASSOC ) ) {
						$rootCover = '../cover/'.$row['cover_image'];
						$rootCoverLarge = '../cover/large_'.$row['cover_image'];
						$rootBg    = '../backgrounds/'.$row['bg'];
							
							//<<-- Cover
							if( file_exists( $rootCover ) && $row['cover_image'] != '' ) {
								unlink( $rootCover );
								unlink( $rootCoverLarge ); //<-- Large
							}
							//<<-- Background
							if( file_exists( $rootBg ) && !preg_match('/^photo_([0-9]{1,3}).jpg$/',$row['bg']) && $row['bg'] != '' ) {
								unlink( $rootBg );
							}
					}
				}//<--- * End Delete Bg - Cover * --->
					
					/* Delete Avatar */
					$_querySqlAvatar = $this->db->prepare("SELECT avatar FROM user WHERE user = ?");
					if( $_querySqlAvatar->execute( array( $_POST['id'] ) ) ) {										
									
							while( $rw = $_querySqlAvatar->fetch( PDO::FETCH_ASSOC ) ) {
								$rootAvatar      = '../avatar/'.$rw['avatar'];
								$rootAvatarLarge = '../avatar/large_'.$rw['avatar'];
								
								if( file_exists( $rootAvatar ) 
									&& $rw['avatar'] != '' 
									&& !in_array($rw['avatar'],DEFAULT_AVATARS)
									) {
									unlink( $rootAvatar ); //<-- 62x62
									unlink( $rootAvatarLarge ); //<-- Large
								}
							}
						}//<<<-- End Delete Avtar -->>>
					
			    return true;
			}
		$this->db = null;
	}
	
	public function updatePassword() {
		/*
		 * ----------------------------------------
		 *  Update Password 
		 *  from Page http://sitename.com/password/
		 * ----------------------------------------
		 */
		$pass        = password_hash($_POST['confirm'], PASSWORD_BCRYPT, ['cost' => 12]);
		$sql = "UPDATE users SET password = :pass WHERE id = :user";
		$stmt = $this->db->prepare( $sql );
		$stmt->bindValue( ':pass', $pass, PDO::PARAM_STR );
		$stmt->bindValue( ':user', $_SESSION['authenticated'], PDO::PARAM_INT );
		$stmt->execute();
		
		if ( $stmt->rowCount() != 0 ) {
			return true;
		}
		 else {
			 return false;
		 }
		$this->db = null;
	}//<<< * End * -->>>>
	
	public function updatePasswordRecover() {
		/*
		 * -------------------------------------------------
		 *  Update password Recover
		 *  from Page http://sitename.com/recover/ID_HASH
		 * -------------------------------------------------
		 */
		if ( preg_match( '/[^a-z0-9\_\-]/i',$_POST['idhash'] ) )
		{
			return false;
		}
		
		$pass = password_hash($_POST['pass_2'], PASSWORD_BCRYPT, ['cost' => 12]);
		$sql = "UPDATE 
		users U 
		INNER JOIN recover_pass R ON U.email = R.email
		SET 
		U.password = :pass,
		R.verified = '1',
		R.date_update = '".$this->_dateNow."'
		WHERE R.id_hash = :idhash";
		
		$stmt = $this->db->prepare( $sql );
		$stmt->bindValue( ':pass', $pass, PDO::PARAM_STR );
		$stmt->bindValue( ':idhash', $_POST['idhash'], PDO::PARAM_INT );
		
		if ( $stmt->execute() )
		{
			return true;
		}
		 else {
			 return false;
		 }
		$this->db = null;
	}//<<< * End * -->>>>
	
	public function getMessages( $_id, $where, $limit ) {
		/*
		 * ----------------------------------------
		 * Get messages private
		 * $_id : ID User
		 * $where : Condition
		 * $limit : Limit Messages
		 * ---------------------------------------
		 */
		$sql = $this->db->prepare( "
		SELECT 
	    mm.recipient_id,
	    m.*,
	    U.id id_user,
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
		    MAX(id) as id
		  FROM
		    messages
		  WHERE
		    `from` = :id && remove_from = '1' ".$where." ||
		    `to` = :id && remove_from = '1' ".$where."
		  GROUP BY
		    `from` + `to` - 1
		  ) mm
		    INNER JOIN
		  messages m
		    On
		  mm.id = m.id
		  LEFT JOIN users U ON U.id = mm.`to`
		  LEFT JOIN users U2 ON U2.id = mm.`from` 
		  GROUP BY id_user, id_2
		  ORDER BY id DESC
  			".$limit."
		");
		if( $sql->execute(  array( ':id' => $_id  )  ) )
			{
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
	}//<<<<-- * End * --->>>>
	
	public function countMessagesID( $_from, $_to ){
		$post = $this->db->query("
        SELECT 
        M.id
        FROM messages M
		LEFT JOIN users U ON U.id = M.`from`
		WHERE 
		`from` = ".$_from." && `to` = ".$_to." && remove_from = '1'
		|| `from` = ".$_to." && `to` = ".$_from." && remove_from = '1'");
        $total_messages =  count( $post->fetchall() );
        
        return $total_messages;
	}
	
	public function getMessageId( $_from, $_to ) {
		/*
		 * ----------------------------------------
		 * Get messages by ID
		 * $_from : User From/Sender
		 * $_to : User To/Addressee
		 * ---------------------------------------
		 */		
		$total_messages = self::countMessagesID( $_from, $_to );
		
		$limitNumber = 5;
		$offset = ($total_messages - $limitNumber );
		$limit	= $total_messages;
		
		if( $total_messages <= $limitNumber ) {
			$_limit = null;
		} else {
			$_limit = "LIMIT $offset, $limit";
		}
		
		$sql = $this->db->prepare( "
		SELECT 
		M.id,
		M.to,
		M.from,
		M.message,
		M.date,
		M.status,
		U.id id_user,
		U.username,
		U.name,
		U.type_account,
		U.avatar,
		CASE WHEN `from` = :from THEN `to` Else `from` END AS conversation
		FROM messages M
		LEFT JOIN users U ON U.id = M.`from`
		WHERE 
		`from` = :from && `to` = :to && remove_from = '1'
		|| `from` = :to && `to` = :from && remove_from = '1'
		GROUP BY M.id
		ORDER BY conversation ASC, date ASC
		".$_limit."
		");
		if( $sql->execute(  array( ':from' => $_from, ':to' => $_to  )  ) )
			{
				//<<<--- Message Readed --->>>
				$_sql = $this->db->prepare( "UPDATE messages SET status = 'readed' WHERE `from` = :_from && `to` = :_user ");
   				$_sql->execute( array( ':_from' => $_from, ':_user' => $_SESSION['authenticated'] ) );
	 
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
	}//<<<<-- * End * --->>>>
	
	public function loadMoreMessageId( $_from, $_to, $_id, $_number ) {
		/*
		 * ----------------------------------------
		 * Get messages by ID
		 * $_from : User From/Sender
		 * $_to : User To/Addressee
		 * ---------------------------------------
		 */
		 $total_messages = self::countMessagesID( $_from, $_to ) - $_number;
		
		$limitNumber = 5;
		$offset = ( $total_messages - $limitNumber );
		$limit	= $total_messages;
		
		if( $offset < 0 ) {
			$offset = 0;
		}
		
		$_limit = "LIMIT $offset, $limit";
		
		$sql = $this->db->prepare( "
		SELECT 
		M.id,
		M.to,
		M.from,
		M.message,
		M.date,
		M.status,
		U.id id_user,
		U.username,
		U.name,
		U.type_account,
		U.avatar
		FROM messages M
		LEFT JOIN users U ON U.id = M.`from`
		WHERE 
		`from` = $_from && `to` = $_to && remove_from = '1' && M.id < $_id
		|| `from` = $_to && `to` = $_from && remove_from = '1'  && M.id < $_id
		GROUP BY M.id
		ORDER BY M.id ASC
		".$_limit."
		");
		
		//return $sql;
		
		if( $sql->execute(  
				array( 
				':from' => $_from, 
				':to' => $_to,
				':id' => $_id 
		)  ) )
			{
				//<<<--- Message Readed --->>>
				$_sql = $this->db->prepare( "UPDATE messages SET status = 'readed' WHERE `from` = :_from && `to` = :_user ");
   				$_sql->execute( array( ':_from' => $_from, ':_user' => $_SESSION['authenticated'] ) );
	 
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
	}//<<<<-- * End * --->>>>
	
	public function deleteMsg() {
   	/*
	 * -------------------
	 * Delete Messages
	 * -------------------
	 */
   	 $sql = $this->db->prepare( "UPDATE messages SET remove_from = '0' 
   	 WHERE id = :_id && `from` = :_user 
   	 && remove_from = '1' 
   	 || id = :_id 
   	 && `to` = :_user 
   	 && remove_from = '1'
   	 " 
	 );
   	 $sql->execute( array( ':_id' => $_POST['_msgId'], ':_user' => $_SESSION['authenticated'] ) );
	 
	 if ( $sql->rowCount() != 0 )
		{
			return( 1 );
		}
	 else {
		 return false;
	 }
		$this->db = null;
   }//<-- end Method
   
   public function recoverPass() {
   	  /*
		 * ----------------------------------------
		 * Recover Pass
	     * @Verified : Check email is valid 
		 * ---------------------------------------
		 */
   	  $verified = self :: checkEmail( $_POST['email_recover'] ) ? 1 : 0;
   	  
   	  if( $verified == 1 )
   	  {
   	  	/* If e-mail is True, We insert */
   	  	$sql  = "INSERT INTO recover_pass VALUES( null, :idHash, :email, '0', '".$this->_dateNow."', '' );";
		$stmt = $this->db->prepare( $sql );
		$stmt->bindValue( ':idHash', $_POST['id_hash'], PDO::PARAM_STR );
		$stmt->bindValue( ':email', $_POST['email_recover'], PDO::PARAM_STR );
		$stmt->execute();
		
		 if ( $stmt->rowCount() != 0 ){
			return true;
		}
		 else {
			 return false;
		 }
		
   	  }//<<<---- If
		$this->db = null;
   	  
   	  
   }//<<<--- End Function
   
   
   public function search( $data, $where, $group_order, $limit, $session ) {
   	   	/*
		 * ----------------------------------------
		 * Search #Hastag or some word
		 * $data    : Word to search
		 * $where   : Condition
		 * $limit   : Limit of records
		 * $session : Current Session
		 * ---------------------------------------
		 */
		 
			$data = trim( $data );
			$sql = "
			SELECT 
			COUNT(DISTINCT FA.id ) favoriteUser,
			COUNT(DISTINCT B.id) blockUser,
			P.id,
			P.token_id,
			P.post_details,
			P.photo,
			P.user,
			P.date,
			P.video_code,
			P.video_title,
			P.video_site,
			P.video_url,
			P.url_soundcloud,
			P.title_soundcloud,
			P.doc_site,
			P.doc_url,
			P.url,
			P.url_thumbnail,
			P.url_title,
			P.url_description,
			P.url_host,
			P.geolocation,
			U.id user_id,
			U.name,
			U.username,
			U.avatar,
			U.type_account,
			U.mode,
			MATCH( P.post_details, P.video_title, P.title_soundcloud, P.url_title ) 
			AGAINST( '".$data."' IN BOOLEAN MODE ) relevance
			FROM posts P 
			LEFT JOIN users U ON P.user = U.id
			LEFT JOIN favorites  FA ON P.id = FA.id_favorite && FA.id_usr = :id && FA.status = '1'
			LEFT JOIN block_user B ON U.id = B.user && B.user_blocked = :id && B.status = '1'
			WHERE 
			P.post_details LIKE :query 
			&& U.mode = '1' ".$where." 
			&& B.id IS NULL 
			&& P.repost_of_id = 0
			|| MATCH( P.post_details, P.video_title, P.title_soundcloud, P.url_title ) AGAINST( '".$data."' IN BOOLEAN MODE ) 
			&& P.repost_of_id = 0
			&& U.mode = '1' && B.id IS NULL ".$where." 
			|| MATCH( P.post_details, P.video_title, P.title_soundcloud, P.url_title ) AGAINST( '".$data."*' IN BOOLEAN MODE )
			&& P.repost_of_id = 0 
			&& U.mode = '1' 
			&& B.id IS NULL ".$where."
			".$group_order ."
			".$limit."
			";
			$output = $this->db->prepare( $sql );
			$output->bindValue( ':query', '%'.$data.'%', PDO::PARAM_STR );
			$output->bindValue( ':id',  $session, PDO::PARAM_INT );
			
			
			if ( $output->execute() )
			{
				return $output->fetchall( PDO::FETCH_ASSOC );
				$this->db = null;
			}
			
    }//<--- * END FUNCTION *-->
    
    public function searchUsers( $data, $where, $limit, $session ) {
   	   		/*
			 * ----------------------------------------
			 * Search Users
			 * $data    : Word to search
			 * $where   : Condition
			 * $limit   : Limit of records
			 * $session : Current Session
			 * ---------------------------------------
			 */
			$data = trim( $data, '@' );
			$data = trim( $data, '#' );
			$sql = "
			SELECT 
			COUNT(DISTINCT FO.id ) followActive,
			COUNT(DISTINCT B.id) blockUser,
			U.bio,
			U.location,
			U.country,
			U.id,
			U.name,
			U.username,
			U.avatar,
			U.type_account,
			U.mode
			FROM users U 
			LEFT JOIN followers FO ON U.id = FO.following && FO.follower = :id && FO.status = '1'
			LEFT JOIN block_user B ON U.id = B.user && B.user_blocked = :id && B.status = '1'
			WHERE  U.username LIKE :query 
			&& U.mode = '1' 
			&& B.id IS NULL ".$where." 
			|| U.bio LIKE :query 
			&& U.mode = '1' 
			&& B.id IS NULL ".$where." 
			|| U.name LIKE :query 
			&& U.mode = '1' 
			&& B.id IS NULL ".$where." 
			GROUP BY U.id
			ORDER BY U.id DESC
			".$limit."
			";
			$output = $this->db->prepare( $sql );
			$output->bindValue( ':query', '%'.$data.'%', PDO::PARAM_STR );
			$output->bindValue( ':id',  $session, PDO::PARAM_INT );
			
			if ( $output->execute() )
			{
				return $output->fetchall( PDO::FETCH_ASSOC );
				$this->db = null;
			}
			
    }//<--- * END FUNCTION *-->
    
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
	
	public function checkReposted( $id, $user ) {
		/*
		 * ----------------------------------------------
		 * Repost of User
		 * ----------------------------------------------
		 */
		$id    = (int)$id;
		
		$sql = $this->db->prepare( "SELECT status FROM posts WHERE repost_of_id = ? && user = ? " );
		$sql->execute( array( $id, $user ) );
		return $sql->fetchall();
		$this->db = null;
	}
	
	 public function reposted() {
   	/*
	 * ----------------------------------------------------------------------------
	 *  Resposted
	 * @$active  :"Check to see if the user has already repost"
	 * @verified : "Check if the publication exists"
	 * ----------------------------------------------------------------------------
	 */
	 
   	$active   = self :: checkReposted( $_POST['id'], $_SESSION['authenticated'] );
   	$verified = self :: checkPost( $_POST['id'], $_POST['token'] ) ? 1 : 0;
	
	
	if( $verified == 1 && empty( $active ) )
	{
		$_idPost  = (int)$_POST['id'];
		$_sql     = $this->db->prepare("
		SELECT 
		post_details, 
		token_id,
		photo,
		video_code,
		video_title,
		video_site,
		video_url,
		user,
		video_thumbnail,
		date,
		url_soundcloud,
		title_soundcloud,
		thumbnail_song,
		doc_site,
		doc_url,
		url,
		url_thumbnail,
		url_title,
		url_description,
		url_host,
		geolocation
		FROM posts 
		WHERE id = :id
		");
		$_sql->execute( array(  
							':id' => $_idPost 
							) 
						);
		$response = $_sql->fetch( PDO::FETCH_OBJ );
		
		/** If not exists, insert new record  **/
		$sql = $this->db->prepare("INSERT INTO posts 
		VALUES( 
		null,
	   	 ?,
	   	 ?,
	   	 ?,
	   	 ?,
	   	 ?,
	   	 ?,
	   	 ?,
	   	 ?,
	   	 ?,
	   	 ?,
	   	 '1',
	   	 ?,
	   	 ?,
   		 '1',
   		 ?,
   		 ?,
   		 ?,
   		 ?,
   		 ?,
   		 ?,
		 ?,
		 ?,
		 ?,
		 ?,
		 ?,
		 '0',
		 '0',
		 '0'
		 );
		 ");
	    $sql->bindValue( 1,  $_POST['id'], PDO::PARAM_INT );
		$sql->bindValue( 2,  $response->user, PDO::PARAM_INT );
		$sql->bindValue( 3,  $response->token_id, PDO::PARAM_STR );
		$sql->bindValue( 4,  ''.$response->post_details.'', PDO::PARAM_STR );
		$sql->bindValue( 5,  ''.$response->photo.'', PDO::PARAM_STR );
		$sql->bindValue( 6,  ''.$response->video_code.'', PDO::PARAM_STR );
		$sql->bindValue( 7,  ''.$response->video_title.'', PDO::PARAM_STR );
		$sql->bindValue( 8,  ''.$response->video_site.'', PDO::PARAM_STR );
		$sql->bindValue( 9,  ''.$response->video_url.'', PDO::PARAM_STR );
		$sql->bindValue( 10,  $_SESSION['authenticated'], PDO::PARAM_INT );
		$sql->bindValue( 11, ''.$response->video_thumbnail.'', PDO::PARAM_STR );
		$sql->bindValue( 12, $response->date, PDO::PARAM_STR );
		$sql->bindValue( 13, $response->url_soundcloud, PDO::PARAM_STR );
		$sql->bindValue( 14, $response->title_soundcloud, PDO::PARAM_STR );
		$sql->bindValue( 15, $response->thumbnail_song, PDO::PARAM_STR );
		$sql->bindValue( 16, $response->doc_site, PDO::PARAM_STR );
		$sql->bindValue( 17, $response->doc_url, PDO::PARAM_STR );
		$sql->bindValue( 18, $response->url, PDO::PARAM_STR );
		$sql->bindValue( 19, $response->url_thumbnail, PDO::PARAM_STR );
		$sql->bindValue( 20, $response->url_title, PDO::PARAM_STR );
		$sql->bindValue( 21, $response->url_description, PDO::PARAM_STR );
		$sql->bindValue( 22, $response->url_host, PDO::PARAM_STR );
		$sql->bindValue( 23, $response->geolocation, PDO::PARAM_STR );
		$sql->execute();
		
		if( $sql->rowCount() !=  0 ) {
			
			if( $response->user != $_SESSION['authenticated'] ) {
				/* Send Interaction */
			self :: sendInteraction( $response->user, $_SESSION['authenticated'], $_idPost, 2 );	
			}
			return( 1 );
		}
		
	}
	
	if( $verified == 1 && !empty( $active ) && $active[0]['status'] == '1' )
	{
		/** If exists, update status to Delete/Trash  **/
		$sql = $this->db->prepare("UPDATE posts SET status = '0' WHERE user = ? && repost_of_id = ? ");
	    $sql->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT );
		$sql->bindValue( 2, $_POST['id'], PDO::PARAM_INT );
		$sql->execute();
		if( $sql->rowCount() !=  0 )
		{
			return( 2 );
		}
	}
	else if ( $verified == 1 && !empty( $active ) && $active[0]['status'] == '0' )
		{
			/** If exists and status == Delete/Trash, update status to Active  **/
			$sql = $this->db->prepare("UPDATE posts SET status = '1' WHERE user = ? && repost_of_id = ? ");
		    $sql->bindValue( 1, $_SESSION['authenticated'], PDO::PARAM_INT );
			$sql->bindValue( 2, $_POST['id'], PDO::PARAM_INT );
			$sql->execute();
		if( $sql->rowCount() !=  0 )
		{
			return( 3 );
		}
	}
		return false;
	   	$this->db = null;
		
   }//<-- end
   
   public function getTotalSummary( $usr ) {
   	   /*
		 * --------------------------------------------
		 * Get Total Followers, Followings and Post
		 * -------------------------------------------
		 */
   	   if ( isset( $usr ) ) {
			$sql = "
			SELECT 
			COUNT( DISTINCT FO.id ) totalFollowers,
			COUNT( DISTINCT FOL.id ) totalFollowing,
			COUNT( DISTINCT PO.id ) totalPosts
			FROM users U 
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
    }//<--- * END FUNCTION *-->
    
    
    //<<<--- * Delete All Messages * --->>>
    public function deleteAllMsg() {
   	/*
	 * -------------------
	 * Delete All Messages
	 * -------------------
	 */
   	 $sql = $this->db->prepare( "UPDATE messages SET remove_from = '0' 
   	 WHERE `to` = :_idFrom 
   	 && `from` = :_user 
   	 || `from` = :_idFrom 
   	 && `to` = :_user 
   	 " 
	 );
   	 $sql->execute( array( ':_idFrom' => $_POST['_userId'], ':_user' => $_SESSION['authenticated'] ) );
	 
	 if ( $sql->rowCount() != 0 ) {
			return( 1 );
		}
	 else {
		 return false;
	 }
		$this->db = null;
   }//<-- End Delete All Messages
   
   public function searchUsersMentions( $data, $where, $limit, $session ) {
   	   		/*
			 * ----------------------------------------
			 * Search Users
			 * $data    : Word to search
			 * $where   : Condition
			 * $limit   : Limit of records
			 * $session : Current Session
			 * ---------------------------------------
			 */
			$data = trim( $data, '@' );
			$data = trim( $data, '#' );
			$sql = "
			SELECT 
			COUNT(DISTINCT FO.id ) followActive,
			COUNT(DISTINCT B.id) blockUser,
			U.bio,
			U.location,
			U.id,
			U.name,
			U.username,
			U.avatar,
			U.type_account,
			U.mode
			FROM users U 
			LEFT JOIN followers FO ON U.id = FO.following && FO.follower = :id && FO.status = '1'
			LEFT JOIN block_user B ON U.id = B.user && B.user_blocked = :id && B.status = '1'
			WHERE  U.username LIKE :query 
			&& U.mode = '1' 
			&& B.id IS NULL ".$where." 
			&& U.mode = '1' 
			&& B.id IS NULL ".$where." 
			|| U.name LIKE :query 
			&& U.mode = '1' 
			&& B.id IS NULL ".$where." 
			GROUP BY U.id
			ORDER BY U.type_account DESC
			".$limit."
			";
			$output = $this->db->prepare( $sql );
			$output->bindValue( ':query', '%'.$data.'%', PDO::PARAM_STR );
			$output->bindValue( ':id',  $session, PDO::PARAM_INT );
			
			if ( $output->execute() ) {
				return $output->fetchall( PDO::FETCH_ASSOC );
				$this->db = null;
			}
			
    }//<--- * END FUNCTION *-->
    
    //<<<<<<<<<<<<<<<<<<<<<<<< ---------- v2.6 ------------->>>>>>>>>>>>>>>>>>
    
    // Begin Method
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
			
	}//<--- * End Method *-->
	
	// Begin Method
    public function allUsername() {
			/*
			 * -----------------------------
			 * "all Username" : 
			 * show all users in sitemaps.xml
			 * -----------------------------
			 */
			$sql = $this->db->prepare( "
			SELECT 
			username
			FROM users
			" );
			
			//return $sql;
			if( $sql->execute() )
			{
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
			$this->db = null;
			
	}//<--- * End Method *-->
	
	// Begin Method
    public function allPages() {
			/*
			 * -----------------------------
			 * "all Pages" : 
			 * show all Pages in sitemaps.xml
			 * -----------------------------
			 */
			$sql = $this->db->prepare( "
			SELECT 
			url
			FROM pages_general
			" );
			
			//return $sql;
			if( $sql->execute() )
			{
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
			$this->db = null;
			
	}//<--- * End Method *-->
	
	public function getMedia_2( $_id ) {
			/*
			 * -------------------------------------------
			 * Get photos / videos of the publications
			 * $_id : ID Publication/Post
			 * $_token : Token unique of Publication/Post
			 * -------------------------------------------
			 */
			$sql = $this->db->prepare( "
			SELECT 
			P.id,
			P.token_id,
			P.photo,
			P.post_details,
			P.video_code,
			P.video_title,
			P.video_site,
			P.video_url,
			P.user,
			P.date,
			P.url_soundcloud,
			P.title_soundcloud,
			P.doc_site,
			P.doc_url,
			P.url,
			P.url_thumbnail,
			P.url_title,
			P.url_description,
			P.url_host,
			U.username
			FROM posts P
			LEFT JOIN users U ON P.user = U.id
			WHERE P.id = :id && P.status = '1'
			" );
			
			if( $sql->execute(  array( ':id' => $_id  )  ) ){
				return  $sql->fetchall( PDO::FETCH_ASSOC );
			}
	}//<--- * END FUNCTION *-->
	
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
	
	public function editBgPosition() {
   		/*
		 * ---------------------------
		 * Background Position
		 * ---------------------------
		 */
		$sql  = "UPDATE 
		profile_design 
		SET 
		cover_position = :pos
		WHERE user = :user";
		$stmt = $this->db->prepare( $sql );
		$stmt->bindValue( ':pos', $_GET['_bgPosition'], PDO::PARAM_STR );
		$stmt->bindValue( ':user', $_SESSION['authenticated'], PDO::PARAM_INT );
		$stmt->execute();
		
		if ( $stmt->rowCount() != 0 ) {
				return( 1 );
			}
		 else {
			 return false;
		 }
	 
		$this->db = null;
		
   }//<-- End Method
   
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
			$this->db = null;
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
			$this->db = null;
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
			$this->db = null;
		}
   }//<-- End Method
  
}//*************************************** End Class AjaxRequest() *****************************************//
?>