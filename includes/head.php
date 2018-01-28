<?php
  session_start();
  $document_root = $_SERVER['DOCUMENT_ROOT'];
  require('config/settings.php');
  require('../config.php');  
  require('lib/mysql.class.php');
  $DB = new ConnectMySQL;

  require('config/db_connect.php');
  $docname = pathinfo($_SERVER['PHP_SELF'])['basename'];
  if (!isset($_SERVER['PHP_AUTH_USER'])){
    Header("WWW-Authenticate: Basic realm='Admin'");
    Header("HTTP/1.0 401 Unauthorized");
    echo "
    <link href='../css/admin.css' rel='stylesheet'>
    <title>Администрирование</title>
    <h1>Модуль администрирование</h1><br>Необходима авторизация";
    exit();
  }
  else{
    $name = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];
    $row = $DB->getData("select password from adm_users where title='$name'")[0];
    if (!$row){
      Header("WWW-Authenticate: Basic realm='Admin'");
      Header("HTTP/1.0 401 Unauthorized");
      echo "
      <link href='../css/admin.css' rel='stylesheet'>
      <title>Администрирование</title>
      <h1>Модуль администрирование</h1><br>Ошибка авторизации";
      exit();
    }
    else{
      $real_password = $row['password'];
      if ($real_password!=$password)
      {
    	Header("WWW-Authenticate: Basic realm='Admin'");
    	Header("HTTP/1.0 401 Unauthorized");
    	echo "
       	<link href='../css/admin.css' rel='stylesheet'>
    	<title>Администрирование</title>
    	<h1>Модуль администрирование</h1><br>Введен неверный пароль";
    	exit();
      }
    }
  }
  date_default_timezone_set('Europe/Moscow');
  $date = date('Y-m-d');
  $months = array('01'=>'Январь','02'=>'Февраль','03'=>'Март','04'=>'Апрель','05'=>'Май','06'=>'Июнь','07'=>'Июль','08'=>'Август','09'=>'Сентябрь','10'=>'Октябрь','11'=>'Ноябрь','12'=>'Декабрь');
  $months2 = array('01'=>'января','02'=>'февраля','03'=>'марта','04'=>'апреля','05'=>'мая','06'=>'июня', '07'=>'июля','08'=>'августа','09'=>'сентября','10'=>'октября','11'=>'ноября','12'=>'декабря');
  $adm_user = $DB->getData("select * from adm_users where title='$name'")[0];
  $adm_access = isset($adm_user['adm_access']) ? $adm_user['adm_access'] : '';
  $sql_part = $adm_user['id']=='1' ? "" : "&&adm_access like '%$adm_access%'";  

  $main_menu = array();
  $menu = $DB->getData("select * from adm_menu where level='0'&&sh='1'$sql_part order by id");
  foreach ($menu as $m){  
    $main_menu[$m['id']]['link'] = !empty($m['link']) ? $m['link'] : '';
    $main_menu[$m['id']]['title'] = $m['title'];
    $main_menu[$m['id']]['icon'] = !empty($m['icon']) ? $m['icon'] : 'fa-bars';
    $main_menu[$m['id']]['id'] = $m['id'];
    $sub_menu = $DB->getData("select * from adm_menu where level='".$m['id']."'&&sh='1'$sql_part order by id");
    foreach ($sub_menu as $sm){
      $main_menu[$m['id']]['child'][$sm['id']]['link'] = !empty($sm['link']) ? $sm['link'] : 'table.php?page='.$sm['page'];
      $main_menu[$m['id']]['child'][$sm['id']]['image'] = !empty($sm['image']) ? '/images/adm_menu/'.$sm['image'] : 'images/files.gif';
      $main_menu[$m['id']]['child'][$sm['id']]['title'] = $sm['title'];
      $main_menu[$m['id']]['child'][$sm['id']]['id'] = $sm['id'];
    }
  }
  //echo "<pre>"; print_r($main_menu); echo "</pre>";
  include("templates/header.tpl");
?>