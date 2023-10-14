<?php
session_start();
$mode = "input";
$errmessage = array();
if (isset($_POST["back"]) && $_POST["back"]) {
  $mode = "input";


  // 何もしない
} else if (isset($_POST["confirm"]) && $_POST["confirm"]) {
  // 確認画面
  if (!$_POST['fullname']) {
    $errmessage[] = "名前を入力してください";
  } else if (mb_strlen($_POST['fullname']) > 100) {
    $errmessage[] = "名前は100文字以内にしてください";
  }
  $_SESSION["fullname"] = htmlspecialchars($_POST['fullname'], ENT_QUOTES);


  if (!$_POST['email']) {
    $errmessage[] = "Eメールを入力してください";
  } else if (mb_strlen($_POST['email']) > 200) {
    $errmessage[] = "Eメールは200文字以内にしてください";
  } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $errmessage[] = "メールアドレスが不正です";
  }
  $_SESSION["email"] = htmlspecialchars($_POST['email'], ENT_QUOTES);


  if (!$_POST['message']) {
    $errmessage[] = "お問い合わせ内容を入力してください";
  } else if (mb_strlen($_POST['message']) > 500) {
    $errmessage[] = "お問い合わせ内容は500文字以内にしてください";
  }
  $_SESSION["message"] = htmlspecialchars($_POST['message'], ENT_QUOTES);


  if ($errmessage) {
    $mode = "input";
  } else {
    $token = bin2hex(random_bytes(32));
    $_SESSION['token'] = $token;

    $mode = "confirm";
  }

} else if (isset($_POST['send']) && $_POST['send']) {
  // 送信ボタンを押したとき
  if ($_POST['token'] != $_SESSION['token']) {
    $errmessage[] = '不正な処理が行われました';
    $_SESSION = array();
    $mode = 'input';
  } else {
    $message = "お問い合わせを受け付けました \r\n"
      . "名前: " . $_SESSION['fullname'] . "\r\n"
      . "email: " . $_SESSION['email'] . "\r\n"
      . "お問い合わせ内容:\r\n"
      . preg_replace("/\r\n|\r|\n/", "\r\n", $_SESSION['message']);
    mail($_SESSION['email'], 'お問い合わせありがとうございます', $message);
    mail('uemura@hoge.com', 'お問い合わせありがとうございます', $message);
    $_SESSION = array();
    $mode = 'send';
  }
} else {

  $_SESSION = array();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>お問い合わせフォーム</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <style>
    body {
      padding: 10px;
      max-width: 600px;
      margin: 0px auto;
    }

    div.button {
      text-align: center;
    }
  </style>
</head>

<body>
  <?php if ($mode == 'input') { ?>
    <!-- 入力画面 -->
    <?php
    if ($errmessage) {
      echo '<div class="alert alert-danger" role="alert">';
      echo implode('<br>', $errmessage);
      echo '</div>';
    }
    ?>

    <form action="./index.php" method="post">


      名前 <input type="text" class="form-control" name="fullname" value="<?php echo $_SESSION['fullname'] ?>"><br>
      Eメール <input type="email" class="form-control" name="email" value="<?php echo $_SESSION['email'] ?>"><br>
      お問い合わせ内容<br>
      <textarea cols="40" rows="8" name="message" class="form-control"><?php echo $_SESSION['message'] ?></textarea><br>
      <div class="button"><input type="submit" name="confirm" value="確認" class="btn btn-primary mb-3 btn-lg" /></div>

    </form>
  <?php } else if ($mode == 'confirm') { ?>
      <!-- 確認画面 -->
      <form action="./index.php" method="post">
        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
        名前
      <?php echo $_SESSION['fullname'] ?><br>
        Eメール
      <?php echo $_SESSION['email'] ?><br>
        お問い合わせ内容<br>
      <?php echo nl2br($_SESSION['message']) ?><br>
        <input type="submit" name="back" value="戻る" class="btn btn-primary mb-3 btn-lg" />
        <input type="submit" name="send" value="送信" class="btn btn-primary mb-3 btn-lg" />
      </form>
  <?php } else { ?>
      <!-- 完了画面 -->
      送信しました。お問い合わせありがとうございました<br>
  <?php } ?>
</body>

</html>