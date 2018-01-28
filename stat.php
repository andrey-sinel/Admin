<?php
  require("includes/head.php");
  require($document_root.'/admin/scripts/functions.php');
  $section = $DB->getData("select title, level, adm_access from adm_menu where link='stat.php'")[0];
  if (!count($section)){
    die("Ошибка обращения к базе. ".mysqli_error($db));
  }
  if ($adm_access!=$section['adm_access'] && $adm_user['id']>1){
    die("Доступ к этому разделу запрещен.");
  }
  $graph_max_height = '100';
  $graph_max_legend = '15';
  
  $date_to = !empty($_REQUEST['date_to']) ? $_REQUEST['date_to'] : date('Y-m-d');
  $date_from = !empty($_REQUEST['date_from']) ? $_REQUEST['date_from'] : date('Y-m-d', strtotime('-1 month'));
  $time_from = strtotime($date_from);
  $time_to = strtotime($date_to);
  $per_length = round($time_to-$time_from);
  
  $users = $DB->getData("select * from users")[0]['count'];
  $users_per = $DB->getData("select * from users where date_reg>'".$date_from."' && date_reg<='".$date_to."'")[0]['count'];
  $users_cust = $DB->getData("select distinct user_id from transactions where status='1' && date>'".$date_from."' && date<='".$date_to."'")[0]['count'];
  
  $docs = $DB->getData("select * from documents")[0]['count'];
  $docs_per = $DB->getData("select * from documents where date>'".$date_from."' && date<='".$date_to."'")[0]['count'];
  $docs_done = $DB->getData("select * from documents where status='3' && date>'".$date_from."' && date<'".$date_to."'")[0]['count'];

  $transactions = $DB->getData("select * from transactions where status='1'");
  $transactions_per = $DB->getData("select * from transactions where status='1' && date>'".$date_from."' && date<='".$date_to."'");

  $payment = 0;
  foreach ($transactions as $tr){
    $payment += $tr['total'];
    $trans_price[] = $tr['total'];
  }
  $payment_per = 0;
  foreach ($transactions_per as $tr){
    $payment_per += $tr['total'];
    $trans_per_price[] = $tr['total'];
    $trans_value[$tr['date']][] = $tr['total'];
    $trans_sum[$tr['date']] = array_sum($trans_value[$tr['date']]);
    $trans_am[$tr['date']] = count($trans_value[$tr['date']]);
  }
  $trans_max_sum = !count($trans_sum) ? max($trans_sum) : 1;
  $trans_max_am = !empty($trans_am) ? max($trans_am) : 1;
  //echo "<pre>"; print_r($trans_price); echo "</pre>";
  //echo "<pre>"; print_r($trans_per_price); echo "</pre>";

  echo "
  <script>
    toggle_sections(".$section['level'].");
  </script>";
  echo "
  <h1>Статистика</h1>
  <div class='stat_buttons'>
    <a href='stat.php?date_from=".date('Y-m-d', strtotime('-1 week'))."&date_to=".date('Y-m-d')."'>Неделя</a>
    <a href='stat.php?date_from=".date('Y-m-d', strtotime('-1 month'))."&date_to=".date('Y-m-d')."'>Месяц</a>
    <a href='stat.php?date_from=".date('Y-m-d', strtotime('-3 months'))."&date_to=".date('Y-m-d')."'>Квартал</a>
  </div><br />
  <form action='stat.php'>
  <div class='stat_form'>
    <div>Период с</div>
    <input type='hidden' name='date_from' id='date_from' value='".$date_from."'>
    <input type='text' id='date_from_cal' value='".implode('.',array_reverse(explode('-',$date_from)))."' style='width:100px;'>
    <script type='text/javascript'>
      $(function(){
        $('#date_from_cal').datepicker({
          altField:'#date_from',
          altFormat:'yy-mm-dd',
          dateFormat:'dd.mm.yy',
          constraintInput:true,
          changeMonth:true,
          changeYear:true
        });
        $('#date_from_cal').mask('99.99.9999');
      });
    </script>
    <div>по</div>
    <input type='hidden' name='date_to' id='date_to' value='".$date_to."'>
    <input type='text' id='date_to_cal' value='".implode('.',array_reverse(explode('-',$date_to)))."' style='width:100px;'>
    <script type='text/javascript'>
      $(function(){
        $('#date_to_cal').datepicker({
          altField:'#date_to',
          altFormat:'yy-mm-dd',
          dateFormat:'dd.mm.yy',
          constraintInput:true,
          changeMonth:true,
          changeYear:true
        });
        $('#date_from_cal').mask('99.99.9999');
      });
    </script>
    <input type='submit' value='Позазать' class='form_button'>
  </div>
  </form>
  <!--<p>Период: ".$per_length." дней</p>-->
  <div class='stat_table'>
    <div class='stat_column'>
      <h2>Пользователи</h2>
      <div class='stat_param_act'>
        <p>Зарегистрировано за выбранный период</p>
        <div class='act_value'>$users_per</div>
      </div>
      <div class='stat_param_all'>
        <p>Зарегистрировано за весь период</p>
        <div class='value'>$users</div>
      </div>
      <div class='stat_param_act'>
        <p>Покупателей за выбранный период</p>
        <div class='act_value'>$users_cust</div>
      </div>
      <div class='stat_param_all'>
        <p>Покупателей за весь период</p>
        <div class='value'>$users_per</div>
      </div>
    </div>
    <div class='stat_column'>
      <h2>Заказы</h2>
      <div class='stat_param_act'>
        <p>Оформлено за выбранный период</p>
        <div class='act_value'>$docs_per</div>
      </div>
      <div class='stat_param_all'>
        <p>Оформлено за весь период</p>
        <div class='value'>$docs</div>
      </div>
      <div class='stat_param_act'>
        <p>Оплачено за выбранный период</p>
        <div class='act_value'>$docs_done</div>
      </div>
      <div class='stat_param_all'>
        <p>Оплачено за весь период</p>
        <div class='value'>$docs_per</div>
      </div>
    </div>
    <div class='stat_column'>
      <h2>Оплата</h2>
      <div class='stat_param_act'>
        <p>Получено за выбранный период</p>
        <div class='act_value'>$payment_per</div>
        <p>руб.</p>
      </div>
      <div class='stat_param_all'>
        <p>Получено за весь период</p>
        <div class='value'>$payment</div>
        <p>руб.</p>
      </div>
      <div class='stat_param_act'>
        <p>Средний чек за выбранный период</p>
        <div class='act_value'>".round($payment_per/count($trans_per_price))."</div>
        <p>руб.</p>
      </div>
      <div class='stat_param_all'>
        <p>Средний чек за весь период</p>
        <div class='value'>".round($payment/count($trans_price))."</div>
        <p>руб.</p>
      </div>
    </div>
  </div>
  <div class='stat_graph'>
    <h2>Совершено покупок</h2>
    <div>";
    for ($t=$time_from; $t<$time_to; $t=$t+86400){
      $damval = $trans_am[date('Y-m-d', $t)]*1;
      echo "
      <div class='stat_graph_column' data-alt='".date('d.m', $t).": ".$damval." транзакций'>
        <span>".$damval."</span>
        <div class='stat_scale' style='height:".($damval*$graph_max_height/$trans_max_am)."'></div>
      </div>";
    }
  echo "
    </div>
    <div class='stat_graph_legend'>";
    $graph_legend_len = round($per_length/$graph_max_legend);
    for ($l=1; $l<$graph_max_legend+1; $l++){
      $ldate = $time_from+($per_length/$graph_max_legend)*$l;
      echo date('d.m', $ldate)." ";
    }
    echo "
    </div><br /><br /><br />
    <h2>Получено денег</h2>
    <div>";
    for ($t=$time_from; $t<$time_to; $t=$t+86400){
      $dsumval = $trans_sum[date('Y-m-d', $t)]*1;
      echo "
      <div class='stat_graph_column' data-alt='".date('d.m', $t).": ".$dsumval." рублей'>
        <div class='stat_scale' style='height:".($dsumval*$graph_max_height/$trans_max_sum)."'></div>
      </div>";
    }
  echo "
    </div>
    <div class='stat_graph_legend'>";
    $graph_legend_len = round($per_length/$graph_max_legend);
    for ($l=1; $l<$graph_max_legend+1; $l++){
      $ldate = $time_from+($per_length/$graph_max_legend)*$l;
      echo date('d.m', $ldate)." ";
    }
    echo "
    </div><br /><br /><br />
  </div>";
  include("includes/foot.php");
?>