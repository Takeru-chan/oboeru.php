<!doctype html>
<html lang="ja"><head>
<meta charset="utf-8">
<meta name="apple-mobile-web-app-capable" content="yes">
<style>
  body{text-align:center;font-size:64px;}
  h1{font-size:96px;}
  *{-webkit-touch-callout:none;-webkit-user-select:none;user-select:none;}
  ul{list-style:none;}
  #original{height:5em;text-align:left;margin:0 1em;}
  #translate{height:5em;color:#fff;text-align:left;margin:0 1em;}
  #translate:active{color:#999;}
  #prev{float:left;margin-left:4em;}
  #next{float:right;margin-right:4em;}
</style>
</head><body>
<h1><a href='./oboeru.php'>おぼえるくん-α</a></h1>
<?php
$rnd = "no:";
$dir = $_GET["dir"];
if ($dir != "") {
  $min = $_GET["min"];
  $max = $_GET["max"];
  $curr = $_GET["curr"];
	if ($curr < 0) {$rnd = "yes";}
	if ($rnd == "no") {
		$curr - 1 < $min ? $prev = $max : $prev = $curr - 1 ;
		$curr + 1 > $max ? $next = $min : $next = $curr + 1 ;
	} else {
		$curr = mt_rand($min,$max);
		$prev = mt_rand($min,$max);
		$next = mt_rand($min,$max);
	}
  $line = @file(__DIR__ . "/${dir}/data.${curr}", FILE_IGNORE_NEW_LINES);
  echo "<p id='original'>".$line[0]."</p>";
  echo "<hr>";
  echo "<p id='translate' ontouchstart=''>".$line[1]."</p>";
  echo "<hr>";
  echo "<p id='prev'><a href='?dir=".$dir."&min=".$min."&max=".$max."&curr=".$prev."'>Prev</a></p>";
  echo "<p id='next'><a href='?dir=".$dir."&min=".$min."&max=".$max."&curr=".$next."'>Next</a></p>";
} else {
  $line = @file(__DIR__ . "/oboeru.list", FILE_IGNORE_NEW_LINES);
  echo "<ul>";
  for ($i=0; $i<count($line); $i++) {
    $param = explode(",",$line[$i]);
    echo "<li><a href='?dir=".$param[1]."&min=".$param[2]."&max=".$param[3]."&curr=".$param[4]."'>".$param[0]."</a></li>";
  }
  echo "</ul>";
}
?>
</body></html>