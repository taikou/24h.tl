<div class="panel panel-default panel-live">
			
<div class="panel-heading grid-panel-title">
    <?php echo $_SESSION['LANG']['post_recent']; ?>
  </div>
  
  <button type="button" class="btn btn-info btn-border btn-df btn-sm btn-block margin-bt news_post"></button>

<div class="posts">
	<?php if( $countPost != 0 ): ?>
		<div class="loading-bar load"></div>
		
		<?php endif;
		
		if( $countPost == 0 ): ?>
		
			<div class="panel-footer notfound text-center">
				<?php echo $_SESSION['LANG']['no_post_index']; ?>
			</div>
		<?php endif; ?>
	</div><!-- posts -->
</div><!-- ****** ./ End Posts ****** -->
