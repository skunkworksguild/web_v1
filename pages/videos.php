<?php
 // Google Api
require(dirname(__FILE__) . '/../../sw_include/sw_google_api.php');

  
  // Tier 17
  //$playlist_id = 'PLvMdibkDukCQd03lnmHpfI06g6qj0dNL-';
  
  // Tier 18
  $playlist_id = 'PLvMdibkDukCR_bHDwXQPU90Ybr2dqxG6W';
  
  $feedURL = 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId='. $playlist_id . '&key=' . $google_api_key;
  $url_string = file_get_contents($feedURL);
  $playlistData = json_decode($url_string, true);

?>
<div class="sideSection">
  <div class="title"><a href="http://www.youtube.com/user/skunkworksbalnazzar">Videos</a></div>
  <div class="sectionRight">
  <?php 

	foreach ($playlistData['items'] as $video){
	
		// url example w/playlist: https://www.youtube.com/watch?v=bCALg0wJyD4&index=2&list=PLvMdibkDukCQd03lnmHpfI06g6qj0dNL-
		$url = 'https://www.youtube.com/watch?v=' . $video['snippet']['resourceId']['videoId'] . '&index=' . $video['snippet']['position'] . '&list=' . $playlist_id;
		$title = $video['snippet']['title'];
		$thumbnail = $video['snippet']['thumbnails']['medium']['url'];  // default is 120px wide
	
	
		
		echo '<div class="videoThumb"><a href="'.$url.'"><img src="'.$thumbnail.'"></a></div>'."\n";                             
		echo '<div class="videoTitle">'.$title.'</div>'."\n"; 
	
	
	}

  ?>
  
  </div>
</div>
