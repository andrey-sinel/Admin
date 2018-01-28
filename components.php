<?php
  require("includes/head.php");
  require($document_root.'/admin/scripts/functions.php');
  if ($_REQUEST['page']) $page =  $_REQUEST['page'];
  else if ($_REQUEST['table']) $page =  $_REQUEST['table'];
  else $page = '';
  $section = mysql_fetch_row(mysql_query("select title, level, adm_access from adm_menu where link='$docname'"));
  if (!$section){
    die("Ошибка обращения к базе.");
  }
  if ($adm_access!=$section[2] && $adm_user['id']>1){
    die("Доступ к этому разделу запрещен.");
  }
  echo "
  <script>
    toggle_sections(".$section[1].");
  </script>";
  if ($_REQUEST['comp']){
    $comp = $_REQUEST['comp'];
    include($document_root."/components/$comp/config.php");
    echo "
    <h1><a href='components.php'>Компоненты</a> - ".$components[$comp]['name']."</h1>
    <div class='clear' style='height:20px;'></div>
    <table border='0' cellspacing='0' cellpadding='11'>";
    foreach ($components[$comp] as $k=>$v){
      echo "
      <tr><td><b>$k</b></td>
      <td><input type='text' name='data[$k]' value='$v'></td></tr>";
    }
    echo "
    <tr><td>
      <input type='submit' value='Сохранить' class='form_button'></form>
    </td></tr></table>";
  }
  else{
    echo "
    <h1><a href='components.php'>Компоненты</a></h1>
    <div class='clear' style='height:20px;'></div>
    <table border='0' cellspacing='0' cellpadding='4' class='page_list'>
    <tr>
      <td class='table_header'>&nbsp;</td>
      <td class='table_header'>Компонент</td>
      <td class='table_header'>Код компонента</td>
    </tr>";
    $handle = opendir($document_root."/components/");
    while (false !==($fil = readdir($handle))){ 
      if (($fil!=".") && ($fil!="..")){
        include($document_root."/components/$fil/config.php");
        echo "
        <tr>
          <td class='table_content'>
            <a href='components.php?comp=$fil'><img src='images/edit.png' border='0' title='Редактировать' style='cursor:pointer; margin:6px;'></a>
          </td>
          <td class='table_content'>
            ".$components[$fil]['name']."
          </td>
          <td class='table_content'>
            $fil
          </td>
        </tr>";
      }
    }
    closedir($handle);
    echo "
    </table>";
  }
  include("includes/foot.php");
?>