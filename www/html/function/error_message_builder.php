<?php

// 名前を入れた時にエラーメッセージを返却するクラス
class NameErrorMessageBuilder
{

  // クラスの状態（車でいう速度）
  private string $form_field_name;

  function __construct()
  {
    $this->form_field_name = FULL_NAME;
  }

  // クラスの状態に関するメソッド（確認：車でいうメーター）
  public function getFormFieldName(string $value): string
  {
    return $this->form_field_name;
  }

  public function getErrorMessage(): ?string
  {
    if (!$_POST[$this->form_field_name]) {
      return "名前を入力してください";
    } else if (mb_strlen($_POST[$this->form_field_name]) > 100) {
      return "名前は100文字以内にしてください";
    }
    return null;
  }
}

// 名前を入れた時にエラーメッセージを返却するクラス
class MailErrorMessageBuilder
{

  // クラスの状態（車でいう速度）
  private string $form_field_name;

  function __construct()
  {
    $this->form_field_name = EMAIL;
  }

  // クラスの状態に関するメソッド（確認：車でいうメーター）
  public function getFormFieldName(): string
  {
    return $this->form_field_name;
  }

  public function getErrorMessage(): ?string
  {
    if (!$_POST[$this->form_field_name]) {
      return "Eメールを入力してください";
    } else if (mb_strlen($_POST[$this->form_field_name]) > 200) {
      return "Eメールは200文字以内にしてください";
    } else if (!filter_var($_POST[$this->form_field_name], FILTER_VALIDATE_EMAIL)) {
      return "メールアドレスが不正です";
    }
    return null;
  }
}

class InquiryTypeErrorMessageBuilder
{

  // クラスの状態（車でいう速度）
  private string $form_field_name;

  function __construct()
  {
    $this->form_field_name = "inquiry_type_key";
  }

  // クラスの状態に関するメソッド（確認：車でいうメーター）
  public function getFormFieldName(): string
  {
    return $this->form_field_name;
  }

  public function getErrorMessage(): ?string
  {
    if (!$_POST[$this->form_field_name]) {
      return "種別を選択してください";
    } else if (
      $_POST[$this->form_field_name] <= 0 || 3 < $_POST[$this->form_field_name]
    ) {
      return "種別が不正です";
    }
    return null;
  }
}

class MessageErrorMessageBuilder
{

  // クラスの状態（車でいう速度）
  private string $form_field_name;

  function __construct()
  {
    $this->form_field_name = INQUIRY_CONTENTS;
  }

  // クラスの状態に関するメソッド（確認：車でいうメーター）
  public function getFormFieldName(): string
  {
    return $this->form_field_name;
  }

  public function getErrorMessage(): ?string
  {
    if (!$_POST[$this->form_field_name]) {
      return "お問い合わせ内容を入力してください";
    } else if (mb_strlen($_POST[$this->form_field_name]) > 500) {
      return "お問い合わせ内容は500文字以内にしてください";
    }
    return null;
  }
}


?>