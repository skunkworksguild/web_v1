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
  
  function classcolor($class) {
    if($class == 'Death Knight') {
      return '#C41F3B';
    } else if($class == 'Druid') {
      return '#FF7D0A';
    } else if($class == 'Hunter') {
      return '#ABD473';
    } else if($class == 'Mage') {
      return '#69CCF0';
    } else if($class == 'Monk') {
      return '#00FF96';
    }  else if($class == 'Paladin') {
      return '#F58CBA';
    } else if($class == 'Priest') {
      return '#FFFFFF';
    } else if($class == 'Rogue') {
      return '#FFF569';
    } else if($class == 'Shaman') {
      return '#2459FF';
    } else if($class == 'Warlock') {
      return '#9482C9';
    } else if($class == 'Warrior') {
      return '#C79C6E';
    } else {
      return $class;
    }
  }

  $list = array('Death Knight', 'Druid', 'Hunter', 'Mage', 'Monk', 'Paladin', 'Priest', 'Rogue', 'Shaman', 'Warlock', 'Warrior');
  $cnt = 0;

  $result = $db->query("SET NAMES utf8");
  
  
  echo '<center><div style="float: left; width: 1200px;">';
  
  foreach($list as $class) {
  
		$query = "SELECT
		  name,
		  race,
		  class,
		  guild_rank
		  FROM SkunkRoster WHERE (guild_rank = 'Guild Master' OR guild_rank = 'Officer' OR guild_rank = 'Raider' OR guild_rank = 'Aspirant')
							 AND class IS NOT NULL AND class = '" . $class . "' ORDER BY class, name";
		  
		$result = $db->query($query) or die ("Query failed: " . mysql_error() . " Actual query: " . $query);
		
		
			while($row = $result->fetch_array())
			{
			
				if($cnt >= 4){
					echo '</div>'."\n";
					$cnt = 0;
				}
					
				if($cnt == 0){
					 echo '<div style="float: left; width: 1200px;">';				
				}
				   
				echo '<a href="http://us.battle.net/wow/en/character/stormreaver/' . $row['name'] . '/advanced">';	
				echo '<div style="margin: 1px; width: 293px; float:left; height: 250px; background-image: url(http://skunkworksguild.com/images/avatar/' . $row['name'] .'-profilemain.jpg)">';
					    
					echo '<div style="float: left; text-shadow: 3px 3px #333333; font-size: 32px; color: '. classcolor($class) . ';">' . $row['name'] . '</div>';
						
				echo '</div>';
				echo '</a>'; 
				$cnt++;

			}

	}
	echo '</div>';
	echo '</div></center>';
?>
