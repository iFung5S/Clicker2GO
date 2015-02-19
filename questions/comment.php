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
  $comment=$comment."<div style = 'text-align:center;'>No comment</div>";
else {

  $bar =" <div style = 'text-align:center;'>";
  if ($page > 1)
  {
    $bar = $bar." <a href='comment.php?page=$first&qid=$qid'>first</a> 
                          <a href='comment.php?page=$prev&qid=$qid'>prev</a> ";
  }
    $bar = $bar. "all $pages pages($page/$pages) ";
    for ($i=1;$i< $page;$i++)
      $bar = $bar. "<a href='comment.php?page=$i&qid=$qid'>[$i]</a> ";
    $bar = $bar."[$page]";
    for ($i=$page+1;$i<= $pages;$i++)
      $bar = $bar. " <a href='comment.php?page=$i&qid=$qid'>[$i]</a> ";
  if ($page < $pages)
  {
    $bar = $bar." <a href='comment.php?page=$next&qid=$qid'>next</a>
                          <a href='comment.php?page=$last&qid=$qid'>last</a> ";
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
      $time = "today ".date("H:i",$time);
    else if (date("Y-m-d",$time) == date("Y-m-d",strtotime("-1 day")))
      $time = "yesterday ".date("H:i",$time);
    else
      $time = date("Y-m-d H:i",$time);

    $comment = $comment.
              "<table border='0' width='98%'>
              <tr>
　　　         <td width='25%' bgcolor='#E0E0E0' align='left'>$name</td>
              <td width='75%' bgcolor='#E0E0E0' align='left'>$content</td>
              </tr>
              <tr><td width='25%'></td>
              <td width='75%' align='right' style='font-size:12px'>$time</td>
              </tr></table>";
  }

  $comment = $comment.$bar;
}
  echo str_replace("##comment##", $comment, file_get_contents('comment'));
  ?>
