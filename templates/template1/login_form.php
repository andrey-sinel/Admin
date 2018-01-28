<div id="login_popup">
  <div class="popup_block">
    <div class="close_popap_btn"></div>
    <div class="column_half blue_block">
      <h1>Вход</h1>
        <div>
          <? if ($login_mess){ ?>
          <span class="error_message">
            <?=$login_mess?>
          </span>
          <? } ?>
        </div>
        <!--<div class="desktop_only">
          <p><b>Войти через</b></p>
          <script src='//ulogin.ru/js/ulogin.js'></script>
          <div data-ulogin='display=panel;theme=classic;fields=first_name,last_name,email,phone,city;providers=vkontakte,odnoklassniki,mailru,facebook,yandex,google;redirect_uri=<?='/'.$url?>;mobilebuttons=0;'></div><br />
        </div>-->
        <br />
        <div>
        <form action='/documents' method='post' id="login_form">
          <input type="hidden" name="action" value="login">
          <div class='enter_block'>
            <div>Логин: </div>
            <input type='text' name='login' size='23' value='<?=$login?>' class="enter_input"><br>
            <span id='login_mess'></span>
            <div>
              <span>Пароль:</span>
              <span><a href="/remind">Забыли пароль</a></span>
            </div>
            <input type='password' name='pass' id="password" size='23' value='' class="enter_input">
            <span id='password_mess'></span>
          </div>
          <div class='enter_buttons'>
            <input type="button" class='primary_button enter_button' value="ВОЙТИ">
          </div>
        </form>
      </div>
    </div>
    <div class="column_half">
      <h1>Регистрация</h1>
        <div>
          <? if ($register_mess){ ?>
          <span class="error_message">
            <?=$register_mess?>
          </span>
          <? } ?>
        </div><br />
      <h3>После регистрации Личного кабинета у вас появятся возможности:</h3>
      <div class="register_list">
        <ul>
          <li>Получать смс на телефон при готовности выписки</li>
          <li>Иметь доступ к архиву всех заказанных вами выписок</li>
          <li>Пользоваться пакетными предложениями</li>
        </ul>
      </div><br />
      <form action='/<?=$url?>' method='post' id="register_form">
        <input type="hidden" name="action" value="register">
        <div class='enter_block'>
          <div>E-mail: </div>
          <input type='text' name='login' size='23' value='' class="register_input" style='margin:0;'><br>
          <span id='login_mess'></span>
        </div
        <div class='enter_buttons'>
          <input type="button" class='primary_button register_button' value="РЕГИСТРАЦИЯ">
        </div>
      </form>
    </div>
  </div>
</div>