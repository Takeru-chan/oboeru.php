<!doctype html>
<html lang="ja"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=320px">
<style>
  body{font-family:-apple-system;text-align:center;font-size:18px;}
  h1{font-size:20px;height:45px;line-height:45px}
  h1 a,#nav a{text-decoration:none;}
  *{-webkit-touch-callout:none;-webkit-user-select:none;user-select:none;margin:0;padding:0;}
  ul{list-style:none;}
  li a{line-height:2em;}
  .mainview{overflow:scroll;}
  .mainview p{margin:1em;}
  #original{overflow:scroll;text-align:left;margin:0 1em;}
  #translate{overflow:scroll;color:#fff;text-align:left;margin:0 1em;}
  #translate:active{color:#999;}
  #nav{line-height:45px;display:flex;justify-content:space-around;align-items:center;}
  .btn a{background:lightgray;border-radius:18px;padding:5px 9px;color:gray;text-shadow:1px 1px 0 white;}
</style>
</head><body>
<h1 class='btn'><a href='./oboeru.php'>おぼえるくん-α</a></h1><hr>
<?php
$viewheight = 360; // メインビューの高さはここのみで指定。その他は自動計算
$halfheight = $viewheight / 2;
$dir = $_GET["dir"];
if ($dir != "") {
  foreach(glob($dir."/data.*") as $file) {
    $ext = explode('.',$file)[1];
    if (is_numeric($ext)) {$result[] = $ext;}
  }
  $_GET["min"] != "" ? $min = $_GET["min"] : $min = min($result);
  $_GET["max"] != "" ? $max = $_GET["max"] : $max = max($result);
  $rnd = $_GET["rnd"];
  if($rnd < 0) {
    $curr = mt_rand($min,$max);
    $prev = mt_rand($min,$max);
    $next = mt_rand($min,$max);
  } else {
    $_GET["curr"] != "" ? $curr = $_GET["curr"] : $curr = $min;
    $curr - 1 < $min ? $prev = $max : $prev = $curr - 1 ;
    $curr + 1 > $max ? $next = $min : $next = $curr + 1 ;
  }
  $mode = $_GET["mode"];
  if($mode == "letter") {
    $style = "style='height:".$halfheight."px;text-align:center;font-size:64px;line-height:".$halfheight."px;'";
  } else {
    $style = "style='height:".$halfheight."px;'";
  }
  $line = @file(__DIR__ . "/${dir}/data.${curr}", FILE_IGNORE_NEW_LINES);
  echo "<p id='original' ".$style.">".$line[0]."</p>";
  echo "<hr>";
  echo "<p id='translate' ontouchstart='' ".$style.">".$line[1]."</p>";
} else {
  $mode = $_GET["mode"];
  if($mode == 'credit') {
    echo "<div class='mainview' style='height:".$viewheight."px'><p>単語/短文帳 Webアプリ<br>Version α</p>";
    echo "<p>License<br>This application has released under <a href='http://opensource.org/licenses/MIT'>the MIT license</a>.</p></div>";
  } else {
    $line = @file(__DIR__ . "/oboeru.list", FILE_IGNORE_NEW_LINES);
    echo "<ul class='mainview' style='height:".$viewheight."px'>";
    for ($i=0; $i<count($line); $i++) {
      if(!preg_match('/^#/',$line[$i])) {
        $param = explode(",",$line[$i]);
        echo "<li><a href='?dir=".$param[1]."&min=".$param[2]."&max=".$param[3]."&rnd=".$param[4]."&curr=".$param[4]."&mode=".$param[5]."'>".$param[0]."</a></li>";
      }
    }
    echo "</ul>";
  }
}
echo "<hr><div id='nav'>";
if ($dir != "") {
  echo "<p class='btn'><a href='?dir=".$dir."&min=".$min."&max=".$max."&rnd=".$rnd."&curr=".$prev."&mode=".$mode."'>&#9664;</a></p>";
}
echo "<p><a href='?mode=credit'>&copy;Takeru-chan, 2018</a></p>";
if ($dir != "") {
  echo "<p class='btn'><a href='?dir=".$dir."&min=".$min."&max=".$max."&rnd=".$rnd."&curr=".$next."&mode=".$mode."'>&#9654;</a></p>";
}
echo "</div>";
?>
</body></html>
