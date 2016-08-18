<?php

$sel = "";

if(isset($_GET['p'])){

  if($_GET['p'] == "roster") {
    $sel = "roster";
  } elseif($_GET['p'] == "forum") {
    $sel = "forum";
  } elseif($_GET['p'] == "about") {
    $sel = "about";
  } elseif($_GET['p'] == "apply") {
    $sel = "apply"; 
  } elseif($_GET['p'] == "stats") {
    $sel = "stats";
  } else {
    $sel = "home";
  }

}
?>

<div class="nav">


<div class="innerNav">
<ul id="navBar" class="drop">
  <li><a href="?p=home"<?php if($sel == "home"){echo ' class="selected"';}?>>News</a></li>
  <li><img src="images/gear6.png"></li>
  <li><a href="?p=roster"<?php if($sel == "roster"){echo ' class="selected"';}?>>Roster</a></li>
  <li><img src="images/gear6.png"></li>
  <li><a href="?p=about"<?php if($sel == "about"){echo ' class="selected"';}?>>About</a></li>
  <li><img src="images/gear6.png"></li>
  <li><a href="?p=apply"<?php if($sel == "apply"){echo ' class="selected"';}?>>Apply</a></li> 
  <li><img src="images/gear6.png"></li>
  <li><a href="https://www.warcraftlogs.com/guilds/73/">Logs</a></li> 
  <li><img src="images/gear6.png"></li>
  <li><a href="http://www.skunkworksguild.com/forums/"<?php if($sel == "forum"){echo ' class="selected"';}?>>Forum</a></li>
  
<?php 
  // Raider tools
  switch($user->data['group_id']){
  case 7:  // Officer
  case 9:  // Guild Master
  case 8:  // Member
  case 12: // Raider
  case 14: // The Architect
  case 17: // Advisor
  case 18: // Aspirant
 
?>
  <li><img src="images/gear6.png"></li>
  <li><a href="#" <?php if($sel == "stats" || $sel == "gc" || $sel == "transmog"){echo ' class="selected"';}?>>Raider</a>
   <ul>
     <li><a href="?p=stats">General Stats</a></li>
     <li><a href="?p=gc">Gear Check</a></li>
     <li><a href="?p=logs">Raw Log Files</a></li>
	  <li><a href="?p=transmog">Transmog Gallery</a></li>
   </ul>
  </li>

<?php

  break;
  default:
  break;
}

// Officer tools
switch($user->data['group_id']){
  case 7:  // Officer
  case 9:  // Guild Master
?>
  <li><img src="images/gear6.png"></li>
  <li><a href="#" <?php if($sel == "o_imgup"){echo ' class="selected"';}?>>Officer</a>
   <ul>
     <li><a href="?p=o_imgup">News Image Upload</a></li>
   </ul>
  </li>
  <?php

  break;
  default:
  break;
}
?>
</ul>  
</div>
</div>

