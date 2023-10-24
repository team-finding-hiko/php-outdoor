<?php
class ModeStateClass {
    private string $_mode;

    public function __construct(string $var) {
        $this->$_mode = $var;
    }
    
    // TODO メソッド
    public function method_sample(): string
    {
        if (isset($_POST[BACK]) && $_POST[BACK]) {
            $mode = INPUT;
          
            // 何もしない
          } else if (isset($_POST[CONFIRM]) && $_POST[CONFIRM]) {
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
              $_SESSION[TOKEN] = $token;
              $mode = CONFIRM;
            }
          
          } else if (isset($_POST[SEND]) && $_POST[SEND]) {
            // 送信ボタンを押したとき
            if ($_POST[TOKEN] != $_SESSION[TOKEN]) {
              $temporarily_errormessage[] = '不正な処理が行われました';
              $_SESSION = array();
              $mode = INPUT;
            } else {
              $message = "お問い合わせを受け付けました \r\n"
                . "名前: " . $_SESSION[FULL_NAME] . "\r\n"
                . "email: " . $_SESSION[EMAIL] . "\r\n"
                . "種別: " . $inquiry_type[$_SESSION[INQUIRY_TYPE_KEY]] . "\r\n"
                . "お問い合わせ内容:\r\n"
                . preg_replace("/\r\n|\r|\n/", "\r\n", $_SESSION[INQUIRY_CONTENTS]);
              mail($_SESSION[EMAIL], 'お問い合わせありがとうございます', $message);
              mail('ok919872i@gmail.com', 'お問い合わせありがとうございます', $message);
              $_SESSION = array();
              $mode = SEND;
            }
          } else {
            $_SESSION[FULL_NAME] = "";
            $_SESSION[EMAIL] = "";
            $_SESSION[INQUIRY_TYPE_KEY] = "";
            $_SESSION[INQUIRY_CONTENTS] = "";
          }
    }
}

class SessionControllClass {

}

class MailControllerClass {
    private Array $this->$inquiry_type;

    public function __construct(Array $var) {
        $this->$inquiry_type = $var;
    }

    public function create_message(): string {
        return "お問い合わせを受け付けました \r\n"
                . "名前: " . $_SESSION[FULL_NAME] . "\r\n"
                . "email: " . $_SESSION[EMAIL] . "\r\n"
                . "種別: " . $this->$inquiry_type[$_SESSION[INQUIRY_TYPE_KEY]] . "\r\n"
                . "お問い合わせ内容:\r\n"
                . preg_replace("/\r\n|\r|\n/", "\r\n", $_SESSION[INQUIRY_CONTENTS]);
    }

    public function send_mail(string $message): void {
        mail($_SESSION[EMAIL], 'お問い合わせありがとうございます', $message);
        mail('ok919872i@gmail.com', 'お問い合わせありがとうございます', $message);
    }
}

?>