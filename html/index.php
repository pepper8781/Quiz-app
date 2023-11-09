<?php

require __DIR__.'/../lib/functions.php';

$dataList = fetchAll();

if (!$dataList){
  header('HTTP/1.1 404 Not Found');

  // レスポンスの種類を指定する
  header('Content-Type: text/html; charset=UTF-8');

  include __DIR__.'/../template/404.tpl.php';

  exit(0);
}

$questions = [];
foreach ($dataList as $data) {
  $questions[] = generateFormattedData($data);
}

$assignData=[
  'questions' => $questions,
];
loadTemplate('index', $assignData);