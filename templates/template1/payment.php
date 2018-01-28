<main>
  <section class="full_screen">
    <div class="page_title">
      <h1>Выберите способ оплаты</h1>
    </div>
    <div class="page_body">
    <? if(isset($_SESSION['total_price'])){ ?>
      <div class="payment_details">
        <p>Номер заказа: <b><?=$_SESSION['transaction_id']?></b></p>
        <p>Сумма к оплате: <b><?=$_SESSION['total_price']?> рублей</b></p>
      </div>
      <div class="payment_methods">
        <h2>Выберите способ оплаты</h2>
        <script type='text/javascript' src='https://paymaster.ru/ru-RU/widget/Basic/1?LMI_MERCHANT_ID=77a79cc1-5303-4a74-95c4-c9443d513e79&LMI_PAYMENT_AMOUNT=<?=$_SESSION['total_price']?>&LMI_PAYMENT_DESC=Выписка%20из%20Росреестра&LMI_CURRENCY=RUB&LMI_PAYMENT_NO=<?=$_SESSION['transaction_id']?>&LMI_PAYER_EMAIL=<?=$_SESSION['mail']?>'></script>
        <br /><br /><br /><br /><br /><br />
      </div>
      <script type="text/javascript">
        yaCounter46713153.reachGoal('payment'); return true;
      </script>
    <? }else{ ?>
      Ошибка
    <? } ?>
    </div>
  </section>
</main>