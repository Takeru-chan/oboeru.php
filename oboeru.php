<!doctype html>
<html lang="ja"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=320px">
<?php
$viewheight = 360; // メインビューの高さはここのみで指定。その他は自動計算
$halfheight = $viewheight / 2;
$mode = $_GET["mode"];
if($mode == "letter") {
  $lettermode = "text-align:center;font-size:64px;line-height:".$halfheight."px;";
} else {
  $lettermode = "";
}
$stylesheet = <<< EOM
<style>
  body{font-family:-apple-system;text-align:center;font-size:18px;}
  h1{font-size:20px;height:45px;line-height:45px}
  *{-webkit-touch-callout:none;-webkit-user-select:none;user-select:none;margin:0;padding:0;}
  ul{list-style:none;}
  li a{line-height:2em;}
  .mainview{overflow:scroll;height:{$viewheight}px;}
  .mainview p{margin:1em;}
  #original{overflow:scroll;text-align:left;margin:0 1em;height:{$halfheight}px;{$lettermode}}
  #translate{overflow:scroll;color:#fff;text-align:left;margin:0 1em;height:{$halfheight}px;{$lettermode}}
  #translate:active{color:#999;}
  #nav{line-height:45px;display:flex;justify-content:space-around;align-items:center;}
  .btn a{text-decoration:none;background:lightgray;border-radius:18px;padding:5px 9px;color:gray;text-shadow:1px 1px 0 white;}
</style>
EOM;
echo $stylesheet;
?>
</head><body>
<h1 class='btn'><a href='./oboeru.php'>おぼえるくん-α</a></h1><hr>
<?php
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
  $line = @file(__DIR__ . "/${dir}/data.${curr}", FILE_IGNORE_NEW_LINES);
  echo "<p id='original'>".$line[0]."</p>";
  echo "<hr>";
	$audiofile = $dir."/".$curr.".m4a";
	if (file_exists($audiofile)) {
	  echo "<p id='translate' ontouchstart=''><audio src='".$audiofile."' controls></audio><br>".$line[1]."</p>";
	} else {
		echo "<p id='translate' ontouchstart=''>".$line[1]."</p>";
	}
} else {
  if($mode == 'credit') {
    echo "<div class='mainview'><p>単語/短文帳 Webアプリ<br>Version α</p>";
    echo "<p>License<br>This application has released under <a href='http://opensource.org/licenses/MIT'>the MIT license</a>.</p></div>";
  } else {
    $line = @file(__DIR__ . "/oboeru.list", FILE_IGNORE_NEW_LINES);
    echo "<ul class='mainview'>";
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
