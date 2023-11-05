<?php
class Mode {
    private string $mode;

    public function __construct(string $init_mode = INPUT) {
        $this->mode = $init_mode;
    }

    public function getMode(): string {
        return $this->mode;
    }

    public function setMode(string $mode_type): void {
        $this->mode = $mode_type;
    }
}

class SessionControll {
    private Array $session;

    private Array $session_types = array(FULL_NAME, EMAIL, INQUIRY_TYPE_KEY, INQUIRY_CONTENTS, TOKEN);

    public function __construct(Array $var) {
        $this->session = $var;
    }

    public function setPostToSession(Array $post): Array {
        foreach ($this->session_types as $value) {
            $session[$value] = htmlspecialchars($post[$value], ENT_QUOTES);
        }
        return $session;
    }

    public function resetWithEmpty(): Array {
        foreach ($this->session_types as $value) {
            $session[$value] = "";
        }
        return $session;
    }

    public function reset(): Array {
        return array();
    }
}

class MailControllerClass {
    private Array $inquiry_type;

    public function __construct(Array $var) {
        $this->inquiry_type = $var;
    }

    public function createMessage(): string {
        return "お問い合わせを受け付けました \r\n"
                . "名前: " . $_SESSION[FULL_NAME] . "\r\n"
                . "email: " . $_SESSION[EMAIL] . "\r\n"
                . "種別: " . $this->$inquiry_type[$_SESSION[INQUIRY_TYPE_KEY]] . "\r\n"
                . "お問い合わせ内容:\r\n"
                . preg_replace("/\r\n|\r|\n/", "\r\n", $_SESSION[INQUIRY_CONTENTS]);
    }

    public function sendMail(string $message): void {
        mail($_SESSION[EMAIL], 'お問い合わせありがとうございます', $message);
        mail('ok919872i@gmail.com', 'お問い合わせありがとうございます', $message);
    }
}

class ActionStateCheck {
    private Array $post;

    public function __construct(Array $var) {
        $this->post = $var;
    }

    public function check(string $action_state): bool {
        return isset($this->post[$action_state]) && $this->post[$action_state];
    }
}
?>