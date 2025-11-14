
<script src="build/qcode-decoder.min.js"></script>

<img id='image' src="https://24h.tl/upload/47yevxl0qbyr8orn.png">
<script>
	var image =
  document.getElementById("image");

QCodeDecoder()
  .decodeFromImage(image,function(er,res){
  console.log(er);
    console.log(res);
  });
</script>



<?
exit;
ini_set('memory_limit', '-1');

include '/home/homepage/webs/24h.tl/html/public/qr/vendor/autoload.php';
$QRCodeReader = new Libern\QRCodeReader\QRCodeReader();
$qrcode = $QRCodeReader->decode('https://steemitimages.com/DQmRp3dpt2bUyw67uaNYyzQkcicp2fu7g9s5k2vD2uUuVXC/qr%20code.png');
print 'decode:'.$qrcode;




exit;
?>





<script src="dist/jsQR.js?"></script>
<script>

function test() {

    var data = createImageData(document.getElementById('test_img'));

 //   document.getElementById('test_canvas').getContext('2d').putImageData(data, 0, 0);

	console.log(data.length);
//	data.length=225*225*4; 
length = 225*225;
var data2=createImageData(document.getElementById('test_img'));
var add=0;
// set every fourth value to 50
for(var i=0; i < length; i+=1){
	data2[i]=data[i];
	if(i%3==2){
		add++;
		data2[(i+add)]=50;
	}
  //  data[i] = 50;
}
	console.log(data2);
	const code = jsQR(data2,225,225);
	console.log(code);
}

function createImageData(img) {

    var cv = document.createElement('canvas');

    cv.width = img.naturalWidth;
    cv.height = img.naturalHeight;

    var ct = cv.getContext('2d');

    ct.drawImage(img, 0, 0);

//	var code = jsQR(ct,200,200);
//	alert(code);

    var data = ct.getImageData(0, 0, cv.width, cv.height);

    return data;

}

</script>

<p>
<img src="data:image/png;base64,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" id="test_img">
<button onclick="test()">draw</button>
</p>

<p>
<canvas id="test_canvas" width=225 height=225 style="border: 1px solid;"></canvas>
</p>


<?php





exit;
require_once('../../class_ajax_request/classAjax.php');
include_once('../../application/functions.php'); 

if(_Function::check_sns_instagram('yamada','_taikou')){
	print 'instagram: OK';
}else{
	print 'instagram: NG';
}
print '<br>';

if(_Function::check_sns_twitter('yamada','_daayama')){
	print 'twitter: OK';
}else{
	print 'twitter: NG';
}
print '<br>';


exit;
?>
枠付き大 ／ <script src="https://source.pixiv.net/source/embed.js" data-id="69307261" data-size="large" data-border="on" charset="utf-8"></script>
枠付き中 ／ <script src="https://source.pixiv.net/source/embed.js" data-id="69307261" data-size="medium" data-border="on" charset="utf-8"></script>
枠付き小 ／ <script src="https://source.pixiv.net/source/embed.js" data-id="69307261" data-size="small" data-border="on" charset="utf-8"></script>
枠無し大 ／ <script src="https://source.pixiv.net/source/embed.js" data-id="69307261" data-size="large" data-border="off" charset="utf-8"></script>
枠無し中 ／ <script src="https://source.pixiv.net/source/embed.js" data-id="69307261" data-size="medium" data-border="off" charset="utf-8"></script>
枠無し小 ／ <script src="https://source.pixiv.net/source/embed.js" data-id="69307261" data-size="small" data-border="off" charset="utf-8"></script>
