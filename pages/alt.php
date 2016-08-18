<?php
require(dirname(__FILE__) . '/../../sw_include/sw_db.php');
// Alt Rolls

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


$query = "SET NAMES utf8";
$db->query($query);

$query = "SELECT * FROM SkunkRoster 
          WHERE guild_rank = 'Guild Master' 
             OR guild_rank = 'Officer'
			 OR guild_rank = 'Raider'
          ORDER BY class, name";
$result = $db->query($query);
$numRaiders = $result->num_rows;


/*
echo '<pre>';
print_r($_POST);
echo '</pre>';
*/


/*
$showOutput = false;

if(isset($_POST['submit'])){

	$ourList = $_POST['selectedChars'];
	$numChars = count($ourList);

	// create our query (so we can get class colors too...)
	$query = "SELECT * FROM SkunkRoster WHERE ";


	for($i=0; $i < $numChars; $i++){

		if($i==0){
			$query .= "name = '$ourList[$i]' ";
		}
		else{
			$query .= "OR name = '$ourList[$i]' ";
		}

	}

    $query .= "ORDER BY RAND()";
   // echo $query . "<br>";
	$rOut = $db->query($query);

	$showOutput = true;

}


?>

<div class="entireSection">
    <div class="title">
		<div class="newsSubject"><img src="images/gear6.png">Alt Roll</div>
    </div>
   
    <div class="section">
	

    	<div class="statsTitle">
    		<?php
    		echo '<form action="" method="post">';
    		echo '<table width=100%>';
				echo '<tr><td  style="text-align:left">';
				for ($i = 0; $i < $numRaiders; $i++) {

					$character = $result->fetch_assoc();
			       
			        if($i % 5 == 0){
			        	echo '</td><td  style="text-align:left">';
			        }

			        echo '<input type="checkbox" name="selectedChars[]" value="' . $character['name'] . '"><span style="color:'. $classColorArray[$character['class']] . '">' . $character['name'] . '</span><br>';

				}

				echo '</td></tr>';
			echo '</table><br>';
			echo '<input type="submit" name="submit" value="Generate Random Order">';
			echo '</form>';




    		?>
    	</div>
    	<div style="text-align: left; margin-right:50% margin-left:50%">
    	<br>
  		<?php
    	if($showOutput){

	    	for ($i = 1; $i <= $numChars; $i++) {

					$char = $rOut->fetch_assoc();

					echo $i . ' - <span style="color:'. $classColorArray[$char['class']] . '">' . $char['name'] . '</span><br>';


			}
	   	}
    	?>
    	</div>

    </div>
</div>
*/
?>
<div class="entireSection">
    <div class="title">
		<div class="newsSubject"><img src="images/gear6.png">Alt Roll</div>
    </div>
   
    <div class="section">
	

    	<div class="statsTitle">
		
		
		</div>

    </div>
</div>


