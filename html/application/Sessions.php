<?php
/*
 * -------------------------------------
 * Sessions.php
 * -------------------------------------
 */
class Session
{
	public static function init() {
		// セッション Cookie のセキュリティ設定
		ini_set('session.cookie_httponly', 1);
		
		// HTTPS 環境の場合のみ secure 属性を有効化
		$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
		           || (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
		ini_set('session.cookie_secure', $isHttps ? 1 : 0);
		
		ini_set('session.cookie_samesite', 'Strict');
		
		session_start();
	}//<--- * END init() * --->
	
	public function destroy( $action = false  ) {
		if( $action === true ) {
			$_SESSION = array();
			session_destroy();
		}
	}//<--- * END destroy() * -->
	
	public function get( $key ) {
		if( isset( $_SESSION[$key] ) ) {
			return $_SESSION[$key];
		} else {
			return false;
		}
	}//<--- * END get() * -->
	
}//<------------ * END CLASS * ------------->
?>