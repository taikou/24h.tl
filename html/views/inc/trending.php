 <?php
 $countTrends = count(  $this->trending );
 
 if( $countTrends == 0 ) {
 	$noresult = '<li>'.$_SESSION['LANG']['no_trending'].'</li>';
 }
 
 //<--- MAX NUMBER TRENDS
 $numbMax = 2;
 if( isset( $this->trending ) && $countTrends != 0 ):
  ?>
   
   <!-- Start Panel -->
      	<div class="panel panel-default">
			<span class="panel-heading btn-block grid-panel-title">
				<i class="glyphicon glyphicon-fire myicon-right"></i> <?php echo $_SESSION['LANG']['trending']; ?>
				
				</span>
			<div class="panel-body">
				
				<ul class="list-grid-user list-grid-block">

					<?php 
	   		foreach ( $this->trending as $key ) {
	   			if( $key->total >= $numbMax ):
					++$number;
				?>
				<li>
	   					<a href="<?php echo URL_BASE."search/?q=%23".$key->trends ?>">#<?php echo $key->trends ?></a>
	   				</li>
				<?php
				endif;
				
				if( $key->total <= $numbMax && !isset( $number ) ):
					  $noresult = '<li>'.$_SESSION['LANG']['no_trending'].'</li>';
					endif;
				 }//<--- FOREACH
			   //<-- No Trends -->
			   echo $noresult;
	   		?>
				
				</ul><!-- list-grid -->
			</div><!-- Panel Body -->
    	</div><!--./ Panel Default -->
   <?php endif; ?>