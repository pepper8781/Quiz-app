<?php

require __DIR__.'/../lib/functions.php';

$id = $_POST['id'] ?? '';
$selectedAnswer = $_POST['selectedAnswer'] ?? '';

$data = fetchById($id);

if (!$data){
  error404();
}

$formattedData = generateFormattedData($data);


$correctAnswer = $formattedData['correctAnswer'];
$correctAnswerValue = $formattedData['correctAnswerValue'];
$explanation = $formattedData['explanation'];

$result = $selectedAnswer === $correctAnswer;

$response = [
  'result' => $result,
  'correctAnswer' => $correctAnswer,
  'correctAnswerValue' => $correctAnswerValue,
  'explanation' => $explanation
];

header('Content-Type: application/json; charset=UTF-8');
echo json_encode($response);