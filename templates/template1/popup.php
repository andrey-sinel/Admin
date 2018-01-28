  <div id="address_popup">
    <div class="popup_block">
      <div class="close_popap_btn"></div>
    <form action="order.php" id="address_form" method="post">
      <input type="hidden" name="guid" value="<?=$new_guid?>">
      <input type="hidden" name="address" id="address_value" value="">
      <input type="hidden" name="number" id="cadnomer_value" value="">
      <input type="hidden" name="region" id="region_value" value="">
      <div class="loading"><br />
        <img src="/img/loading.gif" style=""><br>
        <p>Подождите. Идёт поиск объектов недвижимости.</p>
      </div>
      <div class="step_block step_1">
        <div id="address_list">Загрузка...</div>
        <div class="button_group">
          <div class="default_button close_btn desktop_only" data-step="1">Вернуться</div>
        </div>
      </div>
      <div class="step_block step_2">
        <div class="cadnomer"></div>
        <h1>Какие данные вы хотите получить из Росреестра?</h1>
        <? foreach($documents_list as $doc_id=>$doc_name){ ?>
        <div class="item_point">
          <div>
            <label>
              <input type="checkbox" name="documents[<?=$doc_id ?>]" value="<?=$doc_name[1] ?>" checked class='item_checkbox'>
              <div class='checkbox_img'></div>
            </label>
          </div>
          <div><?=$doc_name[0] ?></div>
          <div><?=$doc_name[1] ?> ₽</div>
        </div>
        <? } ?>
        <div class="button_group">
          <div class="default_button prev_step desktop_only" data-step="2">Вернуться</div>
          <div class="primary_button next_step" data-step="2">Продолжить</div>
        </div>
      </div>
      <div class="step_block step_3">
        <div class="cadnomer"></div>
        <h1>Куда отпраивть документы?</h1>
        <div>
          <p>Готовые документы будут отправлены Вам по электронной почте на указанный ниже адрес.</p>
          <p>На указанный номер телефона Вы получите SMS-уведомление о готовности выписки.</p>
        </div>
        <div>
          <label>
            <div class="input_alt desktop_only">Ваш e-mail</div>
            <input type="text" name="mail" placeholder="Ваш e-mail" value="<?=$user_data['mail']?>" class="step_3_reqired">
          </label>
        </div>
        <div>
          <label>
            <div class="input_alt desktop_only">Ваш телефон</div>
            <input type="text" name="phone" id="phone" placeholder="Ваш телефон" value="<?=$user_data['phone']?>" data-mask="+7 (000) 000-00-00">
          </label>
        </div>
        <div class="button_group">
          <div class="default_button prev_step desktop_only" data-step="3">Вернуться</div>
          <div class="primary_button finish_btn" data-step="3">Продолжить</div>
        </div>
      </div>
    </form>
    </div>
  </div>
  <script type="text/javascript">
    $("#phone").mask("+7 (999) 999-99-99");
  </script>