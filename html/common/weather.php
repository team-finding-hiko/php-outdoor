<?php
require_once('./apikey.php');
//OpenWeatherMapのAPIにて気象情報取得
$apikey = APIKEY;
$url = "http://api.openweathermap.org/data/2.5/forecast?q=tokyo&appid={$apikey}&lang=ja&units=metric";

$weather_json = file_get_contents($url);
$weather_array = json_decode($weather_json, true);

$date = $weather_array["list"]["0"]["dt_txt"];
$jma_weather = $weather_array["list"]["0"]["weather"]["0"]["main"];
$open_icon = $weather_array["list"]["0"]["weather"]["0"]["icon"];
$weather_icon_url = "https://openweathermap.org/img/wn/{$open_icon}@2x.png";
$jma_temperature = $weather_array["list"]["0"]["main"]["temp"];

?>


<table border="1" width="580px">
  <tr>
    <td colspan="2" align="center">東京の天気</td>
  </tr>
  <tr>
    <td align="center">日時</td>
    <td align="center">
      <?= $date ?>
    </td>
  </tr>

  <tr>
    <td align="center">天気</td>
    <td align="center">
      <?= $jma_weather ?>
      <img src="<?php echo $weather_icon_url ?>">
    </td>
  </tr>

  <tr>
    <td align="center">気温</td>
    <td align="center">
      <?= $jma_temperature ?> ℃
    </td>
  </tr>
</table>