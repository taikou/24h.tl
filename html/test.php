<script>
var ss = sessionStorage;
if(ss.getItem('html')){
	document.write(ss.getItem('html'));
	console.log('session');
}else{
	var req = new XMLHttpRequest();
	req.onreadystatechange = function() {

	  if (req.readyState == 4) { // 通信の完了時
		if (req.status == 200) { // 通信の成功時
			document.write(req.responseText);
			console.log('ajax');
			ss.setItem('html',req.responseText);
		}
	  }else{
	 //   result.innerHTML = "loading...";
	  }
	}
	req.open('GET', '/', true);
	req.send(null);
}
</script>