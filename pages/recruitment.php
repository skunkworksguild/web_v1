<?php
// Database Info
require(dirname(__FILE__) . '/../../sw_include/sw_db.php');

  function bbcode2($msg){
    //youtube
    $youtube_regex = "%\[youtube\:[a-z0-9]+\](.*)\[\/youtube\:[a-z0-9]+\]%";
    $msg = preg_replace($youtube_regex, '<p align="center"><object type="application/x-shockwave-flash" style="width:662px; height:500px;" data="http://www.youtube.com/v/\\1&amp;hl=en&amp;fs=1&amp;hd=1"><param name="movie" value="http://www.youtube.com/v/\\1&amp;hl=en&amp;fs=1&amp;hd=1"></param><param name="allowFullScreen" value="true"></param></object></p> ', $msg);
    //image
    $msg = preg_replace("%\[img\:[a-z0-9]+\]%", "<img class=\"displayed\" src=\"", $msg);
    $msg = preg_replace("%\[\/img\:[a-z0-9]+\]%", "\" alt=\"\">", $msg);
    //link
    $link_regex = "%\[url=(.*)\:[a-z0-9]+\](.*)\[\/url\:[a-z0-9]+\]%";
    $msg = preg_replace($link_regex, '<a href="\\1">\\2</a>', $msg);
    $link_regex = "%\[url\:[a-z0-9]+\](.*)\[\/url\:[a-z0-9]+\]%";
    $msg = preg_replace($link_regex, '<a href="\\1">\\1</a>', $msg);
    //bold
    $msg = preg_replace("%\[b\:[a-z0-9]+\]%", "<b>", $msg);
    $msg = preg_replace("%\[\/b\:[a-z0-9]+\]%", "</b>", $msg);
    //italic
    $msg = preg_replace("%\[i\:[a-z0-9]+\]%", "<i>", $msg);
    $msg = preg_replace("%\[\/i\:[a-z0-9]+\]%", "</i>", $msg);
    //underline
    $msg = preg_replace("%\[u\:[a-z0-9]+\]%", "<u>", $msg);
    $msg = preg_replace("%\[\/u\:[a-z0-9]+\]%", "</u>", $msg);
    $msg = nl2br($msg);
    $msg = str_replace("<br />", "<br>", $msg);
    return $msg;
  }

  $recruit_query = "select post_text from phpbb_posts where post_id = '24160';"; 
  $recruit_result = $db->query($recruit_query);
  $recruit_row = $recruit_result->fetch_array();
  $recruit_post = $recruit_row['post_text'];
  list($junk1, $recruitList, $junk2) = split("%%RECRUITMENT%%", $recruit_post, 3);
  $arr = split("\n", trim($recruitList));

?>

<div class="sideSection">
  <div class="title">Recruitment</div>
  <div class="sectionRight">
  <?php
    foreach($arr as $line)
    {
      if(strpos($line, "subtitle:") !== false)
      {
        echo '<div class="recruitmentSubtitle">'.bbcode2(substr($line, 9)).'</div>';
      }
      elseif(strpos($line, "title:") !== false)
      {
        echo '<div class="recruitmentTitle">'.bbcode2(substr($line, 6)).'</div>';
      }
      elseif(strpos($line, "role:") !== false)
      {
        echo '<div class="recruitmentList">'.bbcode2(substr($line, 6)).'</div>';
      }
      else
      {

    	$data = explode(",", trim($line));
      	$class = ucfirst($data[0]);
      		
    		if($class == "All")
    		{
    			$class = trim($data[1]);
    			$imgString = $imgString = '<img src="images/classes/All.png">';
    			echo '<div class="recruitmentList">'.$class.'<div>'.$imgString.'</div></div>';
    		}
    		elseif($class == "None")
    		{
    			$class = trim($data[1]);
    			$imgString = $imgString = '<img src="images/classes/None.png">';
    			echo '<div class="recruitmentList">'.$class.':<div>'.$imgString.'</div></div>';	
    		}	
    		else
    		{	
          // Separate by Class,Spec
          // Multiple on one line would like like
          // Class, Spec, Class, Spec
          echo '<div class="recruitmentList">';

          $i=0;

          while($data[$i] != NULL){  
           
		   $class = trim($data[$i]);
            $spec = trim($data[$i+1]);

            if( ($class == "Warrior" && ($spec == "Fury" || $spec == "Arms")) || $class == "Mage" || $class == "Warlock" || $class == "Hunter"){
              $imgString = '<img src="images/classes/' . $class . '.png" title="' . $spec . '">';
            }
            else{
              $imgString = '<img src="images/classes/' . $class. '/' . $spec . '.png" title="' . $spec . '">';
            }

            echo '<div class="recruitmentEntry">';
            echo $class . '<br>';
            echo $imgString;
            echo '</div>';

            $i+= 2;
    	  }  
           echo '</div>';    
	      }           
      } 
    }
	
  ?>
  </div>
</div>
 