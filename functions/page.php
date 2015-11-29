<?php

function page($name,$content,$script=array(), $css=[]){//$script is array of links to *.js files
  require_once($_SERVER['DOCUMENT_ROOT']."/data/data.php");
  require_once($_SERVER['DOCUMENT_ROOT']."/functions/menu.php");

//  header('Content-type: application/xhtml+xml');
  echo<<<_END
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:fb="http://ogp.me/ns/fb#" >
<head profile="http://www.w3.org/2005/10/profile">
<link rel="icon" type="image/png" href="img/face.jpg" />
<meta charset="utf-8" />
<meta name="description" content="Homepage of Michal Salajka" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>web portfolio of michal salajka::$name</title>

<meta property="og:type" content="website" />
<meta property="og:image" content="/" />
<meta property="og:title" content="Web portfolio of Michal Salajka" />
<meta property="og:description" content="Check out a homepage of one of 7 billion human beings. Information, projects, books, ideas included." />

<link rel="stylesheet" type="text/css" href="/css/reset.css" />
<link rel="stylesheet" type="text/css" href="/css/index.css" />
_END;
foreach($css as $link){
echo '
<link rel="stylesheet" type="text/css" href="'.$link.'" />';
}

echo<<<_END
</head>
<body>
<div id="wrapper">
_END;
  menu($menu,$name);
  echo '<div id="page"><main>'.$content.'</main></div ></div >';

  foreach($script as $link){
    echo<<<_END
<script src="$link" ></script>
_END;
  }
  echo "</body ></html >";
}
