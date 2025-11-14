<?
error_reporting(1);

function esc_html($str){
	return htmlspecialchars($str);
}
function esc_attr_e($word, $attr){
	return $word;
}
function esc_attr($word){
	return $word;
}
function get_the_ID(){
	return 12345;
}
function count_like_layout( $post_id = false ) {
		if ( !$post_id ) {
			$post_id = get_the_ID();
		}
		$reactions = array( 'like', 'love', 'haha', 'wow', 'sad', 'angry' );
//		$total = get_post_meta( $post_id, 'dw_reaction_total_liked', true );
		$total = 12;
		$output = '';
		foreach( $reactions as $reaction ) {
//			$count = get_post_meta( $post_id, 'dw_reaction_' . $reaction );
			$count = 3;
			if ( !empty( $count ) ) {
				$output .= '<span class="dw-reaction-count dw-reaction-count-'.esc_attr( $reaction ).'"><strong>'.esc_attr( count( $count ) ).'</strong></span>';
			}
		}

		return $output;
	}

?>


<head>
	<script src="/public/js/jquery-1.7.1.min.js"></script>
	<script src="assets/js/script.js" type="text/jscript"></script>
	<link href="assets/css/style.css" rel="stylesheet">
</head>

<br>
<br>
<br>
<br>
<br>
<br>
<br>
<div class="dw-reactions dw-reactions-post-9" data-type="unvote" data-nonce="83a4da11cc" data-post="9">
	<div class="dw-reactions-button" style="user-select: none;">
		<span class="dw-reactions-main-button  dw_reaction_like">Like</span>
		<div class="dw-reactions-box">
			<span class="dw-reaction dw-reaction-like"><strong>Like</strong></span>
			<span class="dw-reaction dw-reaction-love"><strong>Love</strong></span>
			<span class="dw-reaction dw-reaction-haha"><strong>Haha</strong></span>
			<span class="dw-reaction dw-reaction-wow"><strong>Wow</strong></span>
			<span class="dw-reaction dw-reaction-sad"><strong>Sad</strong></span>
			<span class="dw-reaction dw-reaction-angry"><strong>Angry</strong></span>
		</div>
	</div>
	<div class="dw-reactions-count">
		<span class="dw-reaction-count dw-reaction-count-like"><strong>1339</strong></span>
		<span class="dw-reaction-count dw-reaction-count-love"><strong>636</strong></span>
		<span class="dw-reaction-count dw-reaction-count-haha"><strong>244</strong></span>
		<span class="dw-reaction-count dw-reaction-count-wow"><strong>161</strong></span>
		<span class="dw-reaction-count dw-reaction-count-sad"><strong>129</strong></span>
		<span class="dw-reaction-count dw-reaction-count-angry"><strong>241</strong></span>
	</div>
</div>

		
						
<? exit; ?>

		<div class="dw-reactions dw-reactions-post-12345" data-type="unvote" data-nonce="reaction" data-post="12345">
			<?php if ( $button=1 ) : ?>
				<?php if (1) : ?>
				<div class="dw-reactions-button">
					<span class="dw-reactions-main-button <?php echo esc_attr( strtolower( $is_liked ) ) ?>">LIKE</span>
					<div class="dw-reactions-box">
						<span class="dw-reaction dw-reaction-like"><strong><?php esc_attr_e( 'Like', 'reactions' ) ?></strong></span>
						<span class="dw-reaction dw-reaction-love"><strong><?php esc_attr_e( 'Love', 'reactions' ) ?></strong></span>
						<span class="dw-reaction dw-reaction-haha"><strong><?php esc_attr_e( 'Haha', 'reactions' ) ?></strong></span>
						<span class="dw-reaction dw-reaction-wow"><strong><?php esc_attr_e( 'Wow', 'reactions' ) ?></strong></span>
						<span class="dw-reaction dw-reaction-sad"><strong><?php esc_attr_e( 'Sad', 'reactions' ) ?></strong></span>
						<span class="dw-reaction dw-reaction-angry"><strong><?php esc_attr_e( 'Angry', 'reactions' ) ?></strong></span>
					</div>
				</div>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ( 1 ) : ?>
				<div class="dw-reactions-count">
					<?php echo count_like_layout( $post_id ); ?>
				</div>
			<?php endif; ?>
		</div>
		
	




