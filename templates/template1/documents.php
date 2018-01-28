<main>
  <section class="full_screen">
    <form action="/package.php" method="post" id="documents_form">
      <div class="page_title">
        <h1 class="width_30">Мои выписки</h1>
        <div class="width_20 align_center">
          <a href='/vypiska' class="primary_button">Новая выписка</a>
        </div>
        <div class="width_40">
          <div>С выделенными</div>
          <div>
            <select name="action">
              <option value="pay">Оплатить</option>
              <option value="delete">Удалить</option>
            </select>
          </div>
          <input type="submit" class="primary_button" value="Выполнить">
        </div>
      </div>
      <div class="transactions_table">
        <div class="transaction_header">
          <div class="item_point checkbox_block">
            <? if ($doc['status']==0){ ?>
            <label>
              <input type="checkbox" id="select_all" class='item_checkbox'>
              <div class='checkbox_img'></div>
            </label>
            <? } ?>
          </div>
          <div style="width:60px;">Номер док-та</div>
          <div>Дата подачи</div>
          <div style="width:200px;">
            <select onChange="" class="doctype_list">
              <option value="" style="width:100px">Тип выписки</option>
            <? foreach($documents_list as $doc_type){ ?>
              <option value="<?=$doc_type[2]?>" 
              <? if($_SESSION['doctype']==$doc_type[2]) echo " selected"?>>
                <?=str_replace('Выписка','',mb_substr($doc_type[0],0,30))?>...
              </option>
            <? } ?>
            </select>
          </div>
          <div>Кадастровый номер</div>
          <div>Адрес объекта</div>
          <div>Статус</div>
          <div>Цена</div>
        </div>
      <?php
        $documents = $DB->getData("select * from documents where user_id='".$user_data['id']."' && code like '%".$_SESSION['doctype']."%' order by id desc", $pnum);
        foreach ($documents['data'] as $doc){
      ?>
        <div class="transaction_block">
          <div class="item_point checkbox_block">
            <? if ($doc['status']==0){ ?>
            <label>
              <input type="checkbox" name="documents[<?=$doc['id']?>]" id="<?=$doc['id']?>" value="<?=$documents_list[$doc['code']][1]?>" class='item_checkbox'>
              <div class='checkbox_img'></div>
            </label>
            <? } ?>
          </div>
          <div>
            <span class="mobile_only">Номер документа: </span>
            <?=$doc['id']?>
          </div>
          <div>
            <span class="mobile_only">Дата: </span>
            <?=formatDate($doc['date'])?>
          </div>
          <div><?=$documents_list[$doc['code']][0]?></div>
          <div><?=$doc['number']?></div>
          <div>
          <? if($doc['address']){ ?>
            <?=$doc['address']?>
          <? }else{ ?>
            <img src="/img/load.gif">
          <? } ?>
          </div>
          <div>
            <span class="mobile_only">Статус: </span>
            <?=$documents_statuses[$doc['status']]?>
            <? if ($doc['status']==0){ ?>
              <!--<p><a href='/order.php?guid=<?=$doc['guid']?>'>Оплатить</a></p>-->
              <p><a onClick="$('#<?=$doc['id']?>').prop('checked', true); $('#documents_form').submit();" style="cursor:pointer;">Оплатить</a></p>
            <? }else if ($doc['status']==3){ ?>
              <p><a href='/getdoc.php?guid=<?=$doc['guid']?>&format=pdf'>Скачать PDF</a><br>
            <? } ?>
          </div>
          <div>
            <span class="mobile_only">Цена: </span>
            <?=$documents_list[$doc['code']][1]?><br /><br />
          </div>
        </div>
      <? } ?>
      </div>
      <? if (!$documents['count']){ ?>
      <div>
        <p>У вас нет заказанных выписок.</p>
      </div>
      <? } ?>
    </form>
    <?
      if ($documents['count']>PAGE_LIMIT){
        $pages_num = $documents['count']/PAGE_LIMIT+1;
    ?>
    <div class="pagination">
    <? for ($p=1; $p<$pages_num; $p++){ ?>
      <? if ($p==$pnum){ ?>
      <div class="current_page">
        <?=$p?>
      </div>
      <? }else{ ?>
      <div onClick="location.href='/documents/<?=$p?>'">
        <a href="/documents/<?=$p?>"><?=$p?></a>
      </div>
      <? } ?>
    <? } ?>
    </div>
    <? } ?>
  </section>
  <br /><br />
</main>