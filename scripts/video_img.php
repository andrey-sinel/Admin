<?php
  $document_root = $_SERVER['DOCUMENT_ROOT'];
  require('../config/db_connect.php');
  $result = mysqli_query($db, "select * from ru_video");
  while ($row = mysqli_fetch_array($result)){
    $title = $row['title'];
    $large_image = str_replace('mod_','','/images/ru_video/'.$row['id'].'/large/'.$row['m_image']);
    $large_image2 = '/images/ru_video/'.$row['id'].'/large/mod_'.str_replace('mod_','',$row['m_image']);
    $small_image2 = '/images/ru_video/'.$row['id'].'/small/mod_'.str_replace('mod_','',$row['m_image']);
    echo "
    <b>[".$row['id']."] = $title</b><br>$image<br>$image2<br><img src='$large_image' height='200'>";

    $icon = '/images/interface/play.png';
    $scr_img = ImageCreateFromJpeg($document_root.$large_image);
    $size = GetImageSize($document_root.$large_image); 
    $scr_width = $size[0];
    $scr_height = $size[1];
    $icon_img = ImageCreateFromPng($document_root.$icon);
    $size = GetImageSize($document_root.$icon); 
    $icon_width = $size[0]; 
    $icon_height = $size[1]; 
    $left_margin = $scr_width/2-$icon_width/2;
    $top_margin = $scr_height/2-$icon_height/2;

    $large_img = ImageCreateTrueColor($scr_width, $scr_height); 
    ImageCopyResampled($large_img, $scr_img, 0, 0, 0, 0, $scr_width, $scr_height, $scr_width, $scr_height); 
    ImageCopyResampled($large_img, $icon_img, $left_margin, $top_margin, 0, 0, $icon_width, $icon_height, $icon_width, $icon_height); 
    ImageJpeg($large_img, $document_root.$large_image2, 100); 
    //ImageDestroy($large_img);

    if ($scr_width>$scr_height){
      $small_width = "120"; 
      $small_height = ($small_width/$scr_width) * $scr_height;
    }
    else{
      $small_height = "120"; 
      $small_width = ($small_height/$scr_height) * $scr_width;
    }

    $small_img = ImageCreateTrueColor($small_width, $small_height); 
    ImageCopyResampled($small_img, $large_img, 0, 0, 0, 0, $small_width, $small_height, $scr_width, $scr_height); 
    ImageJpeg($small_img, $document_root.$small_image2, 100); 
    ImageDestroy($small_img);

    echo "<img src='$large_image2' height='200'><br><br>";
    mysqli_query($db, "update ru_video set m_image='mod_".str_replace('mod_','',$row['m_image'])."' where id='".$row['id']."'");
  }
?>