<?php
$img_path = "https://openweathermap.org/img/wn/10d@2x.png";
$img = file_get_contents($img_path);
header('Content-type: image/png');
echo $img;
?>