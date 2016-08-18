<?php

//////////////////////////////////////////
//
// Skunkworks Character Parser
// Test
//
// Reference:
// http://blizzard.github.com/api-wow-docs/
// Keep in mind the doc here are not always 100% up to date with the actual function
// Use the examples at the bottom of this page for confirmation
//
// Note:
// There is currently a limit 3000 requests/day per site so consider that before purging item db.
// Idea is to have this run by a cron job.
// 
// Last edit:
// 
// 
//////////////////////////////////////////


//DB
require(dirname(__FILE__) . '/../../sw_include/sw_db.php');
// Blizzard API Key
require(dirname(__FILE__) . '../../sw_include/sw_api.php');

class Skunkworks_Character_Parser {

    // Blizzard Info
    private $armoryServer = 'https://us.api.battle.net';
    private $characterBaseName = '/wow/character/';
    private $guildBaseName = '/wow/guild/';
    private $itemBaseName = '/wow/item/';
	private $apiKey = ''; // Gets set below
	
    // WowHead
    private $wowheadImageBaseLarge = 'http://wow.zamimg.com/images/wow/icons/large/';
    private $wowheadImageBaseSmall = 'http://wow.zamimg.com/images/wow/icons/small/';
	
    // Skunkworks Specific
    private $realm = 'Stormreaver';
    private $guild = 'Skunkworks';
    private $numMembers = 0;
    private $raidingRoster = '';
	
    // Utility
	private $numRequests = 0;
	
    private $itemSlot = array('head', 'neck', 'shoulder', 'back', 'chest', 'shirt', 'tabard', 'wrist',
	'hands', 'waist', 'legs', 'feet', 'finger1', 'finger2', 'trinket1', 'trinket2',
	'mainHand', 'offHand', 'ranged'
    );
					  						   

	/////////////////////////////////////////////////////////////////
	//
	// Constructor 
	// 
	/////////////////////////////////////////////////////////////////
    public function __construct() {
		//echo 'Character Parser Created!';
    }


	/////////////////////////////////////////////////////////////////
	//
	// Decode the odd characters
	// 
	/////////////////////////////////////////////////////////////////
    public function utf8ArrayDecode($input) {
		$return = array();

		foreach ($input as $key => $val) {
		    if (is_array($val)) {
				$return[$key] = utf8_array_decode($val);
		    } else {
				$return[$key] = utf8_decode($val);
		    }
		}
		return $return;
    }

	/////////////////////////////////////////////////////////////////
	//
	// Retrieve the Api data and put it in an array
	//
	/////////////////////////////////////////////////////////////////
    public function getJson($url) {
	
	
		// Add the api key to all requests
		$url .= '&apikey=' . $this->apiKey;
		
		echo $url . '<br>';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.2) Gecko/20070319 Firefox/2.0.0.3");
		$url_string = curl_exec($ch);
		curl_close($ch);
		
		// Update # of requests
		$this->numRequests++;

		$data = json_decode($url_string, true);
		
		return $data;
    }
	
	public function addKey($key){
		$this->apiKey = $key;
	}



    public function getItem($id, $ct = "") {

		$url = $this->armoryServer . $this->itemBaseName . $id;
		
		if($ct != ''){
			$url .= '/' . $ct;
		}
		
		// Add
		$url .= "?local=us_en";
	
		echo "Item URL: " . $url . '<br>';

		
		$item = $this->getJson($url);

		
		echo '<pre>';
		print_r($item);
		echo '</pre>';

	}



	/////////////////////////////////////////////////////////////////
	//
	// Get the character and print out the resulting array
	// 
	///////////////////////////////////////////////////////////////// 
    public function getCharacter($name, $realm) {

		$characterRank = -1;
		
		// check the realm for a blank
		if($realm == ''){
			$realm = "Stormreaver";
		}
		
		$url = $this->armoryServer . $this->characterBaseName . $realm . '/' . $name;

		echo "Character URL: " . $url . '<br>';
		
		// Add extra stuff
		$url .= '?fields=guild,stats,feed,talents,items,reputation,titles,professions,appearance,companions,mounts,pets,achievements,progression,pvp';
		
		$character = $this->getJson($url);

		if(isset($character['status'])){
			if($character['status'] == 'nok'){
				echo 'Error accessing the Blizzard Armory.  Reason: ' . $character['reason'] . '<br>';
			}
			exit;
		}
		
		
		// Have to make a separate guild request for the guild rank -_-
		
		$url = $this->armoryServer . $this->guildBaseName . $this->realm . '/' . $this->guild;
		
		// Add extra stuff
		$url .= '?fields=members';
		
		$fullRoster = $this->getJson($url);

		if(isset($fullRoster['status'])){
			if($fullRoster['status'] == 'nok'){
				echo 'Error accessing the Blizzard Armory.  Reason: ' . $fullRoster['reason'] . '<br>';
			}
			exit;
		}
		
		//Filter
		foreach ($fullRoster['members'] as $raider) {
			if($raider['character']['name'] == $character['name']){
				$characterRank = $raider['rank'];
			}
		}
		
		echo 'Guild Rank: ' . $characterRank . '<br>';
		
		
		// Print the character out
		echo '<pre>';
		print_r($character);
		echo '</pre>';
	
	}


	
}
?>

<form action="" method="post">
Name:
<br>
<input type="text" name="character">
<br>
Realm:
<br>
<input type="text" name="realm">
<br>
Item:
<br>
<input type="text" name="item">
<br>
Item Context: (Optional for some items)
<br>
<input type="text" name="item-context">
<br>
<input type="submit" name="submit" value="submit">
</form>

<?php

echo '<pre>';
print_r($_POST);
echo '</pre>';




if(isset($_POST['character'])){

	$skunkworks = new Skunkworks_Character_Parser();
	
	$skunkworks->addKey($apikey);

	if ($_POST['character'] != ""){
		$skunkworks->getCharacter($_POST['character'], $_POST['realm'] );
	}
}


if(isset($_POST['item'])){

	$skunkworks = new Skunkworks_Character_Parser();
	$skunkworks->addKey($apikey);

	$skunkworks->getItem($_POST['item'], $_POST['item-context']);

}


?>