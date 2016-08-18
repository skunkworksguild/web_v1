<?php
// Guild Summary

// Making sure the date is correct
date_default_timezone_set("America/Detroit");   
$dir = scandir('logs');

?>

<div class="entireSection">
    <div class="title">
		<div class="newsSubject"><img src="images/gear6.png">Raw Log Files</div>
    </div>
   
    <div class="section">
	

    	<div class="statsTitle">
    		<h3><a href="logs/wcr/wowCardioRaidBeta.zip">Download WoWCardioRaid (for WoW 6.01)</a></h3>
    	</div>

  		<ul>
  		<?php

		// loop through the files in the directory
		foreach($dir as $file){

			// Write a filter
			if( !strpos($file, ".zip") == false ){
				echo '<li><a href="logs/' . $file . '">' . $file . '</a></li>';
			}
		}
		?>

		</ul>
    </div>
</div>