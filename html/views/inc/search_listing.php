<div class="panel panel-default panel-live">
			
<div class="panel-heading grid-panel-title">
	<h3 class="panel-title titleBar box-class" id="query_data" data-query="<?php echo htmlentities( strip_tags( $_GET['q'], ENT_QUOTES ) ); ?>">
		<?php echo $_SESSION['LANG']['result_of']; ?> 
		<em style="color: #999;"><?php echo htmlentities( strip_tags( $_GET['q'] ), ENT_QUOTES,'UTF-8' ); ?></em>
		<?php echo $this->titleH4; ?>
	</h3>
  </div>
  
  <button type="button" class="btn btn-info btn-border btn-df btn-sm btn-block margin-bt news_post"></button>

<div class="posts">
<div class="loading-bar load"></div>
		
	<?php if( $this->$countPost == 0 ): ?>
		
			<div class="panel-footer notfound text-center"  style="display: none;">
				<?php echo $_SESSION['LANG']['no_results']; ?>
			</div>
		<?php endif; ?>
</div>


	
</div><!-- ****** ./ End Posts ****** -->