<?php
require(dirname(__FILE__) . '/../../sw_include/sw_db.php');

date_default_timezone_set('America/New_York');
// Show current time

echo "<h1>SKUNKWORKS RAID TOPIC VISITS</h1>";
echo "Current Time/Date - " . date("n/d/Y - g:i:s A", time());


// loop through all the topics_ids in forum 73 (Tier 17)

$query = "SELECT topic_id, topic_title FROM phpbb_topics where forum_id = 73";
$result = $db->query($query);
$num_rows = $result->num_rows;

for($i=0; $i<$num_rows; $i++){

	$row = $result->fetch_assoc();	

	// Echo out the topic name
	echo "<b><h4>" . $row['topic_title'] . "</b></h4>";


	// Loop through all the users of this topic and put their last visit time
	$q2 = "SELECT u.username, rf.last_viewed FROM phpbb_rf_track rf, phpbb_users u WHERE u.user_id = rf.user_id AND topic_id =" . $row['topic_id'];
	$r2 = $db->query($q2);
	$num_rows2 = $r2->num_rows;
	
	echo '<table>';
	for($j = 0; $j < $num_rows2; $j++){
	
		$row2 = $r2->fetch_assoc();	
	
		echo '<tr><td>' . $row2['username'] . '</td><td>' . date("n/d/Y - g:i:s A", $row2['last_viewed']) . '</td></tr>';

	}
	echo '</table>';

}

?>