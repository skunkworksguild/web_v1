<?php
require(dirname(__FILE__) . '/../../sw_include/sw_db.php');
date_default_timezone_set('America/New_York');

?>

<html>
<head>
<style type="text/css">

td.value {

	border-left: 1px solid #e5e5e5;

	border-right: 1px solid #e5e5e5;

	padding:0;

	border-bottom: none;

}

td {

	padding: 4px 6px;

	border-bottom:1px solid #e5e5e5;

	border-left:1px solid #e5e5e5;

	background-color:#fff;

}

body {

	font-family: Verdana, Arial, Helvetica, sans-serif;

	font-size: 80%;

}

td.value img {

	vertical-align: middle;

	margin: 5px 5px 5px 0;

}

table {

	background-image:url(bg_fade.png);

	background-repeat:repeat-x;

	background-position:left top;

	width: 33em;

}

</style>
</head>
<body>
<?php

/*
$lastThree = $now - 7889232;
$lastSix = $now - 15778463;
$lastYear = $now - 31556926;
*/

$patches = array( array("Seige of Orgrimmar (Tier16)", "9/10/13", "4/1/14"),
				  array("Throne of Thunder (Tier15)", "3/5/13", "9/10/13"),
				  array("Cata Dragon Soul (Tier13)", "11/29/11", "9/25/12"),
				  array("Cata Firelands (Tier12)", "6/28/11", "11/29/11"),
				  array("Cata BWD/TC/Tot4W (Tier11)", "12/7/10", "6/28/11"),
				  array("WotLK Icecrown Citadel (Tier10)", "12/08/09", "12/7/10"),
				  array("WotLK Crusader's Colosseum (Tier 9)", "8/04/09", "12/08/09"),
				  array("WotLK Ulduar (Tier 8)", "4/14/09", "8/04/09"),
				  array("WotLK Naxx/Malygos/Sarth (Tier 7)", "11/13/08", "4/14/09")
				);
				 

echo '<h1><b>Posts in the Raid Forums<b></h1>';

// keep showing every 6 months until we go past our set last date
foreach($patches as $raids){

	$sDate = strtotime($raids[1]);
	$eDate = strtotime($raids[2]);
	$rName = $raids[0];

	$query = "SELECT u.username, u.user_id, p.poster_id, p.forum_id, p.post_time, COUNT(p.poster_id) FROM phpbb_users u, phpbb_posts p 
			  WHERE u.user_id = p.poster_id
			  AND p.forum_id = 6
			  AND p.post_time > $sDate AND p.post_time < $eDate
			  GROUP BY u.username";
			  
	$result = $db->query($query);
	
	$max = 0;  // for determining length of bar
	$totalPosts = 0;
	
	for($i=0; $i < $result->num_rows; $i++){
		
		$row = $result->fetch_row();	
		$users[$row[0]] = $row[5];
		
		if($row[5] > $max){
			$max = $row[5];
		}
		
		$totalPosts += $row[5];
	}

	// sort the results
	arsort($users);	

	
	// Display!
	$lDate = date("j/M/Y", $lastYear);

	echo '<h2> Posts in Raid Forum for ' . $rName . "<br>";
	echo 'From ' . date("n/j/Y", $sDate) . ' to ' . date("n/j/Y", $eDate) . '(Total Posts: '. $totalPosts . ')</h3>';

	echo ' <table cellspacing="0" cellpadding="0">';

	foreach($users as $name => $posts){
		
		$bgWidth = ($posts / $max * 100) * 8;
		//$bgWidth = $bgWidth . "%";
		
		echo '<tr>';
		echo '<td>' . $name . '</td>';
		echo '<td class="value"><img src="chart.png" alt="" width="'. $bgWidth . '" height="16" />' . $posts . '(' . (int)($posts/$totalPosts * 100) . '%)</td>';
		echo '</tr>';
	}	
	 
	echo '</table>';
	
	unset($users);
}

?>

</body>
</html>