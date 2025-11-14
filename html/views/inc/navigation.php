<?php
if(!preg_match('/24h_timeline_tab/',$_SERVER['HTTP_USER_AGENT'])){ //tab版アプリではヘッダーナビ非表示
	//authenticated
	if( Session::get( 'authenticated' ) ) : 
		
		/* Notifications Messages */
		if( $this->notiMsg->total != 0 ) {
			$displayBlock  = ' style="display: block;"';
			$iNotification = ' style="display: inline-block;"';
		} else {
			$displayBlock  = null;
			$iNotification = null;
		}
		
		/* Notifications Interactions */
		if( $this->notiIntera->total != 0 ) {
			$_displayBlock = ' style="display: block;"';
			$_iNotification = ' style="display: inline-block;"';
		} else {
			$_displayBlock = null;
			$_iNotification = null;
		}
?>
<!-- navegation -->	
<!-- navbar -->  
<?php include 'views/inc/profile_summary_inc.php'; ?>
 <div class="navbar navbar-inverse navbar-fixed-top" role="navigation" id="menu-wrap">
 		
    	<!-- container -->  
      <div class="containerxxx">
      
      	<!-- navbar-header -->  
        <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        	
        	<?php if( isset( $totalNotify ) ) : ?>
        	<span class="notify"><?php echo $totalNotify; ?></span>
        	<?php endif; ?>
            
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo URL_BASE; ?>">
          <?php include 'views/inc/logo.php'; ?>
          	</a>
        </div><!-- navbar-header -->  
					
					<!-- collapse -->  
   <div class="collapse navbar-collapse" style="max-height: none;">
   	
       <ul class="nav navbar-nav navbar-right topbarxxx">
 
          
          
           <li class="dropdown">
          <a class="dropdown-toggle" id="dropdownMenu4" data-toggle="dropdown" href="javascript:void(0);">
          	<i class="fa fa-search"></i>  <span class="title-dropdown"><?php echo $_SESSION['LANG']['search_word']; ?></span>
          	</a> 
   
              <ul class="dropdown-menu dropdown-settings arrow-up-user" role="menu" aria-labelledby="dropdownMenu4" style="width: 200px; right: -15px;">
	
   	<form class="navbar-form navbar-left" role="search" id="search_engine" accept-charset="UTF-8" autocomplete="off" action="search/" method="get">
     	<div class="form-group">
     		
     		<div class="wrap_autocomplete">
     			<input type="text" id="btnItems" name="q" class="form-control search-cls mention" placeholder="<?php echo $_SESSION['LANG']['search_word']; ?>"  style="padding-right: 10px !important;">
     		
     			<div id="boxLogin" class="boxSearch boxAutoComplete" style="width: 315px; left: 0;">
						     <ul class="toogle_search">
										<li class="searchGlobal" style="margin-bottom: 5px;"></li>
										<span class="load_search"></span>
									</ul>
									</div><!-- BOX -->
						</div><!-- wrap -->
     		
     		<button type="submit" id="buttonSearch"><i class="fa fa-search"></i></button>
     	</div><!--/.form-group -->
     </form><!--./navbar-form -->
	   </ul>         
		   </li>
        
        
        
         <!-- Start Messages -->
         <li class="dropdown">
          <a href="<?php echo URL_BASE; ?>messages/" class="messages" title="<?php echo $_SESSION['LANG']['messages']; ?>">
          	<span class="notify" id="noti_msg"<?php echo $displayBlock; ?>><?php echo $this->notiMsg->total; ?></span>
          		<span class="fa fa-envelope"></span> <span class="title-dropdown"><?php echo $_SESSION['LANG']['messages']; ?></span>
          	</a> 
           </li><!--/ End Messages -->
           
        <!-- Start Discover -->
         <li class="dropdown">
          <a href="<?php echo URL_BASE; ?>discover/" title="<?php echo $_SESSION['LANG']['discover']; ?>">
          	<span class="hashtag-ico">#</span> <span class="title-dropdown"><?php echo $_SESSION['LANG']['discover']; ?></span>
          	</a> 
           </li><!--/ End Discover -->
           
           <!-- Start Notifications -->
         <li class="dropdown">
          <a href="<?php echo URL_BASE; ?>interactions/" title="<?php echo $_SESSION['LANG']['interactions']; ?>">
          	<span class="notify" id="noti_connect"<?php echo $_displayBlock; ?>><?php echo $this->notiIntera->total; ?></span>
          	<span class="icon-bell"></span> <span class="title-dropdown"><?php echo $_SESSION['LANG']['interactions']; ?></span>
          	</a> 
           </li><!--/ End Notifications -->
           
<? /*           
           <li class="dropdown">
          <a href="<?php echo URL_BASE.$this->infoSession->username; ?>" class="userAvatar myprofile">
          		<img src="<?php echo URL_BASE.'thumb/48-48-public/avatar/'.$this->infoSession->avatar; ?>" alt="User" class="img-rounded" width="24" height="24" /> 
          	<span class="title-dropdown"><?php echo $_SESSION['LANG']['my_profile']; ?></span>
          	</a> 
           </li><!--/ End Profile -->
*/ ?>           
           <!-- Start Settings -->
         <li class="dropdown">
 <? if($_SESSION['debug']){ ?>
                 

          	<a class="menu-top" id="sign-btn-focus" data-toggle="dropdown" href="#" style="font-size: 17px; padding:15px 2px;">
          		<i class="glyphicon glyphicon-user myicon-right"></i>
          	</a>       
          	   	
           <ul class="dropdown-menu menu-home-dropdown dropdown-home arrow-up" role="menu">
      	<ul>
      	<? 
		foreach($_SESSION['multilogin'] as $uid=>$uname){
			if($uid==$_SESSION['authenticated'])continue;
			?>
			<li>
				<button  class="change_account" onClick="alert('a');" data-user="<?=$uid?>">@<?=$uname?></button>
			</li>	
			<?
		}	
      	?>	
      	</ul>	        <!-- Start Login Form -->

      	<div class="panel panel-default panel-login bg-none border-none margin-zero">

          <div class="login-form bg-none">
          	
          	<form action="" method="post" name="form" id="signin_form" class="signInForm">
	            <div class="form-group">
	              <input type="text" class="form-control login-field login-input" name="user" id="user" placeholder="<?php echo $_SESSION['LANG']['placeholder_email_username']; ?>" title="<?php echo $_SESSION['LANG']['placeholder_email_username']; ?>">
	              <label class="login-field-icon fui-user" for="user"></label>
	            </div>
	
	            <div class="form-group">
	              <input type="text" class="form-control login-field login-input" name="password" id="password_signin" placeholder="<?php echo $_SESSION['LANG']['placeholder_pass']; ?>" title="<?php echo $_SESSION['LANG']['placeholder_pass']; ?>">
	              <label class="login-field-icon fui-lock" for="password_signin"></label>
	            </div>
	            
	            <div class="alert alert-danger" id="error" role="alert"></div>
	            <div class="alert alert-success" id="success_signin" role="alert"></div>
	            
	            <button type="submit" class="btn btn-primary btn-lg btn-block" id="buttonSignIn" data-wait="<?php echo $_SESSION['LANG']['please_wait']; ?>"><?php echo $_SESSION['LANG']['sign_in']; ?></button>
	            

	            

	            
          </form><!--./form -->
        </div><!-- /End panel-login -->
        
        </div>
        </ul>        
                 
 <? /*                
                  <a class="dropdown-toggle" id="dropdownMenu4" data-toggle="dropdown" href="javascript:void(0);">

          		<img src="<?php echo URL_BASE.'thumb/48-48-public/avatar/'.$this->infoSession->avatar; ?>" alt="User" class="img-rounded" width="24" height="24" /> <span class="title-dropdown"><?php echo $_SESSION['LANG']['settings']; ?></span>

</a> 
        	         	
           <ul class="dropdown-menu dropdown-settings arrow-up-user" role="menu" aria-labelledby="dropdownMenu4">
           		<li><a href="<?php echo URL_BASE.$this->infoSession->username; ?>" class="myprofile"><i class="glyphicon glyphicon-user myicon-right"></i> <?php echo $_SESSION['LANG']['my_profile']; ?></a></li>
				<li><a href="<?php echo URL_BASE; ?>profile/"><i class="glyphicon glyphicon-pencil myicon-right"></i> <?php echo $_SESSION['LANG']['edit_profile']; ?></a></li>
				<li><a href="<?php echo URL_BASE; ?>settings/"><i class="glyphicon glyphicon-cog myicon-right"></i> <?php echo $_SESSION['LANG']['settings']; ?></a></li>
					<li class="divider"></li>
				<li class="bottomList"><a class="logout"><i class="glyphicon glyphicon-log-out myicon-right"></i> <?php echo $_SESSION['LANG']['log_out']; ?></a></li>
*/ ?>
<? }else{  ?>
          		<a href="<?php echo URL_BASE.$this->infoSession->username; ?>?you" class="userAvatar myprofile">
           		<img src="<?php echo URL_BASE.'thumb/48-48-public/avatar/'.$this->infoSession->avatar; ?>" alt="User" class="img-rounded" width="24" height="24" /> <span class="title-dropdown"><?php echo $_SESSION['LANG']['settings']; ?></span>
         		</a>
<?  } ?>         				
           </ul><!--/.dropdown-menu -->
           </li><!--/ End Settings -->

           
       </ul><!--/.navbar-nav -->
        </div><!--/.nav-collapse -->
      </div><!--/.container -->
    </div><!--/. End NaBar -->
					
					
<?php
	   else :
		   
		   ?>

					
<!-- navbar -->  
<?php include 'views/inc/profile_summary_inc.php'; ?>
 <div class="navbar navbar-inverse navbar-fixed-top" role="navigation" id="menu-wrap">
    	<!-- container -->  
      <div class="container">
      
      	<!-- navbar-header -->  
        <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo URL_BASE; ?>">
          <?php include 'views/inc/logo.php'; ?>
          	</a>
        </div><!-- navbar-header -->  
   
   <!-- collapse -->  
   <div class="collapse navbar-collapse">
   	
   	<ul class="nav navbar-nav navbar-right">
       	
       	 <li class="dropdown">
          	<a class="menu-top" id="sign-btn-focus" data-toggle="dropdown" href="#" style="font-size: 17px; padding:15px 2px;">
          		<i class="glyphicon glyphicon-user myicon-right"></i> <?php echo $_SESSION['LANG']['sign_in']; ?>
          	</a>       
          	   	
           <ul class="dropdown-menu menu-home-dropdown dropdown-home arrow-up" role="menu">

        <!-- Start Login Form -->
      	<div class="panel panel-default panel-login bg-none border-none margin-zero">
      		
          <div class="login-form bg-none">
          	
          	<!-- recover form -->
          	<form style="display: none;" action="" method="post" name="form" id="recover_pass_form" class="recoverForm">
	            <div class="form-group">
	              <input type="text" class="form-control login-field login-input" name="email_recover" id="email_recover" placeholder="<?php echo $_SESSION['LANG']['placeholder_email_recover']; ?>" title="<?php echo $_SESSION['LANG']['placeholder_email_recover']; ?>">
	              <label class="login-field-icon fui-mail" for="email_recover"></label>
	            </div>
	            <div class="alert alert-danger" id="error_recover" role="alert"></div>
	            <div class="alert alert-success" id="success_recover" role="alert"></div>
	            
	            <button type="submit" class="btn btn-primary btn-lg btn-block" id="buttonRecoverPass"><?php echo $_SESSION['LANG']['send']; ?></button>
	            <a class="login-link-2 buttonBack" href="javascript:void(0);"><i class="fa fa-arrow-left"></i> <?php echo $_SESSION['LANG']['back']; ?></a>
	           
	         
          </form><!--./form -->
          
          	
          	<form action="" method="post" name="form" id="signin_form" class="signInForm">
	            <div class="form-group">
	              <input type="text" class="form-control login-field login-input" name="user" id="user" placeholder="<?php echo $_SESSION['LANG']['placeholder_email_username']; ?>" title="<?php echo $_SESSION['LANG']['placeholder_email_username']; ?>">
	              <label class="login-field-icon fui-user" for="user"></label>
	            </div>
	
	            <div class="form-group">
	              <input type="password" class="form-control login-field login-input" name="password" id="password_signin" placeholder="<?php echo $_SESSION['LANG']['placeholder_pass']; ?>" title="<?php echo $_SESSION['LANG']['placeholder_pass']; ?>">
	              <label class="login-field-icon fui-lock" for="password_signin"></label>
	            </div>
	            
	            <div class="alert alert-danger" id="error" role="alert"></div>
	            <div class="alert alert-success" id="success_signin" role="alert"></div>
	            
	            <button type="submit" class="btn btn-primary btn-lg btn-block" id="buttonSignIn" data-wait="<?php echo $_SESSION['LANG']['please_wait']; ?>"><?php echo $_SESSION['LANG']['sign_in']; ?></button>
	            
	            <?php if( YOUR_CONSUMER_KEY != '' || APP_ID != '' ) : ?>
	             <span class="login-link-2"><?php echo $_SESSION['LANG']['or_sign_in_with']; ?></span>
	            <?php endif; ?> 
	             
	             <?php if( APP_ID != '' ) : ?>
	           	<div class="facebook-login">
	           		<a href="<?php echo URL_BASE; ?>oauth/?oauth_provider=facebook" class="fb-button btn btn-block btn-lg btn-primary"><i class="fa fa-facebook"></i> 
	           	facebook
	         </a>
	           	</div>
	           	<?php endif; ?>
	           	
	           	<?php if( YOUR_CONSUMER_KEY != '' ) : ?>
					<div class="facebook-login">
						<a href="<?php echo URL_BASE; ?>oauth/?oauth_provider=twitter" class="btn btn-block btn-lg btn-info"><i class="fa fa-twitter"></i> Twitter</a>
					</div>
					
					
					<?php endif; ?>
	            
	            <a class="login-link-2 buttonForgot" href="javascript:void(0);"><?php echo $_SESSION['LANG']['placeholder_forgot_pass']; ?></a>
	            
	            <div class="btn-block text-center">
	            	<label class="checkbox-inline">
					  <input type="checkbox" value="1" name="Keeplogged" id="Keeplogged" tabindex="3"> 
					   <span class="label-terms-2"><?php echo $_SESSION['LANG']['keep_logged']; ?></span>
					</label>
	            </div>
	            
	          </div><!-- login-form -->
          </form><!--./form -->
        </div><!-- /End panel-login -->
        
        		</ul>
           </li>

        
                
                        
           <?php if( NEW_REGISTRATIONS == 1 ) : ?>
        <!-- Start Regist Form -->
         	 <li class="dropdown" id="regnavmenu">
          	<a class="menu-top" id="sign-btn-focus" data-toggle="dropdown" href="#" style="font-size: 17px; padding:15px 10px;">
          		<i class="glyphicon glyphicon-edit myicon-right"></i> <?php echo $_SESSION['LANG']['sign_up']; ?>
          	</a>       
          	   	
           <ul class="dropdown-menu menu-home-dropdown dropdown-home arrow-up" role="menu">

       	<div class="panel panel-default panel-login bg-none border-none margin-zero">
          <div class="login-form bg-none">
          	<form action="" method="post" name="form" id="signup_form">
            <div class="form-group">
              <input type="text" class="form-control login-field login-input" value="" name="full_name" id="full_name" placeholder="<?php echo $_SESSION['LANG']['full_name']; ?>" title="<?php echo $_SESSION['LANG']['full_name']; ?>">
              <label class="login-field-icon fui-user" for="full_name"></label>
            </div>
            
            <div class="form-group">
              <input type="text" class="form-control login-field login-input" name="username" id="username" placeholder="<?php echo $_SESSION['LANG']['username']; ?>" minlength="4" maxlength="15" title="<?php echo $_SESSION['LANG']['username']; ?>">
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
			<span class="login-link2" id="twitter-btn-text"><?php echo $_SESSION['LANG']['or_sign_up_with']; ?></span>
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
        </div><!-- /End Regist Form --> 
        
        <?php endif; ?>
                             
                                        
                                     
         </ul><!--/.navbar-nav -->
        </div><!--/.nav-collapse -->
      </div><!--/.container -->
    </div><!--/. End NaBar -->
<?php endif; ?>

<? }else{ ?>
<style>
body {padding-top: 0 !important; margin-top: -20px !important;}
</style>
<? } ?>
