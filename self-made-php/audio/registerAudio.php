<?php

function register_audio($content) {
    if( is_page( 'audio/new' ))  
    {

        ?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="Content-Type" content="width=device-width, initial-scale=1.0">
  <title>test</title>
  <style>
    div{
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
<div>
<h1>音声ファイル登録ページ</h1>
  <form action="http://ec2-18-209-25-203.compute-1.amazonaws.com/register_audio_db/" method="post" enctype="multipart/form-data"  accept = "audio/mp3">
    <div><br>
      <input type="text" name="filename" placeholder="ファイル名(.mp3を除く)を入力"> <br>
      <p>音声ファイルを入力</p>
      <input type="file" name="upfile">
    </div>
    <div>
        <?php
            session_start();
            if(! empty($_SESSION['register_audio'])){
                echo("<br>".$_SESSION['register_audio']."<br>");
                unset($_SESSION['register_audio']);
            }else{
                echo("<br><br>");
            }
            ?><br>
      <input type="submit" name = "submit" value="送信">
    </div>
  </form>
</div>
</body>
</html>

<?php

  }
  else
  {
    return $content;
  }
}

add_filter('the_content', 'register_audio');

?>