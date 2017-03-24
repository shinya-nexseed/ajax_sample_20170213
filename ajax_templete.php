<?php
$dsn = 'mysql:dbname=todo;host=localhost';
$user = 'root';
$password = '';
$dbh = new PDO($dsn, $user, $password);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->query('SET NAMES utf8');

$sql = 'SELECT * FROM `tasks`';
$stmt = $dbh->prepare($sql);
$stmt->execute();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8" />
  <title>jQuery & Ajax & PHP Example</title>

  <!-- Bootstrap -->
  <link href="assets/css/bootstrap.css" rel="stylesheet">
  <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
  <h1>jQuery & Ajax & PHP Example</h1>

  <form method="post">
    <?php while($task = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
      <?php if($task['completed']): ?>
        <!-- completedが1であればtrueを返す（checkを入れる） -->
        <input type="checkbox" id="<?php echo $task['id']; ?>" checked="check"><span class="checked"><?php echo $task['title']; ?></span><br>
      <?php else: ?>
        <input type="checkbox" id="<?php echo $task['id']; ?>"><span><?php echo $task['title']; ?></span><br>
      <?php endif; ?>
    <?php endwhile; ?>
  </form>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="assets/js/jquery-3.1.1.js"></script>
  <script src="assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="assets/js/bootstrap.js"></script>
  <script>
  $(document).ready(function() // HTMLがすべて読み込まれたあと実行
  {

    /**
     * 送信ボタンクリック
     */
    $('input').click(function() // inputタグがクリックされたら実行
    {
      var id = $(this).attr('id'); // クリックされたタグのidの値を取得

      //POSTメソッドで送るデータを定義します var data = {パラメータ名 : 値};
      var data = {task_id : id}; // JSで連想配列を定義
      // $data = array('hoge' => 'ほげ');
      // ここで定義したkeyが受け取り側の$_POSTのkeyになる

      /**
       * Ajax通信メソッド
       * @param type  : HTTP通信の種類
       * @param url   : リクエスト送信先のURL
       * @param data  : サーバに送信する値
       */
      $.ajax({
          type: "POST",
          url: "send_templete.php",
          data: data,

      /**
       * Ajax通信が成功した場合に呼び出されるメソッド
       */
      }).done(function(data) {
        // Ajax通信が成功した場合に呼び出される

        // PHPから返ってきたデータの表示
        // alert(data);

        // jsonデータをJSの配列にパース（変換）する
        var task_data = JSON.parse(data);

        // inputタグのclassやチェックのon/offを切り替える（DOM操作）
        var input_tag = document.getElementById(task_data['id']);
        input_tag.checked = task_data['completed']; // checkboxのon/off切替
        input_tag.nextSibling.classList.toggle('checked');
        // inputタグの次のタグ（span）の、classについているcheckedを出し入れ

      /**
       * Ajax通信が失敗した場合に呼び出されるメソッド
       */
      }).fail(function(data) {
          alert('error!!!' + data);
      });

    });
  });
  </script>
</body>
</html>
