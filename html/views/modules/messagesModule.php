<div class="panel panel-default panel-live posts">
			
			<div class="panel-heading grid-panel-title">
    <h3 class="panel-title titleBar" data-title="<?php echo  $this->title; ?>"><?php if( isset( $this->title ) ): echo stripslashes( $this->title ); endif; ?></h3>
  </div>

		<div class="loading-bar"></div>
			
		<?php if( $this->countMgs == 0 ): ?>
		
			<div class="panel-footer notfound text-center" style="display: none;">
				<?php echo $_SESSION['LANG']['without_msg']; ?>
			</div>
		<?php endif; ?>
		
				
	
</div>
			   		


