<?php
// Database Info
require(dirname(__FILE__) . '/../../sw_include/sw_db.php');

  function parserank($rank) {
    if($rank == 'Guild Master') {
      return '<span style="color:#CC6600">Guild Leader</span>';
    } else if($rank == 'Officer') {
      return '<span style="color:#6633CC">Officer</span>';
    } else if($rank == 'Raider') {
      return '<span style="color:#3366FF">Raider</span>';
    } else if($rank == 'Aspirant') {
      return '<span style="color:#669900">Trial</span>';
	}
   }
  #00FF96
  function classcolor($class) {
    if($class == 'Death Knight') {
      return '<span style="color:#C41F3B">'.$class.'</span>';
    } else if($class == 'Druid') {
      return '<span style="color:#FF7D0A">'.$class.'</span>';
    } else if($class == 'Hunter') {
      return '<span style="color:#ABD473">'.$class.'</span>';
    } else if($class == 'Mage') {
      return '<span style="color:#69CCF0">'.$class.'</span>';
    } else if($class == 'Monk') {
      return '<span style="color:#00FF96">'.$class.'</span>';
    } else if($class == 'Paladin') {
      return '<span style="color:#F58CBA">'.$class.'</span>';
    } else if($class == 'Priest') {
      return '<span style="color:#FFFFFF">'.$class.'</span>';
    } else if($class == 'Rogue') {
      return '<span style="color:#FFF569">'.$class.'</span>';
    } else if($class == 'Shaman') {
      return '<span style="color:#2459FF">'.$class.'</span>';
    } else if($class == 'Warlock') {
      return '<span style="color:#9482C9">'.$class.'</span>';
    } else if($class == 'Warrior') {
      return '<span style="color:#C79C6E">'.$class.'</span>';
    } else {
      return $class;
    }
  }
  
  $list = array('Death Knight', 'Druid', 'Hunter', 'Mage', 'Monk', 'Paladin', 'Priest', 'Rogue', 'Shaman', 'Warlock', 'Warrior');
  $result = $db->query("SET NAMES utf8");
  
  $startRow = false;
  $endRow = false;
  
  
  foreach($list as $class) {
  
		$query = "SELECT
		  name,
		  race,
		  class,
		  server,
		  guild_rank
		  FROM SkunkRoster WHERE (guild_rank = 'Guild Master' OR guild_rank = 'Officer' OR guild_rank = 'Raider' OR guild_rank = 'Aspirant')
							 AND class IS NOT NULL AND class = '" . $class . "' ORDER BY class, name";
		  
		$result = $db->query($query) or die ("Query failed: " . mysql_error() . " Actual query: " . $query);
	
		switch(strtolower($class)){
			case "death knight": 
			case "mage":
			case "priest":
			case "warlock":
				$startRow = true;
				break;
			case "hunter":
			case "paladin":
			case "shaman":
			case "warrior":
				$endRow = true;
				break;			
			default:
				break;		
		}
		

		if($startRow){
			echo '<div class="rosterRow">';
			$startRow = false;
		}
	
	    if($class == 'Warlock'){
			echo '<div class="rosterClass" style="margin-left: 80px; margin-right: 80px;">';
		}
		else {
			echo '<div class="rosterClass">';
		}
		?>
			
				<div class="rosterTitle"><img src="images/<?php echo strtolower($class); ?>.png"><?php echo classcolor($class); ?></div>

				
				<?php 
				while($row = $result->fetch_array()) {  
?>
				
				<a href="http://us.battle.net/wow/en/character/<?php echo $row['server'] . '/' . $row['name']; ?>/advanced">
					<div class="rosterPlayer" style="background-image: url(http://skunkworksguild.com/images/avatar/<?php echo $row['name']; ?>-inset.jpg);"></div>
					<div class="playerInfo">
						<div class="rosterName"><?php echo $row['name']; ?></div>
						<div class="rosterRank"><?php echo parserank($row['guild_rank']); ?></div>
					</div>
				</a>
<?php
				}
?>
			</div>	
<?php
		if($endRow){
			echo '</div>';
			$endRow = false;
		}	
			
    }
?>
