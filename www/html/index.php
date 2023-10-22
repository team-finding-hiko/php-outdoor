<?php
// 定数
// ############################################################################
const FULL_NAME = "full_name";
const EMAIL = "email";
const INQUIRY_TYPE_KEY = "inquiry_type_key";
const INQUIRY_CONTENTS = "inquiry_contents";
const INPUT = "input";
// ############################################################################
session_start();

require_once('./function/error_message_builder.php');
// TODO 引数の設定
$name_error_message_builder = new NameErrorMessageBuilder;
$mail_error_message_builder = new MailErrorMessageBuilder;
$inquiry_type_error_message_builder = new InquiryTypeErrorMessageBuilder;
$message_error_message_builder = new MessageErrorMessageBuilder;
$inquiry_type = array();
// TODO ENUM化
$inquiry_type[0] = '種別を選択してください';
$inquiry_type[1] = '質問';
$inquiry_type[2] = 'ご意見';
$inquiry_type[3] = '資料請求';


$mode = INPUT;
$temporarily_errormessage = array();
$error_message = array();
// TODO クラス化検討
if (isset($_POST["back"]) && $_POST["back"]) {
  $mode = INPUT;

  // 何もしない
} else if (isset($_POST["confirm"]) && $_POST["confirm"]) {
  // 確認画面

  $temporarily_errormessage[] = $name_error_message_builder->getErrorMessage();
  $_SESSION[FULL_NAME] = htmlspecialchars($_POST[FULL_NAME], ENT_QUOTES);

  $temporarily_errormessage[] = $mail_error_message_builder->getErrorMessage();
  $_SESSION[EMAIL] = htmlspecialchars($_POST[EMAIL], ENT_QUOTES);

  $temporarily_errormessage[] = $inquiry_type_error_message_builder->getErrorMessage();
  $_SESSION[INQUIRY_TYPE_KEY] = htmlspecialchars($_POST[INQUIRY_TYPE_KEY], ENT_QUOTES);

  $temporarily_errormessage[] = $message_error_message_builder->getErrorMessage();
  $_SESSION[INQUIRY_CONTENTS] = htmlspecialchars($_POST[INQUIRY_CONTENTS], ENT_QUOTES);

  // エラーメッセージの中のnullや空文字を除去する
  $error_message = array_filter($temporarily_errormessage);
  if ($error_message) {
    $mode = INPUT;
  } else {
    $token = bin2hex(random_bytes(32));
    $_SESSION['token'] = $token;
    $mode = "confirm";
  }

} else if (isset($_POST['send']) && $_POST['send']) {
  // 送信ボタンを押したとき
  if ($_POST['token'] != $_SESSION['token']) {
    $temporarily_errormessage[] = '不正な処理が行われました';
    $_SESSION = array();
    $mode = 'input';
  } else {
    $message = "お問い合わせを受け付けました \r\n"
      . "名前: " . $_SESSION[FULL_NAME] . "\r\n"
      . "email: " . $_SESSION[EMAIL] . "\r\n"
      . "種別: " . $kind[$_SESSION[INQUIRY_TYPE_KEY]] . "\r\n"
      . "お問い合わせ内容:\r\n"
      . preg_replace("/\r\n|\r|\n/", "\r\n", $_SESSION[INQUIRY_CONTENTS]);
    mail($_SESSION[EMAIL], 'お問い合わせありがとうございます', $message);
    mail('uemura@hoge.com', 'お問い合わせありがとうございます', $message);
    $_SESSION = array();
    $mode = 'send';
  }
} else {
  $_SESSION[FULL_NAME] = "";
  $_SESSION[EMAIL] = "";
  $_SESSION[INQUIRY_TYPE_KEY] = "";
  $_SESSION[INQUIRY_CONTENTS] = "";
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
    if ($error_message) {
      echo '<div class="alert alert-danger" role="alert">';
      echo implode('<br>', $error_message);
      echo '</div>';
    }
    ?>

    <form action="./index.php" method="post">

      名前 <input type="text" class="form-control" name="<?php echo FULL_NAME ?>"
        value="<?php echo $_SESSION[FULL_NAME] ?>"><br>
      Eメール <input type=EMAIL class="form-control" name="<?php echo EMAIL ?>" value="<?php echo $_SESSION[EMAIL] ?>"><br>
      種別：
      <select name="<?php echo INQUIRY_TYPE_KEY ?>" class="form-control">
        <?php foreach ($inquiry_type as $inquiry_type_key => $inquiry_type_value) { ?>
          <?php if ($_SESSION[INQUIRY_TYPE_KEY] == $inquiry_type_key) { ?>
            <option value="<?php echo $inquiry_type_key ?>" selected><?php echo $inquiry_type_value ?></option>
          <?php } else { ?>
            <option value="<?php echo $inquiry_type_key ?>"><?php echo $inquiry_type_value ?></option>
          <?php } ?>
        <?php } ?>
      </select><br>
      お問い合わせ内容<br>
      <textarea cols="40" rows="8" name="<?php echo INQUIRY_CONTENTS ?>"
        class="form-control"><?php echo $_SESSION[INQUIRY_CONTENTS] ?></textarea><br>
      <div class="button"><input type="submit" name="confirm" value="確認" class="btn btn-primary mb-3 btn-lg" /></div>

    </form>
  <?php } else if ($mode == 'confirm') { ?>
      <!-- 確認画面 -->
      <form action="./index.php" method="post">
        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
        名前
      <?php echo $_SESSION[FULL_NAME] ?><br>
        Eメール
      <?php echo $_SESSION[EMAIL] ?><br>
        種別
      <?php echo $inquiry_type[$_SESSION[INQUIRY_TYPE_KEY]] ?><br>
        お問い合わせ内容<br>
      <?php echo nl2br($_SESSION[INQUIRY_CONTENTS]) ?><br>
        <input type="submit" name="back" value="戻る" class="btn btn-primary mb-3 btn-lg" />
        <input type="submit" name="send" value="送信" class="btn btn-primary mb-3 btn-lg" />
      </form>
  <?php } else { ?>
      <!-- 完了画面 -->
      送信しました。お問い合わせありがとうございました<br>
  <?php } ?>
</body>

</html>