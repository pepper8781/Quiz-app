<?php

require __DIR__.'/../lib/functions.php';

//問題情報取得
$id = escape($_GET['id'] ?? '');

$data = fetchById($id);

//データが取得できない(該当の問題データが存在しない)場合の動作
if (!$data){
  header('HTTP/1.1 404 Not Found');

  // レスポンスの種類を指定する
  header('Content-Type: text/html; charset=UTF-8');

  //エラー画面に飛ばす
  include __DIR__.'/../template/404.tpl.php';

  exit(0);
}

$formattedData = generateFormattedData($data);

$correctAnswer = $formattedData['correctAnswer'];
$correctAnswerValue = $formattedData['correctAnswerValue'];
$explanation = $formattedData['explanation'];


$assignData = [
  'id' => $formattedData['id'],
  'question' => $formattedData['question'],
  'answers' => $formattedData['answers']
];
loadTemplate('question',$assignData);