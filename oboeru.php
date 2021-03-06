<?php
$viewheight = 350; // メインビューの高さはここのみで指定。その他は自動計算
$halfheight = $viewheight / 2;
$mode = $_GET["mode"];
$lettermode = $mode == "letter" ? "text-align:center;font-size:64px;line-height:".$halfheight."px;" : "";
$stylesheet = <<< EOM
<style>
  body{font-family:-apple-system;text-align:center;font-size:18px;width:320px;margin:auto;}
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
?>
<!doctype html>
<html lang="ja"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=320px">
<?php echo $stylesheet; ?>
</head><body>
<h1 class='btn'><a href='./oboeru.php'>おぼえるくん-α</a></h1><hr>
<?php
$dir = $_GET["dir"];
if ($dir != "") {
  foreach(glob($dir."/data.*") as $file) {
    $ext = explode('.',$file)[1];
    if (is_numeric($ext)) {$result[] = $ext;}
  }
  $min = $_GET["min"] != "" ? $_GET["min"] : min($result);
  $max = $_GET["max"] != "" ? $_GET["max"] : max($result);
  $rnd = $_GET["rnd"];
  if($rnd < 0) {
    $curr = mt_rand($min,$max);
    $prev = mt_rand($min,$max);
    $next = mt_rand($min,$max);
  } else {
    $curr = $_GET["curr"] != "" ? $_GET["curr"] : $min;
    $prev = $curr - 1 < $min ? $max : $curr - 1 ;
    $next = $curr + 1 > $max ? $min : $curr + 1 ;
  }
  $line = @file(__DIR__ . "/${dir}/data.${curr}", FILE_IGNORE_NEW_LINES);
  echo "<p id='original'>".$line[0]."</p><hr>";
  $audiofile = $dir."/".$curr.".m4a";
  if (file_exists($audiofile)) {
    $audiotag = "<audio src='".$audiofile."' controls></audio><br>";
  }
  echo "<p id='translate' ontouchstart=''>".$audiotag.$line[1]."</p>";
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
  $prevbtn = "<p class='btn'><a href='?dir=".$dir."&min=".$min."&max=".$max."&rnd=".$rnd."&curr=".$prev."&mode=".$mode."'>&#9664;</a></p>";
  $nextbtn = "<p class='btn'><a href='?dir=".$dir."&min=".$min."&max=".$max."&rnd=".$rnd."&curr=".$next."&mode=".$mode."'>&#9654;</a></p>";
}
echo $prevbtn."<p><a href='?mode=credit'>&copy;Takeru-chan, 2018</a></p>".$nextbtn."</div>";
?>
</body></html>
