<?php
// Database Info
require(dirname(__FILE__) . '/../../sw_include/sw_db.php');

  function bbcode($msg){
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
  
  $topic_list_query = "select topic_id,topic_first_poster_name,topic_time,topic_first_post_id from phpbb_topics WHERE forum_id = '52' ORDER BY topic_time DESC LIMIT 5;";
  $topic_list_result = $db->query($topic_list_query);    
  
  while($topic_row = $topic_list_result->fetch_array()) {
    $topic_poster = $topic_row['topic_first_poster_name'];
    $topic_time = $topic_row['topic_time'];
    $topic_number = $topic_row['topic_id'];

    $details_query = "select post_subject,post_text from phpbb_posts where post_id = '".$topic_row['topic_first_post_id']."';"; 
    $details_result = $db->query($details_query);
    $details_row = $details_result->fetch_array();
    $topic_subject = $details_row['post_subject'];
    $topic_text = bbcode($details_row['post_text']);

    $count_query = "select count(*) as count from phpbb_posts where topic_id = '".$topic_number."';";
    $count_result = $db->query($count_query);
    $count_row = $count_result->fetch_array();   

    $num_comments = $count_row['count'] - 1;
    
    $second_post_query = "select post_id from phpbb_posts where topic_id = '".$topic_number."' AND post_id != ".$topic_row['topic_first_post_id']." ORDER BY post_id LIMIT 1;";
    $second_post_result = $db->query($second_post_query);
    $second_post_row =$second_post_result->fetch_array();
    $reply_post_number = $second_post_row['post_id'];
    if($reply_post_number == null)
    {
      $reply_post_number = $topic_row['topic_first_post_id'];
    }
  

  echo '
<div class="mainSection">
  <div class="title">
    <div class="newsSubject"><img src="images/gear6.png">'.$topic_subject.'</div>
    <div class="newsDate">Posted by <span>'.$topic_poster.'</span> on '.date('M d, Y', $topic_time).'</div>
  </div>
  <div class="section">
    '.$topic_text.'
    <div class="commentsLink">
      <a href="http://skunkworksguild.com/forums/viewtopic.php?f=52&t='.$topic_number.'#p'.$reply_post_number.'">Comments ('.$num_comments.')</a>
    </div>
  </div>
</div>
';
} 
?>
