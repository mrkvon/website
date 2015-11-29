<?php

include("dbloginread.php");

header('Content-type: application/json');


$dblogin=array('hostname'=>$db_hostname,'database'=>$db_database,'username'=>$db_username,'password'=>$db_password);

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
  $statement=$pdo->prepare('SELECT act.activityid, act.activity,act.comment AS ac,st.spacetimeid,st.latitude,st.longitude,st.precision,st.time,st.comment as stc from activity act LEFT JOIN spacetime st ON (act.activityid = st.activityid)');
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

echo (json_encode($data));