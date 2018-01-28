<?php
  require("includes/head.php");
  require($document_root.'/admin/scripts/functions.php');
  $page =  !empty($_REQUEST['page']) ? $_REQUEST['page'] : '';
  $section = $DB->getData("select title, level, adm_access from adm_menu where page='$page'")[0];
  if (!$section){
    die("Ошибка обращения к базе.");
  }
  if ($adm_access!=$section['adm_access'] && $adm_user['id']>1){
    die("Доступ к этому разделу запрещен.");
  }
  echo "
  <script>
    toggle_sections(".$section['level'].");
  </script>";
  $level = isset($_REQUEST['level']) ? $_REQUEST['level'] : 0;
  $fth = isset($_REQUEST['fth']) ? $_REQUEST['fth'] : 0;
  $fields_values = array();
  $fields = $DB->getData("select field, value, class, alt, many from adm_fields");
  foreach ($fields as $f){
    $fields_values[$f['field']] = $f['value'];
    $fields_classes[$f['field']] = $f['class'];
    $fields_alts[$f['field']] = $f['alt'];
    $fields_many[$f['field']] = $f['many'];
  }
  echo "</h1>";
  
  // Обновление записи
  if (isset($_POST['id'])){
    //echo "<pre>"; print_r($_POST); echo "</pre>";
    $id = $_REQUEST['id'];
    $page = $_REQUEST['page'];
    $sql = "update $page set ";
    foreach ($_POST as $k=>$v){
      if ($k=='id') continue;
      if (mysqli_query($db, "select $k from $page limit 1")){
        $sql .= "$k='".addslashes($v)."', ";
      }
    }
    if (isset($_FILES['image'])){ 
      if (!file_exists("../images/$page")){
        mkdir("../images/$page",0777);
      }
      if (!file_exists("../images/$page/$id")){
        mkdir("../images/$page/$id",0777);
      }
      $file = $_FILES['image']['tmp_name'];
      include("scripts/translit.php");
      $filename = translit($_FILES['image']['name']);
      $filesize = $_FILES['image']['size'];
      $filetype = $_FILES["image"]["type"];
      if ($filetype=='image/jpeg' || $filetype=='image/gif' || $filetype=='image/png'){
        if (file_exists("../images/$page/$filename")){
          echo "<div class='alt_message'>Файл с именем \"$filename\" уже загружен.</div>";
          $sql .= "image='$filename', ";
        }
        else{
          if ($filesize<1000000){
            if (copy($file,"../images/$page/$id/$filename")){
              echo "<div class='alt_message'>Картинка загружена.</div>";
              $sql .= "image='$filename', ";
            }
            else echo "<div class='alt_message'>Ошибка при загрузке файла.</div>";
          }
          else echo "<div class='alt_message'>Размер файла не должен превышать 1 Мб.</div>";
        }
      }
    }
    $sql .= "id='$id' where id='$id'";
    //echo "$sql";
    $result = mysqli_query($db, $sql);
    if ($result){
      echo "<div class='alt_message'>Запись сохранена.</div>";
    }
    else echo "<div class='alt_message'>Ошибка записи!</div>";
    if (isset($_POST['del_image'])){
      $result2 = mysqli_query($db, "update $page set image='' where id='$id'");
      if ($result2){
        if (unlink("../images/$page/$id/".$_POST['del_image'])){
          echo "<div class='alt_message'>Картинка удалена.</div>";
        }
        else{
          echo "<div class='alt_message'>Ошибка удаления файла.</div>";
        }
      }
      else echo "<div class='alt_message'>Ошибка записи!</div>";
    }
    if ($_REQUEST['save']){
      // Если нажата "Сохранить" - уходим со страницы редактирования
      // Если "Применить" - остаёмся на странице
      unset($_REQUEST['id']);
    }
  }
  
  // Редактирование записи
  if (isset($_REQUEST['id'])){
    //print_r($_REQUEST);
    $id = $_REQUEST['id'];
    $field_group = !empty($_REQUEST['field_group']) ? $_REQUEST['field_group'] : 'main';
    $sql_query = "select * from ".$page." where id='".$id."' limit 1";
    $data = $DB->getData($sql_query)[0]; 
    $path[] = "<a href='table.php?page=$page'>".$section['title']."</a>";
    if ($data['level']){
      $level_arr = array();
      $level_arr = array_reverse(levelTitle($data['level']));
      foreach ($level_arr as $l){
        $path[] = $l;
      }
    }
    $fields_groups = $DB->getData("select * from adm_fields_groups where sh='1' order by sort");
    foreach ($fields_groups as $fg){
      $fgroups[] = array(
        'url'   => $fg['url'],
        'title' => $fg['title']
      );
    }
    $header = strip_tags($data['title']);
    include("templates/item.tpl");
    echo "
    <table border='0' cellspacing='0' cellpadding='11' width='100%'>
    <tr><td colspan='2'>
    <form action='table.php' method='post' enctype='multipart/form-data'>
      <input type='hidden' name='id' value='$id'>
      <input type='hidden' name='page' value='$page'>
      <input type='hidden' name='sort' value='$sort'>
      <input type='hidden' name='fth' value='$fth'>
      <input type='hidden' name='field_group' id='field_group' Аvalue='$field_group'>
    </td></tr>";
    $columns = $DB->getData("show columns from $page"); 
    foreach ($columns as $column){
      if ($column['Field']=='id') continue;
      echo "
      <tr class='fields ".$fields_classes[$column['Field']]."'>
      <td nowrap width='160'>
        <b>".$fields_values[$column['Field']].": </b>";
      if (!empty($fields_alts[$column['Field']])){
        echo "
        <div class='alt_block'>
          <img src='images/alt.png' alt='' />
          <span>".$fields_alts[$column['Field']]."</span>
        </div>";
      }
      echo "
      </td><td>";
      // Еслит название поле совпаает с названием таблицы БД
      // выводим записи из этой	таблицы
      if (is_array($inc = $DB->getData("select title, url from ".$column['Field']." where sh='1'&&url!=''&&level='0'"))){
        // Если поле предполагает несколько вариантов
        // выводим список checkbox
        if ($fields_many[$column['Field']]){
          echo "
          <input type='hidden' value='".$data[$column['Field']]."' name='".$column['Field']."' id='".$column['Field']."'>";
          foreach ($inc as $i){
            echo "
            <div style='margin:5px 15px 5px 0;'>
              <label onClick=''>
                <input type='checkbox' value='".$i[1]."' class='".$column['Field']."' onClick='check_".$column['Field']."()'"; if (strstr($data[$column['Field']],''.$i[1].'')){ echo " checked";} echo ">
                ".$i[0]."
              </label>
            </div>
            <div id='".$column['Field']."_sub'></div>";
          }
          echo "
          <script>
            function check_".$column['Field']."(){
              var selectedItems = new Array();
              $(\"input[class='".$column['Field']."']:checked\").each(function() {selectedItems.push($(this).val());});
              $('#".$column['Field']."').val(selectedItems);
            }
          </script>";
        }
        // иначе выводим список select
        else{
          $content_val = $data[$column['Field']];
          echo "
          <select name='".$column['Field']."'>
            <option value=''>---</option>";
            contentMenu(0,0,$column['Field']);
          echo "
          </select>";
        }
      }
      // Если поле предполагает список таблиц БД
      else if ($column['Field']=='inc_table'){
        $tables = mysqli_query($db, "show tables");
        echo "
        <select name='tables'>
          <option value=''>---</option>";
        while ($t = mysqli_fetch_row($tables)){
          if (strstr($t[0],'adm_')) continue;
          echo "
          <option value='".$t[0]."'"; if ($data[$column['Field']]==$t[0]){ echo " selected";} echo ">".$t[0]."</option>";
        }
        echo "
        </select>";
      }
      
      /* Пользовательские поля */
      else if ($column['Field']=='status' &&  $page=='transactions'){
        echo 
        $transaction_statuses[$data['status']];     
      }
      else if ($column['Field']=='status' &&  $page=='documents'){
        echo 
        $documents_statuses[$data['status']]."<br />
        <a href='https://nakogo.ru/cron/documents_status.php?id=".$data['id']."' target='_blank'>Статус документа в Kadnet</a>";     
      }
      else if ($column['Field']=='documents' &&  $page=='transactions'){
        foreach (explode(',',$data['documents']) as $doc){
          $doc_data = mysqli_fetch_assoc(mysqli_query($db, "select * from documents where id='".$doc."'"));
          echo "
          <a href='table.php?page=documents&id=".$doc."'>".$doc." - ".$doc_data['code']."</a>&nbsp;&nbsp; - &nbsp;&nbsp;".$documents_statuses[$doc_data['status']]."<br>";     
        }
      }
      else if ($column['Field']=='documents' &&  $page=='users'){
        $docs = mysqli_query($db, "select * from documents where user_id='".$data['id']."'");
        while ($doc = mysqli_fetch_assoc($docs)){
          echo "
          <a href='table.php?page=documents&id=".$doc['id']."'>".$doc['id']." - ".$doc['code']."</a>&nbsp;&nbsp; - &nbsp;&nbsp;".$documents_statuses[$doc['status']]."<br>";     
        }
      }
      /* Пользовательские поля */  
      
      else if ($column['Field']=='user_id'){
        $user_data = mysqli_fetch_array(mysqli_query($db, "select * from users where id='".$data['user_id']."'"));
        echo "
        <a href='table.php?page=users&id=".$data['user_id']."'>".$user_data['title']." (".$data['user_id'].")</a>";
      }
      // Выбор прав доступа
      else if ($column['Field']=='access'){
        $inc = $DB->getData("select title, url from users_access where sh='1'&&level='0'");
        echo "
        <select name='access'>
          <option value=''>---</option>";
        foreach ($inc as $i){
          echo "
          <option value='".$i[1]."'"; if ($data[$column['Field']]==$i[1]){ echo " selected";} echo ">".$i[0]."</option>";
        }
        echo "
        </select>";
      }
      // Переключатель Да/нет
      else if (strstr($column['Field'],'sh')){
        echo "
        <input type='hidden' name='".$column['Field']."' value='0'>
        <label>
          <div class='switch'>
            <input type='checkbox' name='".$column['Field']."' value='1'"; if ($data[$column['Field']]=='1'){ echo " checked"; } echo ">
            <div class='switch_btn'></div>
            <div class='switch_bg'></div>
          </div>
        </label>";
      }
      else if ($column['Field']=='title'){
        echo "
        <input type='text' name='title' id='title' style='width:500px' value='".stripslashes($data['title'])."' onBlur=\"transtlit_url()\">";
      }
      // Уровень вложенности
      else if ($column['Field']=='level'){
        $id = $data['id'];
        $level = $data['level'];
        echo "
        <select name='level'>
          <option value='0'"; if ($data['level']==0){ echo " selected";} echo ">---</option>";
          levelMenu(0,0,"$page");
        echo "
        </select>";
      }
      else if ($column['Field']=='image'){
        $image_path = !empty($data['image']) ? "../images/$page/$id/".$data['image'] : 'images/blank.gif';
        echo "
        <label>";
        if (!empty($data['image'])){
          echo "
          <img src='$image_path' height='150'><br>";
        }
        echo "
          <input type='file' name='image'>
        </label>";
        if (!empty($data['image'])){
          echo "
        <label>
          <input type='checkbox' name='del_image' value='".$data['image']."'>
          Удалить картинку
        </label>";
        }
      }
      else if ($column['Field']=='m_image'){
        $m_image_path = !empty($data['m_image']) ? "../images/$page/$id/small/".$data['m_image'] : 'images/m_image.png';
        echo "
        <input type='hidden' name='m_image' id='m_image' value='".$data['m_image']."'>
        <img src='$m_image_path' height='130' id='m_image_$id'><br>
        <!--<div style='width:130px; height:130; text-align:center; border:solid 1px #aaa;'><br>Нажмите на картинку, чтобы сделать её главной в записи.</div>-->
        <div id='img_list'>";
        if (!file_exists("../images/$page")){
          mkdir("../images/$page",0777);
        }
        if (!file_exists("../images/$page/$id")){
          mkdir("../images/$page/$id",0777);
        }
        if (!file_exists("../images/$page/$id/small")){
          mkdir("../images/$page/$id/small",0777);
        }
        if (!file_exists("../images/$page/$id/large")){
          mkdir("../images/$page/$id/large",0777);
        }
        $handle = opendir($document_root."/images/$page/$id/small");
        $images = $DB->getData("select * from images where page='$page'&&publ_id='$id' order by sort");
        foreach ($images as $i){
          echo "
          <img src='../images/$page/$id/small/".$i['file']."' class='m_image_th' onClick=\"main_image('../images/$page/$id/small/".$i['file']."','".$i['file']."','$id')\">";
        }
        echo "</div><br>
        <span onClick=\"$.fancybox.open({type:'iframe', href:'upload_image.php?page=$page&id=$id'})\" style='margin:10px 0; cursor:pointer;' class='form_button'>Добавить картинки</span>";
      }
      else if (strstr($column['Field'],'date')){
        echo "
        <input type='hidden' name='date' id='date_".$column['Field']."' value='".$data[$column['Field']]."'>
        <label id='date_".$column['Field']."_cal'>
          <img src='images/datepicker.gif' style='cursor:pointer; margin:10px 0 0 10px;'>
          <input type='text' value='".implode('.',array_reverse(explode('-',$data[$column['Field']])))."' style='width:100px;'>
        </label>
        <script type='text/javascript'>
          $(function(){
            $('#date_".$column['Field']."_cal').find('input').datepicker({
              altField:'#date_".$column['Field']."',
              altFormat:'yy-mm-dd',
              dateFormat:'dd.mm.yy',
              constraintInput:true,
              changeMonth:true,
              changeYear:true
            });
            $('#date_".$column['Field']."_cal').find('input').mask('99.99.9999');
          });
        </script>";
      }
      // Полный пусть к записи
      else if (strstr($column['Field'],'path')){
        $path = "/$page";
        $url_arr = array();
        $url_arr = array_reverse(levelURL($id));
        foreach ($url_arr as $u){ $path .= $u; }
        echo "
        <input type='text' value='$path' name='path' style='width:400px;'>";
      }
      else if ($column['Field']=='text'){
        echo "
        <textarea name='text' id='text' style='width:100%; height:400px;' class='tinymce_class'>".$data['text']."</textarea>
      </td><td valign='top' class='comp_list'>
        <h2>Компоненты:</h2>";
        $components_dir = $document_root."/components/";
        if (file_exists($components_dir)){
          $handle = opendir($components_dir);
          while (false !==($fil = readdir($handle))){ 
            if (($fil!=".") && ($fil!="..")){
              include($document_root."/components/$fil/config.php");
              echo "
              <div style='cursor:pointer;' onClick=\"$.fancybox.open({type:'iframe', href:'comp_settings.php?comp=?$fil'})\">".$components[$fil]['name']."</div>";
            }
          }
          closedir($handle);
        }
        echo "
        <script>
          function postInitWork()
          {
            var editor = tinyMCE.getInstanceById('text');
            editor.getBody().style.backgroundColor = \"".$data['bg_color']."\";
          }
        </script>";
      }    
      else{
        if ($column['Type']=='int(11)'){
          echo "
          <input type='text' name='".$column['Field']."' id='".$column['Field']."' style='width:120px;' value='".$data[$column['Field']]."'>";
        }
        else if ($column['Type']=='text'){
          echo "
          <textarea name='".$column['Field']."' id='".$column['Field']."' style='width:500px;' rows='20'>".$data[$column['Field']]."</textarea>";
        }
        else if ($column['Type']=='varchar(255)'){
          echo "
          <textarea name='".$column['Field']."' id='".$column['Field']."' style='width:500px;' rows='4'>".$data[$column['Field']]."</textarea>";
        }
        else{
          echo "
          <input type='text' name='".$column['Field']."' id='".$column['Field']."' style='width:400px;' value='".$data[$column['Field']]."'>";
        }
      }
      echo "
      <script>
        $('.".$fields_classes[$column['Field']]."_btn').show();
      </script>
      </td></tr>\n";
    }
    echo "
    <tr><td colspan='2' align='center'>
      <input type='submit' name='apply' value='Применить' class='form_button'>
      &nbsp;&nbsp;
      <input type='submit' name='save' value='Сохранить' class='form_button2'>
    </td></tr></form>
    </table>
    <script type='text/javascript'>
      toggle_fields('$field_group');
    </script>";
  }
  else{
    if (!empty($page)){
      $path[] = "<a href='table.php?page=$page'>".$section['title']."</a>";
      if ($level){
        $level_arr = array();
        $level_arr = array_reverse(levelTitle($level));
        foreach ($level_arr as $l){
          $path[] = $l;
        }
      }
      $header = strip_tags(str_replace("- ","",$path[count($path)-1]));
      include("templates/section.tpl");
    }
    else{
      $path[] = "<a href='index.php'>Главная</a>";
      $header = "Ошибка";
      include("templates/section.tpl");
    }
  }
  include("includes/foot.php");
?>