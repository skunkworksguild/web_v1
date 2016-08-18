<?php
// Database Info
require(dirname(__FILE__) . '/../../sw_include/sw_db.php');
// Guild Summary

// Making sure the date is correct
date_default_timezone_set("America/Detroit");

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
			<td width="9%" class="itemTitle">AvgLvl</td>
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
			
			// Computer the average equipped item level manually
			$query = "SELECT AVG(item_ilevel) as avgCompLevel FROM SkunkItems WHERE 
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
				// The non-upgrade part
				echo '<td width="9%" style="text-align: center">' . (int)$ilvl->avgCompLevel;	
				// The upgrade part
				echo '<span style="color:#00FF00">  (' . floor(((int)$ilvl->avgCompLevel) + $upgradeAverage) . ')</span>';	
				echo '</td>';
				
				$raiderIlvlTotal += (int)$ilvl->avgCompLevel;
				$raiderIlvlTotalUpgrade += floor(((int)$ilvl->avgCompLevel) + $upgradeAverage);
			}
			else 
				echo '<td width="7%" style="text-align: center">' . Error . '</td>';
			
			for ($j = 0; $j < sizeof($gearSlot); $j++) {
			
				$slot = "item_" . $gearSlot[$j] . "_id";
				
				$query = "SELECT * FROM SkunkItems WHERE item_id = " . $character[$slot];
				
				$itemResult = $db->query($query);
				
				$item = $itemResult->fetch_object();
				
				// Figure out the icon border color
				/*
				if($item->item_ilevel >= $heroicItemLevel){
					$iconBorder = 'style="border:2px #000000 solid"';
				}
				elseif($item->item_ilevel >= $normalItemLevel){
					$iconBorder = 'style="border:3px #000000 solid"';
				}
				else
					$iconBorder = 'style="border:2px #000000 solid"';
				*/
				$iconBorder = 'style="border:2px #000000 solid"';
				
				// Remember this page is called from the index
				echo '<td width="4%" class="itemEntry">';
				if($item->item_id > 0){
					echo '<a href="http://www.wowhead.com/item=' . $item->item_id .'"><img src="images/icons/' . $item->item_icon . '.jpg" width="25" height="25" '. $iconBorder .'></a>';
					
					$item_itemLevelIncrement = $character["item_" . $gearSlot[$j] . "_upgrade_itemLevelIncrement"];
					if($item_itemLevelIncrement > 0){
						echo '<span style="color:#00FF00;">' . ($item->item_ilevel + $item_itemLevelIncrement)  .'</span>';
					}
					else{
						echo '' . $item->item_ilevel;
					}
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
			    <span style="color:#00FF00">  <?php echo '(' . (int)($raiderIlvlTotalUpgrade/$numRaiders) . ')'; ?> </span>	
			</td>
			<td colspan="16"></td>
		</tr>	
		</table>
	<?php
	}
	?>

	</div>
    
	<div id="mounts">
		<h3>Mounts</h3>
	</div>
	
	<div id="mountsContent">
	<?php
	$query = "SELECT * FROM SkunkMounts p, SkunkRoster r 
		      WHERE p.raider_id = r.raider_id AND ". $rankQueryString ."
			  ORDER BY r.class, r.name
	          ";
	$result = $db->query($query);
	
	if ($result){
		//// Mounts
		// Make sure order matches up with what is in the database
		
		// Totals
		$mountTotals = array(0, 0, 0, 0, 0, 0, 0);
		$numMembers = $result->num_rows;
	?>
		<table class="mounts">	  
    	<tr>
			<td class="mount" width="3%">#</td>
			<td class="mount" width="13%">Name</td>
			<td class="mount" width="12%"><a href="http://www.wowhead.com/spell=97493"><img src="images/icons/inv_misc_orb_05.jpg"></a>Pureblood Firehawk</td>
			<td class="mount" width="12%"><a href="http://www.wowhead.com/spell=107845"><img src="images/icons/ability_mount_drake_red.jpg"></a>Handmaiden</td>
			<td class="mount" width="12%"><a href="http://www.wowhead.com/spell=107842"><img src="images/icons/ability_mount_drake_red.jpg"></a>Blazing Drake</td>
			<td class="mount" width="12%"><a href="http://www.wowhead.com/spell=72286"><img src="images/icons/ability_mount_pegasus.jpg"></a>Invicible</td>
			<td class="mount" width="12%"><a href="http://www.wowhead.com/spell=63796"><img src="images/icons/inv_misc_enggizmos_03.jpg"></a>Mimiron's Head</td>
			<td class="mount" width="12%"><a href="http://www.wowhead.com/spell=40192"><img src="images/icons/inv_misc_summerfest_brazierorange.jpg"></a>Ashes of Al'ar</td>
			<td class="mount" width="12%"><a href="http://www.wowhead.com/spell=148417"><img src="images/icons/inv_mount_hordescorpiong.jpg"></a>Kor'kron Juggernaut</td>
		</tr>
	<?php		
		
		for ($i = 0; $i < $result->num_rows; $i++) {
		
			// Character
			$character = $result->fetch_array();
			
			echo '<tr>';
			
			echo '<td width = "3%">' . ($i+1) . '</td>';
			
			echo '<td width="13%" style="font-weight:bold; color:'. $classColorArray[$character['class']] . '">' . $character['name'] . '</td>';
		
			for($j = 1; $j < 8; $j++){
				if($character[$j] == 1){						
					echo '<td class = "markg" width="12%"></td>';
					$mountTotals[$j-1]++;
				}
				else 
					echo '<td class = "markn" width="12%"></td>';
			}
			
			echo '</tr>';
			
		}
		// Totals
		?>	
		<tr>
			<td width="3%"></td>
			<td width="13%"><b>TOTALS:</b></td>
			<td class="statCount" width="12%"><?php echo $mountTotals[0] . ' ( ' . (int)(($mountTotals[0]/$numMembers) * 100) . '%)'; ?></td>
			<td class="statCount" width="12%"><?php echo $mountTotals[1] . ' ( ' . (int)(($mountTotals[1]/$numMembers) * 100) . '%)'; ?></td>
			<td class="statCount" width="12%"><?php echo $mountTotals[2] . ' ( ' . (int)(($mountTotals[2]/$numMembers) * 100) . '%)'; ?></td>
			<td class="statCount" width="12%"><?php echo $mountTotals[3] . ' ( ' . (int)(($mountTotals[3]/$numMembers) * 100) . '%)'; ?></td>
			<td class="statCount" width="12%"><?php echo $mountTotals[4] . ' ( ' . (int)(($mountTotals[4]/$numMembers) * 100) . '%)'; ?></td>
			<td class="statCount" width="12%"><?php echo $mountTotals[5] . ' ( ' . (int)(($mountTotals[5]/$numMembers) * 100) . '%)'; ?></td>
			<td class="statCount" width="12%"><?php echo $mountTotals[6] . ' ( ' . (int)(($mountTotals[6]/$numMembers) * 100) . '%)'; ?></td>
        </tr>
		</table>
	<?php
	}
	?>
	</div>
	
	?>
	
	<div class="statsTitle">
  		<h3> T14 (Glory of the Pandaria Raider)</h3>
    </div>

	<div>
	<?php
	
	//// Achievements
	// Make sure order matches up with what is in the database - bitwise AND masked
	// Tier 14
	$query = "SELECT a.raider_id, a.tier14_1, a.tier14_2, r.raider_id, r.class, r.name FROM SkunkAchievements a, SkunkRoster r 
		      WHERE a.raider_id = r.raider_id AND ". $rankQueryString ." 
			  ORDER BY r.class, r.name";
			  
	$result = $db->query($query);
	
	if ($result->num_rows > 0){
				
				
		// Totals
		$t14Totals = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
		$numMembers = $result->num_rows;
		
		?>
		<table class="achievements">	  
    	<tr>
			<td class="achievementColumn" width="3%">#</td>
			<td class="achievementColumn" width="13%">Name</td>
			<!-- Odd -->
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6823"><img src="images/icons/ability_hunter_beasttraining.jpg"></a></td>                              
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6674"><img src="images/icons/thumbsup.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=7056"><img src="images/icons/trade_archaeology_silverscrollcase.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6687"><img src="images/icons/thumbsup.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6686"><img src="images/icons/ability_deathknight_pillaroffrost.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6455"><img src="images/icons/ability_rogue_shadowstrikes.jpg"></a></td>		
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6937"><img src="images/icons/inv_misc_archaeology_amburfly.jpg"></a></td>   
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6936"><img src="images/icons/trade_archaeology_candlestub.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6683"><img src="images/icons/spell_brokenheart.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6553"><img src="images/icons/inv_misc_ammo_arrow_05.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6518"><img src="images/icons/spell_yorsahj_bloodboil_yellow.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6922"><img src="images/icons/spell_holy_borrowedtime.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6717"><img src="images/icons/ability_rogue_envelopingshadows.jpg"></a></td>   
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6933"><img src="images/icons/trade_herbalism.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6824"><img src="images/icons/spell_shadow_coneofsilence.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6825"><img src="images/icons/spell_arcane_mindmastery.jpg"></a></td>	
			<!-- Heroic -->
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6719"><img src="images/icons/achievement_moguraid_01.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6720"><img src="images/icons/achievement_moguraid_02.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6721"><img src="images/icons/achievement_moguraid_03.jpg"></a></td>                              
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6722"><img src="images/icons/achievement_moguraid_04.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6723"><img src="images/icons/achievement_moguraid_05.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6724"><img src="images/icons/achievement_moguraid_06.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6725"><img src="images/icons/achievement_raid_mantidraid02.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6726"><img src="images/icons/achievement_raid_mantidraid03.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6727"><img src="images/icons/achievement_raid_mantidraid05.jpg"></a></td>   
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6728"><img src="images/icons/achievement_raid_mantidraid04.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6729"><img src="images/icons/achievement_raid_mantidraid06.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6730"><img src="images/icons/achievement_raid_mantidraid07.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6731"><img src="images/icons/achievement_raid_terraceofendlessspring01.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6732"><img src="images/icons/achievement_raid_terraceofendlessspring02.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6733"><img src="images/icons/achievement_raid_terraceofendlessspring03.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=6734"><img src="images/icons/achievement_raid_terraceofendlessspring04.jpg"></a></td>
	 </tr>
	<?php		
		
		for ($i = 0; $i < $result->num_rows; $i++) {
		
			// Character
			$character = $result->fetch_array();
			
			echo '<tr>';
			
			echo '<td width = "3%">' . ($i+1) . '</td>';
			
			echo '<td width="13%" style="font-weight:bold; color:'. $classColorArray[$character['class']] . '">' . $character['name'] . '</td>';
		
			// 1st half of tier 14
			for($j = 0; $j < 16; $j++){
				if( $character['tier14_1'] & (1 << $j) ){						
					echo '<td class = "markg" width="3.3%"></td>';
					$t14Totals[$j]++;
				}
				else 
					echo '<td class = "markn" width="3.3%"></td>';
			}
			
			// 2nd half of tier 14 (damn 32 bits!)
			for($j = 0; $j < 16; $j++){
				if( $character['tier14_2'] & (1 << $j) ){						
					echo '<td class = "markg" width="3.3%"></td>';
					$t14Totals[$j+16]++;
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
			<td class="statCount" width="3.3%"><?php echo $t14Totals[0]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[1]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[2]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[3]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[4]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[5]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[6]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[7]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[8]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[9]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[10]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[11]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[12]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[13]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[14]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[15]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[16]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[17]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[18]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[19]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[20]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[21]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[22]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[23]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[24]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[25]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[26]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[27]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[28]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[29]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[30]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[31]; ?></td>
        </tr>
		<tr>
			<td width="3%"></td>
			<td width="13%"><b>%</b></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[0]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[1]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[2]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[3]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[4]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[5]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[6]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[7]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[8]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[9]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[10]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[11]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[12]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[13]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[14]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[15]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[16]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[17]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[18]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[19]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[20]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[21]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[22]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[23]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[24]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[25]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[26]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[27]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[28]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[29]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[30]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[31]/$numMembers) * 100) . '%'; ?></td>	
        </tr>
		</table>
	
	<?php
	}
	?>

	</div>

	<div class="statsTitle">
  		<h3> T15 (Glory of the Thundering Raider)</h3>
    </div>

	<div>
	<?php
	
	//// Achievements
	// Make sure order matches up with what is in the database - bitwise AND masked
	// Tier 15
	$query = "SELECT a.raider_id, a.tier15, r.raider_id, r.class, r.name FROM SkunkAchievements a, SkunkRoster r 
		      WHERE a.raider_id = r.raider_id AND ". $rankQueryString ." 
			  ORDER BY r.class, r.name";
			  
	$result = $db->query($query);
	
	if ($result->num_rows > 0){
				
				
		// Totals
		$t15Totals = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
		$numMembers = $result->num_rows;
		
		?>
		<table class="achievements">	  
    	<tr>
			<td class="achievementColumn" width="3%">#</td>
			<td class="achievementColumn" width="13%">Name</td>
			<!-- Odd -->
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8094"><img src="images/icons/ability_vehicle_electrocharge.jpg"></a></td>                              
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8038"><img src="images/icons/trade_archaeology_dinosaurskeleton.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8073"><img src="images/icons/inv_pet_magicalcradadbox.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8077"><img src="images/icons/achievement_boss_tortos.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8082"><img src="images/icons/inv_misc_head_dragon_01.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8097"><img src="images/icons/inv_misc_football.jpg"></a></td>		
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8098"><img src="images/icons/spell_nature_elementalprecision_1.jpg"></a></td>   
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8037"><img src="images/icons/ability_deathknight_roilingblood.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8081"><img src="images/icons/ability_touchofanimus.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8087"><img src="images/icons/thumbup.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8086"><img src="images/icons/spell_holy_pureofheart.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8090"><img src="images/icons/spell_nature_lightningoverload.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8089"><img src="images/icons/achievement_boss_ra_den.jpg"></a></td>
			<!-- Heroic -->
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8056"><img src="images/icons/achievement_boss_jinrokhthebreaker.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8057"><img src="images/icons/achievement_boss_horridon.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8058"><img src="images/icons/achievement_boss_councilofelders.jpg"></a></td>                              
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8059"><img src="images/icons/achievement_boss_tortos.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8060"><img src="images/icons/achievement_boss_megaera.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8061"><img src="images/icons/achievement_boss_ji-kun.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8062"><img src="images/icons/achievement_boss_durumu.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8063"><img src="images/icons/achievement_boss_primordius.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8064"><img src="images/icons/achievement_boss_darkanimus.jpg"></a></td>   
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8065"><img src="images/icons/achievement_boss_iron_qon.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8066"><img src="images/icons/achievement_boss_mogufemales.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8067"><img src="images/icons/achievement_boss_leishen.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8068"><img src="images/icons/achievement_boss_ra_den.jpg"></a></td>
		</tr>
	<?php		
		
		for ($i = 0; $i < $result->num_rows; $i++) {
		
			// Character
			$character = $result->fetch_array();
			
			echo '<tr>';
			
			echo '<td width = "3%">' . ($i+1) . '</td>';
			
			echo '<td width="13%" style="font-weight:bold; color:'. $classColorArray[$character['class']] . '">' . $character['name'] . '</td>';
		
			for($j = 0; $j < 26; $j++){
				if($character['tier15'] & (1 << $j) ){						
					echo '<td class = "markg" width="3.3%"></td>';
					$t15Totals[$j]++;
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
			<td class="statCount" width="3.3%"><?php echo $t15Totals[0]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[1]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[2]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[3]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[4]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[5]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[6]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[7]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[8]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[9]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[10]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[11]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[12]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[13]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[15]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[15]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[16]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[17]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[18]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[19]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[20]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[21]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[22]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[23]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[24]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t15Totals[25]; ?></td>
        </tr>
		<tr>
			<td width="3%"></td>
			<td width="13%"><b>%</b></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[0]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[1]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[2]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[3]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[4]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[5]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[6]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[7]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[8]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[9]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[10]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[11]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[12]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[13]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[15]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[15]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[16]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[17]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[18]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[19]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[20]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[21]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[22]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[23]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[24]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t15Totals[25]/$numMembers) * 100) . '%'; ?></td>		
        </tr>
		</table>
	
	<?php
	}
	?>

	</div>

	<div class="statsTitle">
  		<h3> T16 (Glory of the Orgrimmar Raider)</h3>
    </div>

	<div>
	<?php
	
	//// Achievements
	// Make sure order matches up with what is in the database - bitwise AND masked
	// Tier 16
	$query = "SELECT a.raider_id, a.tier16, r.raider_id, r.class, r.name FROM SkunkAchievements a, SkunkRoster r 
		      WHERE a.raider_id = r.raider_id AND ". $rankQueryString ." 
			  ORDER BY r.class, r.name";
			  
	$result = $db->query($query);
	
	if ($result->num_rows > 0){
				
				
		// Totals
		$t16Totals = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
		$numMembers = $result->num_rows;
		
		?>
		<table class="achievements">	  
    	<tr>
			<td class="achievementColumn" width="3%">#</td>
			<td class="achievementColumn" width="13%">Name</td>
			<!-- Odd -->
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8536"><img src="images/icons/spell_frost_summonwaterelemental_2.jpg"></a></td>                              
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8528"><img src="images/icons/ability_fixated_state_red.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8532"><img src="images/icons/spell_holy_surgeoflight.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8521"><img src="images/icons/sha_ability_rogue_bloodyeye.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8530"><img src="images/icons/achievement_bg_3flagcap_nodeaths.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8520"><img src="images/icons/inv_misc_enggizmos_38.jpg"></a></td>		
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8453"><img src="images/icons/ability_paladin_blessedhands.jpg"></a></td>   
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8448"><img src="images/icons/achievement_character_tauren_male.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8538"><img src="images/icons/sha_spell_warlock_demonsoul.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8529"><img src="images/icons/inv_crate_04.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8527"><img src="images/icons/inv_misc_shell_04.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8543"><img src="images/icons/ability_siege_engineer_sockwave_missile.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8531"><img src="images/icons/achievement_boss_klaxxi_paragons.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8537"><img src="images/icons/ability_garrosh_siege_engine.jpg"></a></td>
			<!-- Heroic -->
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8463"><img src="images/icons/achievement_boss_immerseus.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8465"><img src="images/icons/achievement_boss_golden_lotus_council.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8466"><img src="images/icons/achievement_boss_norushen.jpg"></a></td>                              
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8467"><img src="images/icons/sha_inv_misc_slime_01.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8468"><img src="images/icons/achievement_boss_galakras.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8469"><img src="images/icons/achievement_boss_ironjuggernaut.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8470"><img src="images/icons/achievement_boss_korkrondarkshaman.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8471"><img src="images/icons/achievement_boss_general_nazgrim.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8472"><img src="images/icons/achievement_boss_malkorok.jpg"></a></td>   
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8478"><img src="images/icons/achievement_boss_spoils_of_pandaria.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8479"><img src="images/icons/achievement_boss_thokthebloodthirsty.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8480"><img src="images/icons/achievement_boss_siegecrafter_blackfuse.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8481"><img src="images/icons/achievement_boss_klaxxi_paragons.jpg"></a></td>
			<td class="achievementColumn" width="3.3%"><a href="http://www.wowhead.com/achievement=8482"><img src="images/icons/achievement_boss_garrosh.jpg"></a></td>
		</tr>
	<?php		
		
		for ($i = 0; $i < $result->num_rows; $i++) {
		
			// Character
			$character = $result->fetch_array();
			
			echo '<tr>';
			
			echo '<td width = "3%">' . ($i+1) . '</td>';
			
			echo '<td width="13%" style="font-weight:bold; color:'. $classColorArray[$character['class']] . '">' . $character['name'] . '</td>';
		
			for($j = 0; $j < 28; $j++){
				if($character['tier16'] & (1 << $j) ){						
					echo '<td class = "markg" width="3.3%"></td>';
					$t16Totals[$j]++;
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
			<td class="statCount" width="3.3%"><?php echo $t16Totals[0]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[1]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[2]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[3]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[4]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[5]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[6]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[7]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[8]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[9]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[10]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[11]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[12]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[13]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[15]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[15]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[16]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[17]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[18]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[19]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[20]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[21]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[22]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[23]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[24]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[25]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[26]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t16Totals[27]; ?></td>
        </tr>
		<tr>
			<td width="3%"></td>
			<td width="13%"><b>%</b></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[0]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[1]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[2]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[3]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[4]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[5]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[6]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[7]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[8]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[9]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[10]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[11]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[12]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[13]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[15]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[15]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[16]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[17]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[18]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[19]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[20]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[21]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[22]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[23]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[24]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[25]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[26]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t16Totals[27]/$numMembers) * 100) . '%'; ?></td>	
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
		$cModesTotals = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
		$numMembers = $result->num_rows;
		
		?>
		<table class="achievements">	  
    	<tr>
			<td class="achievementColumn" width="3%">#</td>
			<td class="achievementColumn" width="10%">Name</td>
			
			<!-- Odd -->
			<td class="achievementColumn" style="background-image: url(http://skunkworksguild.com/images/icons/achievement_greatwall.jpg); background-repeat: no-repeat; background-position: center" width="5%">
				<a style="display: block; text-align: center; height:27px; width: 100%" href="http://www.wowhead.com/achievement=6894"> </a>
			</td>
			<td class="achievementColumn" style="background-image: url(http://skunkworksguild.com/images/icons/achievement_dungeon_mogupalace.jpg); background-repeat: no-repeat; background-position: center" width="5%">
				<a style="display: block; text-align: center; height:27px; width: 100%" href="http://www.wowhead.com/achievement=6892"> </a>
			</td>
			<td class="achievementColumn" style="background-image: url(http://skunkworksguild.com/images/icons/inv_helmet_52.jpg); background-repeat: no-repeat; background-position: center" width="5%">
				<a style="display: block; text-align: center; height:27px; width: 100%" href="http://www.wowhead.com/achievement=6895"> </a>
			</td>
			<td class="achievementColumn" style="background-image: url(http://skunkworksguild.com/images/icons/spell_holy_resurrection.jpg); background-repeat: no-repeat; background-position: center" width="5%">
				<a style="display: block; text-align: center; height:27px; width: 100%" href="http://www.wowhead.com/achievement=6896"> </a>
			</td>
			<td class="achievementColumn" style="background-image: url(http://skunkworksguild.com/images/icons/spell_holy_senseundead.jpg); background-repeat: no-repeat; background-position: center" width="5%">
				<a style="display: block; text-align: center; height:27px; width: 100%" href="http://www.wowhead.com/achievement=6897"> </a>
			</td>
			<td class="achievementColumn" style="background-image: url(http://skunkworksguild.com/images/icons/achievement_shadowpan_hideout.jpg); background-repeat: no-repeat; background-position: center" width="5%">
				<a style="display: block; text-align: center; height:27px; width: 100%" href="http://www.wowhead.com/achievement=6893"> </a>
			</td>
			<td class="achievementColumn" style="background-image: url(http://skunkworksguild.com/images/icons/achievement_dungeon_siegeofniuzaotemple.jpg); background-repeat: no-repeat; background-position: center" width="5%">
				<a style="display: block; text-align: center; height:27px; width: 100%" href="http://www.wowhead.com/achievement=6898"> </a>
			</td>
			<td class="achievementColumn" style="background-image: url(http://skunkworksguild.com/images/icons/achievement_brewery.jpg); background-repeat: no-repeat; background-position: center" width="5%">
				<a style="display: block; text-align: center; height:27px; width: 100%" href="http://www.wowhead.com/achievement=6888"> </a>
			</td>
			<td class="achievementColumn" style="background-image: url(http://skunkworksguild.com/images/icons/achievement_jadeserpent.jpg); background-repeat: no-repeat; background-position: center" width="5%">
				<a style="display: block; text-align: center; height:27px; width: 100%" href="http://www.wowhead.com/achievement=6884"> </a>
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
		
			for($j = 0; $j < 27; $j+=3){
								
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
			
			echo '<td>' . $numGold . ' / 9</td>';
			
			echo '</tr>';
			
		}
		/*
		?>
		
		<tr>
			<td width="3%"></td>
			<td width="13%"><b>TOTALS:</b></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[0]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[1]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[2]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[3]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[4]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[5]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[6]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[7]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[8]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[9]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[10]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[11]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[12]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[13]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[14]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[15]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[16]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[17]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[18]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[19]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[20]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[21]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[22]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[23]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[24]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[25]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[26]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[27]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[28]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[29]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[30]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t14Totals[31]; ?></td>
        </tr>
		<tr>
			<td width="3%"></td>
			<td width="13%"><b>%</b></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[0]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[1]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[2]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[3]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[4]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[5]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[6]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[7]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[8]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[9]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[10]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[11]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[12]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[13]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[14]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[15]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[16]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[17]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[18]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[19]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[20]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[21]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[22]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[23]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[24]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[25]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[26]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[27]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[28]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[29]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[30]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t14Totals[31]/$numMembers) * 100) . '%'; ?></td>	
        </tr>
		<?php 
		*/
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