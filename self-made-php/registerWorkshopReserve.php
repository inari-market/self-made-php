<?php
//実装時はコメント解除
//いったん放置
function register_workshop_reserve($content) {
  if( is_page( 'workshopReserves/new' ))  //固定ページ「sample_cal」の時だけ処理させる
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
        <form action="http://ec2-44-212-247-129.compute-1.amazonaws.com/workshopReserves/new" method="POST">

            <br>
            <p>ワークショップの選択</p>
            
            <?php
            //ワークショップテーブルを参照に予約できるものをラジオボタンで表示
            include_once dirname( __FILE__ ).'/../db.php';
            $dbh = DbUtil::Connect();
            $sql = 'SELECT * FROM workshop where deadline >= now() and capacity > (select count(*) from workshop_reserve where
            workshop.workshop_id = workshop_reserve.workshop_id)';
            // SQL文を実行する準備をします。
            $stmt = $dbh->prepare( $sql );
            // SQL文を実行します。
            $stmt->execute();
            ?>

        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
            <input type="radio" name="id" value=<?php echo($row['workshop_id']); ?>> 
            <?php echo($row['workshop_name']); ?> <br>
    <?php } ?>
            <br>
            <p>氏名</p>
                <input type="text" name="name1" placeholder="氏名を入力" maxlength="16"> <br>
            <p>携帯電話番号</p>
                <input type="text" name="phone_number" placeholder="携帯電話番号を入力" maxlength="16"> <br>
            <p>メールアドレス</p>
                <input type="text" name="mail" placeholder="メールアドレスを入力" maxlength="50"> <br>
            
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
