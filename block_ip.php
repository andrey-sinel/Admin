<?php
  include("includes/head.php");
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
  echo "
  <h1>Блокировка по IP</h1>
  <div>
    <form action='visits.php' method='post'>
    Заблокировать IP
    <input type='text' name='ip'>&nbsp;&nbsp;&nbsp;
    время блокировки <input type='text' name='time' size='4'>
    <input type='submit' value='OK' class='form_button'></form><br>
  </div>
  <table border='0' cellspacing='0' cellpadding='4' class='page_list'>
    <tr>
      <td class='table_header'>IP</td>
      <td class='table_header'>Дата</td>
      <td class='table_header'>Срок</td>
    </tr>";
  $blocked_dir = $document_root.'/../logs/blocked';
  $handle = opendir($blocked_dir);
  while (false !==($fil = readdir($handle))){ 
    if ($fil=="." || $fil=="..") continue;
    $blocked_file = "$blocked_dir/$fil";
    $time_block = date("d.m.Y", filemtime($blocked_file)); 
    $block_sec = file_get_contents($blocked_file);
    $ip = str_replace('.txt', '', $fil);
    echo "
    <tr>
      <td class='table_content'>$ip</td>
      <td class='table_content'>$time_block</td>
      <td class='table_content'>$block_sec</td>
    </tr>";
  }
  closedir($handle);
  echo "
  </table>";
?>