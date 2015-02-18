<?php
  include_once ('../lib/dbCon.php');
  $pagesize=10; 
  $qid = $_GET['qid'];
  $comments = ORM::for_table('comments')
           ->where('qid',$qid)
           ->order_by_desc('time')
           ->find_many();
  $rows = $comments->count();
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
  $comments = $comments
              ->limit($pagesize)
              ->offset($offset)
              ->find_many();
  $comment="<input name='qid' type='hidden' value=$qid /></form>
           <div id=rectangle> </div>";
if ($pages == 0)
  $comment=$comment."<div style = 'text-align:center;'>No comment</div>";
else {

  $comment = $comment." <div align='center'>";
  if ($page > 1)
  {
    $comment = $comment." <a href='comment.php?page=$first&qid=$qid'>first</a> 
                          <a href='comment.php?page=$prev&qid=$qid'>prev</a> ";
  }
    $comment = $comment. "all $pages pages($page/$pages) ";
    for ($i=1;$i< $page;$i++)
     $comment = $comment. "<a href='comment.php?page=$i&qid=$qid'>[$i]</a> ";
     $comment = $comment."[$page]";
    for ($i=$page+1;$i<= $pages;$i++)
     $comment = $comment. " <a href='comment.php?page=$i&qid=$qid'>[$i]</a> ";
  if ($page < $pages)
  {
    $comment = $comment." <a href='comment.php?page=$next&qid=$qid'>next</a>
                          <a href='comment.php?page=$last&qid=$qid'>last</a> ";
  }
   $comment = $comment. "</div>";

  foreach ($comments as $each_comment) 
  {
  $uid=$each_comment->uid;
  $name = ORM::for_table('users')->find_one($uid)->name;
  $content = $each_comment->comment;
  $time = $each_comment->time;
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

  $comment = $comment." <div align='center'>";
  if ($page > 1)
  {
    $comment = $comment." <a href='comment.php?page=$first&qid=$qid'>first</a> 
                          <a href='comment.php?page=$prev&qid=$qid'>prev</a> ";
  }
    $comment = $comment. "all $pages pages($page/$pages) ";
    for ($i=1;$i< $page;$i++)
     $comment = $comment. "<a href='comment.php?page=$i&qid=$qid'>[$i]</a> ";
     $comment = $comment."[$page]";
    for ($i=$page+1;$i<= $pages;$i++)
     $comment = $comment. " <a href='comment.php?page=$i&qid=$qid'>[$i]</a> ";
  if ($page < $pages)
  {
    $comment = $comment." <a href='comment.php?page=$next&qid=$qid'>next</a>
                          <a href='comment.php?page=$last&qid=$qid'>last</a> ";
  }
   $comment = $comment. "</div>";
}
  echo str_replace("##comment##", $comment, file_get_contents('comment'));
  ?>

