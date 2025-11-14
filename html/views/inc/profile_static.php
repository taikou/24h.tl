<!-- Start Panel -->
      	<div class="panel panel-default">

    				<div class="panel-body padding-top padding-top-zero padding-right-zero padding-left-zero">
    					
    					<?php if( $this->infoSession->cover_image != '' ) : ?>
    					
    					<div class="cover-wall" style="background: url(<?php echo URL_BASE.'public/cover/'.$this->infoSession->cover_image; ?>) no-repeat center center <?php echo $this->infoSession->color_link; ?>; background-size: cover;"></div>
    					
    					<?php 
else: ?>
	<div class="cover-wall" style="background: <?php echo $this->infoSession->color_link; ?>; background-size: cover; height: 50px;"></div>
    					
<?php
    					endif; ?>
    					<div class="media media-visible pd-right">
						  <a class="pull-left photo-card-live myprofile" href="<?php echo URL_BASE.$this->infoSession->username; ?>">
						    <img src="<?php echo URL_BASE; ?>thumb/160-160-public/avatar/<?php echo $this->infoSession->avatar; ?>" alt="Image" class="border-image-profile img-rounded photo-card" width="80" />
						  </a>
						  <div class="media-body">
						    <h1 class="user-name-profile-card">
						    	<a href="<?php echo URL_BASE.$this->infoSession->username; ?>" class="myprofile">
						    		<span class="word-brk"><?php echo stripslashes( $this->infoSession->name ); ?></span>
						    		</a>
    								
    								<?php if( $this->infoSession->type_account == '1' ) : ?>
    								
    								<i class="fa fa-check-circle verified verified-sm showTooltip" title="<?php echo $_SESSION["LANG"]["verified"]; ?>" data-toggle="tooltip" data-placement="right"></i>
    								
    								<?php endif; ?>
    								
    								</h1>
    								<p class="text-col">
    									<small class="ajaxUsernameUi">@<?php echo $this->infoSession->username; ?></small>
    								</p>
    								
						  </div>
						</div>

    			
			    		<ul class="nav list-inline nav-pills btn-block nav-user-profile-wall">
			    			<li><a href="<?php echo URL_BASE.$this->infoSession->username; ?>" class="myprofile"><?php echo $_SESSION['LANG']['posts']; ?> <small class="btn-block sm-btn-size counter-sm"><?php echo _Function::formatNumber( $this->dataPosts->totalPosts ); ?></small></a></li>
			    			<li><a href="<?php echo URL_BASE.$this->infoSession->username.'/followers'; ?>" class="AjaxlinkFollowers"><?php echo $_SESSION['LANG']['followers']; ?> <small class="btn-block sm-btn-size counter-sm"><?php echo _Function::formatNumber( $this->dataFollowers->totalFollowers ); ?></small></a></li>
			    			<li><a href="<?php echo URL_BASE.$this->infoSession->username.'/following'; ?>" class="AjaxlinkFollowing"><?php echo $_SESSION['LANG']['following']; ?> <small class="btn-block sm-btn-size counter-sm"><?php echo _Function::formatNumber( $this->dataFollowing->totalFollowing ); ?></small></a></li>
			    		</ul>
			    		
			    
    				</div><!-- Panel Body -->
    			</div><!--./ Panel Default -->