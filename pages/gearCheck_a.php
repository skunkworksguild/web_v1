<?php
// Guild Summary
// Database Info
require(dirname(__FILE__) . '/../../sw_include/sw_db.php');

// Making sure the date is correct
date_default_timezone_set("America/Detroit");

// For MoP 88 is the lowest 'rare-quality' gem
// Change as needed
$gemMinItemLevel = 88;


$itemColorArray = array(
       '#CCCCCC',    // Vendor Trash (Gray)
       '#FFFFFF',    // Common (White)
	   '#1EFF00',    // Uncommon (Green)
	   '#0070FF',    // Rare (Blue)
       '#A335EE',    // Epic (Purple)
	   '#FF8000'     // Legendary (Orange)
);

$classColorArray = array(
    'Death Knight' => '#C41F3B', // red
    'Druid' => '#FF7D0A', // orange
    'Hunter' => '#ABD473', // green
    'Mage' => '#69CCF0', // light blue
    'Monk' => '#00FF96', // spring green
    'Paladin' => '#F58CBA', // pink
    'Priest' => '#FFFFFF', // white
    'Rogue' => '#FFF569', // yellow
    'Shaman' => '#0070DE', // blue
    'Warlock' => '#9482C9', // purple
    'Warrior' => '#C79C6E'  // tan
);

$guildRankColor = array(
    'Guild Leader' => 'color:#CC6600',
    'Officer' => 'color:#6633CC',
    'Raider' => 'color:#3366FF',
    'Aspirant' => 'color:#3399FF'
);
 
$gearSlot = array(
	'head',        // 0
	'neck',        // 1
	'shoulder',    // 2
	'back',        // 3
	'chest',       // 4
	//'shirt',       // 5
	//'tabard',      // 6
	'wrist',       // 7
	'hands',       // 8
	'waist',       // 9
	'legs',        // 10
	'feet',        // 11
	'finger1',       // 12
	'finger2',       // 13
	'trinket1',    // 14
	'trinket2',    // 15
	'mainHand',    // 16
	'offHand',     // 17
);

// Timestamp of the last update
$lastUpdate = 0;

/*
// Reforging: 
// Information not verified
*/
// Possible colors?
// Spirit - Blue
// Dodge - Purple
// Parry - Pink
// Hit - Yellow
// Crit - Green
// Haste - Orange
// Expertise - Cyan
// Mastery - Red

$reforge[113] = '<span style="color:#FF0000">	    Spirit</span> -> <span style="color:#00FF00">Dodge</span>';
$reforge[114] = '<span style="color:#FF0000">	    Spirit</span> -> <span style="color:#00FF00">Parry</span>';
$reforge[115] = '<span style="color:#FF0000">	    Spirit</span> -> <span style="color:#00FF00">Hit</span>';
$reforge[116] = '<span style="color:#FF0000">	    Spirit</span> -> <span style="color:#00FF00">Crit</span>';
$reforge[117] = '<span style="color:#FF0000">	    Spirit</span> -> <span style="color:#00FF00">Haste</span>';
$reforge[118] = '<span style="color:#FF0000">		Spirit</span> -> <span style="color:#00FF00">Expertise</span>';
$reforge[119] = '<span style="color:#FF0000">	 	Spirit</span> -> <span style="color:#00FF00">Mastery</span>';
$reforge[120] = '<span style="color:#FF0000">    	 Dodge</span> -> <span style="color:#00FF00">Spirit</span>';
$reforge[121] = '<span style="color:#FF0000">		 Dodge</span> -> <span style="color:#00FF00">Parry</span>';
$reforge[122] = '<span style="color:#FF0000">		 Dodge</span> -> <span style="color:#00FF00">Hit</span>';
$reforge[123] = '<span style="color:#FF0000">		 Dodge</span> -> <span style="color:#00FF00">Crit</span>';
$reforge[124] = '<span style="color:#FF0000">		 Dodge</span> -> <span style="color:#00FF00">Haste</span>';
$reforge[125] = '<span style="color:#FF0000">		 Dodge</span> -> <span style="color:#00FF00">Expertise</span>';
$reforge[126] = '<span style="color:#FF0000">		 Dodge</span> -> <span style="color:#00FF00">Mastery</span>';
$reforge[127] = '<span style="color:#FF0000">		 Parry</span> -> <span style="color:#00FF00">Spirit</span>';
$reforge[128] = '<span style="color:#FF0000">	 	 Parry</span> -> <span style="color:#00FF00">Dodge</span>';
$reforge[129] = '<span style="color:#FF0000">	 	 Parry</span> -> <span style="color:#00FF00">Hit</span>';
$reforge[130] = '<span style="color:#FF0000">    	 Parry</span> -> <span style="color:#00FF00">Crit</span>';
$reforge[131] = '<span style="color:#FF0000">	     Parry</span> -> <span style="color:#00FF00">Haste</span>';
$reforge[132] = '<span style="color:#FF0000">	     Parry</span> -> <span style="color:#00FF00">Expertise</span>';
$reforge[133] = '<span style="color:#FF0000">	     Parry</span> -> <span style="color:#00FF00">Mastery</span>';
$reforge[134] = '<span style="color:#FF0000">	       Hit</span> -> <span style="color:#00FF00">Spirit</span>';
$reforge[135] = '<span style="color:#FF0000">	       Hit</span> -> <span style="color:#00FF00">Dodge</span>';
$reforge[136] = '<span style="color:#FF0000">	       Hit</span> -> <span style="color:#00FF00">Parry</span>';
$reforge[137] = '<span style="color:#FF0000">	       Hit</span> -> <span style="color:#00FF00">Crit</span>';
$reforge[138] = '<span style="color:#FF0000">	       Hit</span> -> <span style="color:#00FF00">Haste</span>';
$reforge[139] = '<span style="color:#FF0000">	       Hit</span> -> <span style="color:#00FF00">Expertise</span>';
$reforge[140] = '<span style="color:#FF0000">	       Hit</span> -> <span style="color:#00FF00">Mastery</span>';
$reforge[141] = '<span style="color:#FF0000"> 	      Crit</span> -> <span style="color:#00FF00">Spirit</span>';
$reforge[142] = '<span style="color:#FF0000">         Crit</span> -> <span style="color:#00FF00">Dodge</span>';
$reforge[143] = '<span style="color:#FF0000">         Crit</span> -> <span style="color:#00FF00">Parry</span>';
$reforge[144] = '<span style="color:#FF0000">         Crit</span> -> <span style="color:#00FF00">Hit</span>';
$reforge[145] = '<span style="color:#FF0000">         Crit</span> -> <span style="color:#00FF00">Haste</span>';
$reforge[146] = '<span style="color:#FF0000">         Crit</span> -> <span style="color:#00FF00">Expertise</span>';
$reforge[147] = '<span style="color:#FF0000">         Crit</span> -> <span style="color:#00FF00">Mastery</span>';
$reforge[148] = '<span style="color:#FF0000">	     Haste</span> -> <span style="color:#00FF00">Spirit</span>';
$reforge[149] = '<span style="color:#FF0000">	     Haste</span> -> <span style="color:#00FF00">Dodge</span>';
$reforge[150] = '<span style="color:#FF0000">	     Haste</span> -> <span style="color:#00FF00">Parry</span>';
$reforge[151] = '<span style="color:#FF0000">	     Haste</span> -> <span style="color:#00FF00">Hit</span>';
$reforge[152] = '<span style="color:#FF0000">	     Haste</span> -> <span style="color:#00FF00">Crit</span>';
$reforge[153] = '<span style="color:#FF0000">	     Haste</span> -> <span style="color:#00FF00">Expertise</span>';
$reforge[154] = '<span style="color:#FF0000">        Haste</span> -> <span style="color:#00FF00">Mastery</span>';
$reforge[155] = '<span style="color:#FF0000">    Expertise</span> -> <span style="color:#00FF00">Spirit</span>';
$reforge[156] = '<span style="color:#FF0000">    Expertise</span> -> <span style="color:#00FF00">Dodge</span>';
$reforge[157] = '<span style="color:#FF0000">    Expertise</span> -> <span style="color:#00FF00">Parry</span>';
$reforge[158] = '<span style="color:#FF0000">    Expertise</span> -> <span style="color:#00FF00">Hit</span>';
$reforge[159] = '<span style="color:#FF0000">    Expertise</span> -> <span style="color:#00FF00">Crit</span>';
$reforge[160] = '<span style="color:#FF0000">    Expertise</span> -> <span style="color:#00FF00">Haste</span>';
$reforge[161] = '<span style="color:#FF0000">    Expertise</span> -> <span style="color:#00FF00">Mastery</span>';
$reforge[162] = '<span style="color:#FF0000">      Mastery</span> -> <span style="color:#00FF00">Spirit</span>';
$reforge[163] = '<span style="color:#FF0000">      Mastery</span> -> <span style="color:#00FF00">Dodge</span>';
$reforge[164] = '<span style="color:#FF0000">      Mastery</span> -> <span style="color:#00FF00">Parry</span>';
$reforge[165] = '<span style="color:#FF0000">      Mastery</span> -> <span style="color:#00FF00">Hit</span>';
$reforge[166] = '<span style="color:#FF0000">      Mastery</span> -> <span style="color:#00FF00">Crit</span>';
$reforge[167] = '<span style="color:#FF0000">      Mastery</span> -> <span style="color:#00FF00">Haste</span>';
$reforge[168] = '<span style="color:#FF0000">      Mastery</span> -> <span style="color:#00FF00">Expertise</span>';

// Gem Flags
// Binary
$gemFlags['RED']       = 1;
$gemFlags['BLUE']      = 2;
$gemFlags['PURPLE']    = 3;  // Red + Blue
$gemFlags['YELLOW']    = 4;
$gemFlags['ORANGE']    = 5;  // Red + Yellow
$gemFlags['GREEN']     = 6;  // Yellow + Blue

$gemFlags['META']      = 8;
$gemFlags['HYDRAULIC'] = 16; // Sha-Touched
$gemFlags['COGWHEEL']  = 32; // Engineering


$gemSocketColorArray = array( 'RED' => '#FF0000',
					    	  'YELLOW' => '#FFFF00',
					    	  'BLUE' => '#0000FF',
					    	  'META' => '#FFFFFF',
					    	  'HYDRAULIC' => '#FF8000',
					    	  'COGWHEEL' => '$FFFFFF');



/*

// Conqueror = Paladin, Priest, Warlock
// Protector = Hunter, Shaman, Warrior, Monk
// Vanquisher = Druid, Death Knight, Mage, Rogue
*/

// Function to return color
// Use the name input for special cases

$query = "SET NAMES utf8";
$db->query($query);


?>

<div class="entireSection">
    <div class="title">
		<div class="newsSubject"><img src="images/gear6.png">Raid Gear Check</div>
    </div>
   
    <div class="section">
	
    <div class="statsTitle">
    <?php

    $query = "SELECT * FROM SkunkRoster WHERE guild_rank = 'Alt' OR guild_rank = 'Officer Alt' ORDER BY class, name";
	$result = $db->query($query);
	$numRaiders = $result->num_rows;

	
	echo '<table width=100%>';
	echo '<tr><td>';
	
	
	// Assume 11 columns.. Calculate rows from that
	$nR = $numRaiders / 9;
	
	for ($i = 0; $i < $numRaiders; $i++) {

		$character = $result->fetch_assoc();

	
        if($i % $nR == 0){
        	echo '</td><td>';
        }

        // Get class color
        echo '<a href="#' . $character['name'] . '"><span style="color:'. $classColorArray[$character['class']] . '">' . $character['name'] . '</span></a><br>';

	}

	echo '</td></tr>';
	echo '</table>';
    ?>
    </div>
    
	
	<?php

    // Do the same exact query again
	$query = "SELECT * FROM SkunkRoster WHERE guild_rank = 'Alt' OR guild_rank = 'Officer Alt' ORDER BY class, name";
	$result = $db->query($query);

	if ($result) {
	
		$numRaiders = $result->num_rows;
		$raiderIlvlTotal = 0;
	
			
        //// Gear Section
        // Raider loop
		for ($i = 0; $i < $numRaiders; $i++) {
		
			// Character
			$character = $result->fetch_assoc();

			/*
			echo '<pre>';
			print_r($character);
			echo '</pre>';
			*/


			// Only get a single timestamp for now (may want to show individual updates in the future)
			if($i == 0){
				// E: System time off?  Timestamp is not converting to CST (system is MST).  Need to look at in future.
				$lastUpdate = strtotime($character['last_update']) + 3600;
			}
			
			
			echo '<div>';
			
			echo '<div style="display:table-cell; float: left;"><a name="'. $character['name'] . '"><span style="font-weight:bold; font-size:250%; color:'. $classColorArray[$character['class']] . '">' . $character['name'] . '</span></a></div>';		
			
			// Computer the average equipped item level manually
			$query = "SELECT AVG(item_ilevel) as avgCompLevel 
					  FROM SkunkItems WHERE 
					  item_id = ".  $character['item_head_id'] ." OR
					  item_id = ".  $character['item_neck_id'] ."  OR
					  item_id = ".  $character['item_shoulder_id'] ."  OR
					  item_id = ".  $character['item_back_id'] ."  OR
					  item_id = ".  $character['item_chest_id'] ."  OR
					  item_id = ".  $character['item_wrist_id'] ."  OR
					  item_id = ".  $character['item_hands_id'] ."  OR
					  item_id = ".  $character['item_waist_id'] ."  OR
					  item_id = ".  $character['item_legs_id'] ."  OR
					  item_id = ".  $character['item_feet_id'] ."  OR
					  item_id = ".  $character['item_finger1_id'] ."  OR
					  item_id = ".  $character['item_finger2_id'] ."  OR
					  item_id = ".  $character['item_trinket1_id'] ."  OR
					  item_id = ".  $character['item_trinket2_id'] ."  OR
					  item_id = ".  $character['item_mainHand_id'] ."  OR
					  item_id = ".  $character['item_offHand_id'] ." AND item_id != 0";
			$itemLevelResult = $db->query($query);
			if($itemLevelResult){			
				// Compute average upgrade item levels with a running average

				$numSlotsEquipped = 0;
				$upgradeTotal = 0;
				$upgradeAverage = 0;
				for ($j = 0; $j < sizeof($gearSlot); $j++) {
					if($character["item_" . $gearSlot[$j] . "_id"] != 0){				
						$numSlotsEquipped++;
						$upgradeTotal+= $character["item_" . $gearSlot[$j] . "_upgrade_itemLevelIncrement"];
					}
				}
				$upgradeAverage = $upgradeTotal / $numSlotsEquipped;

				$ilvl = $itemLevelResult->fetch_object();
				echo '<div style="display:table-cell; float: right; margin-top:20px; padding-right:8px;"> Average Item Level: ' . (int)$ilvl->avgCompLevel;
				if($upgradeAverage > 0){

					echo '<span style="color:#00FF00">  (' . floor(((int)$ilvl->avgCompLevel) + $upgradeAverage) . ')</span>';
				}
				echo '</div>';	
			}
			else 
				echo '<td width="7%" style="text-align: center"> Error computing average ilvl</td>';

			echo "<br>";
			echo '<table style="border-collapse:collapse; width: 100%; border: 2px solid black; font-size:0.9em;">';
			
			// Gear slot loop (16 atm)
			for ($j = 0; $j < sizeof($gearSlot); $j++) {
			
				$item_id = $character["item_" . $gearSlot[$j] . "_id"];

				$item_itemLevelIncrement = $character["item_" . $gearSlot[$j] . "_upgrade_itemLevelIncrement"];

				$severity = 0;

				// If something doesn't exist, make it 0
				switch($gearSlot[$j]){		
				
					// Tinker + Enchant + Enchant Spell
					case "back":				
						$enchant_id = $character["item_" . $gearSlot[$j] . "_enchant_id"];
						$gem0_id = $character["item_" . $gearSlot[$j] . "_gem0_id"];
						$gem1_id = $character["item_" . $gearSlot[$j] . "_gem1_id"];
						$gem2_id = $character["item_" . $gearSlot[$j] . "_gem2_id"];
						$tinker_id = $character["item_" . $gearSlot[$j] . "_tinker_id"];
						$extraSocket = 0;
						break;
						
					// Extra Socket + Enchant
					case "wrist":
						$enchant_id = $character["item_" . $gearSlot[$j] . "_enchant_id"];
						$gem0_id = $character["item_" . $gearSlot[$j] . "_gem0_id"];
						$gem1_id = $character["item_" . $gearSlot[$j] . "_gem1_id"];
						$gem2_id = $character["item_" . $gearSlot[$j] . "_gem2_id"];
						$extraSocket = $character["item_" . $gearSlot[$j] . "_extraSocket"];
						$tinker_id = 0;
						break;
					
					// Tinker + Extra Socket + Enchant
					case "hands":
						$enchant_id = $character["item_" . $gearSlot[$j] . "_enchant_id"];
						$gem0_id = $character["item_" . $gearSlot[$j] . "_gem0_id"];
						$gem1_id = $character["item_" . $gearSlot[$j] . "_gem1_id"];
						$gem2_id = $character["item_" . $gearSlot[$j] . "_gem2_id"];
						$tinker_id = $character["item_" . $gearSlot[$j] . "_tinker_id"];
						$extraSocket = $character["item_" . $gearSlot[$j] . "_extraSocket"];
						break;
					
					// Tinker + Extrasocket - Enchant
					case "waist":
						$gem0_id = $character["item_" . $gearSlot[$j] . "_gem0_id"];
						$gem1_id = $character["item_" . $gearSlot[$j] . "_gem1_id"];
						$gem2_id = $character["item_" . $gearSlot[$j] . "_gem2_id"];
						$tinker_id = $character["item_" . $gearSlot[$j] . "_tinker_id"];
						$extraSocket = $character["item_" . $gearSlot[$j] . "_extraSocket"];
						$enchant_id = 0;
						break;
												
					// Normal
					case "shoulder":
					case "chest":
					case "legs":
					case "feet":
					case "finger1":
					case "finger2":
					case "mainHand":			
					case "offHand":
						$enchant_id = $character["item_" . $gearSlot[$j] . "_enchant_id"];
						$gem0_id = $character["item_" . $gearSlot[$j] . "_gem0_id"];
						$gem1_id = $character["item_" . $gearSlot[$j] . "_gem1_id"];
						$gem2_id = $character["item_" . $gearSlot[$j] . "_gem2_id"];	
						$tinker_id = 0;
						$extraSocket = 0;
						break;							
					
					// No enchant
					case "head":			
					case "neck":
					case "trinket1":
					case "trinket2":
						$gem0_id = $character["item_" . $gearSlot[$j] . "_gem0_id"];
						$gem1_id = $character["item_" . $gearSlot[$j] . "_gem1_id"];
						$gem2_id = $character["item_" . $gearSlot[$j] . "_gem2_id"];
						$enchant_id = 0;
						$tinker_id = 0;
						$extraSocket = 0;
						break;
					
					
					default:
						break;
				}	

				//// Item slot row starts here
				if($j % 2 == 0){
					echo '<tr style="background-image:url(\'images/50ob.png\');">';
				}
				else 
					echo '<tr style="background-image:url(\'images/25ob.png\');">';
		
			    // Get the main item an then query the db for gems/enchants/etc based on an id > 0 and not empty
				$query = "SELECT * FROM SkunkItems WHERE item_id = $item_id";
				$itemResult = $db->query($query);

				$item = $itemResult->fetch_object();
				
				/*
				echo '<pre>';
				print_r($item);
				echo '</pre>';
				*/
				
				if($item->item_id != 0){
					$itemColor = $itemColorArray[$item->item_quality];
				}
				
				$reforgeNumber = "item_" . $gearSlot[$j] . "_reforge";

				// Add to this as we go through the columns.  It will be printed out on the last column.
			    $suggestion = "";
			    $gemItemLevelWarning = false;

			    ///////////////////////////////////////////////////////////
			    // Get the number of expected Gem Sockets
			    $numSockets = 0;
			    $numSocketsEquipped = 0;
			    if (isset($item->item_socket0) && $item->item_socket0 != '0') { $numSockets++; }
			    if (isset($item->item_socket1) && $item->item_socket1 != '0') { $numSockets++; }
			    if (isset($item->item_socket2) && $item->item_socket2 != '0') { $numSockets++; }

				//echo $numSockets;
				
			    // Socket bonus never counts Profession sockets
		  	    // $numSocketBonus = $numSockets;
			    $socketBonusFilled = true; // assume true until proven otherwise

			    // If this is a belt there should be a belt buckle
			    if ($gearSlot[$j] == 'waist') { $numSockets++; }

			    // If this person is a blacksmith they should have one in their wrists + gloves
			    if ($gearSlot[$j] == 'wrist' && ($character['prof1'] === "Blacksmithing" || $character['prof2'] === "Blacksmithing" )) { $numSockets++; }
			    if ($gearSlot[$j] == 'hands' && ($character['prof1'] === "Blacksmithing" || $character['prof2'] === "Blacksmithing" )) { $numSockets++; }

			    ////////////////////////////////////////////////////////////
			    // WoWHead Link Options 
			    $wowheadLink = 'http://www.wowhead.com/item=' . $item->item_id . '" rel="';

				// gems
				if($gem0_id > 0) $wowheadLink .= 'gems=' . $gem0_id;
				if($gem1_id > 0) $wowheadLink .= ':' . $gem1_id;
				if($gem2_id > 0) $wowheadLink .= ':' . $gem2_id;	

				// enchants
				if($gem0_id > 0 && $enchant_id > 0){ 
					$wowheadLink .= '&amp;';
					$wowheadLink .= 'ench=' . $enchant_id;
				}
				
				// upgrades
				if( ($gem0_id > 0 || $enchant_id > 0) && $item_itemLevelIncrement > 0){
					$wowheadLink .= '&amp;';
					$wowheadLink .= 'upgd=' . ($item_itemLevelIncrement/4);					
				}
			
				// Gear Slot
				echo '<td style="width: 6%;">';
					echo ucfirst($gearSlot[$j]);
				echo '</td>';

				////////////////////////////////////////////////////////////////////////////////////////
				// Image
				// Now with Gems/Enchants in the link!
				echo '<td style="padding:3px; width: 4%;">';
				if($item->item_id == 0){
					echo '<a href="#"><img src="images/icons/empty.png" width="20" height="20" style="border:2px solid #333333; padding:0px; margin:0px; display: block;"></a>';
				}
				else {
					$iconBorder = 'style="border:2px solid'. $itemColor . '; padding:0px; margin:0px; display: block;"';
					echo '<a href="' . $wowheadLink . '"><img src="images/icons/' . $item->item_icon . '.jpg" width="20" height="20" '. $iconBorder .'> </a>';
				}
				echo '</td>';

				////////////////////////////////////////////////////////////////////////////////////////
				// Item level
				echo '<td style="width: 4%;">';
				echo ($item->item_id == 0) ? 'N/A' : $item->item_ilevel;
				echo '</td>';	

				////////////////////////////////////////////////////////////////////////////////////////
				// Item Upgrades
				echo '<td style="width: 4%; color:#00FF00;">';
				echo ($item_itemLevelIncrement == 0) ? '' : "+" . $item_itemLevelIncrement;
				echo '</td>';					
				

				////////////////////////////////////////////////////////////////////////////////////////
				// Name
				echo '<td style="padding:0; color:' . $itemColor . ' width: 28%;">';
				echo ($item->item_id == 0) ? 'Empty' : '<a href="' . $wowheadLink . '" style="font-size:0.9em; color:' . $itemColor . '; font-weight:normal; font-size:12px">' . $item->item_name . '</a>';
													   
														



				echo '</td>';
     
				////////////////////////////////////////////////////////////////////////////////////////
				// Reforge
				echo '<td style="width: 14%;">';
				if($character[$reforgeNumber] > 0){
					echo $reforge[$character[$reforgeNumber]];
				}
				echo '</td>';


				////////////////////////////////////////////////////////////////////////////////////////
				// Gem0
				echo '<td style="width: 3%;">';
				
				if($gem0_id > 0){
					// Gem exists, we need to look the item up in the item DB.
					$query = "SELECT * FROM SkunkItems WHERE item_id = $gem0_id";
					$gResult = $db->query($query);
					$gem = $gResult->fetch_object();
					
					if($item->item_socket0 != "0"){
						$iconBorder = 'style="border:2px solid'. $gemSocketColorArray[$item->item_socket0] . '; padding:0px; margin:0px; display: block;"';
					}
					else {

						$iconBorder = 'style="border:2px solid #FFFFFF; padding:0px; margin:0px; display: block;"';
					}

					echo '<a href="http://www.wowhead.com/item=' . $gem->item_id .'"><img src="images/icons/' . $gem->item_icon . '.jpg" width="20" height="20" '. $iconBorder .'> </a>';
				
					$numSocketsEquipped++;

					// Color check
					if($item->item_socket0 != '0'){
						if(($gemFlags[$item->item_socket0] & $gemFlags[$gem->item_socket0]) == 0){		
							$suggestion .= "No Socket Bonus";
							$severity += 1;
							$socketBonusFilled = false;
						}
					}

					// Item Level Check
					if($gem->item_ilevel < $gemMinItemLevel){
						$suggestion = ($suggestion != "" ? $suggestion . " + " : "") . "Gem iLevel";
						$gemItemLevelWarning = true;
						$severity += 3;
					}


				} else {
					// Check to see if there should be a gem here
					if($numSockets > 0){
						if($item->item_socket0 != "0"){
							$iconBorder = 'style="border:2px solid'. $gemSocketColorArray[$item->item_socket0] . '; padding:0px; margin:0px; display: block;"';
						}
						else {

							$iconBorder = 'style="border:2px solid #FFFFFF; padding:0px; margin:0px; display: block;"';
						}

		
						echo '<a href="#"><img src="images/icons/emptyRed.png" width="20" height="20"'. $iconBorder .'></a>';			
					
						$suggestion .= "Gem 1: Missing";
						$severity += 3;
					}
				}		


				echo '</td>';

				////////////////////////////////////////////////////////////////////////////////////////
				// Gem1
				echo '<td style="width: 3%;">';
				if($gem1_id > 0){
					// Gem exists, we need to look the item up in the item DB.
					$query = "SELECT * FROM SkunkItems WHERE item_id = $gem1_id";
					$gResult = $db->query($query);
					$gem = $gResult->fetch_object();
					if($item->item_socket1 != '0'){
						$iconBorder = 'style="border:2px solid'. $gemSocketColorArray[$item->item_socket1] . '; padding:0px; margin:0px; display: block;"';
					}
					else {

						$iconBorder = 'style="border:2px solid #FFFFFF; padding:0px; margin:0px; display: block;"';
					}
					echo '<a href="http://www.wowhead.com/item=' . $gem->item_id .'"><img src="images/icons/' . $gem->item_icon . '.jpg" width="20" height="20" '. $iconBorder .'> </a>';
									
					$numSocketsEquipped++;

					// Color check
					if($socketBonusFilled != false && $item->item_socket1 != '0'){
						if(($gemFlags[$item->item_socket1] & $gemFlags[$gem->item_socket0]) == 0 && $numSocketBonus > 1){
							$suggestion = ($suggestion != "" ? $suggestion . " + " : "") . "No Socket Bonus";
							$socketBonusFilled = false;
							$severity += 1;
						}
					}

					// Item Level Check
					if($gem->item_ilevel < $gemMinItemLevel && $gemItemLevelWarning = false){
						$suggestion = ($suggestion != "" ? $suggestion . " + " : "") . "Gem iLevel";
						$gemItemLevelWarning = true;
						$severity += 3;
					}

				} else {
					// Check to see if there should be a gem here
					if($numSockets > 1){
						if($item->item_socket1 != "0"){
							$iconBorder = 'style="border:2px solid'. $gemSocketColorArray[$item->item_socket1] . '; padding:0px; margin:0px; display: block;"';
						}
						else {

							$iconBorder = 'style="border:2px solid #FFFFFF; padding:0px; margin:0px; display: block;"';
						}

						echo '<a href="#"><img src="images/icons/emptyRed.png" width="20" height="20"'. $iconBorder .'></a>';	

						$suggestion = ($suggestion != "" ? $suggestion . " + " : "") . "Gem 2: Missing";
						$severity += 3;
					}
				}				
				echo '</td>';


				////////////////////////////////////////////////////////////////////////////////////////
				// Gem2
				echo '<td style="width: 3%;">';
				if($gem2_id > 0){
					// Gem exists, we need to look the item up in the item DB.
					$query = "SELECT * FROM SkunkItems WHERE item_id = $gem2_id";
					$gResult = $db->query($query);
					$gem = $gResult->fetch_object();
					if($item->item_socket2 != '0'){
						$iconBorder = 'style="border:2px solid'. $gemSocketColorArray[$item->item_socket2] . '; padding:0px; margin:0px; display: block;"';
					}
					else {

						$iconBorder = 'style="border:2px solid #FFFFFF; padding:0px; margin:0px; display: block;"';
					}
					echo '<a href="http://www.wowhead.com/item=' . $gem->item_id .'"><img src="images/icons/' . $gem->item_icon . '.jpg" width="20" height="20" '. $iconBorder .'> </a>';
								
					$numSocketsEquipped++;

					// Color check
					if($socketBonusFilled != false && $item->item_socket2 != '0'){
						if(($gemFlags[$item->item_socket2] & $gemFlags[$gem->item_socket0]) == 0 && $numSocketBonus > 2){
							$suggestion = ($suggestion != "" ? $suggestion . " + " : "") . "No Socket Bonus";
							$severity += 1;
						}
					}

					// Item Level Check
					if($gem->item_ilevel < $gemMinItemLevel && $gemItemLevelWarning = false){
						$suggestion = ($suggestion != "" ? $suggestion . " + " : "") . "Gem iLevel";
						$severity += 3;
					}


				} else {
					// Check to see if there should be a gem here
					if($numSockets > 2){
						if($item->item_socket2 != "0"){
							$iconBorder = 'style="border:2px solid'. $gemSocketColorArray[$item->item_socket2] . '; padding:0px; margin:0px; display: block;"';
						}
						else {

							$iconBorder = 'style="border:2px solid #FFFFFF; padding:0px; margin:0px; display: block;"';
						}

						echo '<a href="#"><img src="images/icons/emptyRed.png" width="20" height="20"'. $iconBorder .'></a>';	

						$suggestion = ($suggestion != "" ? $suggestion . " + " : "") . "Gem 3: Missing";
						$severity += 3;
					}
				}							
				echo '</td>';

				////////////////////////////////////////////////////////////////////////////////////////
				// Enchant
				echo '<td style="width: 3%;">';
				if($enchant_id > 0){
					// Gem exists, we need to look the item up in the item DB.
					$query = "SELECT * FROM SkunkSpells WHERE spell_id = $enchant_id";
					$eResult = $db->query($query);
					$enchant = $eResult->fetch_object();
					$iconBorder = 'style="border:2px solid; padding:0px; margin:0px; display: block;"';
					echo '<a href="http://www.wowhead.com/spell=' . $enchant->spell_id .'"><img src="images/icons/' . $enchant->spell_icon . '.jpg" width="20" height="20" '. $iconBorder .'> </a>';
				}	
				elseif($item->item_id != 0 &&
					   $gearSlot[$j] != "head" &&
					   $gearSlot[$j] != "neck" &&
					   $gearSlot[$j] != "waist" &&
					   $gearSlot[$j] != "trinket1" &&
					   $gearSlot[$j] != "trinket2" &&
					   ($gearSlot[$j] != "finger1" && ($character['prof1'] != "Enchanting" && $character['prof2'] != "Enchanting")) &&
					   ($gearSlot[$j] != "finger2" && ($character['prof1'] != "Enchanting" && $character['prof2'] != "Enchanting"))
					  ) 
				{
					echo '<a href="#"><img src="images/icons/empty.png" width="20" height="20" style="border:2px solid #FF0000; padding:0px; margin:0px; display: block;"></a>';			
					$suggestion = ($suggestion != "" ? $suggestion . " + " : "") . "No Enchant";
					$severity += 3;
				}			
				echo '</td>';
					
				////////////////////////////////////////////////////////////////////////////////////////
				// Tinker
				echo '<td style="width: 3%;">';
				// Tinkers are always spells so trim it!
				
				if($tinker_id > 0){
					// Tinker exists, we need to look the item up in the item DB.
					$query = "SELECT * FROM SkunkSpells WHERE spell_id = $tinker_id";
					$tResult = $db->query($query);
					$tinker = $tResult->fetch_object();
					$iconBorder = 'style="border:2px solid'. $itemColorArray[1] . '; padding:0px; margin:0px; display: block;"';
					echo '<a href="http://www.wowhead.com/spell=' . $tinker->spell_id .'"><img src="images/icons/' . $tinker->spell_icon . '.jpg" width="20" height="20" '. $iconBorder .'> </a>';
				}
				elseif( ($character['prof1'] === "Engineering" || $character['prof2'] === "Engineering")  &&
					    ($gearSlot[$j] == "back" || $gearSlot[$j] == "hands" || $gearSlot[$j] == "waist")){					
						echo '<a href="#"><img src="images/icons/empty.png" width="20" height="20" style="border:2px solid #FF0000; padding:0px; margin:0px; display: block;"></a>';			
						$suggestion = ($suggestion != "" ? $suggestion . " + " : "") . "Missing Tinker";
						
						// Hand Tinkers are currently important
						if($gearSlot[$j] == "hands"){ 
							$severity += 3;
						} else {
							$severity += 1;
						}
				}
				

				echo '</td>';

				
				////////////////////////////////////////////////////////////////////////////////////////
				// Suggestion
				if($suggestion != ""){

					if($severity < 3){
						$suggestionBG = '#333333';
					} else {
						$suggestionBG = '#FF0000';
					}
					
					echo '<td style="width:25%; padding-left:8px; background-color:'. $suggestionBG .'; border-left-style:solid; border-width:1px; border-color:#000000;">';
					
					echo $suggestion;
				} 
				else {
					echo '<td style="width:25%; border-left-style:solid; border-width:1px; border-color:#000000;">';
				}
				
				echo '</td>';

				echo '</tr>';	
				
			}

			echo '</table>';

			echo'</div>';
				
		}		
		
		
		// Post the average raider item level
		?>
		
	<?php
	}
	?>


    <?php   
	echo "Last Updated: " . date('F j, Y - g:i:s a'  , $lastUpdate) . ' (CST)';
	?>
    </div>
</div>