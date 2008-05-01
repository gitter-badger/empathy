<?php
function incPlugin($name)
{
  require("empathy/include/plugin/empathy.$name.php");
}

function setEvent($event)
{
  $_GET['event'] = $event;
}

function setModule($module)
{
  $_GET['module'] = $module;
  if($_GET['module'] == "empathy")
  {
    $innerPath = "empathy/";
  }
  else
  {
    $innerPath = "";
  }
  define("INNER_PATH", $innerPath); 
}

function setClass($class)
{
  $_GET['class'] = $class;
//  require(DOC_ROOT."/".INNER_PATH."application/".$_GET['module']."/$class.php"); <- was always here
}

function invalidClass($class)
{
  $error = 0;
  //$classPath = DOC_ROOT."/".INNER_PATH."application/".$_GET['module']."/$class.php";
  if(INNER_PATH == "")
    {
      $classPath = DOC_ROOT."/application/".$_GET['module']."/$class.php";
    }
  else
    {
      $classPath = "empathy/application/".$_GET['module']."/$class.php";
    }
  
  if(!is_file($classPath))
  {
    $error++;
  }
  else
  {
    require($classPath);
    if(!class_exists($class))
    {
      $error++;
    }
  }  
  return $error;
}


function processRequest($module)
{    
  #incPlugin("force_www");
  
  $fullURI = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
  $removeLength = strlen(WEB_ROOT.PUBLIC_DIR);
  $uriString = substr($fullURI, $removeLength + 1);
  $errorCode = 0;

  if($uriString == "")
  {
    setModule($module[DEF_MOD]);
  }
  else
  {
    $uri = explode("/", $uriString);
    if($uri[(sizeof($uri) -1)] == "")
      {
	array_pop($uri);
      }
    $completed = 0;
    $j = 0;
    $skip = 0;
    $modIndex = 0;
    for($i = 0; ($i < sizeof($uri) && $i < 4); $i++)
    {
      $skip = 0;

      if(eregi("=", $uri[$i]))
      {
	$skip = 1;
      }
      
      if(is_numeric($uri[$i]) && !isset($_GET['id']))
      {
	$_GET['id'] = $uri[$i];
	$skip = 1;
      }      
      
      if(!isset($_GET['module']) && $skip == 0)
      {
	while($j < sizeof($module) && $uri[$i] != $module[$j])
	  {
	    $j++;
	  }
	  $modIndex = $j;
	  if($modIndex == sizeof($module))
	  {
	    $modIndex = DEF_MOD;
	    $errorCode = 1; // module not found
	  }
	  else
	  {
	    $skip = 1;
	  }
	  setModule($module[$modIndex]);
      }
      if(!isset($_GET['class']) && $skip == 0)
      {
	if(invalidClass($uri[$i]))
	{
	  $errorCode = 2; // class error
	  setClass($_GET['module']);	  
	}
	else
	{
	  setClass($uri[$i]);
	  $skip = 1;
	}
      }
      if(!isset($_GET['event']) && $skip == 0)
      {
	  setEvent($uri[$i]);
      }   
    }  
  }
}


date_default_timezone_set('Europe/London');
#incPlugin("no_cache");

array_push($module, "empathy");
array_push($moduleIsDynamic, 0);
//require(DOC_ROOT."/empathy/include/CustomController.php");
require(DOC_ROOT."/application/CustomController.php");
require("empathy/include/SmartyPresenter.php");


if(isset($_GET['module']))
{
  setModule($_GET['module']); 
  
}
else
{
  processRequest($module);
}


/*
if((!(isset($_GET['module']))) || (!(in_array($_GET['module'], $module))))
{
  $moduleIndex = DEF_MOD;
  $_GET['module'] = $module[$moduleIndex];
}
else
{
  $i = 0;
  while($_GET['module'] != $module[$i])
  {
    $i++;
  }
  $moduleIndex = $i;
}
*/


if(!(isset($_GET['class'])))
{
  $_GET['class'] = $_GET['module'];
}

$controllerName = $_GET['class'];



//$controllerPath = DOC_ROOT."/".INNER_PATH."application/".$_GET['module']."/$controllerName.php";
if(INNER_PATH == "")
{
  $controllerPath = DOC_ROOT."/application/".$_GET['module']."/$controllerName.php";
}
else
{
  $controllerPath = "empathy/application/".$_GET['module']."/$controllerName.php";
}
$controllerError = 0;

if(!is_file($controllerPath))
{  
  $_GET['event'] = $_GET['class'];
  $_GET['class'] = $_GET['module'];
  $controllerName = $_GET['module'];
  $controllerError = 1;
  //  $controllerPath = DOC_ROOT."/".INNER_PATH."application/".$_GET['module']."/$controllerName.php";
  if(INNER_PATH == "")
    {
      $controllerPath = DOC_ROOT."/application/".$_GET['module']."/$controllerName.php";
    }
  else
    {
      $controllerPath = "empathy/application/".$_GET['module']."/$controllerName.php";
    }
}


if(is_file($controllerPath) && !class_exists($_GET['class']) && isset($_GET['class']))
{
  require($controllerPath);
}


if(!class_exists($controllerName) && $controllerError == 0)
{
  $controllerError = 2; 
  $controllerPath = DOC_ROOT."/empathy/include/CustomController.php";
  $controllerName = "CustomController";
}

if(!(isset($_GET['event'])))
{
  $_GET['event'] = "default_event";
}



$controller = new $controllerName($controllerError);
$controller->$_GET['event']();

//$presenter->smarty->load_filter('output', 'png_image');
$controller->initDisplay();
?>


