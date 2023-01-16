<?php
//実装時はコメント解除
//いったん放置
function register_workshop($content) {
  if( is_page( 'register_workshop' ))  //固定ページ「sample_cal」の時だけ処理させる
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
        <form action="http://ec2-44-212-247-129.compute-1.amazonaws.com/register_workshop_db/" method="POST">

            <br>
            <p>ワークショップ名</p>
                <input type="text" name="workshop_name" placeholder="ワークショップ名を入力" maxlength="64"> <br>
            <p>主催者</p>
                <input type="text" name="organizer" placeholder="主催者名を入力" maxlength="32"> <br>
            <p>概要</p> 
                <textarea  name="introduction" cols="40" maxlength="1024" placeholder="ワークショップの概要を入力"></textarea> <br>
            <p>参加可能人数</p>
                <input type="text" name="capacity" placeholder="参加可能人数を入力" maxlength="11"> <br>
            <p>一人あたりの参加料金</p>
                <input type="text" name="cost" placeholder="一人あたりの参加料金を入力" maxlength="11"> <br>
            <p>開始日</p>
                <input type="date" name="start" value="<?php echo date('Y-m-d'); ?>"> <br><br>
            <p>終了日</p>
                <input type="date" name="end" value="<?php echo date('Y-m-d'); ?>"> <br><br>  
            <p>予約締切日</p>
                <input type="date" name="deadline" value="<?php echo date('Y-m-d'); ?>"> <br><br>           
            
            <?php
            session_start();
            if(! empty($_SESSION['register_workshop'])){
                echo("<br>".$_SESSION['register_workshop']."<br>");
                unset($_SESSION['register_workshop']);
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

add_filter('the_content', 'register_workshop');

?>