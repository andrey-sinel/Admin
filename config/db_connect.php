<?php
  $ip = getenv("REMOTE_ADDR");
  if ($ip=='127.0.0.1'){
    $dbhost='localhost';
    $dbuser='root';
    $dbpass='';
    $base='rosreestr';
  }
  else{
    $dbhost = 'localhost';
    $dbuser = 'cg63235_nakog';
    $dbpass = 'csvR8JFC';
    $base = 'cg63235_nakog';
  }
  $db = mysqli_connect("$dbhost","$dbuser","$dbpass","$base");
  if (!$db){
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
  }
?>