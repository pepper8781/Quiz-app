<?php

function loadTemplate($filename, array $assignData = []) {
  extract($assignData);
  include __DIR__.'/../template/'.$filename.'.tpl.php';
}

function error404() {
  header('HTTP/1.1 404 Not Found');

  //レスポンスの種類を指定する
  header('Content-Type: application/json; charset=UTF-8');


  loadTemplate(404);

  $response = [
    'message' => 'The specified id could not be found', 
  ];
  
  echo json_encode($response);
  exit(0);
}

function fetchAll(){
  //ファイルを開く
  $handler = fopen(__DIR__.'/data.csv', 'r');
  //データを取得
  $questions = [];
  while ($row = fgetcsv($handler)){
    if(isDataRow($row)){
      $questions[] = $row;
    }
  }
  //ファイルを閉じる
  fclose($handler);
  //データを返す
  return $questions;
}

function fetchById($id){
  //ファイルを開く
  $handler = fopen(__DIR__.'/data.csv', 'r');
  //データを取得
  $question = [];
  while ($row = fgetcsv($handler)){
    if(isDataRow($row)){
      if($row[0] === $id){
        $question = $row;
        break;
      }
    }
  }
  //ファイルを閉じる
  fclose($handler);
  //データを返す
  return $question;
}

function isDataRow(array $row)
{
    // データの項目数が足りているか判定
    if (count($row) !== 9) {
        return false;
    }

    // データの項目の中身がすべて埋まっているか確認する
    foreach ($row as $value) {
        // 項目の値が空か判定
        if (empty($value)) {
            return false;
        }
    }

    // idの項目が数字ではない場合は無視する
    if (!is_numeric($row[0])) {
        return false;
    }

    // 正しい答えはa,b,c,dのどれか
    $correctAnswer = strtoupper($row[6]);
    $availableAnswers = ['A', 'B', 'C', 'D'];
    if (!in_array($correctAnswer, $availableAnswers)) {
        return false;
    }

    // すべてチェックが問題なければtrue
    return true;
}

function generateFormattedData($data)
{
    // 構造化した配列を作成する
    $formattedData = [
        'id' => escape($data[0]),
        'question' => escape($data[1], true),
        'answers' => [
            'A' => escape($data[2]),
            'B' => escape($data[3]),
            'C' => escape($data[4]),
            'D' => escape($data[5]),
        ],
        'correctAnswer' => escape(strtoupper($data[6])),
        'correctAnswerValue' => escape($data[7]),
        'explanation' => escape($data[8], true),
    ];

    return $formattedData;
}

function escape($data, $nl2br = false)
{
    // HTMLに埋め込んでも大丈夫な文字に変換する
    $convertedData = htmlspecialchars($data, ENT_HTML5);

    // 改行コードを<br>タグに変換するか判定
    if ($nl2br) {
        /// 改行コードを<br>タグに変換したものをを返却
        return nl2br($convertedData);
    }

    return $convertedData;
}
