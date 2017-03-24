<?php
    // ajaxでPOST送信したデータは、送信先で$_POSTとして受け取れる
    // $_POST['task_id'] = 1;
    $dsn = 'mysql:dbname=todo;host=localhost';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->query('SET NAMES utf8');

    $sql = 'SELECT * FROM `tasks` WHERE `id`=?';
    $data = array($_POST['task_id']);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    // completedの値をトグルしてあげる
    // 1 = true
    // 0 = false
    $completed = (bool)$task['completed'];
    // $task['completed']の値をbool型にキャスト（変換）する
    $completed = !$completed; // トグル処理 true ⇔ false

    $sql = 'UPDATE `tasks` SET `completed`=? WHERE `id`=?';
    $data = array($completed, $task['id']);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

    // 画面の表示を切り替えるためのデータを作成
    $data = array('id' => $task['id'],
                  'title' => $task['title'],
                  'completed' => $completed
                 );

    header("Content-type: text/plain; charset=UTF-8");
    //ここに何かしらの処理を書く（DB登録やファイルへの書き込みなど）
    echo json_encode($data);
    // PHPとJS間でデータのやり取りを行うためにjson形式でデータを送る
?>









