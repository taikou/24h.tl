<!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  <h3 class="panel-title titleBar" data-title="<?php echo  $this->title; ?>"><?php if( isset( $this->title ) ): echo stripslashes( $this->title ); endif; ?></h3>
		  
		  </div><!-- ** panel-heading ** -->
		  
   <div class="panel-body">
				  
			<form class="form-horizontal formAjax" role="form"  action="" method="POST" id="formSettings">
			 
			 <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm"><?php echo $_SESSION['LANG']['current_pass']; ?></label>
			    <div class="col-sm-10">
			      <input  type="password" name="current" id="current" class="form-control input-sm" placeholder="<?php echo $_SESSION['LANG']['current_pass']; ?>">
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm"><?php echo $_SESSION['LANG']['new_pass']; ?></label>
			    <div class="col-sm-10">
			      <input name="new" type="password" id="new" value="" class="form-control input-sm" placeholder="<?php echo $_SESSION['LANG']['new_pass']; ?>">
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm"><?php echo $_SESSION['LANG']['confirm_pass']; ?></label>
			    <div class="col-sm-10">
			      <input type="password" value="" name="confirm" id="confirm" class="form-control input-sm" placeholder="<?php echo $_SESSION['LANG']['confirm_pass']; ?>">
			    </div>
			  </div><!-- **** form-group **** -->
			  
		
			  <div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			    	
			    	<div class="alert alert-danger error-update btn-sm" role="alert"></div>
			    	
			      <button type="submit" id="editProfile" class="btn btn-info btn-sm profile-settings-password" disabled="disabled" style="opacity: 0.5; cursor: default;">
			      	<?php echo $_SESSION['LANG']['save']; ?>
			      	</button>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  
			  
			</form><!-- **** form **** -->
				  
		</div><!-- Panel Body -->

   </div><!-- Panel Default -->
