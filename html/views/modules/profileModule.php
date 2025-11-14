 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<h3 class="panel-title titleBar" data-title="<?php echo  $this->title; ?>"><?php if( isset( $this->title ) ): echo stripslashes( $this->title ); endif; ?></h3>
		  
		  </div><!-- ** panel-heading ** -->
		  
   <div class="panel-body">
				  
			<form class="formAjax form-horizontal" action="" method="POST" id="formSettings">
			 
			 <div class="form-group">
			    <label class="col-sm-2 control-label input-sm"><?php echo $_SESSION['LANG']['name']; ?></label>
			    <div class="col-sm-10">
			      <input type="text" name="name" id="nameEdit" value="<?php echo stripslashes( $this->infoSession->name ) ?>" class="form-control input-sm" placeholder="<?php echo $_SESSION['LANG']['name']; ?>">
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group">
			    <label class="col-sm-2 control-label input-sm"><?php echo $_SESSION['LANG']['location']; ?></label>
			    <div class="col-sm-10">
			      <input type="text" name="location" id="location" value="<?php echo stripslashes( htmlspecialchars( $this->infoSession->location ) ) ?>" class="form-control input-sm" placeholder="<?php echo $_SESSION['LANG']['location']; ?>">
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group">
			    <label class="col-sm-2 control-label input-sm"><?php echo $_SESSION['LANG']['website']; ?></label>
			    <div class="col-sm-10">
			      <input type="text" name="website" value="<?php echo $this->infoSession->website ?>" class="form-control input-sm" placeholder="<?php echo $_SESSION['LANG']['website']; ?>">
			    </div>
			  </div><!-- **** form-group **** -->

			  <div class="form-group">
			    <label class="col-sm-2 control-label input-sm">Instagram ID</label>
				<i id='verified-instagram' class="fa fa-check-circle verified verified-min showTooltip <?=($this->infoSession->sns_instagram_verified?'':'hide')?>" title="" data-toggle="tooltip" data-placement="right" data-original-title="Instagram Varified"></i>
			    <div class="col-sm-10">
			      <input type="text" name="sns_instagram_id" value="<?php echo $this->infoSession->sns_instagram_id ?>" class="form-control input-sm" placeholder="Instagram ID" maxlength="24">
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group">
			    <label class="col-sm-2 control-label input-sm">Twitter ID</label>
				<i id='verified-twitter' class="fa fa-check-circle verified verified-min showTooltip <?=($this->infoSession->sns_twitter_verified?'':'hide')?>" title="" data-toggle="tooltip" data-placement="right" data-original-title="Twitter Varified"></i>
			    <div class="col-sm-10">
			      <input type="text" name="sns_twitter_id" value="<?php echo $this->infoSession->sns_twitter_id ?>" class="form-control input-sm" placeholder="Twitter ID" maxlength="24">
			    </div>
			  </div><!-- **** form-group **** -->
			  			  
			  <div class="form-group">
			    <label class="col-sm-2 control-label input-sm"><?php echo $_SESSION['LANG']['bio']; ?></label>
			    <div class="col-sm-10">
			      <textarea name="bio" rows="4" id="bio" class="form-control input-sm textarea-textx"><?php echo $this->infoSession->bio ?></textarea>
			    </div>
			  </div><!-- **** form-group **** -->

		
			  <div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <button id="editProfile" type="submit" class="btn btn-info btn-sm profile-settings" disabled="disabled" style="opacity: 0.5; cursor: default;"><?php echo $_SESSION['LANG']['save']; ?></button>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			</form><!-- **** form **** -->
				  
		</div><!-- Panel Body -->

   </div><!-- Panel Default -->
   
    <!--********* panel panel-default ***************-->
  <div class="panel panel-default">
		  
   <div class="panel-body">
				  
  <!-- *********** form AVATAR ************* -->  
  <form class="form-horizontal" action="<?php echo URL_BASE; ?>public/ajax/uploadAvatar.php" method="POST" id="formAvatar" accept-charset="UTF-8" enctype="multipart/form-data">
			  
			  <div class="form-group">
			    <label for="exampleInputFile" class="col-sm-2 control-label input-sm"><?php echo $_SESSION['LANG']['avatar']; ?> 5MB</label>
			    
			    <div class="col-sm-10">
			    	
	  <div class="labelAvatar" style="background-image: url(<?php echo URL_BASE."thumb/128-128-public/avatar/".$this->infoSession->avatar; ?>)">
		
	    	<?php if( !in_array($this->infoSession->avatar,DEFAULT_AVATARS) ): ?>
			<div class="deletePhoto" data="<?php echo $this->infoSession->avatar; ?>" style="background: none; cursor: pointer;" title="<?php echo $_SESSION['LANG']['delete_image']; ?>" id="loader_gif_1"></div>
			<?php endif; ?>
			</div>
			
			<button type="button" class="btn btn-default btn-border btn-sm" id="avatar_file" style="margin-top: 10px;">
	    		<i class="icon-camera"></i>
	    		</button>
	    		 <input type="file" name="photo" id="uploadAvatar" accept="image/*" style="visibility: hidden;">
			      	 	
			    </div>
		  	</div><!-- **** form-group **** -->
		
			</form><!-- *********** form AVATAR ************* -->
			
			 <!-- *********** form COVER ************* -->  
  <form class="form-horizontal" action="<?php echo URL_BASE; ?>public/ajax/upload_cover.php" method="POST" id="formCover" accept-charset="UTF-8" enctype="multipart/form-data">
			  
			  <div class="form-group">
			    <label for="exampleInputFile" class="col-sm-2 control-label input-sm"><?php echo $_SESSION['LANG']['cover']; ?> 5MB - 1500px</label>
			    
			    <div class="col-sm-10">
	  
	  <?php 
			if( $this->infoSession->cover_image != '' ):
				$cover = 'background-image: url('.URL_BASE.'public/cover/'.$this->infoSession->cover_image.');';
				endif;
				?>
				
	  <div class="label_cover" id="coverUser" style="-webkit-border-radius: 5px; border-radius: 5px; width: 100%; height: 200px; background-color: #D1D1D1; background-position: center center; background-size: cover; <?php echo $cover; ?>">
	  	
	  	
		
	    	<?php if( $this->infoSession->cover_image != '' ): ?>
			<div class="deleteCover" data="<?php echo $this->infoSession->cover_image; ?>" style="background: none; cursor: pointer; position: absolute; top: 0; right: 0;" title="<?php echo $_SESSION['LANG']['delete_image']; ?>" id="loader_gif_2"></div>
			<?php endif; ?>

			 </div>
			
			<button type="button" class="btn btn-default btn-border btn-sm" id="cover_file" style="margin-top: 10px;">
	    		<i class="icon-camera"></i>
	    		</button>
	    		 <input type="file" name="photo" id="uploadCover" accept="image/*" style="visibility: hidden;">
			      	 	
			   
			    
			    </div>
		  	</div><!-- **** form-group **** -->
		
			</form><!-- *********** form COVER ************* -->

				  
		</div><!-- Panel Body -->

   </div><!-- Panel Default -->
