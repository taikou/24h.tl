<!-- Start Panel -->
     <div class="panel panel-default">
			<div class="panel-body">
				<ul class="list-inline margin-zero menu-footer">
					<li class="btn-block">&copy; <?php echo date('Y')." Decoo"; ?></li>
					<?php foreach( $this->pagesGeneral as $key ) : ?>
						<li><a class="link-footer" href="<?php echo URL_BASE.$key['url'].'/'; ?>"><?php echo stripslashes( $key['title'] ); ?></a> </li>
						<?php endforeach; ?>
					<li><a class="link-footer" href="https://decoo.co.jp//privacy">Privacy</a></li>
			    	<li><a class="link-footer" href="<?php echo URL_BASE.'api/'; ?>">API</a> </li>
			    	
				</ul>
         
	</div><!-- Panel Body -->
    </div><!--./ Panel Default -->
    
