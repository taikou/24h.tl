<?php
/*
	//authenticated
	if( !Session::get( 'authenticated' ) && NEW_REGISTRATIONS == 1 ) : 
	 ?>

 <!-- Start Panel -->
      	<div class="panel panel-default">
    				<span class="panel-heading btn-block grid-panel-title">
    					<i class="icon-user myicon-right"></i> <?php echo $_SESSION['LANG']['title_sign_up']; ?>
    					</span>
    					
    				<div class="panel-body">
    				
    				<!-- Start Login Form -->
      	<div class="panel panel-default panel-login bg-none border-none margin-zero">
          <div class="login-form bg-none padding-zero">
          	<form action="" method="post" name="form" id="signup_form">
            <div class="form-group">
              <input type="text" class="form-control login-field login-input" value="" name="full_name" id="full_name" placeholder="<?php echo $_SESSION['LANG']['full_name']; ?>" title="<?php echo $_SESSION['LANG']['full_name']; ?>">
              <label class="login-field-icon fui-user" for="full_name"></label>
            </div>
            
            <div class="form-group">
              <input type="text" class="form-control login-field login-input" name="username" id="username" placeholder="<?php echo $_SESSION['LANG']['username']; ?>" title="<?php echo $_SESSION['LANG']['username']; ?>">
              <label class="login-field-icon fui-user" for="username"></label>
            </div>
            
            <div class="form-group">
              <input type="text" class="form-control login-field login-input" name="email" id="email" placeholder="<?php echo $_SESSION['LANG']['mail']; ?>" title="<?php echo $_SESSION['LANG']['mail']; ?>">
              <label class="login-field-icon fui-mail" for="email"></label>
            </div>
            
            <div class="form-group">
              <input type="password" class="form-control login-field login-input" name="password" id="password" placeholder="<?php echo $_SESSION['LANG']['placeholder_pass']; ?>" title="<?php echo $_SESSION['LANG']['placeholder_pass']; ?>">
              <label class="login-field-icon fui-lock" for="password"></label>
            </div>
            
            <div class="form-group">
              <input type="text" class="form-control login-field login-input" name="captcha" id="lcaptcha" placeholder="" title="">
              <label class="login-field-icon fui-lock" for="lcaptcha"></label>
            </div>
           
           <div class="alert alert-success" id="success" role="alert"></div>
           <div class="alert alert-danger" id="errorSignUp" role="alert"></div>
         
           <button type="submit" id="buttonSubmit" class="btn btn-block btn-lg btn-success"><?php echo $_SESSION['LANG']['sign_up']; ?></button>

<?php if( YOUR_CONSUMER_KEY != '' || APP_ID != '' ) : ?>
	
	<span class="login-link-2" id="twitter-btn-text"><?php echo $_SESSION['LANG']['or_sign_up_with']; ?></span>
	<?php endif; ?>
	
	<?php if( APP_ID != '' ) : ?>
	           	<div class="facebook-login">
	           		<a href="<?php echo URL_BASE; ?>oauth/?oauth_provider=facebook" class="fb-button btn btn-block btn-lg btn-primary"><i class="fa fa-facebook"></i> 
	           	facebook
	         </a>
	           	</div>
	           	<?php endif; ?>
	
	<?php if( YOUR_CONSUMER_KEY != '' ) : ?>
			
					<div class="facebook-login" id="twitter-btn">
						<a href="<?php echo URL_BASE; ?>oauth/?oauth_provider=twitter" class="btn btn-block btn-lg btn-info"><i class="fa fa-twitter"></i> Twitter</a>
					</div>
				<?php endif; ?>
				
					
    	<label class="checkbox-inline">
		  <input type="checkbox" value="1" name="terms" id="terms" tabindex="3" checked="checked"> 
		   <span class="label-terms-2"><?php echo $_SESSION['LANG']['terms']; ?></span>
		</label>

          </div>
          </form><!--./form -->
        </div><!-- /End Login Form -->
    				
    				</div><!-- Panel Body -->
    			</div><!--./ Panel Default -->
    			
<?php endif; //<<--- SESSION NULL

 if( isset( $this->settings->ad ) && $this->settings->ad != '' ): ?>
 
 <!-- Start Panel -->
      	<div class="panel panel-default">
    				<span class="panel-heading btn-block grid-panel-title">
    					<i class="icon-bullhorn myicon-right"></i> <?php echo $_SESSION['LANG']['advertising']; ?>
    					</span>
    					
    				<div class="panel-body">
    					
    				<div class="btn-df li-group img-responsive-alt">
    					<?php echo stripslashes( $this->settings->ad ); ?>
    				</div>
    				</div><!-- Panel Body -->
    			</div><!--./ Panel Default -->
		<?php endif; ?>
*/