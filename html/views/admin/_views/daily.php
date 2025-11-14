<?php

require_once '/home/homepage/webs/24h.tl/html/application/Config.php';
require_once '/home/homepage/webs/24h.tl/html/application/Db.php';
require_once '/home/homepage/webs/24h.tl/html/application/SPDO.php';

$this->db = SPDO::singleton();

$nowtime=time();
$y=date('Y',$nowtime);
$m=date('m',$nowtime);
$d=date('d',$nowtime);

$thetime=$nowtime;

for($i=0;$i<=120;$i++){

	$ymd=date('Ymd',$thetime);
	$y_m_d=date('Y-m-d',$thetime);
	
	
	$sql="SELECT count(*) as num FROM users WHERE date >= '{$y_m_d} 00:00:00' and date<='{$y_m_d} 23:59:59'";
	$r=$this->db->query($sql)->fetch();
	$data[$ymd]['regnum']=$r['num'];
//print $sql.'<br>';

	$sql="SELECT count(*) as num FROM posts WHERE date >= '{$y_m_d} 00:00:00' and date<='{$y_m_d} 23:59:59'";
	$r=$this->db->query($sql)->fetch();
	$data[$ymd]['postnum']=$r['num'];
//print $sql.'<br>';

	$sql="SELECT count(distinct user) as num FROM posts WHERE date >= '{$y_m_d} 00:00:00' and date<='{$y_m_d} 23:59:59'";
	$r=$this->db->query($sql)->fetch();
	$data[$ymd]['postusernum']=$r['num'];
//print $sql.'<br>';

	$sql="SELECT count(*) as num FROM favorites WHERE status<>0 and date >= '{$y_m_d} 00:00:00' and date<='{$y_m_d} 23:59:59'";
	$r=$this->db->query($sql)->fetch();
	$data[$ymd]['likenum']=$r['num'];
//print $sql.'<br>';


	$thetime-=(3600*24);
}

?>
<table border="1">
	<thead>
	<tr>
		<th>Date</th>
		<th>Regnum</th>
		<th>Postnum</th>
		<th>PostUsernum</th>
		<th>Likenum</th>
	</tr>
	</thead>
	<tbody>
	<?
foreach($data as $ymd=>$d){
		?>
	<tr>
		<td><?=$ymd?></td>
		<td><?=$d['regnum']?></td>
		<td><?=$d['postnum']?></td>
		<td><?=$d['postusernum']?></td>
		<td><?=$d['likenum']?></td>	</tr>		
		<?
}
	?>

	</tbody>
</table>


<script>

     google.charts.load('current', {'packages':['line']});
      google.charts.setOnLoadCallback(drawChartDaily);

    function drawChartDaily() {

      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Day');
      data.addColumn('number', 'Reg');
      data.addColumn('number', 'Post');
      data.addColumn('number', 'Like');

      data.addRows([
	<?
	ksort($data);
	  foreach($data as $ymd=>$d){ 
       $chartdata[]=" ['{$ymd}',  {$d['regnum']}, {$d['postnum']}, {$d['likenum']}]";
	 }
	echo join(",\n",$chartdata);
	?>
	
      ]);

      var options = {
        chart: {
          title: 'Daily Statistics',
          subtitle: ''
        },
        width: 900,
        height: 500
      };

      var chart = new google.charts.Line(document.getElementById('linechart_material'));

      chart.draw(data, google.charts.Line.convertOptions(options));
    }
	
	
</script>
<div id="linechart_material"></div>