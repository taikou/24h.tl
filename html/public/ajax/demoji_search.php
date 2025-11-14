<?php
$keyword=trim($_REQUEST['keyword']);
$offset=$_REQUEST['offset'];
$limit=40;
if(!$offset or !is_numeric($offset) or $offset<0)$offset=0;
if($keyword){
	$ret=file_get_contents('https://i.decoo.jp/apis/v1/demj.php?keywords='.urlencode($keyword).'&offset='.$offset.'&limit='.$limit);
	if($ret){
		$ret=json_decode($ret);
		foreach($ret->datas as $d){
			$emojis[]=$d;
		}
	}
}
	?>
<p style="font-weight: bold;">
　<?=htmlspecialchars($keyword)?>
</p>	
	<?
if(count($emojis)>0){
	?>
<p style="text-align: right;">
(<?=($offset*$limit+1)?>-<?=($offset*$limit+$ret->count)?> of <?=number_format($ret->count_all)?>)
</p>
	<?
	foreach($emojis as $e){
		?>
	<li><img style="margin: 2px 0 5px;image-rendering:crisp-edges;" src="<?=$e->url?>" class="emoticons-ui" title="{<?=$e->code?>}"  /></li>	
		<?
	}
	?>
	<div style="text-align: center;">
	<?
	if($offset>0){
		?>
		<span onclick="send(<?=($offset-1)?>);">&#60;&#60;&#60;Prev</span>　
		<?
	}
	if($ret->count>=$limit){
		?>
		　<span onclick="send(<?=($offset+1)?>);">Next&#62;&#62;&#62;</span>
		<?
	}
	?>
	</div>
	<?
}else{
	?>
	<div style="text-align: center">Not Found</div>
	<?
}
