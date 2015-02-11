<?php
include_once ('../lib/dbCon.php');
  $qid=1;
  $counts=array();
  $a="ABCDEF";
  for ($i=1; $i<=6; $i++) // 6 are the maximum allowed answers
  {
    $N=$a[$i-1];
    $count = ORM::for_table('answers')
             ->where(array(
               'qid'=>$qid,
               'answer'=>'answer'.$i
              ))
             ->count();
    if($count!=0)
    {
      array_push($counts,array(
                "label" => $N.' : '.$count,
                "count" =>$count 
                ));
     }
  }
  $table= json_encode($counts);

  echo str_replace('##table##', $table, file_get_contents('graph'));
 ?>
