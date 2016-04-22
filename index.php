<?php

//setting Twig template engine
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/Twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem($_SERVER['DOCUMENT_ROOT'].'/views');
$twig = new Twig_Environment($loader, array(
  'cache' => $_SERVER['DOCUMENT_ROOT'].'/cache',
  'debug' => true
));

//routing
/*
The following function will strip the script name from URL i.e.  http://www.something.com/search/book/fitzgerald will become /search/book/fitzgerald
*/

function getCurrentUri() {
  $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
  $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
  $uri = trim($uri, '/');
  return $uri;
}

$base_url = getCurrentUri();
$routes = array();
$burl = explode('/', $base_url);
foreach($burl as $route)
{
  if(trim($route) != '')
    array_push($routes, $route);
}

/*
Now, $routes will contain all the routes. $routes[0] will correspond to first route. For e.g. in above example $routes[0] is search, $routes[1] is book and $routes[2] is fitzgerald
*/


$allowed_routes = array('projects', 'openlife', 'ideas', 'links');

if(sizeof($routes) === 0) {
  $template = $twig->loadTemplate('index.html');
  $variables = array();
  echo $template->render($variables);
}
elseif(in_array($routes[0], $allowed_routes)) {
  $pagename = $routes[0];
  $template = $twig->loadTemplate($pagename.'.html');
  $variables = array();

  if($pagename === 'projects') {
    $variables = array();
  }
  if($pagename === 'ideas') {
    $variables = array();
  }
  if($pagename === 'links') {
    $variables = array();
  }

  echo $template->render($variables);
}
else {
  http_response_code(404);
  $template = $twig->loadTemplate('404.html');
  $variables = array();
  echo $template->render($variables);
}

