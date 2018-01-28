<main>
  <section class="full_screen">
    <div class="page_title">
      <h1>Профиль</h1><br /><br />
      <form action="/profile" method="post">
      <div class="profile_table">
      <?
        $profile_menu = $DB->getData("select * from profile where sh='1'&&groups='1' order by sort");
        foreach ($profile_menu as $pm){
      ?>
        <div class="profile_row">
          <div>
            <?=$pm['title']?>
          </div>
          <div>
            <span>
              <? if ($pm['code']=="password"){ ?>
              <strong>********</strong>
              <input type="<?=$pm['type']?>" id="<?=$pm['code']?>" name="profile[<?=$pm['code']?>]" value="" style="width:100%; max-width:<?=$pm['size']?>px;" class="profile_data">
              <? }else{ ?>
              <strong><?=$user_data[$pm['code']]?></strong>
              <input type="<?=$pm['type']?>" id="<?=$pm['code']?>" name="profile[<?=$pm['code']?>]" value="<?=$user_data[$pm['code']]?>" style="width:100%; max-width:<?=$pm['size']?>px;" class="profile_data" <?if($pm['mask']){?>data-mask="<?=$pm['mask']?>"<?}?>>
              <? } ?>
            </span>
            <span class="edit_data">Сменить</span>
            <span class="loading" style="display:none;"></span>
          </div>
        </div>
      <?}?>
      </div><br />
      <div class="profile_table">
      <?
        $profile_menu = $DB->getData("select * from profile where sh='1'&&groups='2' order by sort");
        foreach ($profile_menu as $pm){
      ?>
      <div class="profile_row2">
        <div class="item_point checkbox_block" style='width:20px; height:auto;'>
          <input type="hidden" name="profile[<?=$pm['code']?>]" value="0">
          <label>
            <input type="checkbox" name="profile[<?=$pm['code']?>]" id="<?=$pm['code']?>" value="1" class='profile_checkbox' <? if($user_data[$pm['code']]){ ?>checked<? } ?>>
            <div class='checkbox_img'></div>
          </label>
        </div>
        <div>
          <label for="<?=$pm['code']?>">
            <?=$pm['title']?>
          </label>
        </div>
      </div>
      <?}?>
      </div><br />
      <!--<input type="submit" value="Сохранить" class="primary_button">-->
      </form>
    </div>
  </section>
</main>