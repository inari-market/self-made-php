<?php
//実装時はコメント解除
//いったん放置
function register_workshop_reserve($content) {
  if( is_page( 'register_workshop_reserve' ))  //固定ページ「sample_cal」の時だけ処理させる
  {


?>
   <!-- 入力フォーム -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ワークショップ予約登録ページ</title>
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
    <h1>ワークショップ予約の入力フォーム</h1>
        <form action="http://ec2-44-212-247-129.compute-1.amazonaws.com/register_workshop_reserve_db/" method="POST">

            <br>
            <p>ワークショップ名</p>
                <input type="text" name="exhibition_name" placeholder="企画展名を入力" maxlength="64"> <br>
            <p>お名前</p>
                <input type="text" name="name" placeholder="主催者名を入力" maxlength="50"> <br>
            <p>携帯電話番号</p>
                <input type="text" name="phone_number" placeholder="主催者名を入力" maxlength="50"> <br>
            <p>メールアドレス</p>
                <input type="text" name="mail" placeholder="主催者名を入力" maxlength="50"> <br>
            
            <?php
            session_start();
            if(! empty($_SESSION['register_workshop_reserve'])){
                echo("<br>".$_SESSION['register_workshop_reserve']."<br>");
                unset($_SESSION['register_workshop_reserve']);
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
//実装時はコメント解除

  }
  else
  {
    return $content;
  }
}

add_filter('the_content', 'register_workshop_reserve');

?>