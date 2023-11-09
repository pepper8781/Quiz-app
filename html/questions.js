document.addEventListener("DOMContentLoaded", function() {
  //選択肢のDOM取得
  const answersList = document.querySelectorAll('ol li');

  //選択肢クリック時の動作設定
  answersList.forEach(li => li.addEventListener('click', checkClickedAnswer));

  //問題一覧のDOM取得
  const questions = document.querySelectorAll('ul li');

  // 回答状況の初期化
  if (!localStorage.getItem('isCorrected')) {
    localStorage.setItem('isCorrected', JSON.stringify({
      1: false,
      2: false,
      3: false,
      4: false,
      5: false,
      6: false
    }));
  }
  //全問正解したかどうかの確認・設定
  if(localStorage.getItem('isCelebrated') === null) {
    localStorage.setItem('isCelebrated', false);
  }

  //回答状況の取得
  const isCorrected = JSON.parse(localStorage.getItem('isCorrected'));

  //各問題ページにおいて解答済みかどうか
  let isAnswered = false;

  //問題数を取得
  const specificUl = document.querySelector('.questions');
  const questionsCount = specificUl.querySelectorAll('li').length;

  //回答状況を指定するための配列を取得
  const correctKeys = Object.keys(isCorrected);
  //解答済みの問題は目次で色を変える
  for(let i = 0; i < questionsCount; i++){
    const value = isCorrected[correctKeys[i]];
    if(value === true){
      questions[i].classList.remove("yet");
      questions[i].classList.add("correctedAnswer");
    }
  }

  //正答数の取得
  let trueCount = Object.values(isCorrected).filter(value => value === true).length;
  let countOfCorrect = trueCount;

  //問題数を文字列として表示
  document.querySelector('.leque').textContent = String(questionsCount);

  //正答数を文字列として表示
  const countElement = document.querySelector('.count');
  if (countElement) {
    countElement.textContent = String(countOfCorrect);
  }


  //解答クリック時の動作
  function checkClickedAnswer(event) {
    //一度解答したら２回目のクリックでは動作しないようにする
    if (isAnswered) return;

    //クリックした選択肢の情報取得
    const clickedAnswerElement = event.currentTarget;

    const resultSpan = clickedAnswerElement.querySelector('.result');

    const selectedAnswer = clickedAnswerElement.dataset.answer;

    const questionID = clickedAnswerElement.closest('ol.answers').dataset.id;
    
    //サーバーとのやりとり
    const formData = new FormData();

    formData.append('id',questionID);
    formData.append('selectedAnswer', selectedAnswer)

    //リクエストの実行
    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'answer.php');

    xhr.send(formData)

    //レスポンスの取得成否による動作
    xhr.addEventListener('loadend', function(event){
      const xhr = event.currentTarget;
      if(xhr.status === 200){
        const response = JSON.parse(xhr.response);
        const result = response.result;
        const correctAnswer = response.correctAnswer;
        const correctAnswerValue = response.correctAnswerValue;
        const explanation = response.explanation;
        //後述の画面上の動作
        displayResult(result, clickedAnswerElement, resultSpan, questionID, correctAnswer, correctAnswerValue, explanation);
      }else{
        alert('Error: 解答データの取得に失敗しました');
      }
    });


    isAnswered = true;

  }

  //選択肢クリック時の画面上での動作
  function displayResult(result, clickedAnswerElement, resultSpan, questionID, correctAnswer, correctAnswerValue, explanation){
    if (result) {
      resultSpan.textContent = '〇';
      resultSpan.style.color = 'red';
      clickedAnswerElement.style.color = 'red';
      resultSpan.style.display = 'inline';
      isCorrected[questionID] = true;
      document.querySelector('.comment').style.display = 'block';
    } else {
      resultSpan.textContent = '×';
      resultSpan.style.color = 'blue';
      clickedAnswerElement.style.color = 'blue';
      resultSpan.style.display = 'inline';
      document.querySelector('.onemore').style.display = 'block';
    }

    document.querySelector('.answer').innerHTML = correctAnswer + correctAnswerValue;
    document.querySelector('.explanation').innerHTML = explanation;

    //目次の表示正答数の変更
    let trueCount = Object.values(isCorrected).filter(value => value === true).length;
    
    localStorage.setItem('isCorrected', JSON.stringify(isCorrected));
    
    const countElement = document.querySelector('.count');
    if (countElement) {
      countElement.textContent = String(trueCount);
    }

    const count = document.querySelector('.count');
    if (count) {
      let trueCount = Object.values(isCorrected).filter(value => value === true).length;
      countElement.textContent = String(trueCount);
    }
  }

  //モーダルの表示についての処理
  const modal = document.querySelector('.resetModal');

  const cemodal = document.querySelector('.celebrateModal')

  const close = document.querySelector('.close');

  const openRetry = document.querySelector('.retry');  

  const reset = document.querySelector('.reset');

  const closeModalButton = document.querySelector('.cancel');

  openRetry.addEventListener('click', openModal);

  closeModalButton.addEventListener('click', closeModal);

  //回答状況リセット時確認用のモーダルの動作
  function openModal() {
    modal.style.display = 'flex';
    //拝啓をクリックできないようにするためのカバー
    document.querySelector('.cover').style.display = 'block';
  }

  function closeModal() {
    modal.style.display = 'none';
    document.querySelector('.cover').style.display = 'none';
  }

  //全問正解時のモーダルの動作
  function openCelebrate() {
    if (JSON.parse(localStorage.getItem('isCelebrated'))) return;
    cemodal.style.display = 'flex'
    document.querySelector('.cover').style.display = 'block';
  }

  function closeCelebrate() {
    cemodal.style.display = 'none';
    document.querySelector('.cover').style.display = 'none';
    localStorage.setItem('isCelebrated', true);
  }

  if(countOfCorrect === questionsCount){
    openCelebrate();
  }


  close.addEventListener('click', closeCelebrate);


  //回答状況のリセットのための処理
  reset.addEventListener('click', function() {
    //ローカルストレージ削除
    localStorage.clear();
    
    //回答状況のリセット
    localStorage.setItem('isCorrected', JSON.stringify({
      1: false,
      2: false,
      3: false,
      4: false,
      5: false,
      6: false
    }));

    for(let i = 0; i < questionsCount; i++){
      const value = isCorrected[correctKeys[i]];
      questions[i].classList.remove("correctedAnswer");
      questions[i].classList.add("yet");
      }

    //全問正解判定のリセット
    localStorage.setItem('isCelebrated', false);

    //回答数の初期化
    const countElement = document.querySelector('.count');
    if (countElement) {
      countElement.textContent = "0";
    }

    //上の動作が終わり次第、リセット用モーダルを閉じる
    isModalOpen = false;

    closeModal();

    //カバーを非表示に
    document.querySelector('.cover').style.display = 'none';

  });

});

