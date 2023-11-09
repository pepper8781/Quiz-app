<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <script src="questions.js" defer></script>
  <title>問題<?php echo $id; ?> | Quiz</title>
</head>
<body>
  <h1>Quiz!</h1>
  <h2>問題<?php echo $id; ?></h2>
  <p><?php echo $question; ?></p>
  <h3>選択肢</h3>
    <ol class="answers" data-id=<?php echo $id; ?>>
      <?php foreach($answers as $key => $value): ?>
        <li data-answer=<?php echo $key; ?>><?php echo $value; ?><span class="result"></span></li>
      <?php endforeach; ?>
    </ol>
  <!--正解時表示-->
  <div class="comment">
    <h2>答え</h2>
    <p><span class="answer"></span><br>
    <span class="explanation"></span></p>
  </div>
  <!--不正解時表示-->
  <p class="onemore">もう一度</p>

  <a class="returnMenu" href="index.php">戻る</a>

  <script>
    //リロードボタンのスクリプト
    let btnReload = document.querySelector('.onemore');
    btnReload.addEventListener('click', ()=>{
      location.reload();
    });
  </script>

</body>
</html>