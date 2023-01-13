<?php
//実装時はコメント解除

function register_exhibition($content) {
  if( is_page( 'register_exhibition' ))  //固定ページ「sample_cal」の時だけ処理させる
  {


?>
   <!-- 入力フォーム -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>企画展登録ページ</title>
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
        <div class='c'>
    <h1>企画展情報の入力</h1>
        <form action="http://ec2-44-212-247-129.compute-1.amazonaws.com/register_exhibition_db/" method="POST">

            <br><br><br>
                <input type="text" name="exhibition_name" placeholder="企画 展名を入力">
            <br>
                <div class='l'>
            <p>開始日</p>
            <input type="date" name="start" value="<?php echo date('Y-m-d'); ?>">
            <br>
         <p>終了日</p>
            <input type="date" name="end" value="<?php echo date('Y-m-d'); ?>">
            <br><br>
                </div>
            <br>
            <input type="text" name="organizer" placeholder="主催者名を入力">

            <?php
            session_start();
            if(! empty($_SESSION['register_exhibition'])){
                echo("<br>".$_SESSION['register_exhibition']."<br>");
                unset($_SESSION['register_exhibition']);
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

add_filter('the_content', 'register_exhibition');

?>