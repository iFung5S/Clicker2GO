<?php
  include_once ('../lib/dbCon.php');
  $pagesize=10; 
  $qid = $_GET['qid'];
  $rows = ORM::for_table('comments')
           ->where('qid',$qid)
           ->order_by_desc('time')
           ->find_many()->count();
  if ($rows > 0)
    $pages=ceil($rows / $pagesize);
  else
    $pages=0;
  if (isset($_GET['page'])){
   $page = intval($_GET['page']);
  }
  else{ 
    $page = 1;
  }

  $first=1;
  $prev=$page-1;
  $next=$page+1;
  $last=$pages;

  $offset=$pagesize*($page - 1);
  $comments = ORM::for_table('comments')
              ->where('qid',$qid)
              ->order_by_desc('time')
              ->limit($pagesize)
              ->offset($offset)
              ->find_many();
  $comment="<input name='qid' type='hidden' value=$qid /></form>
           <div id=rectangle> </div>";
if ($pages == 0)
  $comment=$comment."<div style='text-align:center;'>No comment</div>";
else {

  $bar =" <div style = 'text-align:center;'>";
  $bar = $bar. "Page $page of $pages<br />";
  if ($page > 1)
  {
    $bar = $bar." <a href='comment.php?page=$first&amp;qid=$qid'>First</a> 
                          <a href='comment.php?page=$prev&amp;qid=$qid'>Prev</a> ";
  }
    for ($i=1;$i< $page;$i++)
      $bar = $bar. "<a href='comment.php?page=$i&amp;qid=$qid'>[$i]</a> ";
    $bar = $bar."[$page]";
    for ($i=$page+1;$i<= $pages;$i++)
      $bar = $bar. " <a href='comment.php?page=$i&amp;qid=$qid'>[$i]</a> ";
  if ($page < $pages)
  {
    $bar = $bar." <a href='comment.php?page=$next&amp;qid=$qid'>Next</a>
                          <a href='comment.php?page=$last&amp;qid=$qid'>Last</a> ";
  }
   $bar = $bar. "</div>";
   
  $comment = $comment.$bar;
  
  foreach ($comments as $each_comment) 
  {
    $uid=$each_comment->uid;
    $name = ORM::for_table('users')->find_one($uid)->name;
    $content = $each_comment->comment;
    $time = strtotime($each_comment->time);

    if (date("Y-m-d",$time) == date("Y-m-d"))
      $time = "Today ".date("H:i",$time);
    else if (date("Y-m-d",$time) == date("Y-m-d",strtotime("-1 day")))
      $time = "Yesterday ".date("H:i",$time);
    else
      $time = date("Y-m-d H:i",$time);

    $comment = $comment.
              "<table style='width:98%'>
              <tr>
              <td bgcolor='#E0E0E0' style='vertical-align:top;width:25%'>$name</td>
              <td bgcolor='#E0E0E0' style='text-align:left;width:75%'>$content</td>
              </tr>
              <tr><td style='width:25%'></td>
              <td align='right' style='font-size:12px;width:75%'>$time</td>
              </tr></table><br/>";
  }

  $comment = $comment.$bar;
}
  echo str_replace("##comment##", $comment, file_get_contents('comment'));
  ?>
