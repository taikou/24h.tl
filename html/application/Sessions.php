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
		
		// SameSite=Lax に変更（Strict は厳しすぎてセッションが機能しない場合がある）
		ini_set('session.cookie_samesite', 'Lax');
		
		session_start();
		
		// デバッグ: セッション情報をログ出力
		error_log('[SESSION DEBUG] Session ID: ' . session_id());
		error_log('[SESSION DEBUG] Session started: ' . (session_status() === PHP_SESSION_ACTIVE ? 'YES' : 'NO'));
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