<?php  

  if(($v1 = realpath(__DIR__."/../autoload.php"))) require_once $v1;
  elseif(($v2 = realpath(__DIR__."/../vendor/autoload.php"))) require_once $v2;
  elseif(($v3 = realpath(__DIR__."/../../vendor/autoload.php"))) require_once $v3;
  elseif(($v4 = realpath(__DIR__."/../../../vendor/autoload.php"))) require_once $v4;
  elseif(($v5 = realpath(__DIR__."/../../../../vendor/autoload.php"))) require_once $v5;
  else throw new Exception("No autoloader is available", 1);
