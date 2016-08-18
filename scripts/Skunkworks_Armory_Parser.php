<?php

//////////////////////////////////////////
//
// Skunkworks Roster Parser
// Inserts Current Raider Information into the Database
//
// Reference:
// http://blizzard.github.com/api-wow-docs/
// Keep in mind the doc here is not always 100% up to date with the actual functions available
// Use the examples at the bottom of this page for confirmation
//
// Note:
// There is currently a limit 3000 requests/day per site so consider that before purging item db.
// 
//
// Last edit:
// 11/20/14 - Infinitum
//
//////////////////////////////////////////

ini_set('memory_limit', '1280M');
ini_set('max_execution_time', '3000');
ini_set('max_input_time', '600');
ini_set('memory_limit', '1280M');

//DB
require(dirname(__FILE__) . '/../../sw_include/sw_db.php');
// Blizzard API Key
require(dirname(__FILE__) . '../../sw_include/sw_api.php');
// Achievements!
require('Skunkworks_Armory_Achievements.php');
// Enchants!
require('Skunkworks_Armory_Enchant_Conversions.php');

// Set the current Game Max Level
define("MAX_LEVEL", 100);

// Where to store images
// This has to be hard coded for cron to work
$iconFolder = '/home/skunkwrk/public_html/images/icons/';
$avatarFolder = '/home/skunkwrk/public_html/images/avatar/';

// Blizzard Info

$armoryServer = 'https://us.api.battle.net';
$armoryImageServer = 'http://render-api-us.worldofwarcraft.com';  // This might change at some point
$characterBaseName = '/wow/character/';
$guildBaseName = '/wow/guild/';
$itemBaseName = '/wow/item/';
$spellBaseName = '/wow/spell/';
$achievementBaseName = '/wow/achievement/';
$thumbnailBaseName = '/static-render/us/';
$blizzardIconBaseLarge = 'http://media.blizzard.com/wow/icons/56/';
$blizzardIconBaseSmall = 'http://media.blizzard.com/wow/icons/18/';

// Skunkworks Specific
$realm = 'Stormreaver';
$guild = 'Skunkworks';
$numMembers = 0;
$raidingRoster = '';

// Utility
$numRequests = 0;

$itemSlot = array('head', 'neck', 'shoulder', 'back', 'chest', 'shirt', 'tabard', 'wrist',
                  'hands', 'waist', 'legs', 'feet', 'finger1', 'finger2', 'trinket1', 'trinket2',
                  'mainHand', 'offHand'
                 );

// Lookup (Conversion) Arrays
// Guild rank to String
$rankConvert = array(
                   0 => 'Guild Master',
                   1 => 'Officer',
				   2 => 'Officer Alt',
				   3 => 'Officer Emeritus',
                   4 => 'Raider',
                   5 => 'Raider',
                   6 => 'Aspirant',
				   7 => 'Raider Emeritus',
				   8 => 'Social',
				   9 => 'Alt'   
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
                    'Monk', // 10
                    'Druid'	  // 11
                );

// Race Number to String
$raceConvert[2] = 'Orc';
$raceConvert[5] = 'Forsaken';
$raceConvert[6] = 'Tauren';
$raceConvert[8] = 'Troll';
$raceConvert[9] = 'Goblin';
$raceConvert[10] = 'Blood Elf';
$raceConvert[26] = 'Pandarian';  // Horde Panda


// Tier Token
// 1 = Conqueror = Paladin, Priest, Warlock
// 2 = Protector = Hunter, Shaman, Warrior, Monk
// 3 = Vanquisher = Druid, Death Knight, Mage, Rogue

// Avatar Image Extensions
$imageAvatarExt[0] = '.jpg';
$imageAvatarExt[1] = '-inset.jpg';
$imageAvatarExt[2] = '-profilemain.jpg';
 					
					
/* Stats
5 - Intellect
6 - Spirit
7 - Stam

32 - Critical Strike
36 - Haste
40 - Versatility
45 - Spellpower
49 - Mastery
59 - Multistrike
61 - Speed
63 - Avoidence

73 - Agil or Intellect

More work to be done here
*/				
					
					
					
					
					
/////////////////////////////////////////////////////////////////
//
// Retrieve the Api data and put it in an array
//
/////////////////////////////////////////////////////////////////
function getJson($url) {

	global $numRequests;
	global $apikey;
	
	// Add the Blizzard Api Key
	$url .= '&apikey=' . $apikey;

	//echo $url;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.2) Gecko/20070319 Firefox/2.0.0.3");
    $url_string = curl_exec($ch);
    curl_close($ch);

    // Update # of requests
    $numRequests++;

    $data = json_decode($url_string, true);

    return $data;
}

/////////////////////////////////////////////////////////////////
//
// Retrieve an Image
//
/////////////////////////////////////////////////////////////////
function getImage($url) {



    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    $image = curl_exec($ch);
    curl_close($ch);

    return $image;
}

/////////////////////////////////////////////////////////////////
//
// Sorts the raid member array by rank, then by name (alphabetical)
//
/////////////////////////////////////////////////////////////////
function sortMembers($a, $b) {
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



/////////////////////////// DEBUG AREA!!!!! 

/*


$url = $armoryServer . $itemBaseName . 90515;

              echo $url . '<br>';

                $itemInfo = getJson($url);



echo '<pre>';
print_r($itemInfo);
echo '</pre>';

exit(0);

*/
/////////////////////////// DEBUG AREA VANISH!!!! 






/////////////////////////////////////////////////////////////////
//
// Get the data from Blizzard's Servers
// Sorts the raid member array by rank, then by name (alphabetical)
//
/////////////////////////////////////////////////////////////////


$receivedRaiders = false;

$url = $armoryServer . $guildBaseName . $realm . '/' . $guild;

// Add extra data here.. for now all we need is the memberlist
$url .= '?fields=members';
$fullRoster = getJson($url);

if (isset($fullRoster['status'])) {
    if ($fullRoster['status'] == 'nok') {
        echo 'Error accessing the Blizzard Armory.  Reason: ' . $fullRoster['reason'] . '<br>';
    }
    exit;
}


// Filter the roster to only contain current raiders
$numMembers = 0;

foreach ($fullRoster['members'] as $raider) {

    // Ranks:
    // 0 - GM
    // 1 - Officers
    // 4 - Raider
    // 5 - Aspirant
	// 6 - Raider [Extra]
	// 7 - Raider Emeritus
	// 8 - Social
	// 9 - Alt
	
    if ( $raider['rank'] < 2 || 
	     $raider['rank'] == 4 ||		  									 // Raiders
		 $raider['rank'] == 5)                                               // Aspirant
	{             
        $raidingRoster[$numMembers]['name'] = $raider['character']['name'];
		
		$raidingRoster[$numMembers]['class'] = $raider['character']['class'];
        $raidingRoster[$numMembers]['race'] = $raider['character']['race'];
        $raidingRoster[$numMembers]['rank'] = $raider['rank'];
		$raidingRoster[$numMembers]['server'] = $realm;

	
        $numMembers++;
    }

}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// Get trials that are currently not on the server (MANUAL ENTRY HERE)


//DEBUG
//$numMembers = 1;



/////////////////////////////////////////////////////////////////


if ($numMembers > 0) {
    // Sort the roster by ranks
    usort($raidingRoster, "sortMembers");

    // Get the information for each raider
    for ($i = 0; $i < $numMembers; $i++) {

	   $url = $armoryServer . $characterBaseName . $raidingRoster[$i]['server'] . '/' . $raidingRoster[$i]['name'];
	   
        // Set the fields we want
        $url .= '?fields=items,professions,mounts,achievements,progression,stats';

        $numRetry = 0;

        do {
            if ($numRetry > 0) {
                echo 'Trying to get ' . $characterBaseName . '\\' . $raidingRoster[$i]['name'] .'\'s data.  Attempt # ' . $numRetry . '/5<br>';
				echo "url = $url <br>";
            }

            $characterInfo = getJson($url);
           /*
		    echo '<pre>';
            print_r($characterInfo);
            echo '</pre>';
			*/
            $numRetry++;
        }
        while ( ($characterInfo['race'] == '' || $characterInfo['class'] == '') && $numRetry >= 0 && $numRetry < 5);

        // Debug - Print out everything we get
        /*
		echo '<pre>';
        echo $characterInfo['name'] . '<br>';
        print_r($characterInfo['items']);
        echo '</pre>';
        */
		
		echo "<h3>" . $raidingRoster[$i]['name'] . "</h3>";
		

        // What we want to save to the DB
        $raidingRoster[$i]['achievementPoints'] = $characterInfo['achievementPoints'];
        $raidingRoster[$i]['prof1'] = $characterInfo['professions']['primary'][0]['name'];
        $raidingRoster[$i]['prof2'] = $characterInfo['professions']['primary'][1]['name'];
        $raidingRoster[$i]['mounts'] = $characterInfo['mounts'];
        $raidingRoster[$i]['achievements'] = $characterInfo['achievements'];
		$raidingRoster[$i]['stats'] = $characterInfo['stats'];
 

		// Get the character Images
		for($j = 0; $j < 3; $j++){
					
			// Avatar names have a bunch of numbers in it, we will just organize these things using the character name
			$filePath = '/' . $avatarFolder . $characterInfo['name'] . $imageAvatarExt[$j];
			if($j == 0){
				$avatarUrl = $armoryImageServer . $thumbnailBaseName . $characterInfo['thumbnail'];
			} 
			else if($j == 1){
				$avatarUrl = $armoryImageServer . $thumbnailBaseName . str_replace("avatar", "inset", $characterInfo['thumbnail']);
			}
			else {
				$avatarUrl = $armoryImageServer . $thumbnailBaseName . str_replace("avatar", "profilemain", $characterInfo['thumbnail']);	
			}
					
			echo $avatarUrl . '<br>';
			$avatar = getImage($avatarUrl);

			$avatarFile = fopen($filePath, 'w');
			fwrite($avatarFile, $avatar);
			fclose($avatarFile);
			
			// Resize the big image (profile-main)
			if($j == 2){
			
				$avatar = imagecreatefromjpeg($filePath);

				// width, height
				// This size was visually determined so it may change in the future depending on weapons/models/etc.
				//$avatarOut = imagecreatetruecolor(585, 550);
				$avatarOut = imagecreatetruecolor(293, 275);

				// do the crop - which is really just a copy to a smaller canvas
				imagecopyresampled($avatarOut, $avatar, 0, 0, 97, 0, 293, 275, 585, 550);

				// kill the temp image
				imagedestroy($avatar);

				// write to disk
				// may need to play with the quality
				imagejpeg($avatarOut, $filePath, 90);

				// kill the temp image
				imagedestroy($avatarOut);
	
			}
		
			// Print the avatar out
			echo '<img src="http://skunkworksguild.com/images/avatar/' . $characterInfo['name'] . $imageAvatarExt[$j] .'">';
		}
		echo '<br>';
		
		
        // Item Levels
		// These are currently calculated on stats page manually but might as well save Blizzard's version too
        $raidingRoster[$i]['averageItemLevel'] = $characterInfo['items']['averageItemLevel'];
        $raidingRoster[$i]['averageItemLevelEquipped'] = $characterInfo['items']['averageItemLevelEquipped'];

        // Items
        // 18 Slots
        // head|neck|shoulder|back|chest|shirt|tabard|wrist|hands|waist|legs|feet|finger1|finger2|trinket1|trinket2|mainHand|offHand

        foreach ($itemSlot as $slot) {

            if (isset($characterInfo['items'][$slot]['name'])) {

                $raidingRoster[$i]['items'][$slot]['id'] = $characterInfo['items'][$slot]['id'];
                $raidingRoster[$i]['items'][$slot]['name'] = $characterInfo['items'][$slot]['name'];
                $raidingRoster[$i]['items'][$slot]['icon'] = $characterInfo['items'][$slot]['icon'];
				$raidingRoster[$i]['items'][$slot]['level'] = $characterInfo['items'][$slot]['itemLevel'];
				$raidingRoster[$i]['items'][$slot]['quality'] = $characterInfo['items'][$slot]['quality'];
				$raidingRoster[$i]['items'][$slot]['bonusLists'] = $characterInfo['items'][$slot]['bonusLists'];

                // New item enchant rules for WoD
			    // Enchant only on Neck, Rings Back, and Weapon
				
                // Enchant
                if(!empty($characterInfo['items'][$slot]['tooltipParams']['enchant'])){
					$raidingRoster[$i]['items'][$slot]['enchant'] = $enchant_convert[$characterInfo['items'][$slot]['tooltipParams']['enchant']];
                }
				else {
					$raidingRoster[$i]['items'][$slot]['enchant'] = 0;
				}
				
                // Gem 0
				if(!empty($characterInfo['items'][$slot]['tooltipParams']['gem0'])){
					$raidingRoster[$i]['items'][$slot]['gem0'] = $characterInfo['items'][$slot]['tooltipParams']['gem0'];
				}
				else {
					$raidingRoster[$i]['items'][$slot]['gem0'] = 0;
				}
				
				// Tertiary/Bonus
				// Gem 0
				if(!empty($characterInfo['items'][$slot]['bonusLists'])){
					$raidingRoster[$i]['items'][$slot]['bonusLists'] = $characterInfo['items'][$slot]['bonusLists'];
				}
				else {
					$raidingRoster[$i]['items'][$slot]['bonusLists'] = 0;
				}
				
				
				
				
				
                // In addition, download the icon to the local server if we do not already have it
                $filePath = '/' . $iconFolder . $raidingRoster[$i]['items'][$slot]['icon'] . '.jpg';
				
				//echo "FILE PATH - " . $filePath;
                if (!file_exists("$filePath")) {

                    $iconUrl = $blizzardIconBaseLarge . $raidingRoster[$i]['items'][$slot]['icon'] . '.jpg';
					//echo "ICON URL -" . $iconUrl;
                    $icon = getImage($iconUrl);

                    $iconFile = fopen($iconFolder . $raidingRoster[$i]['items'][$slot]['icon'] . '.jpg', 'w');
                    fwrite($iconFile, $icon);
                    fclose($iconFile);
                } 

            }
            else {
                // Flag as empty
			
                $raidingRoster[$i]['items'][$slot]['name'] = 'none';
                $raidingRoster[$i]['items'][$slot]['icon'] = 'none';
				$raidingRoster[$i]['items'][$slot]['level'] = 0;
				$raidingRoster[$i]['items'][$slot]['bonusLists'] = 'none';
                $raidingRoster[$i]['items'][$slot]['id'] = 0;
				$raidingRoster[$i]['items'][$slot]['enchant'] = 0;
				$raidingRoster[$i]['items'][$slot]['gem0'] = 0;
				$raidingRoster[$i]['items'][$slot]['quality'] = 0;
				$raidingRoster[$i]['items'][$slot]['bonusLists'] = 0;
			
            }
        }
    }
    $receivedRaiders = true;
}
else {
    $receivedRaiders = false;
	echo 'getRaiders() Failed! <br>';
}


//////////////////////////////
//
// Store stuff in our database!
//
//////////////////////////////
if ($receivedRaiders) {

    // Set Utf 8
    $query = "SET NAMES utf8";
    $db->query($query);

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
			 server varchar(30),
             tier_token int,
             guild_rank varchar(30),
             achievement_points int,
             prof1 varchar(30),
             prof2 varchar(30),
             average_item_level int,
             average_item_level_equipped int,
       
             item_head_id int,
			 item_head_level int,
             item_head_gem0_id int,
			 item_head_icon varchar(70),
			 item_head_name varchar (70),
			 item_head_quality int,
			 item_head_bonusLists varchar(30),
            
             item_neck_id int,
			 item_neck_level int,
             item_neck_gem0_id int,
			 item_neck_enchant_id int,
             item_neck_icon varchar(70),
			 item_neck_name varchar(70),
			 item_neck_quality int,
			 item_neck_bonusLists varchar(30),
            
             item_shoulder_id int,
			 item_shoulder_level int,
             item_shoulder_gem0_id int,
             item_shoulder_icon varchar(70),
			 item_shoulder_name varchar(70),
			 item_shoulder_quality int,
			 item_shoulder_bonusLists varchar(30),
            
             item_back_id int,
			 item_back_level int,
             item_back_enchant_id int,
             item_back_gem0_id int,
             item_back_icon varchar(70),
			 item_back_name varchar(70),
			 item_back_quality int,
			 item_back_bonusLists varchar(30),
             
             item_chest_id int,
			 item_chest_level int,
             item_chest_gem0_id int,
             item_chest_icon varchar(70),
			 item_chest_name varchar(70),
			 item_chest_quality int,
			 item_chest_bonusLists varchar(30),
            
             item_shirt_id int,
			 item_shirt_icon varchar(70),
			 item_shirt_name varchar(70),
			 item_shirt_quality int,
			 
             item_tabard_id int,
			 item_tabard_icon varchar(70),
			 item_tabard_name varchar(70),
			 item_tabard_quality int,
            
             item_wrist_id int,
			 item_wrist_level int,
             item_wrist_gem0_id int,
             item_wrist_icon varchar(70),
			 item_wrist_name varchar(70),
			 item_wrist_quality int,
			 item_wrist_bonusLists varchar(30),

             item_hands_id int,
			 item_hands_level int,
             item_hands_gem0_id int,
             item_hands_icon varchar(70),
			 item_hands_name varchar(70),
			 item_hands_quality int,
			 item_hands_bonusLists varchar(30),

             item_waist_id int,
			 item_waist_level int,
             item_waist_gem0_id int,
             item_waist_icon varchar(70),
			 item_waist_name varchar(70),
			 item_waist_quality int,
			 item_waist_bonusLists varchar(30),

             item_legs_id int,
			 item_legs_level int,
             item_legs_gem0_id int,
             item_legs_icon varchar(70),
			 item_legs_name varchar(70),
			 item_legs_quality int,
			 item_legs_bonusLists varchar(30),

             item_feet_id int,
			 item_feet_level int,
             item_feet_gem0_id int,
             item_feet_icon varchar(70),
			 item_feet_name varchar(70),
			 item_feet_quality int,
			 item_feet_bonusLists varchar(30),

             item_finger1_id int,
			 item_finger1_level int,
             item_finger1_enchant_id int,
             item_finger1_gem0_id int,
             item_finger1_icon varchar(70),
			 item_finger1_name varchar(70),
			 item_finger1_quality int,
			 item_finger1_bonusLists varchar(30),

             item_finger2_id int,
			 item_finger2_level int,
             item_finger2_enchant_id int,
             item_finger2_gem0_id int,
             item_finger2_icon varchar(70),
			 item_finger2_name varchar(70),
			 item_finger2_quality int,
			 item_finger2_bonusLists varchar(30),

             item_trinket1_id int,
			 item_trinket1_level int,
             item_trinket1_gem0_id int,
             item_trinket1_icon varchar(70),
			 item_trinket1_name varchar(70),
			 item_trinket1_quality int,
			 item_trinket1_bonusLists varchar(30),

             item_trinket2_id int,
			 item_trinket2_level int,
             item_trinket2_gem0_id int,
             item_trinket2_icon varchar(70),
			 item_trinket2_name varchar(70),
			 item_trinket2_quality int,
			 item_trinket2_bonusLists varchar(30),

             item_mainHand_id int,
			 item_mainHand_level int,
             item_mainHand_enchant_id int,
             item_mainHand_gem0_id int,
             item_mainHand_icon varchar(70),
			 item_mainHand_name varchar(70),
			 item_mainHand_quality int,
			 item_mainHand_bonusLists varchar(30),

             item_offHand_id int,
			 item_offHand_level int,
             item_offHand_gem0_id int,
             item_offHand_icon varchar(70),
			 item_offHand_name varchar(70),
			 item_offHand_quality int,
			 item_offHand_bonusLists varchar(30),

             last_update timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
             )";

    if ($db->query($query)) {
        echo "Table <b>SkunkRoster</b> created. <br>";
    } else {
        echo "Unable to create Table <b>SkunkRoster</b>!<br>";
        echo "MySQL Reported Error: " . $db->error . '<br><br>';
    }

    // Items
    // Create the item table if it does not currently exist (should always exist)
    // We don't really care if this fails or not
    //
    // Note:
    // Item IDs are already unique, no need to Auto-Increment
    //
    //
 
    // Spells
    // Create the spell table if it does not currently exist (should always exist)
    // We don't really care if this fails or not
    //
    // Note:
    // Spell IDs are already unique, no need to Auto-Increment
    //
    //
    $query = "CREATE TABLE IF NOT EXISTS SkunkSpells
             (
             spell_id int NOT NULL, 
             PRIMARY KEY(spell_id),
             spell_name varchar(50),
             spell_icon varchar(70)
             )";
    if ($db->query($query)) {
        echo "Table <b>SkunkSpells</b> created. <br>";
    }

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
             ashes_of_alar int,
			 korkron_jugg int,
			 ironhoof_destroyer int,
			 felsteel_annihilator int
             )";
    if ($db->query($query)) {
        echo "Table <b>SkunkMounts</b> created. <br>";
    }

   // Achievements
    $query = "CREATE TABLE SkunkAchievements
             (
             raider_id int NOT NULL AUTO_INCREMENT,
             PRIMARY KEY(raider_id),
             tier11 int,
             tier12 int,
             tier13 int,
             tier14_1 int,
             tier14_2 int,
			 tier15 int,
             tier16 int,
			 tier17_1 int,
			 tier17_2 int,
			 cModes int,
			 pGrounds int,
			 wow10 int
             )";
     if ($db->query($query)) {
        echo "Table <b>SkunkAchievements</b> created. <br>";
    }


    ////////////////////////////////////////////////
    // Fill the tables in
    for ($i = 0; $i < $numMembers; $i++) {
        
        foreach ($itemSlot as $slot) {

            // Try to insert item id into the item database
            $query = "INSERT INTO SkunkItems set item_id = " . $raidingRoster[$i]['items'][$slot]['id'];
            @$db->query($query);

            // Try to insert all the extra things about an item

            // Enchants  and Tinkers go into the Spell Table
            $query = "INSERT INTO SkunkSpells set spell_id = " . $raidingRoster[$i]['items'][$slot]['enchant'];
            @$db->query($query);

            $query = "INSERT INTO SkunkItems set item_id = " . $raidingRoster[$i]['items'][$slot]['gem0'];
            @$db->query($query);

        }

        // Tier Token
        if ($raidingRoster[$i]['class'] == 2 || $raidingRoster[$i]['class'] == 5 || $raidingRoster[$i]['class'] == 9) {
            // Paladin/Priest/Warlock
            $tierToken = 1;
        }
        elseif($raidingRoster[$i]['class'] == 1 || $raidingRoster[$i]['class'] == 3 || $raidingRoster[$i]['class'] == 7 || $raidingRoster[$i]['class'] == 10) {
            // Hunter/Shaman/Warrior/Monk
            $tierToken = 2;
        }
        else {
            // Druid/Mage/Rogue/Death Knight
            $tierToken = 3;
        }

        $query = "INSERT INTO SkunkRoster SET
                 name = '" . $raidingRoster[$i]['name'] . "',
                 race = '" . $raceConvert[$raidingRoster[$i]['race']] . "',
                 class = '" . $classConvert[$raidingRoster[$i]['class']] . "',
				 server = '" . $raidingRoster[$i]['server'] . "',
                 tier_token = " . $tierToken . ",
                 guild_rank = '" . $rankConvert[$raidingRoster[$i]['rank']] . "',
                 achievement_points = " . $raidingRoster[$i]['achievementPoints'] . ",
                 prof1 = '" . $raidingRoster[$i]['prof1'] . "',
                 prof2 = '" . $raidingRoster[$i]['prof2'] . "',
                 average_item_level = " . $raidingRoster[$i]['averageItemLevel'] . ",
                 average_item_level_equipped = " . $raidingRoster[$i]['averageItemLevelEquipped'] . ", 
				 
                 item_head_id = " . $raidingRoster[$i]['items']['head']['id'] . ",
				 item_head_level = " . $raidingRoster[$i]['items']['head']['level'] . ",
                 item_head_gem0_id = " . $raidingRoster[$i]['items']['head']['gem0'] . ",
                 item_head_icon = '" . $raidingRoster[$i]['items']['head']['icon'] . "',
				 item_head_name = '" . $db->real_escape_string($raidingRoster[$i]['items']['head']['name']) . "',
				 item_head_quality = " . $raidingRoster[$i]['items']['head']['quality'] . ",
				 item_head_bonusLists = '" . json_encode($raidingRoster[$i]['items']['head']['bonusLists']) . "',

                 item_neck_id = " . $raidingRoster[$i]['items']['neck']['id'] . ",
				 item_neck_level = " . $raidingRoster[$i]['items']['neck']['level'] . ",
				 item_neck_enchant_id = '" . $raidingRoster[$i]['items']['neck']['enchant'] . "',
                 item_neck_gem0_id = " . $raidingRoster[$i]['items']['neck']['gem0'] . ",
                 item_neck_icon = '" . $raidingRoster[$i]['items']['neck']['icon'] . "',
				 item_neck_name = '" . $db->real_escape_string($raidingRoster[$i]['items']['neck']['name']) . "',
				 item_neck_quality = " . $raidingRoster[$i]['items']['neck']['quality'] . ",
				 item_neck_bonusLists = '" . json_encode($raidingRoster[$i]['items']['neck']['bonusLists']) . "',

                 item_shoulder_id = " . $raidingRoster[$i]['items']['shoulder']['id'] . ",
				 item_shoulder_level = " . $raidingRoster[$i]['items']['shoulder']['level'] . ",
                 item_shoulder_gem0_id = " . $raidingRoster[$i]['items']['shoulder']['gem0'] . ",
                 item_shoulder_icon = '" . $raidingRoster[$i]['items']['shoulder']['icon'] . "',
				 item_shoulder_name = '" . $db->real_escape_string($raidingRoster[$i]['items']['shoulder']['name']) . "',
				 item_shoulder_quality = " . $raidingRoster[$i]['items']['shoulder']['quality'] . ",
			  	 item_shoulder_bonusLists = '" . json_encode($raidingRoster[$i]['items']['shoulder']['bonusLists']) . "',
				  
                 item_back_id = " . $raidingRoster[$i]['items']['back']['id'] . ",
				 item_back_level = " . $raidingRoster[$i]['items']['back']['level'] . ",
				 item_back_enchant_id = '" . $raidingRoster[$i]['items']['back']['enchant'] . "',
                 item_back_gem0_id = " . $raidingRoster[$i]['items']['back']['gem0'] . ",
                 item_back_icon = '" . $raidingRoster[$i]['items']['back']['icon'] . "',
				 item_back_name = '" . $db->real_escape_string($raidingRoster[$i]['items']['back']['name']) . "',
				 item_back_quality = " . $raidingRoster[$i]['items']['back']['quality'] . ",
				 item_back_bonusLists = '" . json_encode($raidingRoster[$i]['items']['back']['bonusLists']) . "',

                 item_chest_id = " . $raidingRoster[$i]['items']['chest']['id'] . ",
                 item_chest_level = " . $raidingRoster[$i]['items']['chest']['level'] . ",
				 item_chest_gem0_id = " . $raidingRoster[$i]['items']['chest']['gem0'] . ",
                 item_chest_icon = '" . $raidingRoster[$i]['items']['chest']['icon'] . "',
				 item_chest_name = '" . $db->real_escape_string($raidingRoster[$i]['items']['chest']['name']) . "',
				 item_chest_quality = " . $raidingRoster[$i]['items']['chest']['quality'] . ",
				 item_chest_bonusLists = '" . json_encode($raidingRoster[$i]['items']['chest']['bonusLists']) . "',
				 
                 item_shirt_id = " . $raidingRoster[$i]['items']['shirt']['id'] . ",
				 item_shirt_icon = '" . $raidingRoster[$i]['items']['shirt']['icon'] . "',
				 item_shirt_name = '" . $db->real_escape_string($raidingRoster[$i]['items']['shirt']['name']) . "',
				 item_shirt_quality = " . $raidingRoster[$i]['items']['shirt']['quality'] . ",
                 item_tabard_id = " . $raidingRoster[$i]['items']['tabard']['id'] . ",
				 item_tabard_name = '" . $db->real_escape_string($raidingRoster[$i]['items']['tabard']['name']) . "',
				 item_tabard_quality = " . $raidingRoster[$i]['items']['tabard']['quality'] . ",
				 item_tabard_icon = '" . $raidingRoster[$i]['items']['tabard']['icon'] . "',

                 item_wrist_id = " . $raidingRoster[$i]['items']['wrist']['id'] . ",
                 item_wrist_level = " . $raidingRoster[$i]['items']['wrist']['level'] . ",
				 item_wrist_gem0_id = " . $raidingRoster[$i]['items']['wrist']['gem0'] . ",
                 item_wrist_icon = '" . $raidingRoster[$i]['items']['wrist']['icon'] . "',
				 item_wrist_name = '" . $db->real_escape_string($raidingRoster[$i]['items']['wrist']['name']) . "',
				 item_wrist_quality = " . $raidingRoster[$i]['items']['wrist']['quality'] . ",
				 item_wrist_bonusLists = '" . json_encode($raidingRoster[$i]['items']['wrist']['bonusLists']) . "',

                 item_hands_id = " . $raidingRoster[$i]['items']['hands']['id'] . ",
				 item_hands_level = " . $raidingRoster[$i]['items']['hands']['level'] . ",
                 item_hands_gem0_id = " . $raidingRoster[$i]['items']['hands']['gem0'] . ",
                 item_hands_icon = '" . $raidingRoster[$i]['items']['hands']['icon'] . "',
				 item_hands_name = '" . $db->real_escape_string($raidingRoster[$i]['items']['hands']['name']) . "',
				 item_hands_quality = " . $raidingRoster[$i]['items']['hands']['quality'] . ",
				 item_hands_bonusLists = '" . json_encode($raidingRoster[$i]['items']['hands']['bonusLists']) . "',

                 item_waist_id = " . $raidingRoster[$i]['items']['waist']['id'] . ",
				 item_waist_level = " . $raidingRoster[$i]['items']['waist']['level'] . ",
                 item_waist_gem0_id = " . $raidingRoster[$i]['items']['waist']['gem0'] . ",
                 item_waist_icon = '" . $raidingRoster[$i]['items']['waist']['icon'] . "',
				 item_waist_name = '" . $db->real_escape_string($raidingRoster[$i]['items']['waist']['name']) . "',
				 item_waist_quality = " . $raidingRoster[$i]['items']['waist']['quality'] . ",
				 item_waist_bonusLists = '" . json_encode($raidingRoster[$i]['items']['waist']['bonusLists']) . "',

                 item_legs_id = " . $raidingRoster[$i]['items']['legs']['id'] . ",
				 item_legs_level = " . $raidingRoster[$i]['items']['legs']['level'] . ",
                 item_legs_gem0_id = " . $raidingRoster[$i]['items']['legs']['gem0'] . ",
                 item_legs_icon = '" . $raidingRoster[$i]['items']['legs']['icon'] . "',
				 item_legs_name = '" . $db->real_escape_string($raidingRoster[$i]['items']['legs']['name']) . "',
				 item_legs_quality = " . $raidingRoster[$i]['items']['legs']['quality'] . ",
				 item_legs_bonusLists = '" . json_encode($raidingRoster[$i]['items']['legs']['bonusLists']) . "',

                 item_feet_id = " . $raidingRoster[$i]['items']['feet']['id'] . ",
                 item_feet_level = " . $raidingRoster[$i]['items']['feet']['level'] . ",
				 item_feet_gem0_id = " . $raidingRoster[$i]['items']['feet']['gem0'] . ",
                 item_feet_icon = '" . $raidingRoster[$i]['items']['feet']['icon'] . "',
				 item_feet_name = '" . $db->real_escape_string($raidingRoster[$i]['items']['feet']['name']) . "',
				 item_feet_quality = " . $raidingRoster[$i]['items']['feet']['quality'] . ",
				 item_feet_bonusLists = '" . json_encode($raidingRoster[$i]['items']['feet']['bonusLists']) . "',
				 
                 item_finger1_id = " . $raidingRoster[$i]['items']['finger1']['id'] . ",
				 item_finger1_level = " . $raidingRoster[$i]['items']['finger1']['level'] . ",
				 item_finger1_enchant_id = '" . $raidingRoster[$i]['items']['finger1']['enchant'] . "',
                 item_finger1_gem0_id = " . $raidingRoster[$i]['items']['finger1']['gem0'] . ",
                 item_finger1_icon = '" . $raidingRoster[$i]['items']['finger1']['icon'] . "',
				 item_finger1_name = '" . $db->real_escape_string($raidingRoster[$i]['items']['finger1']['name']) . "',
				 item_finger1_quality = " . $raidingRoster[$i]['items']['finger1']['quality'] . ",
				 item_finger1_bonusLists = '" . json_encode($raidingRoster[$i]['items']['finger1']['bonusLists']) . "',

                 item_finger2_id = " . $raidingRoster[$i]['items']['finger2']['id'] . ",
				 item_finger2_level = " . $raidingRoster[$i]['items']['finger2']['level'] . ",
				 item_finger2_enchant_id = '" . $raidingRoster[$i]['items']['finger2']['enchant'] . "',
                 item_finger2_gem0_id = " . $raidingRoster[$i]['items']['finger2']['gem0'] . ",
                 item_finger2_icon = '" . $raidingRoster[$i]['items']['finger2']['icon'] . "',
				 item_finger2_name = '" . $db->real_escape_string($raidingRoster[$i]['items']['finger2']['name']) . "',
				 item_finger2_quality = " . $raidingRoster[$i]['items']['finger2']['quality'] . ",
				 item_finger2_bonusLists = '" . json_encode($raidingRoster[$i]['items']['finger2']['bonusLists']) . "',

                 item_trinket1_id = " . $raidingRoster[$i]['items']['trinket1']['id'] . ",
				 item_trinket1_level = " . $raidingRoster[$i]['items']['trinket1']['level'] . ",
                 item_trinket1_gem0_id = " . $raidingRoster[$i]['items']['trinket1']['gem0'] . ",
                 item_trinket1_icon = '" . $raidingRoster[$i]['items']['trinket1']['icon'] . "',
				 item_trinket1_name = '" . $db->real_escape_string($raidingRoster[$i]['items']['trinket1']['name']) . "',
				 item_trinket1_quality = " . $raidingRoster[$i]['items']['trinket1']['quality'] . ",
				 item_trinket1_bonusLists = '" . json_encode($raidingRoster[$i]['items']['trinket1']['bonusLists']) . "',

                 item_trinket2_id = " . $raidingRoster[$i]['items']['trinket2']['id'] . ",
				 item_trinket2_level = " . $raidingRoster[$i]['items']['trinket2']['level'] . ",
                 item_trinket2_gem0_id = " . $raidingRoster[$i]['items']['trinket2']['gem0'] . ",
                 item_trinket2_icon = '" . $raidingRoster[$i]['items']['trinket2']['icon'] . "',
				 item_trinket2_name = '" . $db->real_escape_string($raidingRoster[$i]['items']['trinket2']['name']) . "',
				 item_trinket2_quality = " . $raidingRoster[$i]['items']['trinket2']['quality'] . ",
				 item_trinket2_bonusLists = '" . json_encode($raidingRoster[$i]['items']['trinket2']['bonusLists']) . "',

                 item_mainHand_id = " . $raidingRoster[$i]['items']['mainHand']['id'] . ",
				 item_mainHand_level = " . $raidingRoster[$i]['items']['mainHand']['level'] . ",
				 item_mainHand_enchant_id = '" . $raidingRoster[$i]['items']['mainHand']['enchant'] . "',
                 item_mainHand_gem0_id = " . $raidingRoster[$i]['items']['mainHand']['gem0'] . ",
                 item_mainHand_icon = '" . $raidingRoster[$i]['items']['mainHand']['icon'] . "',
				 item_mainHand_name = '" . $db->real_escape_string($raidingRoster[$i]['items']['mainHand']['name']) . "',
				 item_mainHand_quality = " . $raidingRoster[$i]['items']['mainHand']['quality'] . ",
				 item_mainHand_bonusLists = '" . json_encode($raidingRoster[$i]['items']['mainHand']['bonusLists']) . "',

                 item_offHand_id = " . $raidingRoster[$i]['items']['offHand']['id'] . ",
				 item_offHand_level = " . $raidingRoster[$i]['items']['offHand']['level'] . ",
                 item_offHand_gem0_id = " . $raidingRoster[$i]['items']['offHand']['gem0'] . ",
                 item_offHand_icon = '" . $raidingRoster[$i]['items']['offHand']['icon'] . "',
				 item_offHand_name = '" . $db->real_escape_string($raidingRoster[$i]['items']['offHand']['name']) . "',
				 item_offHand_quality = " . $raidingRoster[$i]['items']['offHand']['quality'] . ",
				 item_offHand_bonusLists = '" . json_encode($raidingRoster[$i]['items']['offHand']['bonusLists']) . "'";
				 
                //echo '<pre>'. $query . '</pre>';


        if ($db->query($query) == true) {
            echo "User <b>" . $raidingRoster[$i]['name'] . "</b> inserted <br>";
        }
        else {
            echo "Error inserting user " . $raidingRoster[$i]['name'] . "<br>";
            echo "MySQL Reported Error: " . $db->error . '<br><br>';
        }

        // Mounts
        // This changed in 5.X
        $mounts = array(0, 0, 0, 0, 0, 0, 0, 0, 0);

        foreach($raidingRoster[$i]['mounts']['collected'] as $mountCheck) {

            switch ($mountCheck['spellId']) {

            case 97493:     // Firehawk
                $mounts[0] = 1;
                break;
            case 107845:    // Life-Binder's
                $mounts[1] = 1;
                break;
            case 107842:    // Blazing Drake
                $mounts[2] = 1;
                break;
            case 72286:     // Invincible
                $mounts[3] = 1;
                break;
            case 63796:     // Mimron's
                $mounts[4] = 1;
                break;
            case 40192:     // Ashes
                $mounts[5] = 1;
                break;
			case 148417:    // Kor'kron Juggernaut
				$mounts[6] = 1;
				break;
			case 171621:    // Ironhoof Destroyer
				$mounts[7] = 1;
				break;
			case 182912:    // Felsteel Annihilator
				$mounts[8] = 1;
				break;
			default:
                break;
            }
        }

        $query = "INSERT INTO SkunkMounts SET
                 pureblood_firehawk = " . $mounts[0] . ",
                 life_binders_handmaiden = " . $mounts[1] . ",
                 blazing_drake = " . $mounts[2] . ",
                 invicible = " . $mounts[3] . ",
                 mimirons_head = " . $mounts[4] . ",
                 ashes_of_alar = " . $mounts[5] . ",
				 korkron_jugg = " . $mounts[6] . ",
				 ironhoof_destroyer = " . $mounts[7] . ",
				 felsteel_annihilator = " . $mounts[8];

        if ($db->query($query) == true) {
            echo "User mounts for <b>" . $raidingRoster[$i]['name'] . "</b> inserted <br>";
        }
        else {
            echo "Error inserting user mounts for " . $raidingRoster[$i]['name'] . "<br>";
            echo "MySQL Reported Error: " . $db->error . '<br><br>';
        }


        //////////////////////////////////////////////////////
        // Achievements
        $tier11 = 0;
        $tier12 = 0;
        $tier13 = 0;
		$tier14_1 = 0;
		$tier14_2 = 0;
        $tier15 = 0;
        $tier16 = 0;
		$tier17_1 = 0;
		$tier17_2 = 0;
		$cModes = 0;
		$pGrounds = 0;
		$wow10 = 0;
        

        // T11
        for ($j = 0; $j < count($tier11Achieve); $j++) {
            if (in_array($tier11Achieve[$j], $raidingRoster[$i]['achievements']['achievementsCompleted'] )) {
                $tier11 += (1 << $j);
            }
        }

        // T12
        for ($j = 0; $j < count($tier12Achieve); $j++) {
            if (in_array($tier12Achieve[$j], $raidingRoster[$i]['achievements']['achievementsCompleted'])) {
                $tier12 += (1 << $j);
            }
        }

        // T13
        for ($j = 0; $j < count($tier13Achieve); $j++) {
            if (in_array($tier13Achieve[$j], $raidingRoster[$i]['achievements']['achievementsCompleted'])) {

                $tier13 += (1 << $j);
            }
        }
		
		// T14
		// Split into 2
        for ($j = 0; $j < 16; $j++) {
            if (in_array($tier14Achieve[$j], $raidingRoster[$i]['achievements']['achievementsCompleted'])) {

                $tier14_1 += (1 << $j);
            }
        }
		
	    for ($j = 0; $j < 16; $j++) {
            if (in_array($tier14Achieve[$j+16], $raidingRoster[$i]['achievements']['achievementsCompleted'])) {

                $tier14_2 += (1 << $j);
            }
        }

        // T15
        for ($j = 0; $j < count($tier15Achieve); $j++) {
            if (in_array($tier15Achieve[$j], $raidingRoster[$i]['achievements']['achievementsCompleted'])) {

                $tier15 += (1 << $j);
            }
        }

        // T16
        for ($j = 0; $j < count($tier16Achieve); $j++) {
            if (in_array($tier16Achieve[$j], $raidingRoster[$i]['achievements']['achievementsCompleted'])) {

                $tier16 += (1 << $j);
            }
        }
		// T17
		// Split into 2
        for ($j = 0; $j < 17; $j++) {
            if (in_array($tier17Achieve[$j], $raidingRoster[$i]['achievements']['achievementsCompleted'])) {

                $tier17_1 += (1 << $j);
            }
        }
		
	    for ($j = 0; $j < 17; $j++) {
            if (in_array($tier17Achieve[$j+17], $raidingRoster[$i]['achievements']['achievementsCompleted'])) {

                $tier17_2 += (1 << $j);
            }
        }
		// Challenge Modes
        for ($j = 0; $j < count($challengeModesWoD); $j++) {
            if (in_array($challengeModesWoD[$j], $raidingRoster[$i]['achievements']['achievementsCompleted'])) {

                $cModes += (1 << $j);
            }
        }	
		// Proving Grounds
        for ($j = 0; $j < count($provingGroundsWoD); $j++) {
            if (in_array($provingGroundsWoD[$j], $raidingRoster[$i]['achievements']['achievementsCompleted'])) {

                $pGrounds += (1 << $j);
            }
        }
		
		// Wow 10th
		// Southshore slayer
		if(in_array(9566, $raidingRoster[$i]['achievements']['achievementsCompleted'])){
			$wow10 += (1 << 0);
		}
		// Corehound
		if(in_array(9550, $raidingRoster[$i]['achievements']['achievementsCompleted'])){
			$wow10 += (1 << 1);
		}
		

        $query = "INSERT INTO SkunkAchievements SET
                 tier11 = " . $tier11 . ",
                 tier12 = " . $tier12 . ",
                 tier13 = " . $tier13 . ",
				 tier14_1 = " . $tier14_1 . ",
				 tier14_2 = " . $tier14_2 . ",
                 tier15 = " . $tier15 . ",
                 tier16 = " . $tier16 . ",
				 tier17_1 = " . $tier17_1 . ",
				 tier17_2 = " . $tier17_2 . ",
                 cModes = " . $cModes . ", 
				 pGrounds = " . $pGrounds . ",
				 wow10 = ". $wow10;
        if ($db->query($query) == true) {
			//echo $query;
            echo "Achievements for <b>" . $raidingRoster[$i]['name'] . "</b> inserted <br>";
        }
        else {
            echo "Error inserting achievements for " . $raidingRoster[$i]['name'] . "<br>";
            echo "MySQL Reported Error: " . $db->error . '<br><br>';
        }


    }  // End individual character loop


    // Figure out what spell ids are missing names/icons
    // Remove the conditional to do a full item reset (WARNING MAY REACH REQUEST LIMIT)
    $query = 'SELECT * FROM SkunkSpells WHERE ISNULL(spell_name) OR ISNULL(spell_icon) OR spell_icon = "" OR spell_name = "" AND spell_id != 0';
    $result = $db->query($query);
    if ($result) {

        // Update the information
        while ($row = $result->fetch_object()) {
		
            if ($row->spell_id != 0) {

                $url = $url = $armoryServer . $spellBaseName . $row->spell_id . "?local=us_EN";

               echo $url . '<br>';

                $spellInfo = getJson($url);

                //print_r($spellInfo);

                $iName = $db->real_escape_string($spellInfo['name']);
                $iIcon = $db->real_escape_string($spellInfo['icon']);

                $query = "UPDATE SkunkSpells SET spell_name = '$iName', spell_icon = '$iIcon' WHERE spell_id = " . $row->spell_id;

                if ($db->query($query)) {
                    echo "Spell $iName updated <br>";
                }
                else {
                    echo "Error updating spell $iName. <br>";
                    echo "MySQL Reported Error: " . $db->error . '<br><br>';
                }
                
                // Download the item icon to the server if we do not already have it
                $filePath = '/' . $iconFolder . $iIcon . '.jpg';
                if (!file_exists("$filePath")) {

                    $iconUrl = $blizzardIconBaseLarge . $iIcon . '.jpg';

                    $icon = getImage($iconUrl);

                    $iconFile = fopen($iconFolder . $iIcon . '.jpg', 'w');
                    fwrite($iconFile, $icon);
                    fclose($iconFile);
                }
                
            }
        }
    }
    else {
        echo "All spells are up to date<br>";
    }




    //$db->close();
}
/*
//////////////////////////////////////////////////////////////////////////////////////////
// If we need to update Item Icons, uncomment this and run once
//
// Connect to the Database
$db = new MySQLi($dbhostname, $dbusername, $dbpassword, $dbname);
if ($db->connect_errno) {
	echo "Failed to connect to mysql: " . $db->connect_error;
}

// Set Utf 8
$query = "SET NAMES utf8";
$db->query($query);


$query = "SELECT * FROM SkunkItems";
$result = $db->query($query);

if($result){

	while (($row = $result->fetch_object()) != NULL) {
	
		// See if the file exists
		$iIcon = $row->item_icon;
		
		// Download the item icon to the server if we do not already have it
		$filePath = '/' . $iconFolder . $iIcon . '.jpg';
		
		echo $filePath . '<br>';
				
		if (!file_exists("$filePath")) {

			$iconUrl = $blizzardIconBaseLarge . $iIcon . '.jpg';

			$icon = getImage($iconUrl);

			$iconFile = fopen($iconFolder . $iIcon . '.jpg', 'w');
			fwrite($iconFile, $icon);
			fclose($iconFile);
			
			echo 'Icon Downloaded! ->' . $iIcon . '.jpg';
		}
	}
}


*/
//////////////////////////////////////////////////////////////////////////////////////////
// If we need to update Achievement Icons, uncomment this and run once
/*
// Create a table with the images
// Achievement Icons
    $query = "CREATE TABLE SkunkAchievementIcons
             (
             achievement_id int NOT NULL,
             PRIMARY KEY(achievement_id),
             icon_name varchar(70)
             )";
     if ($db->query($query)) {
        echo "Table <b>SkunkAchievementIcons</b> created. <br>";
    }
	else{
		echo "Error creating the SkunkAchievementIcons table<br>";
		echo "MySQL Reported Error: " . $db->error . '<br><br>';
	}



$allAchievements = array($tier11Achieve, $tier12Achieve, $tier13Achieve, $tier14Achieve, $tier15Achieve, $tier16Achieve, $tier17Achieve, $challengeModesWoD, $provingGroundsWoD);


for($i = 0; $i < count($allAchievements); $i++){

	//echo 'i = ' . $i . '<br>';

	foreach($allAchievements[$i] as $currentAchievement){
			
		// Get the achievement information
		$url = $armoryServer . $achievementBaseName . '/' . $currentAchievement . "?locale=en_US";
		$achievementInfo = getJson($url);

		//echo '<pre>';
		////print_r($achievementInfo);
		//echo '</pre>';
		
		// Get the achievement Image
		$aIconName = $achievementInfo['icon'];
		
		// Insert this into the database
		$query = "INSERT INTO SkunkAchievementIcons SET achievement_id =". $currentAchievement .", icon_name = '". $aIconName ."'";
		if ($db->query($query)) {
        echo "Inserted achievement: $allAchievements[$i] with icon name $aIconName";
    }
	else{
		echo "Error setting the SkunkAchievementIcons table<br>";
		echo "MySQL Reported Error: " . $db->error . '<br><br>';
		//echo $query . "<br>";
	}
		
		echo $aIconName . '<br>';
		
		// See if we already have it
		$filePath = '/' . $iconFolder . $aIconName . '.jpg';
		
		if (!file_exists("$filePath")) {

			$iconUrl = $blizzardIconBaseLarge . $aIconName . '.jpg';

			$icon = getImage($iconUrl);

			$iconFile = fopen($iconFolder . $aIconName . '.jpg', 'w');
			fwrite($iconFile, $icon);
			fclose($iconFile);
		}
		
	}
}
*/
$db->close();
echo 'Number of Requests made to Blizzard: ' . $numRequests;



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

