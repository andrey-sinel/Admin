  <div class="breadcrumbs">
  <? foreach ($path as $point){ ?>
    <?=$point?>
  <? } ?>
  </div>
  <h1><?=$header?></h1>
  <div id='list_rows'>Загрузка...</div>
  <script>
    list_rows('<?=$page?>','<?=$level?>','sort','asc','0','','');
  </script>