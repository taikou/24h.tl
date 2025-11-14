<?php
include 'vendor/autoload.php';
session_start();
//print_r($_SESSION);
?>
<link rel="stylesheet" href="/public/emojiarea/vendor/mervick/emojionearea/dist/emojionearea.min.css">
<script src="https://24h.tl/public/js/jquery.min.js"></script>
<script type="text/javascript" src="/public/emojiarea/vendor/mervick/emojionearea/dist/emojionearea.min.js"></script>

<br><br><br>
<textarea id="example1">
Lorem ipsum dolor 😍 sit amet, consectetur 👻 adipiscing elit, 🖐 sed do eiusmod tempor ☔ incididunt ut labore et dolore magna aliqua 🐬.
</textarea>
 <div id='example2'></div>
<script type="text/javascript">
  $(document).ready(function() {
    $("#example2").emojioneArea({
	  standalone: true,
	  pickerPosition: "bottom"
    });
  });
</script>


<?
$text='{#1fMnl}こんにちわ！#helloWorld https://twitcasting.tv/c:aizwmtmt　合言葉は私の推しCPをひらがなで';
preg_match_all('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', $text, $matches);
print_r($matches);


$url='https://twitcasting.tv/c:aizwmtmt/';
include '../../application/functions.php';
$html = _Function::file_get_contents_curl($url);
print htmlspecialchars($html);
?>