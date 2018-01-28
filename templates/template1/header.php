<!DOCTYPE html>
<html lang="ru">
<head>
  <title><?=$data['meta_title']?></title>
  <meta name="description" content="<?=$data['description']?>">
  <meta name="keywords" content="<?=$data['keywords']?>">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta property="og:url" content="<?=SITE_URL?>/" />
  <meta property="og:type" content="website" />
  <meta property="og:title" content="<?=$meta_title; ?>" />
  <meta property="og:description" content="<?=$description?>" />
  <meta property="og:image" content="<?=SITE_URL?>/img/logo.gif" />

  <link rel="image_src" href="<?=SITE_URL?>/img/logo.gif">
  <link rel="stylesheet" href="/css/stylesheet2.css" type="text/css" />
  <link rel="stylesheet" href="/templates/<?=TEMPLATE?>/style.css" type="text/css" />

  <script type="text/javascript" src="/js/jquery.min.js"></script>
  <script type="text/javascript" src="/js/suggestions/jquery.suggestions.min.js"></script>

  <!-- Yandex.Metrika counter -->
  <script type="text/javascript" >
      (function (d, w, c) {
          (w[c] = w[c] || []).push(function() {
              try {
                  w.yaCounter46713153 = new Ya.Metrika({
                      id:46713153,
                      clickmap:true,
                      trackLinks:true,
                      accurateTrackBounce:true,
                      webvisor:true,
                      ecommerce:"dataLayer"
                  });
              } catch(e) { }
          });
  
          var n = d.getElementsByTagName("script")[0],
              s = d.createElement("script"),
              f = function () { n.parentNode.insertBefore(s, n); };
          s.type = "text/javascript";
          s.async = true;
          s.src = "https://mc.yandex.ru/metrika/watch.js";
  
          if (w.opera == "[object Opera]") {
              d.addEventListener("DOMContentLoaded", f, false);
          } else { f(); }
      })(document, window, "yandex_metrika_callbacks");
  </script>
  <noscript><div><img src="https://mc.yandex.ru/watch/46713153" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
  <!-- /Yandex.Metrika counter -->

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-109944049-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
  
    gtag('config', 'UA-109944049-1');
  </script>
</head>
<body>
  <!--<div id="loading" style="//display:none; position:fixed; left:0; top:0; width:100%; height:100%; background:rgba(0, 0, 0, 0.5); z-index:100;">
    <div style="position:absolute; left:0; top:0; bottom:0; right:0; margin:auto; width:150px; height:110px; background:#fff; z-index:11; text-align:center; padding:20px; border-radius:10px;">
      <img src="/img/loading.gif" style="margin-bottom:10px;"><br>
      <p>Загрузка...</p>
    </div>
  </div>-->
  <header>
    <div>
      <div class="site_title">
        <a href="/">НАКОГО.РУ</a>
      </div>
      <div class="top_menu">
        <ul>
        <?if($user_data){?>
          <li class="top_menu_item"><a href="/documents" class="desktop_only">Мои выписки</a></li>
          <li class="top_menu_item"><a href="/profile" class="desktop_only">Мой профиль</a></li>
          <li class="top_menu_item"><a href="/egrn" class="desktop_only">Пакетные предложения</a></li>
          <li class="top_menu_item"><a href="/?exit" class="desktop_only">Выйти</a></li>
        <?}else{?>
          <li class="top_menu_item"><a href="/faq" class="desktop_only">Вопросы и ответы</a></li>
          <li class="top_menu_item"><a href="/egrn" class="desktop_only">Пакетные предложения</a></li>
          <li class="top_menu_item"><a href="/profile" class="desktop_only"><span class="' yellow_text">Личный кабинет</span></a></li>
        <?}?>

          <li class="top_menu_item mobile_menu_btn mobile_only">
            <i class="fa fa-bars" aria-hidden="true"></i>&nbsp;
            <div>
              <ul class="sub_menu mobile_only">
                <li>
                  <i class="fa fa-home"></i>&nbsp;
                  <a href="/">Главная</a>
                </li>
              <?if($user_data){?>
                <li>
                  <i class="fa fa-file-text"></i>&nbsp;
                  <a href="/documents">Мои выписки</a>
                </li>
                <li>
                  <i class="fa fa-user"></i>&nbsp;
                  <a href="/profile">Мой профиль</a>
                </li>
                <li>
                  <i class="fa fa-list-ul"></i>&nbsp;
                  <a href="/egrn">Пакетные предложения</a>
                </li>
                <li>
                  <i class="fa fa-home"></i>&nbsp;
                  <a href="/?exit">Выход</a>
                </li>
              <?}else{?>
                <li>
                  <i class="fa fa-user"></i>&nbsp;
                  <a href="/profile">Личный кабинет</a>
                </li>
              <?}?>
              </ul>
            </div>
          </li>
        </ul>
      </div>
      <div class="clear"></div>
    </div>
  </header>
  <div class="top_panel">
    <div>
      <div>
        <a href="/"><img src="/img/logo.png" alt="" /></a>
      </div>
      <div>
        С 1 января 2017 года выписка из ЕГРН, кадастровый паспорт, кадастровая<br>
        выписка заменены одним документом - выпиской из ЕГРН
      </div>
    </div>
  </div>