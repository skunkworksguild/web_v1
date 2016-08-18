<?php 

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './forums/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
$request->enable_super_globals();

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();

// Groups!
/*

7 - Officer
8 - Member
9 - Guild Master
12 - Raider
13 - Aspirant
14 - The Architect
15 - Advisor

*/

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="includes/skunkworks.css" rel="stylesheet" type="text/css">
<link rel="icon" type="image/png" href="images/favicon.png">
<title>Skunkworks :: Stormreaver</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" src="includes/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="//wow.zamimg.com/widgets/power.js"></script><script>var wowhead_tooltips = { "colorlinks": true, "iconizelinks": true, "renamelinks": true }</script>
<style type="text/css">
body
{
  font-size: 0.8em;
  font-family: Verdana;
  text-align: center;
  color:#cccccc;
  margin-top:0px;
}

a:link,a:visited,a:active
{
  color: #cccccc;
  text-decoration: none;
  font-weight: bold;
}

a:hover, a.selected {
  color: #ffffff;
}

</style>
</head>
<body>
<div class="content">
<div class="banner"><a href="http://skunkworksguild.com"><img src="images/logo9.png"></a></div>
  <?php    
    include("pages/nav.php");
  ?>
  <div class="middleBar">
    <?php
	$showRight = true;
	
	if(isset($_GET["p"])){
     
	 if($_GET["p"] == "roster")
      {
        include("pages/roster.php");
      }
	  else if ($_GET["p"] == "roster2")
      {
        include("pages/roster2.php");
      }
      else if ($_GET["p"] == "about")
      {
        include("pages/about.php");
      }
      else if ($_GET["p"] == "apply")
      {
        include("pages/apply.php");
      }
	  else if ($_GET["p"] == "stats")
      {
        include("pages/statistics.php");
		$showRight = false;
      }
	  else if ($_GET["p"] == "gc")
      {
        include("pages/gearCheck.php");
		$showRight = false;
      }
	   else if ($_GET["p"] == "gca")
      {
        include("pages/gearCheck_a.php");
		$showRight = false;
      }
	  else if ($_GET["p"] == "legacyAchievements")
      {
        include("pages/legacyAchievements.php");
		$showRight = false;
      }
	  else if ($_GET["p"] == "transmog") {
        include("pages/transmog.php");
		$showRight = false;
      }
	  else if ($_GET["p"] == "fp") {
        include("pages/fp.php");
		$showRight = false;
      }
    else if ($_GET["p"] == "o_imgup") {
        include("pages/o_imgup.php");
		$showRight = false;
      }	  
	  else if ($_GET["p"] == "logs") {
        include("pages/logs.php");
		$showRight = false;
      }
	  else if ($_GET["p"] == "alt") {
        include("pages/alt.php");
		$showRight = false;
      }	 
      else
      {
        include("pages/home.php");
      } 
	}
	else
    {
        include("pages/home.php");
    } 
    ?>
  </div>
  <?php 
  if($showRight){
  echo '<div class="rightBar">'; 
    
      include("pages/recruitment.php");  
	  include("pages/stream.php");
      include("pages/videos.php");
    
  echo '</div>';
  }
  ?>
</div>
</body>
</html>
