<?php

session_start();

/*********************logging in***********************/
$salt='';
$username='';
$password='';

$user = $pass = "";
$logged=false;

if (isset($_POST['username'])||isset($_POST['password']))
{
  unset($_SESSION['logged']);
  $logged=false;
  
  if(isset($_POST['username'],$_POST['password'])){
    $user = crypt($_POST['username'],$salt);
    $pass = crypt($_POST['password'],$salt);
    
    if($user==$username && $pass==$password){
      $_SESSION['logged']=true;
      $logged=true;
    }
    else{
      unset($_SESSION['logged']);
      $logged=false;
      die('logging in not successful. <a href="/openlife/admin.php" >try again</a> or <a href="/openlife" >leave</a>');
    }
  }
  else{
    die('logging in not successful. <a href="/openlife/admin.php" >try again</a> or <a href="/openlife" >leave</a>');
  }
}
elseif(isset($_POST['logout'])){
  unset($_SESSION['logged']);
  $logged=false;
  die('successfully logged out. <a href="/openlife/admin.php" >log in again</a> or <a href="/openlife" >go away</a>.');
}
elseif(isset($_SESSION['logged'])&&$_SESSION['logged']){
  $logged=true;
}

/*****************************admin interface************************************/
if($logged){
  require_once($_SERVER['DOCUMENT_ROOT'].'/openlife/dblogin.php'); //login info
  
  /************process money form****************/
  if(isset($_POST['money_add'])&&validate_money()){
    $money_type=$_POST['money_direction'];
    $money_value=$_POST['money_amount'];
    $money_currency=$_POST['money_currency']=="..."?$_POST['money_currency_manual']:$_POST['money_currency'];
    $money_comment=$_POST['money_what'];
    $money_time=$_POST['time'];//date('Y-m-d H:i:s');
    
/*
| type      | enum('+','-','=') | YES  |     | NULL              |                |
| value     | decimal(30,10)    | YES  |     | NULL              |                |
| currency  | char(3)           | YES  |     | NULL              |                |
| comment   | varchar(255)      | YES  |     | NULL              |                |
| time
    */
    
    $pdo = new PDO("mysql:host=$db_hostname;dbname=$db_database;charset=utf8", $db_username, $db_password);
    
    //****************without these lines it will not catch error and not transaction well. not rollback.********
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // Start the transaction. PDO turns autocommit mode off depending on the driver, you don't need to implicitly say you want it off
    $pdo->beginTransaction();

    try
    {
      // Prepare the statements
      $statement=$pdo->prepare('INSERT INTO money (type,value,currency,comment,time) VALUES (:ty,:va,:cu,:co,:ti)');
      $statement->bindValue(':ty',strval($money_type), PDO::PARAM_STR);
      $statement->bindValue(':va',strval($money_value), PDO::PARAM_STR);
      $statement->bindValue(':cu',strval($money_currency), PDO::PARAM_STR);
      $statement->bindValue(':co',strval($money_comment), PDO::PARAM_STR);
      $statement->bindValue(':ti',strval($money_time), PDO::PARAM_STR);
      
      $statement->execute();
      // Delete the privileges

      $pdo->commit();
    }
    catch(PDOException $e)
    {
      $pdo->rollBack();
      
      //print_r($e);
      // Report errors
    }
    unset($pdo);
    
  }
  
  
  /***********************process spacetime form***********************/
  //handle time
  elseif(isset($_POST['spacetime_add'])&&validate_time()){
    
    $spacetime_points=array();
    
    
    $activity=$_POST['activity'];
    $latitude=$_POST['latitude'];
    $ptlen=sizeof($latitude);
    $longitude=$_POST['longitude'];
    $precision=$_POST['precision'];
    $time=$_POST['time'];
    $comment=$_POST['comment'];
    if($ptlen==sizeof($longitude)&&$ptlen==sizeof($precision)&&$ptlen==sizeof($time)
      &&$ptlen==sizeof($comment)){
      for($i=0,$len=$ptlen;$i<$len;$i++){
	$spacetime_points[$i]=array(
	  'latitude'=>$latitude[$i],
	  'longitude'=>$longitude[$i],
	  'precision'=>$precision[$i],
	  'time'=>$time[$i],
	  'comment'=>$comment[$i]
	);
      }
    }
    
    //die(print_r($spacetime_points));
    
    $spacetime_activity=$_POST['activity']['a'];
    $spacetime_activity_comment=$_POST['activity']['c'];
    
    
// +-------------+--------------+------+-----+-------------------+----------------+
// | Field       | Type         | Null | Key | Default           | Extra          |
// +-------------+--------------+------+-----+-------------------+----------------+
// | spacetimeid | int(11)      | NO   | PRI | NULL              | auto_increment |
// | latitude    | decimal(9,5) | YES  |     | NULL              |                |
// | longitude   | decimal(9,5) | YES  |     | NULL              |                |
// | precision   | decimal(9,2) | YES  |     | NULL              |                |
// | comment     | varchar(255) | YES  |     | NULL              |                |
// | time        | datetime     | YES  |     | NULL              |                |
// | timestamp   | timestamp    | NO   |     | CURRENT_TIMESTAMP |                |
// +-------------+--------------+------+-----+-------------------+----------------+

    
    // List the SQL strings that you want to use
    $sql['activity']  = "INSERT INTO activity (activity, comment) values (:ac,:co)";
    $sql['spacetime_point'] = "INSERT INTO spacetime (activityid,latitude,longitude,`precision`,comment,time) values (:ai,:la,:lo,:pr,:co,:ti)";
    
    $pdo = new PDO("mysql:host=$db_hostname;dbname=$db_database;charset=utf8", $db_username, $db_password);
    
    //****************without these lines it will not catch error and not transaction well. not rollback.********
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // Start the transaction. PDO turns autocommit mode off depending on the driver, you don't need to implicitly say you want it off
    $pdo->beginTransaction();

    try
    {
      // Prepare the statements
      
      $statement['activity']=$pdo->prepare($sql['activity']);
      $statement['activity']->execute(array(':ac'=>$spacetime_activity,':co'=>$spacetime_activity_comment));
      
      $activity_id=$pdo->lastInsertId();
      
      for($i=0,$len=sizeof($spacetime_points);$i<$len;$i++){
	$statement['points'][$i]=$pdo->prepare($sql['spacetime_point']);
	$statement['points'][$i]->bindValue(':ai',$activity_id, PDO::PARAM_INT);
	$statement['points'][$i]->bindValue(':la',strval($spacetime_points[$i]['latitude']), PDO::PARAM_STR);
	$statement['points'][$i]->bindValue(':lo',strval($spacetime_points[$i]['longitude']), PDO::PARAM_STR);
	$statement['points'][$i]->bindValue(':pr',strval($spacetime_points[$i]['precision']), PDO::PARAM_STR);
	$statement['points'][$i]->bindValue(':co',strval($spacetime_points[$i]['comment']), PDO::PARAM_STR);
	$statement['points'][$i]->bindValue(':ti',strval($spacetime_points[$i]['time']), PDO::PARAM_STR);
	
	$statement['points'][$i]->execute();
      }
      // Delete the privileges

      $pdo->commit();
    }
    catch(PDOException $e)
    {
      $pdo->rollBack();
      
      //print_r($e);
      // Report errors
    }
    unset($pdo);
  }
  else{}

  /***************generate loggedin page***********************/
  //header('Content-type: application/xhtml+xml');
    /*********head, body tag********************/
  echo<<<_END
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >
  <head>
    <meta charset="utf-8" />
    <title>login form</title>
  </head>
  <body>
_END;

  echo 'you are logged in. <form action="" method="post"><input type="submit" name="logout" value="logout" /></form>';


    /*********money form****************/
  echo<<<_END
<div>
money
<form action="" method="post">
<select name="money_direction" >
  <option value="=">=</option>
  <option value="+">+</option>
  <option value="-">-</option>
</select><input type="text" name="money_amount" placeholder="how much" />
<select name="money_currency" >
  <option value="...">...</option>
  <option value="CZK">CZK</option>
  <option value="EUR">EUR</option>
  <option value="GBP">GBP</option>
</select><input type="text" name="money_currency_manual" size="3" placeholder="..." />
<input type="text" name="money_what" placeholder="comment"  />
<input type="text" name="time" placeholder="utc time (yyyymmddhhiiss)"  />
<input type="submit" name="money_add" value="add" />
</form>
</div>
_END;
    /********spacetime form*************/
  echo<<<_END
<div>
spacetime
<form action="" method="post">
<div>
<input type="text" name="activity[a]" placeholder="activity" />
<input type="text" name="activity[c]" placeholder="comment" />
</div>
<div id="st_points">
<div class="spacetime_point" >
<input class="st_active" type="checkbox" /><input class="st_latitude" type="text" name="latitude[]" placeholder="latitude" /><input class="st_longitude" type="text" name="longitude[]" placeholder="longitude" /><input class="st_precision" type="text" name="precision[]" placeholder="precision [m]" /><input class="st_time" type="text" name="time[]" placeholder="time (yyyymmddhhiiss)" /><input type="text" name="comment[]" placeholder="comment" />
</div>
</div>
<button id="add_st_point" type="button">+</button>
<input type="submit" name="spacetime_add" value="add" />
</form>
</div>

<button type="button" id="location_now_button">get current location</button>
<div id="map" style="height:300px;width:400px"></div>


<div>
thoughts
<form action="" method="post">
<input type="text" name="thoughts_what" placeholder="thought" />
<input type="text" name="thoughts_category" placeholder="category" />
<input type="submit" name="thoughts_add" value="add" />
</form>
</div>

<div>
stuff,material
<form action="" method="post">
<input type="text" name="stuff_what" placeholder="stuff" />
<input type="text" name="stuff_category" placeholder="category"  />
<input type="submit" name="stuff_add" value="add" />
</form>
</div>

<div>
offer
<form action="" method="post">

<input type="submit" name="offer_add" value="add" />
</form>
</div>

<div>
need
<form action="" method="post">

<input type="submit" name="need_add" value="add" />
</form>
</div>

<div>
work
<form action="" method="post">

<input type="submit" name="work_add" value="add" />
</form>
</div>

_END;

    /*************end of page, scripts***********/
  echo<<<_END
  <script src="/js/ol/build/ol.js"></script>
  <script src="//code.jquery.com/jquery-2.1.0.min.js"></script>
  <script src="admin.js"></script>
  </body>
  </html>
_END;
}
else{
  /***********generate login page***************/
  header('Content-type: application/xhtml+xml');
  echo<<<_END
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >
  <head>
    <meta charset="utf-8" />
    <title>login form</title>
  </head>
  <body>
    <form action="" method="post">
      <input type="text" name="username" placeholder="username" />
      <br />
      <input type="password" name="password" placeholder="password" />
      <br />
      <input type="submit" name="login" value="login" />
    </form>
  </body>
</html>
_END;
}

function validate_money(){
  if(isset($_POST['money_direction'],$_POST['money_amount'],$_POST['money_currency'],$_POST['money_currency_manual'],$_POST['money_what'],$_POST['money_add'])){
    return true;
  }
  
  return false;
}

function validate_location(){
  if(isset($_POST[' [location_add] => add'])||true){
    return true;
  }
  return false;
}

function validate_stuff(){
  if(isset($_POST['[stuff_what] => [stuff_category] => [stuff_add] => add'])||true){
    return true;
  }
  return false;
}

function validate_thoughts(){
  if(isset($_POST['[thoughts_what] => [thoughts_category] => [thoughts_add] => add'])||true){
    return true;
  }
  return false;
}

function validate_time(){
  if(isset($_POST['[time_from_dd] => [time_from_mm] => [time_from_yyyy] => [time_from_hh] => [time_from_min] => [time_to_dd] => [time_to_mm] => [time_to_yyyy] => [time_to_hh] => [time_to_min] => [time_description] => [time_category] => [time_add] => add ) 1'])||true){
    return true;
  }
  return false;
}

function createTime($yyyy,$mm,$dd,$hh,$min,$ss=0){
  $time_obj=new DateTime();
  $time_obj->setDate($yyyy,$mm,$dd);
  $time_obj->setTime($hh,$min,$ss);
  $time=$time_obj->format('Y-m-d H:i:s');
  
  return $time;
}
