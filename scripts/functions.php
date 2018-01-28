<?php
  function removeDir($path) {
    if ($objs = glob($path."/*")) {
      foreach($objs as $obj) {
        is_dir($obj) ? removeDir($obj) : unlink($obj);
      }
    }
    rmdir($path);
  }
  function contentMenu($lev,$mar,$page){
    global $db, $content_val, $id;
    $margins = array();
    $cnt++;
    $content_groups = mysqli_query($db, "select * from $page where level='$lev' order by title"); 
    while ($cg = mysqli_fetch_array($content_groups)){ 
      echo "<option value='".$cg['url']."'";
      if ($content_val==$cg['url']){ echo " selected";}
      echo ">";
      for ($m=0; $m<$mar; $m++){ echo "----";}
      echo $cg['title']."</option>";
      $mar2 = $mar+1;
      contentMenu($cg['id'],$mar2,$page);
      $cnt++;
      if ($cnt>200) break; // Защита от бесконечных циклов
    }
  }
  function levelMenu($lev,$mar,$page){
    global $db, $level, $id;
    $margins = array();
    $cnt++;
    $levels_groups = mysqli_query($db, "select * from $page where level='$lev' order by sort"); 
    while ($lg = mysqli_fetch_array($levels_groups)){ 
      echo "<option value='".$lg['id']."'";
      if ($level==$lg['id']){ echo " selected";}
      if ($id==$lg['id']){ echo " disabled";}
      echo ">";
      for ($m=0; $m<$mar; $m++){ echo "----";}
      echo $lg['title']."</option>";
      $mar2 = $mar+1;
      levelMenu($lg['id'],$mar2,$page);
      $cnt++;
      if ($cnt>200) break; // Защита от бесконечных циклов
    }
  }
  function levelTitle($lev){
    global $db;
    global $page;
    global $level_arr;
    $parent = mysqli_fetch_row(mysqli_query($db, "select id, title, level from $page where id='$lev'"));
    $level_arr[] = " - <a href='table.php?page=$page&level=".$parent[0]."'>".$parent[1]."</a>";
    if ($parent[2]) levelTitle($parent[2]);
    return $level_arr;
  }
  function levelURL($id){
    global $db;
    global $page;
    global $url_arr;
    $parent = mysqli_fetch_row(mysqli_query($db, "select id, url, level from $page where id='$id'"));
    $url_arr[] = "/".$parent[1];
    if ($parent[2]) levelURL($parent[2]);
    return $url_arr;
  }
  function errorLog($err){
    //echo $err;
  }
?>