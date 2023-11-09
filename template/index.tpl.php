<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>目次 | Quiz</title>
</head>
<body>
  <div class="cover"></div>
  <div class="Logo">
    <h1>Quiz!</h1>
  </div>
  <header>
    <div class="container">
      <div class="menu">
        <h2>問題一覧</h2>
      </div>
      <div class="correctCount">
        <h2>正答数<span class="count"></span>/<span class="leque"></span></h2>
      </div>
    </div>
  </header>
  <ul class="questions">
    <?php foreach($questions as $question): ?>
      <li class='yet'><a href="question.php?id=<?php echo $question['id']?>">問題<?php echo $question['id']?></a></li>
    <?php endforeach;?>
  </ul>
  <script src="questions.js"></script>
  <p class="retry">再挑戦！</p>
  <!--隠しモーダル(回答状況リセット)-->
  <div class="resetModal">
    <div class="modal-content">
      <p class="checkSentence">本当にリセットしますか？</p>
      <div class="resetCheck">
        <p class="cancel">キャンセル</p>
        <p class="reset">リセットする！</p>
      </div>
    </div>
  </div>
  <!--隠しモーダル(全問正解)-->
  <div class="celebrateModal">
    <div class="modal-content">
      <p class="Congratulation">全問正解、Congratulation!!!</p>
        <p class="close">閉じる</p>
    </div>
  </div>

</body>
</html>