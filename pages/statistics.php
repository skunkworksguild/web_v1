<?php
// Database Info
require(dirname(__FILE__) . '/../../sw_include/sw_db.php');
// Guild Summary

// Making sure the date is correct
date_default_timezone_set("America/Detroit");

// Add the achievements
require('scripts/Skunkworks_Armory_Achievements.php');

$prevToken = 0;

$tierToken = array(
	'Invalid',
	'Conqueror',
	'Protector',
	'Vanquisher'
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

$rankQueryString = "(guild_rank = 'Guild Master' OR guild_rank = 'Officer' OR guild_rank = 'Raider' OR guild_rank = 'Aspirant')";

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

// Conqueror = Paladin, Priest, Warlock
// Protector = Hunter, Shaman, Warrior, Monk
// Vanquisher = Druid, Death Knight, Mage, Rogue
*/

$query = "SET NAMES utf8";
$db->query($query);


// Get all the icons we'll need for the achievements
$query = "SELECT * FROM SkunkAchievementIcons";
$result = $db->query($query);
$aIcons[] = 0;
while ($row = $result->fetch_assoc()) {
    $aIcons[$row['achievement_id']] = $row['icon_name'];
}
?>

<div class="entireSection">
    <div class="title">
		<div class="newsSubject"><img src="images/gear6.png">Raid Summary</div>
    </div>
   
    <div class="section">
	
    <div class="statsTitle">
    	<h3>Gear</h3>
    </div>
    
	<div>
	<?php
	
	$query = "SELECT * FROM SkunkRoster 
	          WHERE ". $rankQueryString ." 
			  ORDER BY tier_token, class, name";
	$result = $db->query($query);
	
	if ($result->num_rows > 0) {
	
		$numRaiders = $result->num_rows;
		$raiderIlvlTotal = 0;
	
    ?>
    	<table class="stats">	  
    	    <tr>
			<td width="3%" class= "itemTitle">#</td>
    		<td width="12%" class= "itemTitle">Name</td>
			<td width="8%" class="itemTitle">AP</td>
			<td width="9%" class="itemTitle">AvLvl(Equip)</td>
    		<td width="4%" class= "itemTitle">He</td>
    		<td width="4%" class= "itemTitle">Ne</td>
    		<td width="4%" class= "itemTitle">Sh</td>
    		<td width="4%" class= "itemTitle">Ba</td>
    		<td width="4%" class= "itemTitle">Ch</td>
    		<!--<td class= "itemTitle">Shirt</td>
    		<td class= "itemTitle">Tabard</td>-->
    		<td width="4%" class= "itemTitle">Wr</td>
    		<td width="4%" class= "itemTitle">Ha</td>
    		<td width="4%" class= "itemTitle">Wa</td>
    		<td width="4%" class= "itemTitle">Le</td>
    		<td width="4%" class= "itemTitle">Fe</td>
    		<td width="4%" class= "itemTitle">R1</td>
    		<td width="4%" class= "itemTitle">R2</td>
    		<td width="4%" class= "itemTitle">T1</td>
    		<td width="4%" class= "itemTitle">T2</td>
    		<td width="4%" class= "itemTitle">MH</td>
    		<td width="4%" class= "itemTitle">OH</td>
    	    </tr>
		
		<?php
			
        //// Gear Section
		for ($i = 0; $i < $numRaiders; $i++) {
		
			// Character
			$character = $result->fetch_assoc();
			
			// Only get a single timestamp for now (may want to show individual updates in the future)
			if($i == 0){
				// E: System time off?  Timestamp is not converting to CST (system is MST).  Need to look at in future.
				$lastUpdate = strtotime($character['last_update']) + 3600;
			}
			
			if($character['tier_token'] > $prevToken) {
				$query = "SELECT name FROM SkunkRoster WHERE tier_token = " . $character['tier_token'] . " AND " . $rankQueryString;
				$tokenResult = $db->query($query);
				$numToken = $tokenResult->num_rows;		
			
				echo '</table>';
				echo '<h3>' . $tierToken[$character['tier_token']] . ' (' . $numToken . ')</h3>';
				echo '<table class="stats">';
				
				$prevToken++;
			}
			
			echo '<tr>';
			
			echo '<td width = "3%">' . ($i+1) . '</td>';
			
			echo '<td width="12%" style="font-weight:bold; color:'. $classColorArray[$character['class']] . '">' . $character['name'] . '</td>';
		
			echo '<td width="10%" style="text-align: center">' . $character['achievement_points'] . '</td>';
			
			
			echo '<td width="9%" style="text-align: center">' . (int)$character['average_item_level'] . '(' . (int)$character['average_item_level_equipped'] . ')</td>';
			$raiderIlvlTotal += (int)$character['average_item_level'];
			$raiderIlvlTotalEquipped +=	(int)$character['average_item_level_equipped'];
			
			
			
			for ($j = 0; $j < sizeof($gearSlot); $j++) {
			
				$slotID = "item_" . $gearSlot[$j] . "_id";
				$slotIcon = "item_" . $gearSlot[$j] . "_icon";
				$slotLevel = "item_" . $gearSlot[$j] . "_level";
				$slotName = "item_" . $gearSlot[$j] . "_name";
				$bonusLists = "item_" . $gearSlot[$j] . "_bonusLists";
	
				
				
				
				//$query = "SELECT * FROM SkunkItems WHERE item_id = " . $character[$slot];
				
				//$itemResult = $db->query($query);
				
				///$item = $itemResult->fetch_object();
				
				// Figure out the icon border color
				/*
				if($character[$slotLevel] >= $heroicItemLevel){
					$iconBorder = 'style="border:2px #000000 solid"';
				}
				elseif($character[$slotLevel] >= $normalItemLevel){
					$iconBorder = 'style="border:3px #000000 solid"';
				}
				else
					$iconBorder = 'style="border:2px #000000 solid"';
				*/
				$iconBorder = 'style="border:2px #000000 solid"';
				
				// Form the wowhead link (now crazy with the new bonus tag)
				$wowheadlink = "http://www.wowhead.com/item=" . $character[$slotID] . "/" . str_replace(" ", "-", strtolower($character[$slotName]));
				
				// Add bonuses
				$itemBonus = json_decode($character[$bonusLists]);
				
				//print_r($itemBonus);
				
				if(is_array($itemBonus)){
				
					$firstBonus = true;
					$wowheadlink .= "&bonus=";
				
					//echo $slotName;
					foreach($itemBonus as $bonus){
					
						if($firstBonus == false){
							$wowheadlink .= ":";
						}
						
						$wowheadlink .= $bonus;
						$firstBonus = false;
					
					}
				}
				else 
					$wowheadlink .= "&bonus=0";
				
				
				// Remember this page is called from the index
				echo '<td width="4%" class="itemEntry">';
				if($character[$slotID] > 0){
					echo '<a href="'. $wowheadlink .'"><img src="images/icons/' . $character[$slotIcon] . '.jpg" width="25" height="25" '. $iconBorder .'></a>';
					echo '' . $character[$slotLevel];
				}
				else {
					echo 'Empty';
				}
				echo '</td>';		
				
			}
				
			echo '</tr>';
		}		
		
		
		// Post the average raider item level
		 ?>
		
		<tr>
			<td></td>
			<td style="font-weight: bold">AVERAGES:</td>
			<td></td>
			<td style="text-align: center; font-weight: bold"><?php echo (int)($raiderIlvlTotal/$numRaiders); ?>
				<?php echo '(' . (int)($raiderIlvlTotalEquipped/$numRaiders) . ')'; ?>	
			</td>
			<td colspan="16"></td>
		</tr>	
		</table>
	<?php
	}
	?>

	</div>
	
	<div class="statsTitle">
		<h3>Archimonde/Blackhand Mounts</h3>
	</div>
	
	<div id="mountsContent">
	<?php
	$query = "SELECT * FROM SkunkMounts sm
	          JOIN SkunkRoster r ON sm.raider_id = r.raider_id AND ". $rankQueryString ."
			  ORDER BY r.class, r.name
	          ";
	$result = $db->query($query);
	
	if ($result){
		//// Mounts
		// Make sure order matches up with what is in the database
		
		// Totals
		$mountTotals = array(0, 0);
		$numMembers = $result->num_rows;
	?>
		<table class="mounts">	  
    	<tr>
			<td class="mount" width="3%">#</td>
			<td class="mount" width="13%">Name</td>
			<td class="mount" width="12%"><a href="http://http://www.wowhead.com/item=116660/ironhoof-destroyer"><img src="images/icons/inv_ironhordeclefthoof.jpg"></a>Ironhoof Destroyer/td>
			<td class="mount" width="12%"><a href="http://http://www.wowhead.com/item=123890/felsteel-annihilator"><img src="images/icons/ability_mount_felreavermount.jpg"></a>Felsteel Annihilator</td>
		</tr>
	<?php		
		
		for ($i = 0; $i < $result->num_rows; $i++) {
		
			// Character
			$character = $result->fetch_array();
			//print_r($character['wow10']
			echo '<tr>';
			
			echo '<td width = "3%">' . ($i+1) . '</td>';
			
			echo '<td width="13%" style="font-weight:bold; color:'. $classColorArray[$character['class']] . '">' . $character['name'] . '</td>';
		
			
			if($character['ironhoof_destroyer'] == 1 ){						
				echo '<td class = "markg" width="12%"></td>';
				$mountTotals[0]++;
			}
			else 
				echo '<td class = "markn" width="12%"></td>';
				
			if($character['felsteel_annihilator'] == 1 ){						
				echo '<td class = "markg" width="12%"></td>';
				$mountTotals[1]++;
			}
			else 
				echo '<td class = "markn" width="12%"></td>';
			
			
			echo '</tr>';
			
		}
		// Totals
		?>	
		<tr>
			<td width="3%"></td>
			<td width="13%"><b>TOTALS:</b></td>
			<td class="statCount" width="12%"><?php echo $mountTotals[0] . ' ( ' . (int)(($mountTotals[0]/$numMembers) * 100) . '%)'; ?></td>
			<td class="statCount" width="12%"><?php echo $mountTotals[1] . ' ( ' . (int)(($mountTotals[1]/$numMembers) * 100) . '%)'; ?></td>
        </tr>
		</table>
	<?php
	}
	?>
	</div>
	

	<div class="statsTitle">
  		<h3> T17 (Glory of the Draenor Raider)</h3>
    </div>

	<div>
	<?php
	
	//// Achievements
	// Make sure order matches up with what is in the database - bitwise AND masked
	// Tier 17
	$query = "SELECT a.raider_id, a.tier17_1, a.tier17_2, r.raider_id, r.class, r.name FROM SkunkAchievements a, SkunkRoster r 
		      WHERE a.raider_id = r.raider_id AND ". $rankQueryString ." 
			  ORDER BY r.class, r.name";
			  
	$result = $db->query($query);
	

	
	if ($result->num_rows > 0){
				
				
		// Totals
		$t17Totals = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
		$numMembers = $result->num_rows;
		
		?>
		<table class="achievements">	  
    	<tr>
			<td class="achievementColumn" width="3%">#</td>
			<td class="achievementColumn" width="13%">Name</td>
		<?php 
		  for($i = 0; $i < count($tier17Achieve); $i++){
			echo '<td class="achievementColumn" width="2%"><a href="http://www.wowhead.com/achievement='. $tier17Achieve[$i] .'"><img src="images/icons/'.  $aIcons[$tier17Achieve[$i]] .'.jpg"></a></td>';
	      }
		?>
		</tr>
	<?php		
		
		for ($i = 0; $i < $result->num_rows; $i++) {
		
			// Character
			$character = $result->fetch_array();
			
			echo '<tr>';
			
			echo '<td width = "3%">' . ($i+1) . '</td>';
			
			echo '<td width="13%" style="font-weight:bold; color:'. $classColorArray[$character['class']] . '">' . $character['name'] . '</td>';
		
			// 1st half of tier 17
			for($j = 0; $j < 17; $j++){
				if( $character['tier17_1'] & (1 << $j) ){						
					echo '<td class = "markg" width="3.3%"></td>';
					$t17Totals[$j]++;
				}
				else 
					echo '<td class = "markn" width="3.3%"></td>';
			}
			
			// 2nd half of tier 17 (damn 32 bits!)
			for($j = 0; $j < 17; $j++){
				if( $character['tier17_2'] & (1 << $j) ){						
					echo '<td class = "markg" width="3.3%"></td>';
					$t17Totals[$j+17]++;
				}
				else 
					echo '<td class = "markn" width="3.3%"></td>';
			}
			
			echo '</tr>';
			
		}
		?>
		<tr>
			<td width="3%"></td>
			<td width="13%"><b>TOTALS:</b></td>
			<?php 
			for($i = 0; $i < 34; $i++){
				echo '<td class="statCount" width="3.3%">'. $t17Totals[$i] .'</td>';
			}
			?>
        </tr>
		<tr>
			<td width="3%"></td>
			<td width="13%"><b>%</b></td>
			<?php 
			for($i = 0; $i < 34; $i++){
				echo '<td class="statCount" width="3.3%">'. (int)(($t17Totals[$i]/$numMembers) * 100) .'%</td>';
			}
			?>
        </tr>
		</table>
	
	<?php
	}
	?>

	</div>
	
	<div class="statsTitle">
  		<h3>Challenge Modes</h3>
    </div>

	<div>
	<?php
	
	//// Achievements
	// Make sure order matches up with what is in the database - bitwise AND masked
	// Challenge Modes
	$query = "SELECT a.raider_id, a.cModes, r.raider_id, r.class, r.name FROM SkunkAchievements a, SkunkRoster r 
		      WHERE a.raider_id = r.raider_id AND ". $rankQueryString ." 
			  ORDER BY r.class, r.name";
			  
	$result = $db->query($query);
	
	if ($result->num_rows > 0){
				
				
		// Totals
		$cModesTotals = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
		$numMembers = $result->num_rows;
		
		?>
		<table class="achievements">	  
    	<tr>
			<td class="achievementColumn" width="3%">#</td>
			<td class="achievementColumn" width="10%">Name</td>
			
			<!-- Odd -->
			<td class="achievementColumn" style="background-image: url(http://skunkworksguild.com/images/icons/achievement_dungeon_auchindoun.jpg); background-repeat: no-repeat; background-position: center" width="5%">
				<a style="display: block; text-align: center; height:27px; width: 100%" href="http://www.wowhead.com/achievement=8879"> </a>
			</td>
			<td class="achievementColumn" style="background-image: url(http://skunkworksguild.com/images/icons/achievement_dungeon_ogreslagmines.jpg); background-repeat: no-repeat; background-position: center" width="5%">
				<a style="display: block; text-align: center; height:27px; width: 100%" href="http://www.wowhead.com/achievement=8875"> </a>
			</td>
			<td class="achievementColumn" style="background-image: url(http://skunkworksguild.com/images/icons/achievement_dungeon_blackrockdepot.jpg); background-repeat: no-repeat; background-position: center" width="5%">
				<a style="display: block; text-align: center; height:27px; width: 100%" href="http://www.wowhead.com/achievement=8887"> </a>
			</td>
			<td class="achievementColumn" style="background-image: url(http://skunkworksguild.com/images/icons/achievement_dungeon_blackrockdocks.jpg); background-repeat: no-repeat; background-position: center" width="5%">
				<a style="display: block; text-align: center; height:27px; width: 100%" href="http://www.wowhead.com/achievement=8997"> </a>
			</td>
			<td class="achievementColumn" style="background-image: url(http://skunkworksguild.com/images/icons/achievement_dungeon_shadowmoonhideout.jpg); background-repeat: no-repeat; background-position: center" width="5%">
				<a style="display: block; text-align: center; height:27px; width: 100%" href="http://www.wowhead.com/achievement=8883"> </a>
			</td>
			<td class="achievementColumn" style="background-image: url(http://skunkworksguild.com/images/icons/achievement_dungeon_arakkoaspires.jpg); background-repeat: no-repeat; background-position: center" width="5%">
				<a style="display: block; text-align: center; height:27px; width: 100%" href="http://www.wowhead.com/achievement=8871"> </a>
			</td>
			<td class="achievementColumn" style="background-image: url(http://skunkworksguild.com/images/icons/achievement_dungeon_everbloom.jpg); background-repeat: no-repeat; background-position: center" width="5%">
				<a style="display: block; text-align: center; height:27px; width: 100%" href="http://www.wowhead.com/achievement=9001"> </a>
			</td>
			<td class="achievementColumn" style="background-image: url(http://skunkworksguild.com/images/icons/achievement_dungeon_upperblackrockspire.jpg); background-repeat: no-repeat; background-position: center" width="5%">
				<a style="display: block; text-align: center; height:27px; width: 100%" href="http://www.wowhead.com/achievement=8891"> </a>
			</td>
			<td class="achievementColumn" style="margin-left: 4px;" width="4%">Total</td>
	 </tr>
	<?php		
		
		for ($i = 0; $i < $result->num_rows; $i++) {
		
			// Character
			$character = $result->fetch_array();
			
			echo '<tr>';
			
			echo '<td width = "3%">' . ($i+1) . '</td>';
			
			echo '<td width="13%" style="font-weight:bold; color:'. $classColorArray[$character['class']] . '">' . $character['name'] . '</td>';
			
			$numGold = 0;
		
			for($j = 0; $j < 24; $j+=3){
								
				if($character['cModes'] & (1 << $j) ){						
					echo '<td class = "markgold" width="3.3%">G</td>';
					$cModesTotals[$j]++;
					$numGold++;
				}		
				else if($character['cModes'] & (1 << $j+1) ){						
					echo '<td class = "marksilver" width="3.3%">S</td>';
					$cModesTotals[$j]++;
				} 
				else if($character['cModes'] & (1 << $j+2) ){						
					echo '<td class = "markbronze" width="3.3%">B</td>';
					$cModesTotals[$j]++;
				}
				else 
					echo '<td class = "markn" width="3.3%"></td>';
			}
			
			echo '<td>' . $numGold . ' / 8</td>';
			
			echo '</tr>';
			
		}
		
		?>
		</table>
	
	<?php
	}
	?>

	</div>
	
	</div>

	<div class="statsTitle">
  		<h3> Proving Grounds</h3>
    </div>

	<div>
	<?php
	
	//// Achievements
	// Make sure order matches up with what is in the database - bitwise AND masked
	// Proving Grounds
	$query = "SELECT a.raider_id, a.pGrounds, r.raider_id, r.class, r.name FROM SkunkAchievements a, SkunkRoster r 
		      WHERE a.raider_id = r.raider_id AND ". $rankQueryString ." 
			  ORDER BY r.class, r.name";
			  
	$result = $db->query($query);
	
	if ($result->num_rows > 0){
				
				
		// Totals
		$pGTotals = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
		$numMembers = $result->num_rows;
		
		?>
		<table class="achievements">	  
    	<tr>
			<td class="achievementColumn" width="3%"></td>
			<td class="achievementColumn" width="13%"></td>
			
			<td class="achievementColumn" colspan="6" style="background-color:#81BEF7; text-align: center; color:#000000;">Damage</td>
			<td class="achievementColumn" colspan="6" style="background-color:#00FF00; text-align: center; color:#000000;">Healing</td>
			<td class="achievementColumn" colspan="6" style="background-color:#FE2E2E; text-align: center; color:#000000;">Tanking</td>
			<td class="achievementColumn" width="3.3%"></td>
		</tr>
		
		<tr>
			<td class="achievementColumn" width="3%">#</td>
			<td class="achievementColumn" width="13%">Name</td>
			<!-- Damage -->
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8486"><img src="images/icons/achievement_challengemode_bronze.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8491"><img src="images/icons/achievement_challengemode_silver.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8496"><img src="images/icons/achievement_challengemode_gold.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8501"><img src="images/icons/achievement_challengemode_platinum.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8504"><img src="images/icons/achievement_challengemode_platinum.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8507"><img src="images/icons/achievement_challengemode_platinum.jpg"></a></td>	
			<!-- Healing -->
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8488"><img src="images/icons/achievement_challengemode_bronze.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8493"><img src="images/icons/achievement_challengemode_silver.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8498"><img src="images/icons/achievement_challengemode_gold.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8503"><img src="images/icons/achievement_challengemode_platinum.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8506"><img src="images/icons/achievement_challengemode_platinum.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8509"><img src="images/icons/achievement_challengemode_platinum.jpg"></a></td>
			<!-- Tanking -->
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8487"><img src="images/icons/achievement_challengemode_bronze.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8492"><img src="images/icons/achievement_challengemode_silver.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8497"><img src="images/icons/achievement_challengemode_gold.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8502"><img src="images/icons/achievement_challengemode_platinum.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8505"><img src="images/icons/achievement_challengemode_platinum.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8508"><img src="images/icons/achievement_challengemode_platinum.jpg"></a></td>
			<!-- Other -->
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8697"><img src="images/icons/ability_warrior_throwdown.jpg"></a></td>   
			
		</tr>
	<?php		
		
		for ($i = 0; $i < $result->num_rows; $i++) {
		
			// Character
			$character = $result->fetch_array();
			
			echo '<tr>';
			
			echo '<td width = "3%">' . ($i+1) . '</td>';
			
			echo '<td width="13%" style="font-weight:bold; color:'. $classColorArray[$character['class']] . '">' . $character['name'] . '</td>';
		
			for($j = 0; $j < 19; $j++){
				if($character['pGrounds'] & (1 << $j) ){						
					// Color code these things
						switch($j){
							  // Bronze
							case 0:
							case 6:
							case 12:  echo '<td class = "markbronze" width="3.3%">B</td>';
									  break;
									  // Silver
							case 1:
							case 7:
							case 13:  echo '<td class = "marksilver" width="3.3%">S</td>';
									  break;
									  // Gold			  
							case 2:
							case 8:
							case 14:  echo '<td class = "markgold" width="3.3%">G</td>';
									  break;
									  // Plat 10
							case 3:
							case 9:
							case 15:  echo '<td class = "markplat" width="3.3%">10</td>';
									  break;
									  // Plat 20
							case 4:
							case 10:
							case 16:  echo '<td class = "markplat" width="3.3%">20</td>';
									  break;
									  // Plat 30
							case 5:
							case 11:
							case 17:  echo '<td class = "markplat" width="3.3%">30</td>';
									  break;									  
							default:
									  echo '<td class = "markg" width="3.3%"></td>';
									  break;
						}
				
					$pGTotals[$j]++;
				}
				else 
					echo '<td class = "markn" width="3.3%"></td>';
			}
			
			echo '</tr>';
			
		}
		?>
		<tr>
			<td width="3%"></td>
			<td width="13%"><b>TOTALS:</b></td>
			<td class="statCount" width="3.3%"><?php echo $pGTotals[0]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $pGTotals[1]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $pGTotals[2]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $pGTotals[3]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $pGTotals[4]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $pGTotals[5]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $pGTotals[6]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $pGTotals[7]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $pGTotals[8]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $pGTotals[9]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $pGTotals[10]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $pGTotals[11]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $pGTotals[12]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $pGTotals[13]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $pGTotals[15]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $pGTotals[15]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $pGTotals[16]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $pGTotals[17]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $pGTotals[18]; ?></td>
        </tr>
		<tr>
			<td width="3%"></td>
			<td width="13%"><b>%</b></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($pGTotals[0]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($pGTotals[1]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($pGTotals[2]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($pGTotals[3]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($pGTotals[4]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($pGTotals[5]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($pGTotals[6]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($pGTotals[7]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($pGTotals[8]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($pGTotals[9]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($pGTotals[10]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($pGTotals[11]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($pGTotals[12]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($pGTotals[13]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($pGTotals[15]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($pGTotals[15]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($pGTotals[16]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($pGTotals[17]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($pGTotals[18]/$numMembers) * 100) . '%'; ?></td>
        </tr>
		</table>
	
	<?php
	}
	?>

	</div>
	
	
	
	<?php /*
	<br><br><br>
	<center><h3><a href="http://skunkworksguild.com/?p=legacyAchievements">Legacy Achievements</a></center><h3>
	<br>
	<a href="http://www.wowhead.com/item=49623" class="q5" rel="gems=3518:3518:3518&amp;ench=3368">[Shadowmourne]</a>
	
	<?php
	*/
	// Show the time of last update
	echo "Last Updated: " . date('F j, Y - g:i:s a'  , $lastUpdate) . ' (CST)';
	?>
    </div>
</div>