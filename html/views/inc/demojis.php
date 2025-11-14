<?php
if($_SESSION['debug'] or 1){
	?>

     	<div class="form-group">
     		<div class="wrap_autocomplete">
     			<input type="text" id="demoji_keyword" name="demoji_keyword" class="form-control search-cls" placeholder="Keyword" style="padding-right: 10px !important; border-radius:30px; width:200px;" autocomplete="off">
     		
     			<div id="boxLogin" class="boxSearch boxAutoComplete" style="width: 315px; left: 0;">
						     <ul class="toogle_search">
										<li class="searchGlobal" style="margin-bottom: 5px;"></li>
										<span class="load_search"></span>
									</ul>
									</div><!-- BOX -->
						</div><!-- wrap -->
     		
     		<button type="submit" id="buttonSearch" style="top:10px; left:165px;"><i class="fa fa-search"></i></button>
     	</div><!--/.form-group -->

   	
<ul class="list-inline btn-block margin-zero" id="demoji_palet">
</ul>
<div id="demoji_recent_use" style="display: none;">
<p style="font-weight: bold;">Recent Use</p>	

<?
foreach($this->recentEmojis as $e){
	if($e['type']==0){
		$code=preg_replace('/\{#([0-9a-zA-Z]{4,6})\}/','https://i.decoo.jp/demj/$1',$e['code']);		
	}else{
		$code=preg_replace('/\{%([0-9a-zA-Z]{4,6})\}/','https://i.decoo.jp/mdec/$1',$e['code']);		
	}
	?>
	<li><img style="margin: 2px 0 5px;image-rendering:crisp-edges;" src="<?=$code?>" class="emoticons-ui" title="<?=$e['code']?>"  /></li>
	<?
}
?>	
</div>     	



<script>
$('#demoji_palet').html($('#demoji_recent_use').html());

  var $keyword = $('#demoji_keyword');
  var $resultList = $('#demoji_palet');
 
  // Ajax通信を行う関数
  function send(offset = 0){
  	if($keyword.val()==''){
		$resultList.html($('#demoji_recent_use').html());
	}else{
		$resultList.html('Loading...');
		$.ajax({
		  type: 'GET',
		  dataType: 'html',
		  url:'/public/ajax/demoji_search.php',
		  data: {
			keyword: $keyword.val(),
			offset: offset
		  },
		  success: function(data){
			  // 通信が成功したらリストを総入れ替え
			  $resultList.html(data);
				$('.emoticons-ui').click(function(){
						var smiley = $(this).attr('title');
						$('#add_post').val($('#add_post').focus().val()+" "+smiley+" ");
						$('#button_add').removeAttr('disabled').css({'opacity':'1','cursor':'pointer'});
				});

			}
		});
	}
  };
 $(function(){  

  var send_timeout_id = null;
  $keyword.on('keyup', function(){
    // keyupされた時点で既にsendの実行が予約されていたら一旦削除
    if(send_timeout_id){
      clearTimeout(send_timeout_id);
    }
    // 500ms後にsendを実行するように予約
    send_timeout_id = setTimeout(send, 1000);
  });
});

</script>  
	<?
}

