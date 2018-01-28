<?php
  session_start();
  //print_r($_REQUEST);
  $document_root = $_SERVER['DOCUMENT_ROOT'];
  require('../config/db_connect.php');
  require('../scripts/functions.php');
  date_default_timezone_set('Europe/Moscow');
  $date = date('Y-m-d');
  if (!empty($_REQUEST['page'])){
    $page = $_REQUEST['page'];
    $level = !empty($_REQUEST['level']) ? $_REQUEST['level'] : 0;
    $search = !empty($_REQUEST['search']) ? $_REQUEST['search'] : '';
    $filter = !empty($_REQUEST['filter']) ? $_REQUEST['filter'] : '';
    $fth = $_REQUEST['fth'] ? $_REQUEST['fth'] : 0;
    $amth = 30; // Количество строк на странице
    $columns_names = array(); // Массив полей таблицы
    // Список полей таблицы
    $columns = mysqli_query($db, "show columns from ".$page); 
    while ($c = mysqli_fetch_array($columns)){
      $columns_names[] = $c[0];
      $fields_arr[$c['Field']] = $c['Field'];
    }
    //echo "<pre>"; print_r($fields_arr); echo "</pre>";
    $id = $columns_names[0]; // Первое поле таблицы - ключ ИД
    $fields = mysqli_query($db, "select field, value, class from adm_fields");
    while ($f = mysqli_fetch_row($fields)){
      $fields_values[$f[0]] = $f[1]; // Массив названий столбцов
      $fields_classes[$f[0]] = $f[2]; // Массив классов столбцов
    }
    // Запись исформации о сортировке в сессию
    if ($_REQUEST['sort']) $_SESSION['sort'] = $_REQUEST['sort'];
    if ($_REQUEST['sc']) $_SESSION['sc'] = $_REQUEST['sc'];
    // Если есть поле в таблице сортируе по нему
    if ($_SESSION['sort'] && mysqli_query($db, "select ".$_REQUEST['sort']." from $page")){
      $sort = $_SESSION['sort'];
      $sort_exists = '0';
      if ($_SESSION['sort']=='sort') $sort_exists = '1';
    }
    // Иначе сортируем по полю ИД
    else{
      $sort = "$id";
      $sort_exists = '0';
    }
    $sc = $_SESSION['sc'] ? $_SESSION['sc'] : 'desc'; // Порядок сортировки
    $sc_sim = $sc=='asc' ? '&#9660;' : '&#9650;'; // Стрелка сортировки
    $sc_ch = $sc=='asc' ? 'desc' : 'asc';
    
    // Сохранение порядка сортировки строк
    if ($_REQUEST['action']=='sort'){
      $list = explode('&',$_REQUEST['id']);
      //echo "<pre>"; print_r($list); echo "</pre>";
      $s = 1;
      foreach ($list as $l){
        $l_arr = explode('=',$l);
        //echo $l_arr[1]." - $s<br>";
        mysqli_query($db, "update $page set sort='$s' where $id='".$l_arr[1]."'");
        $s++;
      }
    }
    // Добавление новой строки (записи)
    if ($_REQUEST['action']=='add'){
      $add_query = "insert $page set level='$level'";
      $add_query .= isset($fields_arr['date']) ? ", date='".date('Y-m-d')."'" : "";
      mysqli_query($db, $add_query);
    }
    // Улаление строки (записи)
    if ($_REQUEST['action']=='delete'){
      if ($page=='templates'){
        $s = mysqli_fetch_row(mysqli_query($db, "select url from templates where ;id='".$_REQUEST['id']."'"));
        removeDir($document_root."/templates/".$s[0]);
      }
      $res = 1;
      if ($res){
        mysqli_query($db, "delete from $page where id='".$_REQUEST['id']."'");
      }
    }
    
    $sql_part = "";
    $sql_part .= !empty($filter) ? $filer." && " : "";
    if (isset($fields_arr['level'])){
      $sql_part .= "level='".$level."' && ";
    }
    if (isset($fields_arr['title'])){
      $sql_part .= "title like '%".$search."%' && ";
    }
        
    $sql = "select * from $page where ".$sql_part." id is not null order by $sort $sc";
    //echo "$sql";
    $result = mysqli_query($db, $sql." limit $fth, $amth");
    $num_result = mysqli_num_rows($result);
    $num_result2 = mysqli_num_rows(mysqli_query($db, $sql));
    $visible_areas = array('title','value','date','image','m_image','sh','status','sort','price','adm_alt');
    $editable_areas = array('title','value','price');
    echo "
    <div>
      <button onClick=\"list_rows('$page','$level','id','desc','$fth','add','0')\" class='form_button'>Добавить запись</button>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type='text' id='search' placeholder='Поиск'>
      <button onClick=\"list_rows('$page','$level','title','asc','0','','')\" class='form_button'>Найти</button>
    </div>
    <div class='page_list'"; if ($sort_exists) echo " id='sortable'"; echo ">
      <div class='row row_header'>
        <div class='table_header'>&nbsp;</div>
        <div class='table_header'>
          <a onClick=\"list_rows('$page','$level','".$id."','$sc_ch','$fth','$search','','')\" style='cursor:pointer;'>ID &nbsp;";
          if ($sort=="$id") echo "$sc_sim";
        echo "</a>
        </div>";
      foreach ($columns_names as $f){
        if (!in_array($f, $visible_areas)) continue;
        echo "
        <div class='table_header'>
          <a onClick=\"list_rows('$page','$level','".$f."','$sc_ch','$fth','','')\" style='cursor:pointer;'>".$fields_values[$f]." &nbsp;";
          if ($sort==$f) echo "$sc_sim";
        echo "</a>
        </div>";
      }
      echo "
        <div class='table_header'>Удалить</div>
      </div>";
    $cnt = 0;
    while ($row = mysqli_fetch_array($result)){
      $image = '/images/interface/blank.gif';
      $cnt++;
      echo "
      <div class='row row_content' id='row_".$row['id']."'>
        <div class='table_content'>";
        if (isset($row['no_edit']) && $row['no_edit']){}
        else{
          echo "
          <a href='table.php?page=$page&fth=$fth&id=".$row['id']."'>
            <i class='fa fa-pencil' aria-hidden='true'></i>
          </a>";
        }
        echo "
        </div>
        <div class='table_content'>".$row['id']."</div>";
      $columns = mysqli_query($db, "show columns from $page"); 
      while ($c = mysqli_fetch_row($columns)){
        if (!in_array($c[0], $visible_areas)) continue; // Пропстить поля, которые не разрешены к показу
        // Формируем превью записи
        $preview = strip_tags($row[$c[0]]);
        $slimit = 150;
        if (strlen($preview)>$slimit){
          $preview = substr($preview,0,$slimit);
          $prev_ar = explode(' ',$preview);
          unset($prev_ar[count($prev_ar)-1]);
          $preview = implode(' ',$prev_ar).'...';
        }
        echo "
        <div class='table_content'>";
        if ($c[0]=='image'){
          if ($page=='gallery_albums'){
            echo "
            <img src='/images/adm_menu/gallery.gif' style='max-width:80px; max-height:80px; cursor:pointer;' onClick=\"$.fancybox.open({type:'iframe', href:'gallery/index.php?id=".$row['id']."', width:1200})\">";
          }
          else{
            $image = $row['image'] ? "/images/$page/".$row['image'] : 'images/noimage.png';
            echo "
            <img src='$image' style='max-width:80px; max-height:80px;'>";
          }
        }
        else if ($c[0]=='m_image'){
          $image = $row['m_image'] ? "/images/$page/".$row['id']."/small/".$row['m_image'] : 'images/noimage.png';
          echo "
          <img id='m_image_".$row['id']."' src='$image' style='max-width:80px; max-height:80px; cursor:pointer;' onClick=\"$.fancybox.open({type:'iframe', href:'upload_image.php?page=$page&id=".$row['id']."'})\">";
        }
        else if ($c[0]=='title' && $levels = mysqli_num_rows(mysqli_query($db, "select * from $page where level='".$row['id']."'"))){
          echo "
          <b><a href='table.php?page=$page&search=$search&level=".$row['id']."'>".stripslashes($row['title'])." ($levels)</a></b>";
        }
        else if (in_array($c[0], $editable_areas)){ 
          $rows = mb_strlen($row[$c[0]],'utf8')>30 ? 3 : 2;
          if ($c[1]=='int(11)'){
            echo "
            <input type='text' id='".$c[0]."_".$row['id']."' onBlur=\"save_changes('".$c[0]."','$page','".$row['id']."')\" onKeyUp=\"if(event.keyCode=='13'){save_changes('".$c[0]."','$page','".$row['id']."')}\" style='width:50px;' value='".$row[$c[0]]."'";
            if ($row['no_edit']) echo " readonly";
            else echo " class='editable'";
            echo ">";
          }
          else{
            echo "
            <textarea id='".$c[0]."_".$row['id']."' rows='$rows' onBlur=\"save_changes('".$c[0]."','$page','".$row['id']."')\" onKeyUp=\"if(event.keyCode=='13'){save_changes('".$c[0]."','$page','".$row['id']."')}\"";
            if (isset($row['no_edit']) && $row['no_edit']) echo " readonly";
            else echo " class='editable'";
            echo ">".$row[$c[0]]."</textarea>";
          }
        }
        /*
        else if ($c[0]=='date'){
          echo "
          <input type='date' id='date_".$row['id']."' onBlur=\"save_changes('date','$page','".$row['id']."')\" value='".$row[$c[0]]."'"; if ($row['no_edit']){ echo " readonly";} echo ">";
        }
        */
        else if ($c[0]=='date'){
          echo "
          <input type='hidden' id='date_".$row['id']."' value='".$row[$c[0]]."'>
          <label id='date_".$row['id']."_cal'>
            <img src='images/datepicker.gif' style='cursor:pointer;'>
            <input type='text' value='".implode('.',array_reverse(explode('-',$row[$c[0]])))."' style='width:100px;'>
          </label>
          <script type='text/javascript'>
            $(function(){
              $('#date_".$row['id']."_cal').find('input').datepicker({
                altField:'#date_".$row['id']."',
                altFormat:'yy-mm-dd',
                dateFormat:'dd.mm.yy',
                constraintInput:true,
                changeMonth:true,
                changeYear:true,
                onSelect:function(){
                  save_changes('date','$page','".$row['id']."');
                }
              });
              $('#date_".$row['id']."_cal').find('input').mask('99.99.9999');
            });
          </script>";
        }
        else if ($c[0]=='sort'){
          echo "
          <div style='cursor:move; text-align:center;'>".$row[$c[0]]."</div>";
        }
        else if (strstr($c[0],'sh')){
          echo "
          <label>
            <div class='switch'>
              <input type='checkbox' id='".$c[0]."_".$row['id']."' value='1'"; if ($row[$c[0]]=='1'){ echo " checked"; } echo ">
              <div class='switch_btn'></div>
              <div class='switch_bg'></div>
            </div>
          </label>
          <script type='text/javascript'>
            $(function(){
              $('#".$c[0]."_".$row['id']."').change(function(){ 
                if($('#".$c[0]."_".$row['id']."').is(':checked')){
                  save_changes('".$c[0]."','$page','".$row['id']."','1');
                }
                else{ 
                  save_changes('".$c[0]."','$page','".$row['id']."','0');
                } 
              });
            });
          </script>";
          /*
          echo "
          <div class='switch2'>
            <div"; if ($row[$c[0]]=='1'){ echo " class='act'";} echo " onClick=\"save_changes('".$c[0]."','$page','".$row['id']."','1')\">Да</div>
            <div"; if ($row[$c[0]]=='0'){ echo " class='act'";} echo " onClick=\"save_changes('".$c[0]."','$page','".$row['id']."','0')\">Нет</div>
          </div>";
          echo "
          <select id='".$c[0]."_".$row['id']."' size='2'>
            <option value='1'"; if ($row[$c[0]]=='1'){ echo " selected";} echo " onClick=\"save_changes('".$c[0]."','$page','".$row['id']."')\">Да</option>
            <option value='0'"; if ($row[$c[0]]=='0'){ echo " selected";} echo " onClick=\"save_changes('".$c[0]."','$page','".$row['id']."')\">Нет</option>
          </select>";
          */
        }
        else if ($c[0]=='adm_alt'){
          echo "
          <div>".nl2br($row[$c[0]])."</div>";
        }
        else{
          echo "
          $preview";
        }
        echo "
        </div>";
      }
      if (isset($row['no_delete']) && $row['no_delete']){
        echo "
        <div class='table_content'>&nbsp;</div>";
      }
      else{
        echo "
        <div class='table_content'>
          <a onClick=\"if(confirm('Вы действительно хотите удалить эту запись?')){ list_rows('$page','$level','','','$fth','delete','".$row['id']."')}\">
            <i class='fa fa-trash' aria-hidden='true'></i>
          </a>
        </div>";
      }
      echo "
      </div>";
    }
    echo "
    </div>";
    echo "<br>
    <button onClick=\"list_rows('$page','$level','id','asc','$fth','add','0')\" class='form_button'>Добавить запись</button>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Страница: ";
    $pages = ceil($num_result2/$amth)+1;
    for ($p=1; $p<$pages; $p++){
      $mn = $p*$amth-$amth;
      if ($p==$fth/$amth+1) echo "<span class='paginator_span'><b>$p</b></span>";
      else echo "<span class='paginator_span'><a onClick=\"list_rows('$page','$level','$sort','','$mn','','')\" style='cursor:pointer;'>$p</a></span>";
    }
    echo "
    <script type='text/javascript'>
      $(function(){
        $('#sortable').sortable({
          items:'.row_content',
          cursor:'move',
          update:function(event,ui){
            sort = $(this).sortable('serialize');
            list_rows('$page','$level','sort','asc','$fth','sort',$(this).sortable('serialize'))
          }
        });
      });
    </script>";
  }
  else{
    echo "Не выбрана таблица.";
  }
?>