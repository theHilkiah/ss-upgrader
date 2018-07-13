<?php  

  $autoloaders[] = realpath(__DIR__."/../autoload.php");
  $autoloaders[] = realpath(__DIR__."/../vendor/autoload.php");
  $autoloaders[] = realpath(__DIR__."/../../vendor/autoload.php");
  $autoloaders[] = realpath(__DIR__."/../../../vendor/autoload.php");
  $autoloaders[] = realpath(__DIR__."/../../../../vendor/autoload.php");

  foreach($autoloaders as $loader){
    if($loader && file_exists($loader)){
      require_once($loader); break;
    }
  }
