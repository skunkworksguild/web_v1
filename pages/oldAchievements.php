<?php
// Database Info
require(dirname(__FILE__) . '/../../sw_include/sw_db.php');
// Guild Summary

// Making sure the date is correct
date_default_timezone_set("America/Detroit");

$prevToken = 0;

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

// Protector = Hunter, Shaman, Warrior
// Conqueror = Paladin, Priest, Warlock, Monk
// Vanquisher = Druid, Death Knight, Mage, Rogue
*/


$query = "SET NAMES utf8";
$db->query($query);


?>

<div class="entireSection">
    <div class="title">
		<div class="newsSubject"><img src="images/gear6.png">Old Raid Achivements</div>
    </div>
   
    <div class="section">
	
	<div id="t11">
  		<h3> T11 Achievements</h3>
    </div>

	<div id="t11Content">
	<?php
	//// Achievements
	// Make sure order matches up with what is in the database - bitwise AND masked
	// Tier 11
	$query = "SELECT a.raider_id, a.tier11, r.raider_id, r.class, r.name FROM SkunkAchievements a, SkunkRoster r 
		      WHERE a.raider_id = r.raider_id 
			  ORDER BY r.class, r.name";
			  
	$result = $db->query($query);
	
	if ($result){
				
				
		// Totals
		$t11Totals = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
		$numMembers = $result->num_rows;
		
		?>
		<table class="achievements11">	  
    	<tr>
			<td class="t11" width="3%">#</td>
			<td class="t11" width="13%">Name</td>
			<!-- Odd -->
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5306"><img src="images/icons/ability_hunter_pet_worm.jpg"></a></td>                              
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5307"><img src="images/icons/achievement_dungeon_blackwingdescent_darkironcouncil.jpg"></a></td>
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5310"><img src="images/icons/ability_racial_aberration.jpg"></a></td>
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5308"><img src="images/icons/inv_misc_bell_01.jpg"></a></td>
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5309"><img src="images/icons/warrior_talent_icon_furyintheblood.jpg"></a></td>
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=4849"><img src="images/icons/achievement_boss_onyxia.jpg"></a></td>		
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5300"><img src="images/icons/inv_misc_crop_01.jpg"></a></td>   
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=4852"><img src="images/icons/achievement_dungeon_bastion-of-twilight_valiona-theralion.jpg"></a></td>
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5311"><img src="images/icons/achievement_dungeon_bastion-of-twilight_twilightascendantcouncil.jpg"></a></td>
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5312"><img src="images/icons/spell_shadow_mindflay.jpg"></a></td>
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5304"><img src="images/icons/spell_deathknight_frostfever.jpg"></a></td>
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5305"><img src="images/icons/spell_shaman_staticshock.jpg"></a></td>
			<!-- Heroic -->
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5094"><img src="images/icons/ability_hunter_pet_worm.jpg"></a></td>                              
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5107"><img src="images/icons/achievement_dungeon_blackwingdescent_darkironcouncil.jpg"></a></td>
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5108"><img src="images/icons/achievement_dungeon_blackwingdescent_raid_maloriak.jpg"></a></td>
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5109"><img src="images/icons/achievement_dungeon_blackwingdescent_raid_atramedes.jpg"></a></td>
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5115"><img src="images/icons/achievement_dungeon_blackwingdescent_raid_chimaron.jpg"></a></td>
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5116"><img src="images/icons/achievement_dungeon_blackwingdescent_raid_nefarian.jpg"></a></td>
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5118"><img src="images/icons/achievement_dungeon_bastion-of-twilight_halfus-wyrmbreaker.jpg"></a></td>   
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5117"><img src="images/icons/achievement_dungeon_bastion-of-twilight_valiona-theralion.jpg"></a></td>
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5119"><img src="images/icons/achievement_dungeon_bastion-of-twilight_twilightascendantcouncil.jpg"></a></td>
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5120"><img src="images/icons/achievement_dungeon_bastion-of-twilight_chogall-boss.jpg"></a></td>
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5122"><img src="images/icons/ability_druid_galewinds.jpg"></a></td>
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5123"><img src="images/icons/achievement_boss_murmur.jpg"></a></td>
			<td class="t11" width="3.3%"><a href="http://www.wowhead.com/achievement=5313"><img src="images/icons/achievement_dungeon_bastion-of-twilight_ladysinestra.jpg"></a></td>
	  </tr>
	<?php		
		
		for ($i = 0; $i < $result->num_rows; $i++) {
		
			// Character
			$character = $result->fetch_array();
			
			echo '<tr>';
			
			echo '<td width = "3%">' . ($i+1) . '</td>';
			
			echo '<td width="13%" style="font-weight:bold; color:'. $classColorArray[$character['class']] . '">' . $character['name'] . '</td>';
		
			for($j = 0; $j < 25; $j++){
				if($character['tier11'] & (1 << $j) ){						
					echo '<td class = "markg" width="3.3%"></td>';
					$t11Totals[$j]++;
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
			<td class="statCount" width="3.3%"><?php echo $t11Totals[0]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[1]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[2]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[3]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[4]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[5]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[6]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[7]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[8]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[9]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[10]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[11]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[12]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[13]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[14]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[15]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[16]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[17]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[18]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[19]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[20]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[21]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[22]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[23]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t11Totals[24]; ?></td>
        </tr>
		<tr>
			<td width="3%"></td>
			<td width="13%"><b>%</b></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[0]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[1]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[2]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[3]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[4]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[5]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[6]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[7]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[8]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[9]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[10]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[11]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[12]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[13]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[14]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[15]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[16]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[17]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[18]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[19]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[20]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[21]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[22]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[23]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t11Totals[24]/$numMembers) * 100) . '%'; ?></td>	
        </tr>
		</table>
	
	<?php
	}
	?>

	</div>
	<div id="t12">
  		<h3> T12 Achievements</h3>
    </div>
	
	<div id="t12Content">

	<?php
	// Tier 12
	$query = "SELECT a.raider_id, a.tier12, r.raider_id, r.class, r.name FROM SkunkAchievements a, SkunkRoster r 
		      WHERE a.raider_id = r.raider_id 
			  ORDER BY r.class, r.name";
			  
	$result = $db->query($query);
	
	if ($result){
	
		// Totals
		$t12Totals = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
		$numMembers = $result->num_rows;
		
	?>
		<table class="achievements12">	  
    	<tr>
			<td class="t12" width="3%">#</td>
			<td class="t12" width="13%">Name</td>
			<!-- Odd -->
			<td class="t12" width="3.3%"><a href="http://www.wowhead.com/achievement=5829"><img src="images/icons/achievement_zone_firelands.jpg"></a></td>                              
			<td class="t12" width="3.3%"><a href="http://www.wowhead.com/achievement=5810"><img src="images/icons/misc_arrowright.jpg"></a></td>
			<td class="t12" width="3.3%"><a href="http://www.wowhead.com/achievement=5821"><img src="images/icons/inv_misc_head_nerubian_01.jpg"></a></td>
			<td class="t12" width="3.3%"><a href="http://www.wowhead.com/achievement=5813"><img src="images/icons/spell_magic_polymorphrabbit.jpg"></a></td>
			<td class="t12" width="3.3%"><a href="http://www.wowhead.com/achievement=5830"><img src="images/icons/spell_shadow_painandsuffering.jpg"></a></td>
			<td class="t12" width="3.3%"><a href="http://www.wowhead.com/achievement=5799"><img src="images/icons/achievement_firelands-raid_fandral-staghelm.jpg"></a></td>	
			<!-- Heroic -->
			<td class="t12" width="3.3%"><a href="http://www.wowhead.com/achievement=5806"><img src="images/icons/achievement_boss_shannox.jpg"></a></td>   
			<td class="t12" width="3.3%"><a href="http://www.wowhead.com/achievement=5808"><img src="images/icons/achievement_boss_lordanthricyst.jpg"></a></td>
			<td class="t12" width="3.3%"><a href="http://www.wowhead.com/achievement=5807"><img src="images/icons/achievement_boss_broodmotheraranae.jpg"></a></td>
			<td class="t12" width="3.3%"><a href="http://www.wowhead.com/achievement=5809"><img src="images/icons/achievement_firelands-raid_alysra.jpg"></a></td>
			<td class="t12" width="3.3%"><a href="http://www.wowhead.com/achievement=5805"><img src="images/icons/achievement_firelandsraid_balorocthegatekeeper.jpg"></a></td>
			<td class="t12" width="3.3%"><a href="http://www.wowhead.com/achievement=5804"><img src="images/icons/achievement_firelands-raid_fandral-staghelm.jpg"></a></td>                             
	  </tr>
	  <?php		
		
		for ($i = 0; $i < $result->num_rows; $i++) {
		
			// Character
			$character = $result->fetch_array();
			
			echo '<tr>';
			
			echo '<td width = "3%">' . ($i+1) . '</td>';
			
			echo '<td width="13%" style="font-weight:bold; color:'. $classColorArray[$character['class']] . '">' . $character['name'] . '</td>';
		
			for($j = 0; $j < 12; $j++){
				if($character['tier12'] & (1 << $j) ){						
					echo '<td class = "markg" width="3.3%"></td>';
					$t12Totals[$j]++;
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
			<td class="statCount" width="3.3%"><?php echo $t12Totals[0]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t12Totals[1]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t12Totals[2]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t12Totals[3]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t12Totals[4]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t12Totals[5]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t12Totals[6]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t12Totals[7]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t12Totals[8]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t12Totals[9]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t12Totals[10]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t12Totals[11]; ?></td>	
        </tr>
		<tr>
			<td width="3%"></td>
			<td width="13%"><b>%</b></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t12Totals[0]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t12Totals[1]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t12Totals[2]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t12Totals[3]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t12Totals[4]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t12Totals[5]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t12Totals[6]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t12Totals[7]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t12Totals[8]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t12Totals[9]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t12Totals[10]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t12Totals[11]/$numMembers) * 100) . '%'; ?></td>	
        </tr>
		</table>
	
	<?php
	}
	?>

	</div>
	<div id="t13">
  		<h3> T13 Achievements</h3>
    </div>
	
	<div id="t13Content">
	
	<?php
	$query = "SELECT a.raider_id, a.tier13, r.raider_id, r.class, r.name FROM SkunkAchievements a, SkunkRoster r 
		      WHERE a.raider_id = r.raider_id 
			  ORDER BY r.class, r.name";
			  
	$result = $db->query($query);
	
	if ($result){
				
		// Totals
		$t13Totals = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
		$numMembers = $result->num_rows;
		
	?>
		</table>
		<table class="achievements13">	  
    	<tr>
			<td class="t13" width="3%">#</td>
			<td class="t13" width="13%">Name</td>
			<!-- Odd -->
			<td class="t13" width="3.3%"><a href="http://www.wowhead.com/achievement=6174"><img src="images/icons/spell_nature_massteleport.jpg"></a></td>                              
			<td class="t13" width="3.3%"><a href="http://www.wowhead.com/achievement=6128"><img src="images/icons/inv_enchant_voidsphere.jpg"></a></td>
			<td class="t13" width="3.3%"><a href="http://www.wowhead.com/achievement=6129"><img src="images/icons/achievement_doublerainbow.jpg"></a></td>
			<td class="t13" width="3.3%"><a href="http://www.wowhead.com/achievement=6175"><img src="images/icons/achievement_general_dungeondiplomat.jpg"></a></td>
			<td class="t13" width="3.3%"><a href="http://www.wowhead.com/achievement=6084"><img src="images/icons/spell_shadow_twilight.jpg"></a></td>
			<td class="t13" width="3.3%"><a href="http://www.wowhead.com/achievement=6105"><img src="images/icons/spell_fire_twilightpyroblast.jpg"></a></td>	
			<td class="t13" width="3.3%"><a href="http://www.wowhead.com/achievement=6133"><img src="images/icons/achievment_boss_spineofdeathwing.jpg"></a></td>
			<td class="t13" width="3.3%"><a href="http://www.wowhead.com/achievement=6180"><img src="images/icons/inv_dragonchromaticmount.jpg"></a></td>
			<!-- Heroic -->
			<td class="t13" width="3.3%"><a href="http://www.wowhead.com/achievement=6109"><img src="images/icons/achievment_boss_morchok.jpg"></a></td>   
			<td class="t13" width="3.3%"><a href="http://www.wowhead.com/achievement=6110"><img src="images/icons/achievment_boss_zonozz.jpg"></a></td>
			<td class="t13" width="3.3%"><a href="http://www.wowhead.com/achievement=6111"><img src="images/icons/achievment_boss_yorsahj.jpg"></a></td>
			<td class="t13" width="3.3%"><a href="http://www.wowhead.com/achievement=6112"><img src="images/icons/achievment_boss_hagara.jpg"></a></td>
			<td class="t13" width="3.3%"><a href="http://www.wowhead.com/achievement=6113"><img src="images/icons/achievment_boss_ultraxion.jpg"></a></td>
			<td class="t13" width="3.3%"><a href="http://www.wowhead.com/achievement=6114"><img src="images/icons/achievment_boss_blackhorn.jpg"></a></td>  
			<td class="t13" width="3.3%"><a href="http://www.wowhead.com/achievement=6115"><img src="images/icons/achievment_boss_spineofdeathwing.jpg"></a></td>
			<td class="t13" width="3.3%"><a href="http://www.wowhead.com/achievement=6116"><img src="images/icons/achievment_boss_madnessofdeathwing.jpg"></a></td>			
	  </tr>
	  <?php		
		
		for ($i = 0; $i < $result->num_rows; $i++) {
		
			// Character
			$character = $result->fetch_array();
			
			echo '<tr>';
			
			echo '<td width = "3%">' . ($i+1) . '</td>';
			
			echo '<td width="13%" style="font-weight:bold; color:'. $classColorArray[$character['class']] . '">' . $character['name'] . '</td>';
		
			for($j = 0; $j < 16; $j++){
		
				if( ($character['tier13'] & (1 << $j) )){						
					echo '<td class = "markg" width="3.3%"></td>';
					$t13Totals[$j]++;
				}
				else {
					echo '<td class = "markn" width="3.3%"></td>';
				}
				
			}
			
			echo '</tr>';			
		}
		?>
		<tr>
			<td width="3%"></td>
			<td width="13%"><b>TOTALS:</b></td>
			<td class="statCount" width="3.3%"><?php echo $t13Totals[0]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t13Totals[1]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t13Totals[2]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t13Totals[3]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t13Totals[4]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t13Totals[5]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t13Totals[6]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t13Totals[7]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t13Totals[8]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t13Totals[9]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t13Totals[10]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t13Totals[11]; ?></td>	
			<td class="statCount" width="3.3%"><?php echo $t13Totals[12]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t13Totals[13]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t13Totals[14]; ?></td>
			<td class="statCount" width="3.3%"><?php echo $t13Totals[15]; ?></td>	
        </tr>
		<tr>
			<td width="3%"></td>
			<td width="13%"><b>%</b></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t13Totals[0]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t13Totals[1]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t13Totals[2]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t13Totals[3]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t13Totals[4]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t13Totals[5]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t13Totals[6]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t13Totals[7]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t13Totals[8]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t13Totals[9]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t13Totals[10]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t13Totals[11]/$numMembers) * 100) . '%'; ?></td>	
			<td class="statCount" width="3.3%"><?php echo (int)(($t13Totals[12]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t13Totals[13]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t13Totals[14]/$numMembers) * 100) . '%'; ?></td>
			<td class="statCount" width="3.3%"><?php echo (int)(($t13Totals[15]/$numMembers) * 100) . '%'; ?></td>
        </tr>
		</table>
	<br><br>
	<?php
	}
	?>
	</div>
	<?php
	// Show the time of last update
	echo "Last Updated: " . date('F j, Y - g:i:s a'  , $lastUpdate) . ' (CST)';
	?>
    </div>
</div>