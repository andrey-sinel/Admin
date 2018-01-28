<main>
  <section class="full_screen">
    <div class="page_body2">
      <h1 class="width_30">Частые вопросы</h1>
    <?php
      $questions = $DB->getData("select * from faq");
      foreach ($questions as $q){
    ?>
      <h2 class="question"><?=$q['title']?></h2>
      <div class="answer">
        <?=$q['text']?>
      </div>
    <? } ?>
    </div>
  </section>
  <br /><br />
</main>