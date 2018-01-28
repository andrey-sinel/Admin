<main>
  <? if(isset($_SESSION['payment']) && $_SESSION['payment']){ ?>
  <div class="payment_result">
    <div></div>
    <h2>
      <i class="fa fa-check" aria-hidden="true"></i>
      Поизведён платёж!
    </h2>
    <p>Вы оплатили получение выписки из Росреестра.</p>
    <p>Когда Ваша выписка будет готова, мы пришлём Вам уведомление.</p>
    <a href="/documents/">
      <div class="primary_button">Перейти к моим выпискам</div>
    </a>
    <script type="text/javascript">
      yaCounter46713153.reachGoal('payok'); return true;
    </script>
  </div>
  <? }else{ ?>
  <div class="payment_result">
    <h2>Ошибка! Некорректый запрос</h2>
  </div>
  <? } ?>
</main>