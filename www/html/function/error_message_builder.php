<?php
// class Pot
// {

//   // クラスの状態（車でいう速度）
//   private $water;

//   // クラスの状態の初期化（1回だけ呼ばれる）
//   function __construct()
//   {
//     $this->water = 0;
//   }

//   // クラスの状態に関するメソッド（変更：車でいうアクセル）
//   public function addWater($car)
//   {
//     $this->water += $car;
//   }

//   // クラスの状態に関するメソッド（確認：車でいうメーター）
//   public function getWater()
//   {
//     return $this->water;
//   }
// }

// 名前を入れた時に返却するクラス
class NameErrorMessageBuilder
{

  // クラスの状態（車でいう速度）
  private $form_field_name;

  function __construct()
  {
    $this->form_field_name = "fullname";
  }

  // クラスの状態に関するメソッド（確認：車でいうメーター）
  public function getFormFieldName()
  {
    return $this->form_field_name;
  }

  public function getErrorMessage()
  {
    if (!$_POST[$this->form_field_name]) {
      return "名前を入力してください";
    } else if (mb_strlen($_POST[$this->form_field_name]) > 100) {
      return "名前は100文字以内にしてください";
    }
    return "";
  }
}
?>