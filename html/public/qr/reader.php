<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Demo</title>
    </head>
    <body>
        <h3>Simple initalization with default settings</h3>
        <h3>And decode uploaded image.</h3>
        <hr>
        <canvas with="500" height="500"></canvas>
        <hr>
       <input type="button" id="upload" value="upload" onclick="decodeLocalImage();">
        <ul></ul>
        <script type="text/javascript" src="js/filereader.js"></script>
        <script type="text/javascript" src="js/qrcodelib.js"></script>
        <script type="text/javascript" src="js/webcodecamjs.js?1"></script>
        <script type="text/javascript">
            var txt = "innerText" in HTMLElement.prototype ? "innerText" : "textContent";

			var arg = {
                resultFunction: function(result) {
                    var aChild = document.createElement('li');

			aChild[txt] = result.format + ': ' + result.code;
                    document.querySelector('body').appendChild(aChild);
                }
            };

  			 var options = {
				DecodeQRCodeRate: 5,                    // null to disable OR int > 0 !
				DecodeBarCodeRate: 5,                   // null to disable OR int > 0 !
				successTimeout: 500,                    // delay time when decoding is succeed
				codeRepetition: true,                   // accept code repetition true or false
				tryVertical: true,                      // try decoding vertically positioned barcode true or false
				frameRate: 15,                          // 1 - 25
				width: 500,                             // canvas width
				height: 500
			} ;        
			var decoder = new WebCodeCamJS("canvas").init(arg,options);
            function decodeLocalImage(){
                var ret=decoder.decodeLocalImage();
			//	alert(ret);
            }
        </script>
    </body>
</html>