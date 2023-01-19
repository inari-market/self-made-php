<?php
//実装時はコメント解除
//いったん放置
function register_workshop_reserve($content) {
  if( is_page( 'workshop_reserves/new' ))  //固定ページ「sample_cal」の時だけ処理させる
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
        <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="POST">

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
            <input type="submit" name = "submit" value="登録">
        </form>
        </div>

    </body>
</html>

<?php
if(isset($_POST["submit"])){
    session_start();

    if((! empty ($_POST['id']) ) & (! empty ($_POST['name1'])) & (! empty ($_POST['phone_number'])) 
        &  (! empty ($_POST['mail'])) ){


        include_once dirname( __FILE__ ).'/../db.php';
        // 前のページから値を取得します。

            $inputName= $_POST['name1'];

            $_SESSION['register_workshop_reserve'] = '';


            if(is_numeric($_POST['id']) & is_numeric($_POST['phone_number'])) {
                $inputId=(int)$inputId;
            }else{
                $_SESSION['register_workshop_reserve']="入力が正しくない場合があります";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/workshop_reserves/new/";</script>';
                exit();
            }

            if( preg_match("/\A0((\d{1}[-(]?\d{4}|\d{2}[-(]?\d{3}|\d{3}[-(]?\d{2}|\d{4}[-(]?\d{1}|[5789]0[-(]?\d{4}|800[-(]?\d{3})[-)]?\d{4}|(12|99|18|57)0[-(]?\d{3}[-)]?\d{3})\z/",  $_POST['phone_number']) ) {
                $inputPhone=(int)$inputPhone;
            }else{
                $_SESSION['register_workshop_reserve']="正しい電話番号をご入力ください";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/workshop_reserves/new/";</script>';
                exit();
            }

            if (preg_match('/^[a-z0-9._+^~-]+@[a-z0-9.-]+$/i', $_POST['mail'])) {
                $inputMail=$_POST['mail'];
            } else{
                $_SESSION['register_workshop_reserve']="正しいメールアドレスをご入力ください";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/workshop_reserves/new/";</script>';
                exit();
            }

        try {            


                // SQL文を用意します。
                // :で始まる部分が後から値がセットされるプレースホルダです。
                // 複数回SQL文を実行する必要がある場合はここからexecute()までを 繰り返します。
                $dbh = DbUtil::Connect();
                $sql = 'INSERT INTO workshop_reserve (workshop_id, name, phone_number, mail)
                        VALUES(:workshop_id, :name, :phone_number, :mail)';
                // SQL文を実行する準備をします。
                $stmt = $dbh->prepare( $sql );
                // プレースホルダに実際の値をバインドします。
                //   ->bindValue( プレースホルダ名, バインドする値, データの型 )
                $stmt->bindValue( ':workshop_id', $inputId, PDO::PARAM_INT );
                $stmt->bindValue( ':name', $inputName, PDO::PARAM_STR );
                $stmt->bindValue( ':phone_number', $inputPhone, PDO::PARAM_INT );
                $stmt->bindValue( ':mail', $inputMail, PDO::PARAM_STR );

                // SQL文を実行します。
                $stmt->execute();
                $_SESSION['register_workshop_reserve']="登録完了";                

                unset($inputName);
                unset($inputPhone);
                unset($inputId);
                unset($inputMail);
                unset($_POST['name1']);
                unset($_POST['phone_number']);
                unset($_POST['id']);
                unset($_POST['mail']);

            }catch( PDOException $e ){
                echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                exit();
                }
    }else {
        $_SESSION['register_workshop_reserve']="入力に不備があります";
    }

    echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/workshop_reserves/new/";</script>';
    exit();

}

?>


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
