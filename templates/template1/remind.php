<main>
  <section class="full_screen">
    <div>
      <? if ($error_mess){ ?>
      <span class="error_message">
        <?=$error_mess?>
      </span>
      <? } ?>
    </div>
    <div class="column_half">
      <h1>Восстановление пароля</h1>
      <form action='/<?=$url?>' method='post' id="login_form">
        <input type="hidden" name="remind" value="1">
        <div class='enter_block'>
          <div>Введите адрес e-mail, указанный при регистрации</div>
          <input type='text' name='mail' value='' class="enter_input"><br>
        </div>
        <div class='enter_buttons'>
          <div class='primary_button enter_button'>Восстновить</div>
        </div>
      </form>
    </div>
  </section>
</main>