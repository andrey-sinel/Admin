<link href='css/style.css' rel='stylesheet'>
<title>Настройка компонента</title>
<script type="text/javascript" src="js/jquery.min.js"></script>
<BODY leftMargin="30" topMargin="10">
<table border='0' cellspacing='0' cellpadding='4' width='100%'><tr><td colspan='4'>
<?php
  require('config/db_connect.php');
  $document_root = $_SERVER['DOCUMENT_ROOT'];
  $time = time();
  if ($_REQUEST){
    $comp_id = $_REQUEST['id'] ? $_REQUEST['id'] : "comp_".$time;
    $comp_src = explode('?',$_REQUEST['comp']);
    $comp_arr = explode("___",$comp_src[1]);
    $comp_name = $comp_arr[0];
    $data_id = $comp_arr[1];
    $data_section = $comp_arr[2];
    include($document_root."/components/$comp_name/config.php");
    echo "
    <form action=''>
      <input type='hidden' name='comp_id' value='$comp_id'>
      <tr><td colspan='4'>
        <h2>Настройки компонента</h2>
      </td><tr>
      <tr><td align='right'>Код компонента</td>
      <td><input type='text' name='comp_name' id='comp_name' value='$comp_name' readonly></td></tr>
      <tr><td align='right'>Название компонента</td>
      <td><input type='text' name='comp_title' id='comp_title' value='".$components[$comp_name]['name']."' readonly></td></tr>";
      /*
      foreach ($components[$comp_name] as $param=>$value){
        echo "
        <tr><td align='right'>$param</td>
        <td><input type='text' name='$param' id='$param' value='$value' readonly></td></tr>";
      }
      */
      if ($comp_name=='blocks'){
        echo "
        <tr><td align='right'>Выберите</td>
        <td><select name='data_id' id='data_id'>
          <option value='0'>---</option>";
        $components = mysqli_query($db, "select id, title from ".$comp_name." where sh='1' order by title");
        while ($comp = mysqli_fetch_array($components)){
          echo "
          <option value='".$comp[0]."'"; 
          if ($comp[0]==$data_id) echo " selected";
          echo ">".$comp[1]."</option>";
        }
        echo "  
        </select></td></tr>";
      }
      if ($comp_name=='menu'){
        echo "
        <tr><td align='right'>Выберите тип меню</td>
        <td><select name='data_section' id='data_section'>
          <option value='0'>---</option>";
        $components = mysqli_query($db, "select id, title, url from menu_types where sh='1' order by title");
        while ($comp = mysqli_fetch_array($components)){
          echo "
          <option value='".$comp['url']."'"; 
          if ($comp['url']==$data_section) echo " selected";
          echo ">".$comp['title']."</option>";
        }
        echo "  
        </select></td></tr>";
      }
      if ($comp_name=='catalog'){
        echo "
        <tr><td align='right'>Выберите раздел</td>
        <td><select name='data_section' id='data_section'>
          <option value=''>---</option>";
        $tables = mysqli_query($db, "show tables");
        while ($t = mysqli_fetch_row($tables)){
          if (strstr($t[0],'adm_')) continue;
          echo "
          <option value='".$t[0]."'";
          if ($comp_arr[2]==$t[0]) echo " selected";
          echo ">".$t[0]."</option>";
        }
        echo "
        </select></td></tr>
        <tr><td align='right'>Введите ID</td><td>
          <input type='text' name='data_id' id='data_id' value='0'>
        </td></tr>";
      }
      if ($comp_name=='forms'){
        echo "
        <tr><td align='right'>Выберите форму</td>
        <td><select name='data_id' id='data_id'>
          <option value=''>---</option>";
        $forms = mysqli_query($db, "select id, title from forms where level='0'");
        while ($f = mysqli_fetch_array($forms)){
          echo "
          <option value='".$f[0]."'";
          if ($comp_arr[1]==$f[0]) echo " selected";
          echo ">".$f[1]."</option>";
        }
        echo "
        </select></td></tr>";
      }
      echo "
      <tr><td colspan='4' align='center'>
        <input type='button' onClick='addComp();' value='Сохранить' class='form_button'>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type='button' onClick='delComp();' value='Удалить' class='form_button2'>
      </td></tr>
    </form>";
  }
  else{
    echo "
    Не выбран компонент";
  }
  echo "
  <script>
    function addComp(){
      comp_name = $('#comp_name').val();
      comp_title = $('#comp_title').val();
      data_id = $('#data_id').val();
      data_section = $('#data_section').val();
      if (data_id==undefined) data_id = 0;
      if (data_section==undefined || data_section.lenght<1) data_section = 'content';
      window.top.tinyMCE.execInstanceCommand('text','mceRemoveNode',false,'$comp_id');
      window.top.tinyMCE.execInstanceCommand('text','mceInsertContent',false,'<img src=\"component?'+comp_name+'___'+data_id+'___'+data_section+'\" class=\"component\" alt=\"Компонент '+comp_title+'\" id=\"comp_".$time."\"> ');
      parent.$.fancybox.close();
    }
    function delComp(){
      window.top.tinyMCE.execInstanceCommand('text','mceRemoveNode',false,'$comp_id');
      parent.$.fancybox.close();
    }
  </script>";
?>
</table>