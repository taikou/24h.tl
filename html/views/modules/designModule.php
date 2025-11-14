<div class="panel panel-default">
  <div class="panel-heading grid-panel-title">
    <h3 class="panel-title titleBar" data-title="<?php echo  $this->title; ?>"><?php if( isset( $this->title ) ): echo stripslashes( $this->title ); endif; ?></h3>
  </div>
  <div class="panel-body">
	
	<div class="row">

<?php
		for($i=1;$i<=144;$i++){
			?>
		<div class="col-xs-3 col-sm-3 col-md-3">
			<img class="themeChange" data-status="0" data="<?=$i?>" src="<?php echo URL_BASE; ?>public/img/design/previews/photo_<?=$i?>.jpg" />
		</div>
			<?
		}
?>
				
	<?php /*	
		<div class="col-sm-6 col-md-3">
			<img class="themeChange" data-status="0" data="0" src="<?php echo URL_BASE; ?>public/backgrounds/0.jpg" />
		</div>
		
		<div class="col-sm-6 col-md-3">
			<img class="themeChange" data-status="0" data="1" src="<?php echo URL_BASE; ?>public/backgrounds/1.jpg" />
		</div>
		
		<div class="col-sm-6 col-md-3">
			<img class="themeChange" data-status="0" data="2" src="<?php echo URL_BASE; ?>public/backgrounds/2.jpg" />
		</div>
		
		<div class="col-sm-6 col-md-3">
			<img class="themeChange" data-status="0" data="3" src="<?php echo URL_BASE; ?>public/backgrounds/3.jpg" />
		</div>
		
		<div class="col-sm-6 col-md-3">
			<img class="themeChange" data-status="0" data="4" src="<?php echo URL_BASE; ?>public/backgrounds/4.jpg" />
		</div>
		
		<div class="col-sm-6 col-md-3">
			<img class="themeChange" data-status="0" data="5" src="<?php echo URL_BASE; ?>public/backgrounds/5.jpg" />
		</div>
		
		<div class="col-sm-6 col-md-3">
			<img class="themeChange" data-status="0" data="6" src="<?php echo URL_BASE; ?>public/backgrounds/6.jpg" />
		</div>
		
		<div class="col-sm-6 col-md-3">
			<img class="themeChange" data-status="0" data="7" src="<?php echo URL_BASE; ?>public/backgrounds/7.jpg" />
		</div>
		
		<div class="col-sm-6 col-md-3">
			<img class="themeChange" data-status="0" data="8" src="<?php echo URL_BASE; ?>public/backgrounds/8.jpg" />
		</div>
		
		<div class="col-sm-6 col-md-3">
			<img class="themeChange" data-status="0" data="9" src="<?php echo URL_BASE; ?>public/backgrounds/9.jpg" />
		</div>
		
		<div class="col-sm-6 col-md-3">
			<img class="themeChange" data-status="0" data="10" src="<?php echo URL_BASE; ?>public/backgrounds/10.jpg" />
		</div>
		
		<div class="col-sm-6 col-md-3">
			<img class="themeChange" data-status="0" data="11" src="<?php echo URL_BASE; ?>public/backgrounds/11.jpg" />
		</div>
		
		<div class="col-sm-6 col-md-3">
			<img class="themeChange" data-status="0" data="12" src="<?php echo URL_BASE; ?>public/backgrounds/12.jpg" />
		</div>
		
		<div class="col-sm-6 col-md-3">
			<img class="themeChange" data-status="0" data="13" src="<?php echo URL_BASE; ?>public/backgrounds/13.jpg" />
		</div>
		
		<div class="col-sm-6 col-md-3">
			<img class="themeChange" data-status="0" data="14" src="<?php echo URL_BASE; ?>public/backgrounds/14.jpg" />
		</div>
		
		<div class="col-sm-6 col-md-3">
			<img class="themeChange" data-status="0" data="15" src="<?php echo URL_BASE; ?>public/backgrounds/15.jpg" />
		</div>
	*/ ?>	
	</div><!-- row -->
	
	 </div><!-- panel-body -->
</div><!-- panel -->

<div class="panel panel-default">
  <div class="panel-heading grid-panel-title">
    <h3 class="panel-title"><?php echo $_SESSION['LANG']['choose_theme']; ?></h3>
  </div>
  <div class="panel-body">
     
     <form class="form-horizontal" role="form" action="<?php echo URL_BASE; ?>public/ajax/upload_bg.php" method="POST" id="formBg" accept-charset="UTF-8" enctype="multipart/form-data">

			  <div class="form-group">
			    <label for="exampleInputFile" class="col-sm-2 control-label input-sm"><?php echo $_SESSION['LANG']['background']; ?> 2MB</label>
			    
			    <div class="col-sm-10">
			    	
			    	<?php 
			if( $this->infoSession->bg != '' ):
				$bg = 'background-image: url('.URL_BASE.'thumb_fixed/100-100-public/backgrounds/'.$this->infoSession->bg.');';
				endif;
				?>
				
		<div class="labelAvatar" style="width:128px; height: 128px; border-radius: 5px; background-size: cover; background-position: 50% 50%; margin-bottom: 10px; background-color: #000; <?php echo $bg; ?>">
		
	    	<?php if( $this->infoSession->bg != '' ): ?>
			<div class="deleteBg" data-id="<?php echo $this->infoSession->bg; ?>" style="background-size: cover; background: none; cursor: pointer;" title="<?php echo $_SESSION['LANG']['delete_image']; ?>" id="loader_gif_1"></div>
			<?php endif; ?>
			</div>
	    	
	    	<button type="button" class="btn btn-default btn-border btn-sm" id="upload-bg-btn">
	    		<i class="icon-camera"></i> <?php echo $_SESSION['LANG']['choose_theme']; ?>
	    		</button>
	    		 <input type="file" name="photo" id="upload_bg" accept="image/*" style="visibility: hidden;">
		   
	    </div>
		  	</div><!-- **** form-group **** -->
		
			</form><!-- **** form **** -->
			
			<hr />
			
			<!-- ******************* Settings **************************** -->
			<form class="form-horizontal" role="form" action="" method="POST" id="formSettings">
			  
			  <?php if( $this->infoSession->bg_attachment == 'fixed' ) {
					$checkBox = 'checked="checked"';
				} ?>
			 <!-- **** form-group **** -->
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm"><?php echo $_SESSION['LANG']['mosaic_background']; ?></label>
			    <div class="col-sm-10">
			    	<label class="checkbox-inline">
					  <input class="no-show" <?php echo $checkBox; ?> type="checkbox" value="fixed" name="mosaic" id="mosaic" value="" />
					</label>
					
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <!-- **** form-group **** -->
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm"><?php echo $_SESSION['LANG']['pos_background']; ?></label>
			    <div class="col-sm-10">
			    	<label class="radio-inline">
			    	<input class="radioIn no-show" id="radio_left" type="radio" value="left" name="pos" />
					 <span class="input-sm"><?php echo $_SESSION['LANG']['left']; ?></span>
					</label>
					<label class="radio-inline">
					<input class="radioIn no-show" id="radio_center" type="radio" value="center" name="pos" />
					 <span class="input-sm"><?php echo $_SESSION['LANG']['center']; ?></span>

					</label>
					<label class="radio-inline">
					 <input class="radioIn no-show" id="radio_right" type="radio" value="right" name="pos" /> 
					   <span class="input-sm"><?php echo $_SESSION['LANG']['right']; ?></span>
					</label>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			   <div class="form-group">
			    <label class="col-sm-2 control-label input-sm"><?php echo $_SESSION['LANG']['color_link']; ?></label>
			    <div class="col-sm-10">
			    	<input readonly="readonly" style="text-indent: -9999px; width:auto; opacity: 1; cursor: default; border: 1px solid #DDD; color:#FFF; float: left; background: <?php echo $this->infoSession->color_link; ?>" type="text" class="color form-control input-sm" id="link" value="" />
					<input type="hidden" value="<?php echo $this->infoSession->color_link; ?>" id="linkData" name="link" />
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group">
			    <label class="col-sm-2 control-label input-sm"><?php echo $_SESSION['LANG']['color_background']; ?></label>
			    <div class="col-sm-10">
			    	<input readonly="readonly" style="text-indent: -9999px; width:auto; opacity: 1; cursor: default; border: 1px solid #DDD; color:#FFF; float: left; background: <?php echo $this->infoSession->bg_color; ?>" type="text" class="color form-control input-sm" id="bg_color" value="" />
					<input type="hidden" value="<?php echo $this->infoSession->bg_color; ?>" id="bgData" name="bg_color" />
				</div>
			  </div><!-- **** form-group **** -->
			  
			   <div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <button  id="editProfile" type="submit" class="btn btn-info btn-sm profile-settings-design"><?php echo $_SESSION['LANG']['save']; ?></button>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  </form>
     
  </div><!-- panel-body -->
</div><!-- panel -->