<?php
  require('../config/db_connect.php');
  require('functions.php');
  if ($_REQUEST['action']=='save_image'){
    $path = $_GET['path'];
    $table = $_GET['table'];
    $id = $_GET['id'];
    $value = stripslashes($_GET['value']);
    $query = "update images set title='$value' where id='$id'";
    if (!mysqli_query($db, $query)){
      errorLog("Connect failed: ". mysqli_error($db));
    }
    if ($exist = mysqli_num_rows(mysqli_query($db, "select * from images where title='$value'&&id='$id'"))){
      echo "$value";
    }
  }
  if ($_REQUEST['action']=='save_info'){
    $id = $_GET['id'];
    $page = $_GET['page']; 
    $area = $_GET['area']; 
    $value = stripslashes($_GET['value']); 
    $query = "update $page set $area='$value' where id='$id'";
    if (!mysqli_query($db, $query)){
      errorLog("Connect failed: ". mysqli_error($db));
    }
    if ($exist = mysqli_num_rows(mysqli_query($db, "select * from $page where $area='$value'&&id='$id'"))){
      echo "$value";
    }
  }
?>