<?php
session_start();

// Jump to login page if uid not set
if (!isset($_SESSION['uid'])) {
        header('Location: ../');
}
  $_SESSION['expiry'] = time() + 1800;
  $uid = $_SESSION['uid'];
  include_once ('../lib/dbCon.php');
  $qid = $_POST['qid'];
  //this file only for include in other, $qid get from parent file
  $pagesize = 10;

  $rows = ORM::for_table('comments')
           ->where('qid',$qid)
           ->order_by_desc('time')
           ->find_many()->count();
  if ($rows > 0)
    $pages=ceil($rows / $pagesize);
  else
    $pages=0;
  if (isset($_POST['page'])){
   $page = intval($_POST['page']);
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

  //form for write comment
  $comment="";

  if ($pages == 0)
    $comment .= "<div style='text-align:center;'>No comments found.</div><br />";
  else
  {

  $bar =" <div style='text-align:center;'>";
  $bar = $bar. "Page $page of $pages<br />";
  if ($page > 1)
    $bar .= "<a href='#' onclick='changepage($first,$qid)'>First</a> <a href='#' onclick='changepage($prev,$qid)'>Prev</a> ";
  for ($i = 1; $i < $page; $i++)
    $bar .= "<a href='#' onclick='changepage($i,$qid)'>[$i]</a> ";
  $bar .= "[$page]";
  for ($i = $page + 1; $i <= $pages; $i++)
    $bar .= " <a href='#' onclick='changepage($i,$qid)'>[$i]</a> ";
  if ($page < $pages)
    $bar .= "<a href='#' onclick='changepage($next,$qid)'>Next</a> <a href='#' onclick='changepage($last,$qid)'>Last</a> ";
  $bar = $bar. "</div>";

  $comment .= $bar;

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
      $time = date("d M Y H:i",$time);

    //comments table -- edit style here
    $comment .=
              "<table style='width:90%;margin-left:auto;margin-right: auto;'>
              <tr>
              <td style='background-color:#E0E0E0;vertical-align:top;width:25%;'>$name</td>
              <td style='background-color:#E0E0E0;text-align:left;width:75%;'>$content</td>
              </tr>
              <tr><td style='width:25%'></td>
              <td style='text-align:right;font-size:12px;width:75%'>$time</td>
              </tr></table><br />";
  }

  $comment = $comment.$bar."<br>";
}
echo $comment;
?>
