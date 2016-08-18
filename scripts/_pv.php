<?php
require(dirname(__FILE__) . '/../../sw_include/sw_db.php');
date_default_timezone_set('America/New_York');

// Poll Voting Results

?>

<form action="" method="GET">
	Topic Id:<input type="text" name="tid">
	<br>
	<input type="submit" name="submit" value="Get Results">
</form>

<?php

$topicId = 0;

// Parse form results (if any)
if(isset($_GET['tid'])){


	$topicId = $_GET['tid'];

}





/*
phpbb_poll_votes

topic_id (int)
poll_option_id (int)
vote_user_id (int)



phpbb_poll_options

poll_option_id
topic_id 
poll_option_text (string)
poll_option_total (int)




phpbb_topics

topic_id (int)
topic_title (string)
poll_title (string)




phpbb_users

user_id (int)
username (string)
*/




// random variables

if($topicId > 0){

	echo 'Topic Id: ' . $topicId . '<br>';

	// Get the Poll title
 	$query = "SELECT t.topic_title, t.topic_id, t.poll_title 
 			  FROM phpbb_topics t
 			  WHERE t.topic_id = $topicId";

 	$result = $db->query($query);

 	if($result){
 		$row = $result->fetch_object();
 		echo "<H1>Topic Title: " . $row->topic_title . "</H1><br>" .
 	     "<H2>Poll Title: " . $row->poll_title . "</H2><br>";
 	}
 	else {

 		echo "ERROR - Could not get Post or Poll Title<br>";
 	}

 	
    // Get Poll Options
    $query = "SELECT o.topic_id, o.poll_option_id, o.poll_option_text
              FROM phpbb_poll_options o
              WHERE o.topic_id = $topicId
              ORDER BY o.poll_option_id";

    $result = $db->query($query);

	for($i=0; $i < $result->num_rows; $i++){

		// For each option figure out who voted for it
		$row = $result->fetch_object();
		$currentOptionText = $row->poll_option_text;
		$currentOptionId = $row->poll_option_id;


		echo "<b>" . $currentOptionText . "</b><br>";

		$query = "SELECT u.username, u.user_id, v.vote_user_id, v.poll_option_id, v.topic_id
		      FROM phpbb_users u, phpbb_poll_votes v
	          WHERE u.user_id = v.vote_user_id
			  AND v.topic_id = $topicId
			  AND v.poll_option_id = $currentOptionId
			  GROUP BY u.username";

		//echo $query . '<br>';

		$result2 = $db->query($query);

		for($j=0; $j < $result2->num_rows; $j++){

			$row2 = $result2->fetch_object();
			echo $row2->username . " ";

		}


		echo '<br><br>';

	}

}

?>