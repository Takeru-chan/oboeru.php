<!doctype html>
<html lang="ja"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=320px">
<style>
  body{font-family:-apple-system;text-align:center;font-size:18px;}
  h1{font-size:20px;height:50px;line-height:50px}
  *{-webkit-touch-callout:none;-webkit-user-select:none;user-select:none;margin:0;padding:0;}
  ul{list-style:none;}
  li a{line-height:2em;}
  #original{height:180px;overflow:scroll;text-align:left;margin:0 1em;}
  #translate{height:180px;overflow:scroll;color:#fff;text-align:left;margin:0 1em;}
  #translate:active{color:#999;}
  #prev{float:left;margin-left:4em;}
  #next{float:right;margin-right:4em;}
</style>
</head><body>
<h1><a href='./oboeru.php'>おぼえるくん-α</a></h1><hr>
<?php
$dir = $_GET["dir"];
if ($dir != "") {
  $min = $_GET["min"];
  $max = $_GET["max"];
  $rnd = $_GET["rnd"];
  if($rnd < 0) {
    $curr = mt_rand($min,$max);
    $prev = mt_rand($min,$max);
    $next = mt_rand($min,$max);
  } else {
    $curr = $_GET["curr"];
    $curr - 1 < $min ? $prev = $max : $prev = $curr - 1 ;
    $curr + 1 > $max ? $next = $min : $next = $curr + 1 ;
  }
  $mode = $_GET["mode"];
  if($mode == "letter") {
    $style = "style='text-align:center;font-size:64px;line-height:180px'";
  } else {
    $style = "";
  }
  $line = @file(__DIR__ . "/${dir}/data.${curr}", FILE_IGNORE_NEW_LINES);
  echo "<p id='original' ".$style.">".$line[0]."</p>";
  echo "<hr>";
  echo "<p id='translate' ontouchstart='' ".$style.">".$line[1]."</p>";
  echo "<hr>";
  echo "<p id='prev'><a href='?dir=".$dir."&min=".$min."&max=".$max."&rnd=".$rnd."&curr=".$prev."&mode=".$mode."'>Prev</a></p>";
  echo "<p id='next'><a href='?dir=".$dir."&min=".$min."&max=".$max."&rnd=".$rnd."&curr=".$next."&mode=".$mode."'>Next</a></p>";
} else {
  $line = @file(__DIR__ . "/oboeru.list", FILE_IGNORE_NEW_LINES);
  echo "<ul>";
  for ($i=0; $i<count($line); $i++) {
    if(!preg_match('/^#/',$line[$i])) {
      $param = explode(",",$line[$i]);
      echo "<li><a href='?dir=".$param[1]."&min=".$param[2]."&max=".$param[3]."&rnd=".$param[4]."&curr=".$param[4]."&mode=".$param[5]."'>".$param[0]."</a></li>";
    }
  }
  echo "</ul>";
}
?>
</body></html>
