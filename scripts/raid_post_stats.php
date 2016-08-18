<?php
require(dirname(__FILE__) . '/../../sw_include/sw_db.php');

date_default_timezone_set('America/New_York');
$now = time();
$lastThree = $now - 7889232;
$lastSix = $now - 15778463;
$lastYear = $now - 31556926;

$totalPostsThree = 0;
$totalPostsSix = 0;
$totalPostsYear = 0;


// random variables
$maxThree = 0;

$query = "SELECT u.username, u.user_id, p.poster_id, p.forum_id, p.post_time, COUNT(p.poster_id) FROM phpbb_users u, phpbb_posts p 
          WHERE u.user_id = p.poster_id
		  AND p.forum_id = 6
		  AND p.post_time > $lastThree
		  GROUP BY u.username";
		  
$result = $db->query($query);
 
for($i=0; $i < $result->num_rows; $i++){
	
	$row = $result->fetch_row();	
	$usersThree[$row[0]] = $row[5];
	
	if($row[5] > $maxThree){
		$maxThree = $row[5];
	}
	
	$totalPostsThree += $row[5];
}

	// sort the results
	arsort($usersThree);	


// random variables
$maxSix = 0;

$query = "SELECT u.username, u.user_id, p.poster_id, p.forum_id, p.post_time, COUNT(p.poster_id) FROM phpbb_users u, phpbb_posts p 
          WHERE u.user_id = p.poster_id
		  AND p.forum_id = 6
		  AND p.post_time > $lastSix
		  GROUP BY u.username";
		  
$result = $db->query($query);
 
for($i=0; $i < $result->num_rows; $i++){
	
	$row = $result->fetch_row();	
	$usersSix[$row[0]] = $row[5];
	
	if($row[5] > $maxSix){
		$maxSix = $row[5];
	}
	
	$totalPostsSix += $row[5];
}

	// sort the results
	arsort($usersSix);	

// random variables
$maxYear = 0;

$query = "SELECT u.username, u.user_id, p.poster_id, p.forum_id, p.post_time, COUNT(p.poster_id) FROM phpbb_users u, phpbb_posts p 
          WHERE u.user_id = p.poster_id
		  AND p.forum_id = 6
		  AND p.post_time > $lastYear
		  GROUP BY u.username";
		  
$result = $db->query($query);
 
for($i=0; $i < $result->num_rows; $i++){
	
	$row = $result->fetch_row();	
	$users[$row[0]] = $row[5];
	
	if($row[5] > $maxYear){
		$maxYear = $row[5];
	}
	
	$totalPostsYear += $row[5];
}

	// sort the results
	arsort($users);
	
	

	
	
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

echo '<h1><b>Posts in the Raid Forums<b></h1>';

///////////////////Last Three Months

$lDate = date("j/M/Y", $lastThree);

echo '<h1> Last 3 Months</h1>';
echo '<h2> Posts in Raid Forum since ' . $lDate . '  (Total Posts: '. $totalPostsThree . ')</h2>';

echo ' <table cellspacing="0" cellpadding="0">';

foreach($usersThree as $name => $posts){
	
	$bgWidth = ($posts / $maxThree * 100) * 8;
	//$bgWidth = $bgWidth . "%";
	
	echo '<tr>';
	echo '<td>' . $name . '</td>';
	echo '<td class="value"><img src="chart.png" alt="" width="'. $bgWidth . '" height="16" />' . $posts . '(' . (int)($posts/$totalPostsThree * 100) . '%)</td>';
	echo '</tr>';
}	

echo '</table>';

echo '<br><br>';

///////////////////Last Six Months

$lDate = date("j/M/Y", $lastSix);

echo '<h1> Last 6 Months</h1>';
echo '<h2> Posts in Raid Forum since ' . $lDate . '  (Total Posts: '. $totalPostsSix . ')</h2>';

echo ' <table cellspacing="0" cellpadding="0">';

foreach($usersSix as $name => $posts){
	
	$bgWidth = ($posts / $maxSix * 100) * 8;
	//$bgWidth = $bgWidth . "%";
	
	echo '<tr>';
	echo '<td>' . $name . '</td>';
	echo '<td class="value"><img src="chart.png" alt="" width="'. $bgWidth . '" height="16" />' . $posts . '(' . (int)($posts/$totalPostsSix * 100) . '%)</td>';
	echo '</tr>';
}	

echo '</table>';

echo '<br><br>';

///////////////////Last Year

$lDate = date("j/M/Y", $lastYear);

echo '<h1> Last Year (12 Months)</h1>';
echo '<h2> Posts in Raid Forum since ' . $lDate . '  (Total Posts: '. $totalPostsYear . ')</h3>';

echo ' <table cellspacing="0" cellpadding="0">';

foreach($users as $name => $posts){
	
	$bgWidth = ($posts / $maxYear * 100) * 8;
	//$bgWidth = $bgWidth . "%";
	
	echo '<tr>';
	echo '<td>' . $name . '</td>';
	echo '<td class="value"><img src="chart.png" alt="" width="'. $bgWidth . '" height="16" />' . $posts . '(' . (int)($posts/$totalPostsYear * 100) . '%)</td>';
	echo '</tr>';
}	
 
echo '</table>';


?>

</body>
</html>