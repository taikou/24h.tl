<?php 
error_reporting( 0 );
include('../../application/db.php'); 
include('../../application/DataConfig.php'); 
?>
<!DOCTYPE html>
<html lang="<?=$_SESSION['lang']?>">
  <head>
  	<base href="<?php echo URL_BASE; ?>" target="_top" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo URL_BASE; ?>public/img/favicon.ico">

    <title>Error 404 - <?php echo SITE_NAME; ?> </title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo URL_BASE; ?>public/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo URL_BASE; ?>public/css/main.css" rel="stylesheet">
    
    <!-- FONT Awesome CSS -->
    <link href="<?php echo URL_BASE; ?>public/css/font-awesome.min.css" rel="stylesheet">
    
     <!-- FLAT UI CSS -->
    <link href="<?php echo URL_BASE; ?>public/css/flat-ui.css" rel="stylesheet">
    
        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="<?php echo URL_BASE; ?>public/js/ie-emulation-modes-warning.js"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?php echo URL_BASE; ?>public/js/ie10-viewport-bug-workaround.js"></script>
    
     <link href='//fonts.googleapis.com/css?family=Fjalla+One' rel='stylesheet' type='text/css' />


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <!-- container -->
    <div class="container">
    <!-- row -->
    <div class="row col-pb">

      <div class="col-md-12 text-center">
      	<h6 class="icon-error"><img src="/public/img/1f47b.png" style="width: 90%; max-width: 400px;" ></h6>
      	<h1 class="title-error"><span>Error</span> 404</h1>
      	<p class="subtitle-error"><?=$_SESSION['LANG']['404']?></p>
      	<a class="btn btn-danger btn-border" href="./"><i class="fa fa-home myicon-right"></i> Go to 24h.timeline TOP</a>
     </div><!--/col-md-* -->

    	
  </div><!--************ Row ********************-->

      
    </div><!--******************** Container ******************-->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo URL_BASE; ?>public/js/jquery.min.js"></script>
    <script src="<?php echo URL_BASE; ?>public/js/bootstrap.min.js"></script>
    <script src="<?php echo URL_BASE; ?>public/js/bootstrap-switch.js"></script>
    

  </body>
</html>