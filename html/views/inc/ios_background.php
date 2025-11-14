<?php 
if(preg_match('/(iphone|ipad)/i',$_SERVER['HTTP_USER_AGENT'])){ 
	if(!$bginfo)$bginfo=$this->infoSession;
	?>
	
	body::before {
		position:fixed;
		top:0;
		left:0;
		z-index:-1;
		height:110%;
		width:100%;
		background-position: 50% 0%;
		background-image:url(<?php echo URL_BASE.'public/backgrounds/'.$bginfo->bg; ?>);
		<?php if( $bginfo->bg_attachment == 'fixed' ): ?>
		background-repeat: repeat repeat; 
		<?php else: ?>
		background-repeat: no-repeat; 
		<?php endif; ?>		
		-webkit-background-size:contain;
		background-size:270% auto;
		content:"";
	}
	<?php } ?>