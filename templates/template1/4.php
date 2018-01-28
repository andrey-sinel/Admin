<script type="text/javascript">
//alert(document.body.clientWidth);
</script>
<section class="homepage">
  <div class="home_block">
    <div class="home_doc_image">
      <a href="/"><img src="/img/logo.png" alt="" /></a>
    </div>
    <div>
      <div class="top_block">
        С 1 января 2017 года выписка из ЕГРН, кадастровый паспорт, кадастровая<br>
        выписка заменены одним документом - выпиской из ЕГРН
      </div>
      <h1>Заполните форму и получите электронную выписку ЕГРН по объекту недвижимости</h1>
      <div class="address_block">
        <div class="address_button action_button" data-form="1">
          <div>Адрес</div><div>объекта</div>
        </div><div class="address_button" data-form="2">
          <div>Кадастровый</div><div>номер</div>
        </div>
        <div class="address_data address_data_1">
          <input type="text" name="region" id="region" placeholder="Регион">
          <input type="text" name="gorod" id="gorod" placeholder="Населённый пункт">
          <input type="text" name="ulica" id="ulica" placeholder="Улица">
          <input type="text" name="dom" id="dom" placeholder="Дом">
          <input type="text" name="korpus" id="korpus" placeholder="Корпус">
          <input type="text" name="kvartira" id="kvartira" placeholder="Квартира"><br /><br />
          <input type="hidden" class="address_input" value="">
          <div class="open_popup1 start_button" onClick="yaCounter46713153.reachGoal('zakaz3'); return true;">
            <img src="/img/next_icon.png">
            <span>Поиск объекта</span>
          </div>&nbsp;
        </div>
        <div class="address_data address_data_2" style="display:none;">
          <input type="text" name="number" id="number" placeholder="Кадастровый номер" data-mask="00:00:0000000:0000"><br /><br />
          <input type="hidden" class="address_input" value="">
          <div class="open_popup2 start_button" onClick="yaCounter46713153.reachGoal('zakaz3'); return true;">
            <img src="/img/next_icon.png">
            <span>Поиск объекта</span>
          </div>&nbsp;
        </div>
      </div>
    </div>
  </div>
</section>
<script type="text/javascript">
function join(arr /*, separator */) {
  var separator = arguments.length > 1 ? arguments[1] : ", ";
  return arr.filter(function(n){return n}).join(separator);
}

function formatCity(suggestion) {
  var address = suggestion.data;
  if (address.city_with_type === address.region_with_type) {
      return address.settlement_with_type || "";
    } else {
      return join([
        address.city_with_type,
        address.settlement_with_type]);
    }
  }

  var token = "9581b63dba9f34a99bbc23d5bf4807c2c80844ea",
    type  = "ADDRESS",
    $region = $("#region"),
    $city   = $("#gorod"),
    $street = $("#ulica"),
    $house  = $("#dom");

  // регион и район
  $region.suggestions({
    token: token,
    type: type,
    hint: false,
    bounds: "region-area"
  });
  
  // город и населенный пункт
  $city.suggestions({
    token: token,
    type: type,
    hint: false,
    bounds: "city-settlement",
    constraints: $region,
    formatSelected: formatCity
  });
  
  // улица
  $street.suggestions({
    token: token,
    type: type,
    hint: false,
    bounds: "street",
    constraints: $city
  });
</script>

<div class="address_form">
  <div>
    <p>
      Заполняете форму
      <i class="fa fa-angle-right" aria-hidden="true"></i>
      Оплачиваете выписку
      <i class="fa fa-angle-right" aria-hidden="true"></i>
      Получаете на почту
    </p>
  </div>
</div>
<section>
  <script type="text/javascript">
    $(document).ready(function(){
      $("#address").suggestions({
        token: "9581b63dba9f34a99bbc23d5bf4807c2c80844ea",
        type: "ADDRESS",
        count: 5,
        /* Вызывается, когда пользователь выбирает одну из подсказок */
        onSelect: function(suggestion) {
          $('#address').val(suggestion.value);
        }
      });
    });
  </script>
  <div>
    <?=$data['text']?>
  </div>
  <div id="address_popup">
    <div class="popup_block">
      <div class="close_popap_btn"></div>
      <div class="prev_step" data-step="2">Вернуться</div>
    <form action="order.php" id="address_form" method="post">
      <input type="hidden" name="address" id="address_value" value="">
      <input type="hidden" name="number" id="cadnomer_value" value="">
      <input type="hidden" name="region" id="region_value" value="">
      <div class="step_block step_0"><br />
        <h2>Поиск объектов<br>недвижимости</h2>
        <div>
          <input type="text" id="address" class="address_input" placeholder="Введите адрес или кадастровый номер объекта" value=""><br>
          <div class="open_popup" onClick="yaCounter46713153.reachGoal('poisk3'); return true;"></div>
        </div>
      </div>
      <div class="loading"><br />
        <h2>Поиск объектов<br>недвижимости</h2>
        <div id="search_progress">0%</div>
        <div id="scale_progress">
          <div></div>
        </div>
      </div>
      <div class="step_block step_1">
        <h2>Выберите объект недвижимости</h2>
        <div id="address_list">Загрузка...</div>
      </div>
      <div class="step_block step_2">
        <div class="objinfo">
          <div class="cadnomer"></div>
          <div class="objaddress"></div>
        </div>
        <h2>Какие данные вы хотите получить из Росреестра?</h2>
        <? foreach($documents_list as $doc_id=>$doc_name){ ?>
        <div class="item_point">
          <div>
            <label>
              <input type="checkbox" name="documents[<?=$doc_id ?>]" id="document_<?=$doc_id ?>" value="<?=$doc_name[1] ?>" checked class='item_checkbox' data-price='<?=$doc_name[1]?>'>
              <div class='checkbox_img'></div>
            </label>
          </div>
          <div>
            <label for="document_<?=$doc_id ?>">
              <strong><?=$doc_name[0] ?></strong>
            </label>
            <? if ($doc_name[3]){ ?>
            <p><?=$doc_name[3] ?></p>
            <? } ?>
          </div>
        </div>
        <? } ?>
        <div class="total_price">
          Цена со скидкой: <span id="total_price">0</span> рублей
        </div>
        <div class="button_group">
          <div class="next_step" data-step="2" onClick="yaCounter46713153.reachGoal('tipi'); return true;"></div>
        </div>
      </div>
      <div class="step_block step_3">
        <div class="objinfo">
          <div class="cadnomer"></div>
          <div class="objaddress"></div>
        </div>
        <h2>Как вас уведомить о готовности выписки?</h2>
        <div>
          <p>Когда ваша выписка будет готова вы получите ссылку на указанный адрес.</p>
        </div>
        <div>
          <label>
            <input type="text" name="mail" placeholder="Ваш e-mail" value="<?=$user_data['mail']?>" class="step_3_reqired">
          </label>
        </div><br /><br />
        <div>
          <p>На указанный номер телефона Вы получите SMS-уведомление о готовности выписки.</p>
        </div>
        <div>
          <label>
            <input type="text" name="phone" id="phone" placeholder="Ваш телефон" value="<?=$user_data['phone']?>" data-mask="+7 (000) 000-00-00">
          </label>
        </div><br /><br />
        <div class="button_group">
          <div class="finish_btn" data-step="3" onClick="yaCounter46713153.reachGoal('mailtel'); return true;"></div>
        </div>
      </div>
    </form>
    </div>
  </div>
</section>
<section class="block_on_home1">
  <div class="page_title">
    <h2 class="width_40">Зачем нужна<br>выписка ЕГРН?</h2>
    <div>
      <b>
        ЕГРН - это единый государственный реестр недвижимости,
        создан в соответствии с Федеральным законом "О
        государственной регистрации недвижимости", вступившим в
        силу с 1.01.2017, сюда входят сведения из государственного
        кадастра недвижимости и Единого государственного реестра 
        прав на недвижимое имущество и сделок с ним (ЕРП).
      </b>
    </div>
  </div>
  <div class="page_body">
    <div>
      <p>ЕГРП работал с 1998 года, когда все сделки с недвижимостью по закону стали проходить обязательную регистрацию. В ЕГРП хранились данные о всех объектах недвижимости и сделках с их участием. Ресурс государственный, о истории перехода прав собственности на недвижимое имущество с момента его регистрации. Ресурс с открытым доступом для граждан Российской Федерации, любой нуждающийся может получить здесь выписку из Росреестра о недвижимости и ее собственниках.</p>
      <p>Выписка из ЕГРН представляет собой документ, подтверждающий зарегистрированное право собственности на недвижимость или отсутствие его. Также выписка отображает, наложены ли какие либо ограничения или обременения на объект.</p>
    </div>
  </div>
</section>
<section class="block_on_home2">
  <div class="page_title">
    <h2 class="width_100">Единая выписка<br> ЕГРН включает:</h2>
  </div><br>
  <div class="page_body">
    <div>
      <h3>
        <span>1</span>
        <span>Выписка из ЕГРН об основных<br>характеристиках и<br>зарегистрированныз правах</span>
      </h3>
      <img src="/img/vypiska_egrn1.jpg" alt="" />
    </div>
    <div>
      <h3>
        <span>2</span>
        <span>Выписка из ЕГРН о перехода<br>прав на недвижимое имущество</span>
      </h3>
      <img src="/img/vypiska_egrn2.jpg" alt="" />
    </div>
  </div>
</section>
<section class="block_on_home3">
  <div class="content_block">
    <h2>В каких случаях<br>требуется выписка?</h2>
    <? 
      $block = $DB->getData("select * from blocks where level='1'", '0');
      foreach($block as $param){
    ?>
    <div>
      <div class="title"><?=$param['title']?></div>
      <div class="header"><?=nl2br($param['header'])?></div>
    </div>
    <? } ?>
  </div>
</section>
<section class="block_on_home4">
  <div class="page_body">
    <div>
      <img src="/img/doc_icon.png">
    </div>
    <div>
      Сведения из ЕГРН предоставляются в срок не более 5 рабочих дней со дня подачи запроса в регистрирующий орган. Запрос может быть оформлен на бумажном носителе или в электронной форме. В бумажном виде поставляется в организацию лично или через доверенное лицо. Электронный запрос оформляется через госуслуги или форму на сайте Росреестра.
    </div>
  </div>
</section>
<link rel="stylesheet" href="/templates/<?=TEMPLATE?>/style4.css" type="text/css" />
<script type="text/javascript">
  $(document).ready(function(){
    $('#region').focus();
    $(".address_button").on('click',function(event){
      form = $(this).attr('data-form');
      $('.address_button').removeClass('action_button');
      $(this).addClass('action_button');
      $('.address_data').hide();
      $('.address_data_'+form).show();
    });
    $(".open_popup1").on('click',function(event){
      region = $('#region').val();
      gorod = $('#gorod').val();
      ulica = $('#ulica').val();
      dom = $('#dom').val();
      korpus = $('#korpus').val();
      kvartira = $('#kvartira').val();
      address = region+', '+gorod+', '+ulica+', д. '+dom+', '+korpus+', '+kvartira;
      popupWindows(address);
    });
    $(".open_popup2").on('click',function(event){
      number = $('#number').val();
      popupWindows(number);
    });
  });
</script>