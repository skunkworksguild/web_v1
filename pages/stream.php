<?php
// Available Streams
//$stream_list = "aerivore,arthureld,paralys,navek1408,graffin206,snowhdtv,netphics,vemptastic";
$stream_list = "aerivore,paralys,snowhdtv,vemptastic";

//$jirral = 'warlock_bw.png';
//$raigon = 'warrior_bw.png';
//$rhowr  = 'deathknight_bw.png';
$vemp = 'warlock_bw.png';
$phics = 'hunter_bw.png';
$kaiwen = 'priest_bw.png';
$drcool = 'shaman_bw.png';
$inf = 'druid_bw.png';
//$hotstreak = 'mage_bw.png';
$aeri = 'druid_bw.png';
//$olducu = 'druid_bw.png';
$graffin = 'rogue_bw.png';
$snow = 'hunter_bw.png';



function getJson($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.2) Gecko/20070319 Firefox/2.0.0.3");
	$url_string = curl_exec($ch);
	curl_close($ch);

	$data = json_decode($url_string, true);
	
	//error_log(print_r($data));

	return $data;
 }


//Build the URL 
$url = "https://api.twitch.tv/kraken/streams?channel=$stream_list";
//https://api.twitch.tv/kraken/streams?channel=aerivore,arthureld,hotstreak01,paralys,navek1408,olducu,graffin206,snowhdtv

$result = getJson($url);

if($result != null || $result != false){
	foreach($result['streams'] as $s) {
	
		//error_log(print_r($s));
	
		switch($s['channel']['name']){
			case 'paralys':
				$inf = 'druid.png';
				break;
			case 'arthureld':
				$drcool = 'shaman.png';
				break;	
			case 'aerivore':
				$aeri = 'druid.png';
				break;	
			case 'graffin206':
				$graffin = 'rogue.png';
				break;	
			case 'snowhdtv':
				$snow = 'hunter.png';
				break;
			case 'netphics':
				$phics= 'hunter.png';
				break;
			case 'vemptastic':
				$vemp = 'warlock.png';
				break;
		default:
				break;
		}

	}
}


?>

<div class="sideSection">
  <div class="title">Live Streams</div>
  <div class="sectionRight">
    <div class="streamList">
 <?php 
	// Feel free to add more
	// Aerivore
	echo '<div class="streamEntry"><a href="http://www.twitch.tv/aerivore"><img src="images/' . $aeri . '" width="40" height="40">Aerivore</a></div>';
	// Drcool
	//echo '<div class="streamEntry"><a href="http://www.twitch.tv/arthureld"><img src="images/' . $drcool . '" width="40" height="40">Drcool</a></div>';
	// Graffin
	//echo '<div class="streamEntry"><a href="http://www.twitch.tv/graffin206"><img src="images/' . $graffin. '" width="40" height="40">Graffin</a></div>';
	// Inf
	echo '<div class="streamEntry"><a href="http://www.twitch.tv/paralys"><img src="images/' . $inf . '" width="40" height="40">Infinitum</a></div>';
  ?>
   </div>
   <br>
   <div class="streamList">
   <?php
	// Phics
	//echo '<div class="streamEntry"><a href="http://www.twitch.tv/netphics"><img src="images/' . $phics. '" width="40" height="40">Phics</a></div>';
	// Snow
	echo '<div class="streamEntry"><a href="http://www.twitch.tv/snowhdtv"><img src="images/' . $snow . '" width="40" height="40">Snowz</a></div>'; 
	// Vemp
	echo '<div class="streamEntry"><a href="http://www.twitch.tv/vemptastic"><img src="images/' . $vemp . '" width="40" height="40">Vemp</a></div>'; 
   ?>
   </div>
  </div>
</div>
  
