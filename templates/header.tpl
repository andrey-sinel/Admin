<HTML>
<HEAD>
  <title>Администрирование</title>
  <meta http-equiv="Cache-Control" content="no-cache">
  <link rel="stylesheet" href="../plugins/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
  <link rel="stylesheet" href="css/jquery-ui.css">
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui.js"></script>
  <script type="text/javascript" src="js/jquery.ui.datepicker-ru.js"></script>
  <script type="text/javascript" src="js/jquery.maskedinput.min.js"></script>
  <script type="text/javascript" src="../plugins/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
  <script type="text/javascript" src="../plugins/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $(".image").fancybox();
      $(".popup").fancybox();
    });
  </script>
  <script type="text/javascript" src="js/main.js"></script>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
</HEAD>
<BODY>
<!-- TinyMCE -->
<script type='text/javascript' src='../plugins/tinymce/tiny_mce.js'></script>
<script type='text/javascript'>
  tinyMCE.init({
    mode : "specific_textareas",
    editor_selector : "tinymce_class",
    theme : "advanced",
    oninit : "postInitWork",
    plugins : "safari,pagebreak,style,layer,table,save,advhr,jbimages,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups",
    theme_advanced_buttons1 : "formatselect,fontselect,fontsizeselect,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,",
    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,jbimages,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
    theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    convert_urls : false,
    setup : function(ed) {
      ed.onDblClick.add(function(ed, e){
        if (e.target.className == 'component'){
          $.fancybox.open({type:'iframe', href:'comp_settings.php?comp='+e.target.src+'&id='+e.target.id});
        }
      });
    }
  });
</script>
<!-- /TinyMCE -->

<main>
  <div class="main_menu">
  <? foreach ($main_menu as $point){ ?>
    <? if ($point['link']){ ?>
    <div class="sections_title" id="show_<?=$point['id']?>_sections" onClick="location.href='<?=$point['link']?>'">
      <h2><a href="<?=$point['link']?>"><?=$point['title']?></a></h2>
    <? }else{ ?>
    <div class="sections_title" id="show_<?=$point['id']?>_sections">
      <h2>
        <i class="fa <?=$point['icon']?> mobile_only" aria-hidden="true"></i>&nbsp;
        <?=$point['title']?>
      </h2>
    <? } ?>
    <? foreach ($main_menu[$point['id']]['child'] as $sub_point){ ?>
      <ul class="sections_items sections_<?=$point['id']?>">
        <li onClick="location.href='<?=$sub_point['link']?>'">
          <i class="fa fa-angle-right" aria-hidden="true"></i>&nbsp;
          <a href="<?=$sub_point['link']?>"><?=$sub_point['title']?></a>
        </li>
      </ul>
    <? } ?>
    </div>
  <? } ?>
  </div>
  <div class="content">