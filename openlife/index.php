<?php
//echo ('ahoj');
require_once($_SERVER['DOCUMENT_ROOT']."/functions/page.php");
//echo('hello');
include("dblogin.php");
//echo('hello');
$dblogin=array('hostname'=>$db_hostname,'database'=>$db_database,'username'=>$db_username,'password'=>$db_password);

//$pagename='projects';
$active=(isset($_GET['view'])&&
($_GET['view']=='introduction'||$_GET['view']=='spacetime.activity'||$_GET['view']=='location'||
$_GET['view']=='money'||$_GET['view']=='thoughts'||$_GET['view']=='projects'))?$_GET['view']:'introduction';

$scripts=array('/js/ol/build/ol.js','//code.jquery.com/jquery-2.1.0.min.js','openlife.js');
$css=[
'/js/ol/css/ol.css'
];


$action=array(
  'introduction'=>function(){
    return "<div>
I start a project of open life. I'm going to post personal info about me:
location, time and money, thoughts, work, offerings and needs, maybe blog?. Motivation? Fun, experiment.
</div>";
  },
  'spacetime.activity'=>function($dblogin){
    $outcome='';
    $outcome.='<div>By mistake i deleted my previous positions of last 7 months. No backup. (2014/10/01)</div>';
    $data=array();
    $pdo = new PDO("mysql:host=$dblogin[hostname];dbname=$dblogin[database];charset=utf8", $dblogin['username'], $dblogin['password']);
    
    //****************without these lines it will not catch error and not transaction well. not rollback.********
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // Start the transaction. PDO turns autocommit mode off depending on the driver, you don't need to implicitly say you want it off
    $pdo->beginTransaction();
  // 
    try
    {
      // Prepare the statements
      $statement=$pdo->prepare('SELECT act.activityid, act.activity,act.comment AS ac,st.spacetimeid,st.latitude,st.longitude,st.precision,st.time,st.comment as stc from activity act INNER JOIN spacetime st ON (act.activityid = st.activityid) order by st.time desc');
      $statement->execute();
      
      $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
      $data=$rows;
  // 
      $pdo->commit();
    }
    catch(PDOException $e)
    {
      $pdo->rollBack();
      
      //$outcome=htmlentities(print_r($e,true));
      // Report errors
    }
    unset($pdo);
  //   
    /**********add div which can contain map***********/
    $outcome.='<div id="map"></div>';
    
    
    
    $outcome.='<table>';
    $outcome.='<tr><th>activity</th><th>comment</th><th>time</th><th>latitude</th><th>longitude</th><th>precision</th><th>comment</th></tr>';
    foreach($data as $drow){
      $lat=round($drow['latitude'],4);
      $lon=round($drow['longitude'],4);
      $outcome.="<tr><td>$drow[activity]</td><td>$drow[ac]</td><td>$drow[time]</td><td>$lat</td><td>$lon</td><td>$drow[precision]</td><td>$drow[stc]</td></tr>";
    }
    $outcome.='</table>';
    

    //$outcome.=print_r($data,true);
    return $outcome;
  },
  // 'location'=>function(){
  //   return "<div>location</div>";
  // },
  'money'=>function($dblogin){

    $outcome='<div>This part was removed for safety reasons.</div>';
/*    $data=array();
    $pdo = new PDO("mysql:host=$dblogin[hostname];dbname=$dblogin[database];charset=utf8", $dblogin['username'], $dblogin['password']);
    
    //****************without these lines it will not catch error and not transaction well. not rollback.********
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // Start the transaction. PDO turns autocommit mode off depending on the driver, you don't need to implicitly say you want it off
    $pdo->beginTransaction();

    try
    {
      // Prepare the statements
      $statement=$pdo->prepare('SELECT * from money ORDER BY time DESC');
      $statement->execute();
      
      $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
      $data=$rows;

      $pdo->commit();
    }
    catch(PDOException $e)
    {
      $pdo->rollBack();
      
      //$outcome=htmlentities(print_r($e,true));
      // Report errors
    }
    unset($pdo);
    
    $outcome.='<table>';
    $outcome.='<tr><th>amount</th><th>comment</th><th>date</th></tr>';
    foreach($data as $drow){
      $type_class=($drow['type']=='+'?'class="money_plus"':($drow['type']=='-'?'class="money_minus"':($drow['type']=='='?'class="money_amount"':'')));
      $value=round($drow['value'],2);
      $outcome.="<tr $type_class><td>$drow[type]$value $drow[currency]</td><td>$drow[comment]</td><td>$drow[time]</td></tr>";
    }
    $outcome.='</table>';
*/
    return $outcome;
  },
  'thoughts'=>function(){
    return "<div>thoughts</div>";
  },
  'projects'=>function(){
    return "<div>projects</div>";
  }
);

$content='';

foreach($action as $key=>$value){
  $content.=' <span><a href="/openlife/'.$key.'"'.($key==$active?' style="font-weight:bold" class="sub_active"':'').'>'.$key.'</a></span> ';
}

//$content.=create_view($active,$action,$dblogin);
$content = '<div>Project of openlife is paused until I figure out how to deal with big brother problem. If you want to know something, contact me.</div>';

page('open.life',$content,$scripts, $css);

function create_view($active,$action,$dblogin){
  if(isset($action[$active])){
    return $action[$active]($dblogin);
  }
  return '';
}
