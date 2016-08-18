<?php

//////////////////////////////////////////
//
// Skunkworks Roster Parser (for PvP infos)
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
// 6/5/12 - Infinitum
// 
//////////////////////////////////////////

///////////////////////
//
// Current Members to Track! (Edit at will)
//
///////////////////////
//DB
require(dirname(__FILE__) . '/../../sw_include/sw_db.php');
// Blizzard API Key
require(dirname(__FILE__) . '../../sw_include/sw_api.php');

$pvpRosterBalnazzar = array ( "Neatpete",
			                 "Infinitum",
			                 "Ruju",
			                 "Rhowr",
			                 "Aydin",
			                 "Fiedler",
			                 "Deyis",
			                 "Medmal",
			                 "Kkyyllee",
			                 "Phics",
			                 "Dailyloving",
			                 "Kethas",
			                 "Soshifty",
			                 "Andeh",
			                 "Buttsk",
			                 "Hugginss",
			                 "Tashr",
			                 "Crafty" 
	                		 );

$pvpRosterOtherRealm = array ( 
					   	array ( "Snowhoe", "Wherever" ) 
	                    );


class Skunkworks_Armory_Parser_Pvp {

    // Where to store images
	// This has to be hard coded for cron to work
    private $iconFolder = '/home/skunkwrk/public_html/images/icons/';
	
    // Blizzard Info
    private $armoryServer = 'http://us.battle.net';
    private $characterBaseName = '/api/wow/character/';
    private $guildBaseName = '/api/wow/guild/';
    private $itemBaseName = '/api/wow/item/';
	
    // WowHead
    private $wowheadImageBaseLarge = 'http://wow.zamimg.com/images/wow/icons/large/';
    private $wowheadImageBaseSmall = 'http://wow.zamimg.com/images/wow/icons/small/';
	
    // Skunkworks Specific
    private $realm = 'Balnazzar';
    private $guild = 'Skunkworks';
    private $numMembers = 0;
    private $raidingRoster = '';
	
    // Utility
	private $numRequests = 0;
	
    private $itemSlot = array('head', 'neck', 'shoulder', 'back', 'chest', 'shirt', 'tabard', 'wrist',
	'hands', 'waist', 'legs', 'feet', 'finger1', 'finger2', 'trinket1', 'trinket2',
	'mainHand', 'offHand', 'ranged'
    );
	
	// Pvp Achievements!
	private $pvpAchievements =  array( 
							   		array( "Alterac Valley", 
										array(
											221,  // Alterac Grave Robber
										    582,  // Alterac Valley All-Star
											219,  // Alterac Valley Veteran
										    218,  // Alterac Valley Victory
										    1164, // Everything Counts						    
									        706,  // Frostwolf Howler
									        873,  // Frostwolf Protection
									        708,  // Hero of the Frostwolf Clan
									        224,  // Loyal Defender
									        1168, // Master of Alterac Valley
									        226,  // The Alterac Blitz
								            223,  // The Sickly Gazelle
			                                1166, // To the Looter Go the Spoils
			                                222   // Tower Defense
	                               		)
									),  
							   		array( "Arathi Basin",
										array(   
											583,  // Arathi Basin All-Star
			                                584,  // Arathi Basin Assassin
											165,  // Arathi Basin Perfection
											155,  // Arathi Basin Veteran
										    154,  // Arathi Basin Victory
										    73,   // Disgracin' The Basin
										    159,  // Let's Get This Done
										    1170, // Master of Arathi Basin
										    158,  // Me and the Cappin' Makin' it Happen
										    1153, // Overly Defensive
										    161,  // Resilient Victory
										    156,  // Territorial Dominance
										    710,  // The Defiler
										    157,  // To The Rescue!
										    162   // We Had It All Along *cough*
										)
									),   						   
									array( "The Battle for Gilneas", 
										array(      
											5256, // Battle for Gilneas All-Star
			                                5257, // Battle for Gilneas Assassin
											5247, // Battle for Gilneas Perfection
											5246, // Battle for Gilneas Veteran
											5245, // Battle for Gilneas Victory
										    5248, // Bustin' Caps to Make It Haps
										    5252, // Don't Get Cocky Kid
										    5262, // Double Rainbow
										    5253, // Full Coverage
										    5255, // Jugger Not
										    5258, // Master of the Battle for Gilneas
										    5254, // Newbs to Plowshares
										    5251, // Not Your Average PUG'er
											5249, // One Two Three You Don't Know About Me
											5250  // Out of the Fog
										)
									),
									array( "Eye of the Storm",
										array(
										    223,  // Bloodthirsty Beserker
			                                216,  // Bound for Glory
											784,  // Eye of the Storm Perfection
											209,  // Eye of the Storm Veteran
											208,  // Eye of the Storm Victory
										    214,  // Flurry
										    1171, // Master of Eye of the Storm
										    212,  // Storm Capper
										    211,  // Storm Glory
										    213,  // Stormtrooper
										    587,  // Stormy Assassin
										    1258, // Take a Chill Pill
										    783   // The Perfect Storm
										)
							      	),
							      	array( "Isle of Conquest",
										array(
										    5256,  // A-bomb-inable
			                                5257,  // A-bomb-ination
											5247,  // All Over the Isle
											5246,  // Back Door Job
											5245,  // Cut the Blue Wire... No the Red Wire!
										    5248,  // Demolition Derby
										    5252,  // Four Car Garage
										    5262,  // Glaive Grave
										    5253,  // Isle of Conquest All-Star
										    5255,  // Isle of Conquest Veteran
										    5258,  // Isle of Conquest Victory
										    5254,  // Master of Isle of Conquest
										    5251,  // Mine
											5249,  // Mowed Down
											5250   // Resource Glut
										)
							      	),
							      	array( "Strand of the Ancients",
										array(
										    5256,  // Ancient Courtyard Protector
			                                5257,  // 
											5247,  // Battle for Gilneas Perfection
											5246,  // Battle for Gilneas Veteran
											5245,  // Battle for Gilneas Victory
										    5248,  // Bustin' Caps to Make It Haps
										    5252,  // Don't Get Cocky Kid
										    5262,  // Double Rainbow
										    5253,  // Full Coverage
										    5255,  // Jugger Not
										    5258,  // Master of the Battle for Gilneas
										    5254,  // Newbs to Plowshares
										    5251,  // Not Your Average PUG'er
											5249,  // One Two Three You Don't Know About Me
											5250   // Out of the Fog
										)
							      	),
							      	array( "Twin Peaks",
										array(
										    5256,  // Battle for Gilneas All-Star
			                                5257,  // Battle for Gilneas Assassin
											5247,  // Battle for Gilneas Perfection
											5246,  // Battle for Gilneas Veteran
											5245,  // Battle for Gilneas Victory
										    5248,  // Bustin' Caps to Make It Haps
										    5252,  // Don't Get Cocky Kid
										    5262,  // Double Rainbow
										    5253,  // Full Coverage
										    5255,  // Jugger Not
										    5258,  // Master of the Battle for Gilneas
										    5254,  // Newbs to Plowshares
										    5251,  // Not Your Average PUG'er
											5249,  // One Two Three You Don't Know About Me
											5250   // Out of the Fog
										)
							      	),	
							      	array( "Warsong Gulch",
										array(
										    5256,  // Battle for Gilneas All-Star
			                                5257,  // Battle for Gilneas Assassin
											5247,  // Battle for Gilneas Perfection
											5246,  // Battle for Gilneas Veteran
											5245,  // Battle for Gilneas Victory
										    5248,  // Bustin' Caps to Make It Haps
										    5252,  // Don't Get Cocky Kid
										    5262,  // Double Rainbow
										    5253,  // Full Coverage
										    5255,  // Jugger Not
										    5258,  // Master of the Battle for Gilneas
										    5254,  // Newbs to Plowshares
										    5251,  // Not Your Average PUG'er
											5249,  // One Two Three You Don't Know About Me
											5250   // Out of the Fog
										)
							      	)
							    );

	private $ccCriteria = array( 18658, // Alexstrasza Assaulted First
	  							 18659, // Kalecgos Assaulted First
	  							 18660, // Nozdormu Assaulted First
	  							 18661  // Ysera Assaulted First
								  );				  						   

/////////////////////////////////////////////////////////////////
//
// Constructor 
// 
/////////////////////////////////////////////////////////////////
    public function __construct() {
		// Moved
    }
	
/////////////////////////////////////////////////////////////////
//
// Request how many 'requests' we made
// 
/////////////////////////////////////////////////////////////////
    public function getRequests() {
		return $this->numRequests;
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

/////////////////////////////////////////////////////////////////
//
// Retrieve the item Icon
//
/////////////////////////////////////////////////////////////////
    public function getItemIcon($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 0);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
	$icon = curl_exec($ch);
	curl_close($ch);

	return $icon;
    }

/////////////////////////////////////////////////////////////////
//
// Sorts the raid member array by rank, then by name (alphabetical)
// 
///////////////////////////////////////////////////////////////// 
    public function getRaiders() {

	$url = $this->armoryServer . $this->guildBaseName . $this->realm . '/' . $this->guild;

// We only care about the roster so remove the guild achievements
	$url .= '?fields=members';
	$fullRoster = $this->getJson($url);

	if(isset($fullRoster['status'])){
		if($fullRoster['status'] == 'nok'){
			echo 'Error accessing the Blizzard Armory.  Reason: ' . $fullRoster['reason'] . '<br>';
		}
		exit;
	}
	
// Filter the roster to only contain current raiders
	$i = 0;

	foreach ($fullRoster['members'] as $raider) {

// Ranks:
// 0 - GM
// 1 - O
// 3 - Chupa
// 5 - Raider
// 6 - Aspirant
	    if ( $raider['rank'] < 2 || ($raider['rank'] > 3 && $raider['rank'] < 7) ) {
		$this->raidingRoster[$i]['name'] = $raider['character']['name'];
		$this->raidingRoster[$i]['class'] = $raider['character']['class'];
		$this->raidingRoster[$i]['race'] = $raider['character']['race'];
		$this->raidingRoster[$i]['rank'] = $raider['rank'];
		
		$i++;
	    }
	}

	$this->numMembers = $i;

	if($i > 0){
		// Sort the roster by ranks
		usort($this->raidingRoster, array("Skunkworks_Armory_Parser", "sortMembers"));

		// Get the information for each raider
		for ($i = 0; $i < $this->numMembers; $i++) {
		
		$url = $this->armoryServer . $this->characterBaseName . $this->realm . '/' . $this->raidingRoster[$i]['name'];

			// Set the fields we want
			$url .= '?fields=items,professions,mounts,achievements';

			$numRetry = 0;
			
			do {
				if($numRetry > 0){
					echo 'Trying to get ' . $this->characterBaseName . '\'s data.  Attempt # ' . $numRetry . '/5<br>';
				}
				
				$characterInfo = $this->getJson($url);
				$numRetry++;
			}
			while( ($characterInfo['race'] == '' || $characterInfo['class'] == '') && $numRetry >= 0);
			
			/*
			echo '<pre>';
			print_r($characterInfo);
			echo '</pre>';
			*/
			
			// What we want to save to the DB
			$this->raidingRoster[$i]['achievementPoints'] = $characterInfo['achievementPoints'];
			$this->raidingRoster[$i]['prof1'] = $characterInfo['professions']['primary'][0]['name'];
			$this->raidingRoster[$i]['prof2'] = $characterInfo['professions']['primary'][1]['name'];
			$this->raidingRoster[$i]['mounts'] = $characterInfo['mounts'];
			$this->raidingRoster[$i]['achievements'] = $characterInfo['achievements'];

			// Item Levels
			$this->raidingRoster[$i]['averageItemLevel'] = $characterInfo['items']['averageItemLevel'];
			$this->raidingRoster[$i]['averageItemLevelEquipped'] = $characterInfo['items']['averageItemLevelEquipped'];

			// Items
			// 19 Slots
			// head|neck|shoulder|back|chest|shirt|tabard|wrist|hands|waist|legs|feet|finger1|finger2|trinket1|trinket2|mainHand|offHand|ranged

			foreach ($this->itemSlot as $slot) {
					
				if (isset($this->raidingRoster[$i]['items'][$slot]['name'])) {
								
					if (strpos($characterInfo['items'][$slot]['name'], "Gladiator") == false) {
						$this->raidingRoster[$i]['items'][$slot]['id'] = $characterInfo['items'][$slot]['id'];
						$this->raidingRoster[$i]['items'][$slot]['name'] = $characterInfo['items'][$slot]['name'];
						$this->raidingRoster[$i]['items'][$slot]['icon'] = $characterInfo['items'][$slot]['icon'];

						// In addition, download the icon to the local server if we do not already have it
						$filePath = '/' . $this->iconFolder . $this->raidingRoster[$i]['items'][$slot]['icon'] . '.jpg';
						if (!file_exists("$filePath")) {

							$iconUrl = $this->wowheadImageBaseLarge . $this->raidingRoster[$i]['items'][$slot]['icon'] . '.jpg';
							
							$icon = $this->getItemIcon($iconUrl);

							$iconFile = fopen($this->iconFolder . $this->raidingRoster[$i]['items'][$slot]['icon'] . '.jpg', 'w');
							fwrite($iconFile, $icon);
							fclose($iconFile);				
						}
							
					} 
					else {
						// Flag as Pvp Item
						$this->raidingRoster[$i]['items'][$slot]['id'] = 0;
					}
				}
				else {
					// Flag as empty
					$this->raidingRoster[$i]['items'][$slot]['id'] = 0;
				}
			}
		}
		return true;
    }
	else
		return false;
	
	}

/////////////////////////////////////////////////////////////////
//
// Sorts the raid member array by rank, then by name (alphabetical)
// 
/////////////////////////////////////////////////////////////////
    public function sortMembers($a, $b) {
	if ($a['rank'] == $b['rank']) {
	    return strcmp($a['name'], $b['name']);
	} else {
	    if ($a['rank'] > $b['rank']) {
		return 1;
	    }
	    else
		return -1;
	}
    }

/////////////////////////////////////////////////////////////////
//
// Database Shenanighans
// 
/////////////////////////////////////////////////////////////////
    public function storeRaiders() {
	
	
// Lookup (Conversion) Arrays
// Guild rank to String
	$rankConvert = array(
	    0 => 'Guild Master',
	    1 => 'Officer',
		4 => 'Raider',
	    5 => 'Raider',
	    6 => 'Aspirant'
	);

// Class Number to String
	$classConvert = array(
	    'Unknown', // 0
	    'Warrior', // 1
	    'Paladin', // 2
	    'Hunter', // 3
	    'Rogue', // 4
	    'Priest', // 5
	    'Death Knight', // 6
	    'Shaman', // 7
	    'Mage', // 8
	    'Warlock', // 9
	    'Unknown', // 10
	    'Druid'	  // 11
	);

// Race Number to String
	$raceConvert[2] = 'Orc';
	$raceConvert[5] = 'Forsaken';
	$raceConvert[6] = 'Tauren';
	$raceConvert[8] = 'Troll';
	$raceConvert[9] = 'Goblin';
	$raceConvert[10] = 'Blood Elf';


// Tier Token
// 1 = Conqueror = Paladin, Priest, Warlock
// 2 = Protector = Hunter, Shaman, Warrior
// 3 = Vanquisher = Druid, Death Knight, Mage, Rogue	
	
// Connect to the Database
	$db = new mysqli($this->dbhostname, $this->dbusername, $this->dbpassword, $this->dbname);
	if ($db->connect_errno) {
	    echo "Failed to connect to MySQL: " . $db->connect_error;
	}

// Set Utf 8
	$query = "SET NAMES utf8";
	$db->query($query);

// Backup the current roster table
	$query = "DROP TABLE IF EXISTS SkunkRosterBackup";
	if($db->query($query)) {
		echo "Table <b>SkunkRosterBackup</b> deleted. <br>";
	}
	else {
	    echo "Table <b>SkunkRosterBackup</b> was not deleted.<br>";
	    echo "MySQL Reported Error: " . $db->error . '<br><br>';
	}

	// Copy the current roster table
	// Create the backup table if it does not currently exist (first time run)
	$query = "CREATE TABLE IF NOT EXISTS SkunkRosterBackup LIKE SkunkRoster";
	if ($db->query($query)) {
		echo "Table <b>SkunkRosterBackup</b> created. <br>";
	    
		$query = "INSERT INTO SkunkRosterBackup SELECT * FROM SkunkRoster";
	    
		if($db->query($query)){
			echo "Table <b>SkunkRosterBackup</b> is now backing up table <b>SkunkRoster</b>. <br>";		
		}
		else {
			echo "There was an issues backing up into <b>SkunkRosterBackup</b>.<br>";
			echo "MySQL Reported Error: " . $db->error . '<br><br>';
		}
	}

// Drop the current roster table if it exists
	$query = "DROP TABLE IF EXISTS SkunkRoster";
	if ($db->query($query)) {
	    echo "Table <b>SkunkRoster</b> deleted. <br>";
	} else {
	    echo "Table <b>SkunkRoster</b> does not exist. No problem!<br>";
	    echo "MySQL Reported Error: " . $db->error . '<br><br>';
	}
	
// Drop the current mount table if it exists
	$query = "DROP TABLE IF EXISTS SkunkMounts";
	if ($db->query($query)) {
	    echo "Table <b>SkunkMounts</b> deleted. <br>";
	} else {
	    echo "Table <b>SkunkMounts</b> does not exist. No problem!<br>";
	    echo "MySQL Reported Error: " . $db->error . '<br><br>';
	}
	
// Drop the current achievement table if it exists
	$query = "DROP TABLE IF EXISTS SkunkAchievements";
	if ($db->query($query)) {
	    echo "Table <b>SkunkAchievements</b> deleted. <br>";
	} else {
	    echo "Table <b>SkunkAchievements</b> does not exist. No problem!<br>";
	    echo "MySQL Reported Error: " . $db->error . '<br><br>';
	}

// ..And Recreate the roster table
	$query = "CREATE TABLE SkunkRoster
	(
	    raider_id int NOT NULL AUTO_INCREMENT,
	    PRIMARY KEY(raider_id),
	    name varchar(30),
	    race varchar(30),
	    class varchar(30),  
		tier_token int,
	    guild_rank varchar(30),
	    achievement_points int,
	    prof1 varchar(30),
	    prof2 varchar(30),
	    average_item_level int,
	    average_item_level_equipped int,
	    item_head_id int,
	    item_neck_id int,
	    item_shoulder_id int,
	    item_back_id int,
	    item_chest_id int,
	    item_shirt_id int,
	    item_tabard_id int,
	    item_wrist_id int,
	    item_hands_id int,
	    item_waist_id int,
	    item_legs_id int,
	    item_feet_id int,
	    item_finger1_id int,
	    item_finger2_id int,
	    item_trinket1_id int,
	    item_trinket2_id int,
	    item_mainHand_id int,
	    item_offHand_id int,
	    item_ranged_id int,
		last_update timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
	 )";

	if ($db->query($query)) {
	    echo "Table <b>SkunkRoster</b> created. <br>";
	} else {
	    echo "Unable to create Table <b>SkunkRoster</b>!<br>";
	    echo "MySQL Reported Error: " . $db->error . '<br><br>';
	}

	// Create the backup table if it does not currently exist (first time run)
	$query = "CREATE TABLE IF NOT EXISTS SkunkRosterBackup LIKE SkunkRoster";
	if ($db->query($query)) {
	    $query = "INSERT INTO SkunkRosterBackup SELECT * FROM SkunkRoster";
	    @$db->query($query);
	}
	
	// Items
	// Create the item table if it does not currently exist (should always exist)
	// We don't really care if this fails or not
	//
	// Note:
	// Item IDs are already unique, no need to Auto-Increment
	// 
	//
	$query = "CREATE TABLE IF NOT EXISTS SkunkItems
	(
	    item_id int NOT NULL,
	    PRIMARY KEY(item_id),
	    item_name varchar(50),
	    item_icon varchar(70),
	    item_ilevel int
	)";
	@$db->query($query);
	
	// Mounts
	$query = "CREATE TABLE SkunkMounts
	(
	    raider_id int NOT NULL AUTO_INCREMENT,
	    PRIMARY KEY(raider_id),
	    pureblood_firehawk int,
		life_binders_handmaiden int,
		blazing_drake int, 
		invicible int,
		mimirons_head int,
		ashes_of_alar int
	)";
	@$db->query($query);
	
	// Achievements        
	$query = "CREATE TABLE SkunkAchievements
	(
	    raider_id int NOT NULL AUTO_INCREMENT,
	    PRIMARY KEY(raider_id),
	    tier11 int,
		tier12 int,
		tier13 int,
		chromatic_champion int
	)";
	@$db->query($query);


	// Fill the tables in
	// Try to keep the highest ilvl item
	for ($i = 0; $i < $this->numMembers; $i++) {
    // Try to fix pvp and empty items
	    foreach ($this->itemSlot as $slot) {

			if ($this->raidingRoster[$i]['items'][$slot]['id'] == 0) {

				// Pvp item found, use the item listed in the old table if possible
				$itemSlotColumn = 'item_' . $slot . '_id';
				$query = "SELECT name, $itemSlotColumn FROM SkunkRosterBackup WHERE name = '" . $this->raidingRoster[$i]['name'] . "'";
				$result = $db->query($query);

				if ($result) {
					$row = $result->fetch_object();
					// Note: This could potentially still be 0
					if ($row->$itemSlotColumn != '') {
						$this->raidingRoster[$i]['items'][$slot]['id'] = $row->$itemSlotColumn;
					}
					else {
						echo "MySQL Reported Error: " . $db->error . '<br><br>';
					}
				}
			}
			
			// Try to insert item id into the item database
			$query = "INSERT INTO SkunkItems set item_id = " . $this->raidingRoster[$i]['items'][$slot]['id'];
			@$db->query($query);
					
		}
		
		// Tier Token
		if($this->raidingRoster[$i]['class'] == 2 || $this->raidingRoster[$i]['class'] == 5 || $this->raidingRoster[$i]['class'] == 9){
			// Paladin/Priest/Warlock
			$tierToken = 1;
		}
		elseif($this->raidingRoster[$i]['class'] == 1 || $this->raidingRoster[$i]['class'] == 3 || $this->raidingRoster[$i]['class'] == 7){
			// Hunter/Shaman/Warrior
			$tierToken = 2;
		}
		else{
			// Druid/Mage/Rogue/Death Knight
			$tierToken = 3;
		}

	    $query = "INSERT INTO SkunkRoster SET 
		name = '" . $this->raidingRoster[$i]['name'] . "',
		race = '" . $raceConvert[$this->raidingRoster[$i]['race']] . "',
		class = '" . $classConvert[$this->raidingRoster[$i]['class']] . "', 
		tier_token = " . $tierToken . ",
		guild_rank = '" . $rankConvert[$this->raidingRoster[$i]['rank']] . "', 
		achievement_points = " . $this->raidingRoster[$i]['achievementPoints'] . ", 
	    prof1 = '" . $this->raidingRoster[$i]['prof1'] . "', 
		prof2 = '" . $this->raidingRoster[$i]['prof2'] . "',   
		average_item_level = " . $this->raidingRoster[$i]['averageItemLevel'] . ",
	    average_item_level_equipped = " . $this->raidingRoster[$i]['averageItemLevelEquipped'] . ",  
		item_head_id = " . $this->raidingRoster[$i]['items']['head']['id'] . ",
	    item_neck_id = " . $this->raidingRoster[$i]['items']['neck']['id'] . ",
		item_shoulder_id = " . $this->raidingRoster[$i]['items']['shoulder']['id'] . ",
		item_back_id = " . $this->raidingRoster[$i]['items']['back']['id'] . ",
		item_chest_id = " . $this->raidingRoster[$i]['items']['chest']['id'] . ",
		item_shirt_id = " . $this->raidingRoster[$i]['items']['shirt']['id'] . ",
		item_tabard_id = " . $this->raidingRoster[$i]['items']['tabard']['id'] . ",
		item_wrist_id = " . $this->raidingRoster[$i]['items']['wrist']['id'] . ",
		item_hands_id = " . $this->raidingRoster[$i]['items']['hands']['id'] . ",
		item_waist_id = " . $this->raidingRoster[$i]['items']['waist']['id'] . ",
		item_legs_id = " . $this->raidingRoster[$i]['items']['legs']['id'] . ",
		item_feet_id = " . $this->raidingRoster[$i]['items']['feet']['id'] . ",
		item_finger1_id = " . $this->raidingRoster[$i]['items']['finger1']['id'] . ",
		item_finger2_id = " . $this->raidingRoster[$i]['items']['finger2']['id'] . ",
		item_trinket1_id = " . $this->raidingRoster[$i]['items']['trinket1']['id'] . ",
		item_trinket2_id = " . $this->raidingRoster[$i]['items']['trinket2']['id'] . ",
		item_mainHand_id = " . $this->raidingRoster[$i]['items']['mainHand']['id'] . ",
		item_offHand_id = " . $this->raidingRoster[$i]['items']['offHand']['id'] . ",
		item_ranged_id = " . $this->raidingRoster[$i]['items']['ranged']['id'] . "";
	   
	   
	    if ($db->query($query) == true) {
			echo "User <b>" . $this->raidingRoster[$i]['name'] . "</b> inserted <br>";
	    } 
		else {
			echo "Error inserting user " . $this->raidingRoster[$i]['name'] . "<br>";
			echo "MySQL Reported Error: " . $db->error . '<br><br>';
	    }
		
		// Mounts
		$query = "INSERT INTO SkunkMounts SET 
			     pureblood_firehawk = " . ((in_array(97493, $this->raidingRoster[$i]['mounts']) ) ? 1 : 0) . ",
				 life_binders_handmaiden = " . ((in_array(107845, $this->raidingRoster[$i]['mounts']) ) ? 1 : 0) . ",
			 	 blazing_drake = " . ((in_array(107842, $this->raidingRoster[$i]['mounts']) ) ? 1 : 0) . ",
				 invicible = " . ((in_array(72286, $this->raidingRoster[$i]['mounts']) ) ? 1 : 0) . ",
				 mimirons_head = " . ((in_array(63796, $this->raidingRoster[$i]['mounts']) ) ? 1 : 0) . ",
		         ashes_of_alar = " . ((in_array(40192, $this->raidingRoster[$i]['mounts']) ) ? 1 : 0);
				 
		if ($db->query($query) == true) {
			echo "User mounts for <b>" . $this->raidingRoster[$i]['name'] . "</b> inserted <br>";
	    } 
		else {
			echo "Error inserting user mounts for " . $this->raidingRoster[$i]['name'] . "<br>";
			echo "MySQL Reported Error: " . $db->error . '<br><br>';
	    }	
		
		// Achievements
		$tier11 = 0;
		$tier12 = 0;
		$tier13 = 0;
		$cc = 0;

		// T11
		for($j = 0; $j < count($this->tier11Achieve); $j++){
			if(in_array($this->tier11Achieve[$j], $this->raidingRoster[$i]['achievements']['achievementsCompleted'] )){
				$tier11 += (1 << $j);
			}	
		}
		
		// T12
		for($j = 0; $j < count($this->tier12Achieve); $j++){
			if(in_array($this->tier12Achieve[$j], $this->raidingRoster[$i]['achievements']['achievementsCompleted'])){
				$tier12 += (1 << $j);
			}	
		}
		
		// T13
		for($j = 0; $j < count($this->tier13Achieve); $j++){
			if(in_array($this->tier13Achieve[$j], $this->raidingRoster[$i]['achievements']['achievementsCompleted'])){
				$tier13 += (1 << $j);
			}	
		}

		// Chromatic Champion
		for($j = 0; $j < 4; $j++){
			if(in_array($this->ccCriteria[$j], $this->raidingRoster[$i]['achievements']['criteria'])){
				$cc += (1 << $j);
			}	
		}

		$query = "INSERT INTO SkunkAchievements SET 
			     tier11 = " . $tier11 . ",
				 tier12 = " . $tier12 . ",
			 	 tier13 = " . $tier13 . ",
			 	 chromatic_champion = " . $cc;	 
		if ($db->query($query) == true) {
			echo "Achievements for <b>" . $this->raidingRoster[$i]['name'] . "</b> inserted <br>";
	    } 
		else {
			echo "Error inserting achievements for " . $this->raidingRoster[$i]['name'] . "<br>";
			echo "MySQL Reported Error: " . $db->error . '<br><br>';
	    }	
		
		
	}  // End individual character loop
	
	// Figure out what item ids are missing names/icons/ilevels
	// Remove the conditional to do a full item reset (WARNING MAY REACH REQUEST LIMIT)
	$query = "SELECT * FROM SkunkItems WHERE ISNULL(item_name) OR ISNULL(item_icon) OR ISNULL(item_ilevel)";
	$result = $db->query($query);
	if ($result) {
	
	    // Update the information
	    while (($row = $result->fetch_object()) != NULL) {
		
			if($row->item_id != 0){
		
				$url = $url = $this->armoryServer . $this->itemBaseName . $row->item_id;

				echo $url . '<br>';
			
				$itemInfo = $this->getJson($url);
				$iName = $db->real_escape_string($itemInfo['name']);
				$iIcon = $db->real_escape_string($itemInfo['icon']);
				$iLevel = $itemInfo['itemLevel'];

				$query = "UPDATE SkunkItems SET item_name = '$iName', item_icon = '$iIcon', item_ilevel = $iLevel WHERE item_id = " .$row->item_id;

				if ($db->query($query)) {
					echo "Item $iName updated <br>";
				} 
				else {
					echo "Error updating item $iName. <br>";
					echo "MySQL Reported Error: " . $db->error . '<br><br>';
				}
			}
	    }
	} 
	else {
	    echo "All items are up to date<br>";
	}

	$db->close();
    }

}

$skunkworks = new Skunkworks_Armory_Parser();

if($skunkworks->getRaiders()){
	$skunkworks->storeRaiders();
} 
else {
	echo 'getRaiders() returned false <br>';
}

echo 'Number of Requests made to Blizzard: ' . $skunkworks->getRequests();


 
  /////////
  //Reference on what the API currently returns

  //$itemInfo = $skunkworks->getJson("http://us.battle.net/api/wow/item/69111");
  //$charInfo = $skunkworks->getJson("http://us.battle.net/api/wow/character/Balnazzar/Infinitum?fields=items,professions,mounts,achievements");
  //$guildInfo = $skunkworks->getJson("http://us.battle.net/api/wow/guild/Balnazzar/Skunkworks?fields=members");
  /*
  echo '<pre>';
  print_r($guildInfo) ;
  echo '</pre>';


  echo '<pre>';
  print_r($itemInfo);
  echo '</pre>';

  echo '<pre>';
  print_r($charInfo);
  echo '</pre>';
*/


?>
