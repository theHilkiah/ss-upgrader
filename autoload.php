<?php  

  $autoloaders[] = realpath(__DIR__."/../autoload.php");
  $autoloaders[] = realpath(__DIR__."/../vendor/autoload.php");
  $autoloaders[] = realpath(__DIR__."/../../vendor/autoload.php");
  $autoloaders[] = realpath(__DIR__."/../../../vendor/autoload.php");
  $autoloaders[] = realpath(__DIR__."/../../../../vendor/autoload.php");

  foreac($autoloader as $loader){
    if($loader && file_exists($loader)){
      require_once($loader); break;
    }
  }
