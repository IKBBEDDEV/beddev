<html lang="ja"><meta charset="UTF-8"><head><style>body a {color:#000000; text-decoration:none;}</style></head>
<body>
<?php
include("functions.php");
$db = "event.csv"; 
date_default_timezone_set("Asia/Tokyo");
$script = "http://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]";
$dJ = array("日","月","火","水","木","金","土");
$event_fld = array('dat','cst','xtr','gnr','evt','sub','art','ar2','dsc','vid','snd','gds','pkp','fly');
$i = 18;     // n-----number of items from database to have available (change according to context)
$pre = '<div style="border:1px dotted;padding:20px; margin-right:20px;">'; $post = '</div>';




$grabs = array();
$grabs = read_dbase($db,$event_fld,$i);






if (isset($_GET["event"])) {   ////  ---     get contents of event GET 
	echo $pre;
	$event_data = get_event($db,$event_fld,$_GET["event"]);

//	echo '<pre>'; print_r($event_data); echo '</pre>';

	$dat = date_create_from_format('Y-m-j H:i',$event_data[0]['dat']);    // date + time in date format
	echo '<b>'. $dJ[date_format($dat,"w")] .' '.date_format($dat,"n.j ").' '.$event_data[0]['evt'].'</b><br>';
	echo $event_data[0]['sub'] . "<br>"; 
	echo 'open: '.date_format($dat,"G:i").' ¥'.$event_data[0]['cst'].'<br>'; 
	echo '<br>';
	echo $event_data[0]['art'].'<br><br>';
	echo $event_data[0]['ar2'].'<br><br>';
	echo $event_data[0]['dsc'].'<br>';
//	echo '<br><img src="data/flyer/'.date_format($dat,"ymd").'.jpg"><br>';   ~~~~~~  IMAGE
	echo "<br><b>IK</b>E<b>B</b>UKURO time is <b>".date("H:i").'</b><br>'; time_left($dat);

	sns();
	echo $post;
}  

//  elseif (isset($_GET["genre"])) ... schedule ...search  ...else ;


else {

	$dat = date_create_from_format('Y-m-j H:i',$grabs[0]['dat']);
	$evt = $grabs[0]['evt'];
	echo $pre."DEFAULT TOP. Dynamic and/or random.".$post;
	echo $pre.'<b>NEXT</b><br>';  ////    NEXT    --  this should not appear if this event is shown main 
	echo '<a href="'. $script . '?event=' . substr($grabs[0]["dat"],0,10) . '"><div>';
	echo $dJ[date_format($dat,"w")] .' '.date_format($dat,"n.j ").' '.$evt;
	echo '<br><b>IK</b>E<b>B</b>UKURO time is <b>'.date("H:i").'</b><br>'; 
	time_left($dat);
	echo "</div></a>";
	echo $post;
	array_shift($grabs); 
}

echo $pre.'<b>COMING</b><br>';    ////   COMING   -----------------~~~~~~~~~~~~~~~
$g = count($grabs);
for ($h=0; $h<$g; $h++) {
	$dat = date_create_from_format('Y-m-j H:i',$grabs[$h]['dat']); 
	echo '<a href="'. $script . '?event=' . substr($grabs[$h]["dat"],0,10) . '"><div>' . $dJ[date_format($dat,"w")] . ' ' . date_format($dat,"n.j ") . ' ' . $grabs[$h]["evt"] . '. '; 
	time_left($dat); 
	echo '</div></a>';
}


echo $post.$pre;


foreach ($grabs as $grab)  {
	$genres[$grab['gnr']][]=[  //GENRES     add  genre to URL
	'dat'=>$grab['dat'],
	'url'=>($script.'?genre='.$grab['gnr'].'&event='.substr($grab['dat'],0,10)),
	'event'=>$grab['evt'],
	'artist'=>$grab['art']];   //  

	if ($grab['pkp'] =='TRUE') $pickup_all_data[] = $grab;        //PICKUP   (  ALL FIELDS GET SENT )
}

show_genre($genres,0,0); //(array with all genres key=>genre, which genre , how many items to show)
echo $post.$pre;
show_pick($pickup_all_data); 
echo $post;
?>	
<br>
</body>
</html>
