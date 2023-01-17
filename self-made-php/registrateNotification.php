<?php

function registrateNotification($content) {
  if( is_page( 'registrateNotification' ))  //固定ページ「sample_cal」の時だけ処理させる
  {

?>

<!-- 入力フォーム -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>お知らせ情報登録ページ</title>
        <style type = "text/css">
    <!--
    .c{
        text-align:center;
    }
    .l{
        text-align:left;
}
    .pos{
        position:absolute; bottom:0%; right:0%;
     }
    -->
    </style>
    </head>
    <body>
        <div class='l'>
    <h1>お知らせ情報の入力</h1>
        <form action="http://ec2-18-209-25-203.compute-1.amazonaws.com/registrateNotificationDb/" method="POST">

            <br>
            <p>タイトル</p>
                <input type="text" name="title" placeholder="タイトルを入力" maxlength="32"> <br>
            <p>内容</p> 
                <textarea  name="body" rows="4" cols="40" maxlength="1024" placeholder="内容を入力"></textarea>
            <?php
            session_start();
            if(! empty($_SESSION['registrateNotification'])){
                echo("<br>".$_SESSION['registrateNotification']."<br>");
                unset($_SESSION['registrateNotification']);
            }else{
                echo("<br><br>");
            }
            ?>

            <br>
            <input type="submit" value="登録">
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

add_filter('the_content', 'registrateNotification');

?>