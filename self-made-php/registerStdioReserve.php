<?php
//実装時はコメント解除
//いったん放置
function register_stdio_reserve($content) {
  if( is_page( 'stdio_reserves/new' ))  //固定ページ「sample_cal」の時だけ処理させる
  {


?>
   <!-- 入力フォーム -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>アトリエ予約登録ページ</title>
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
    <h1>アトリエ予約の入力フォーム</h1>

    <?php // echo $content;?>
        <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="POST">

            <br>
            <p>使用日時</p>
                <input type="date" name="start_date" value="<?php echo date('Y-m-d'); ?>">
                <input type="time" name="start_time" step="3600" min="09:00" max="12:00"> から
                <input type="date" name="end_date" value="<?php echo date('Y-m-d'); ?>"> 
                <input type="time" name="end_time" step="3600" min="12:00" max="17:00"> <br><br>
            <p>使用目的</p>
                <input type="text" name="purpose" placeholder="使用目的を入力" maxlength="50"> <br>
            <p>冷暖房の使用有無</p>
                <input type="radio" name="air" value="1">使用する  <input type="radio" name="air" value="2">使用しない  <br><br>
            <p>氏名</p>
                <input type="text" name="name1" placeholder="氏名を入力" maxlength="32"> <br>
            <p>住所</p>
                <input type="text" name="address" placeholder="住所を入力" maxlength="50"> <br>
            <p>携帯電話番号</p>
                <input type="text" name="phone_number" placeholder="携帯電話番号を入力" maxlength="16"> <br>
            
            <?php
            session_start();
            if(! empty($_SESSION['register_stdio_reserve'])){
                echo("<br>".$_SESSION['register_stdio_reserve']."<br>");
                unset($_SESSION['register_stdio_reserve']);
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

    if((! empty ($_POST['start_date']) ) & (! empty ($_POST['start_time'])) & (! empty ($_POST['end_date']) ) & (! empty ($_POST['end_time'])) 
    & (! empty ($_POST['name1'])) & (! empty ($_POST['phone_number'])) &  (! empty ($_POST['address'])) & (! empty ($_POST['purpose'])) & (! empty ($_POST['air']))){


        include_once dirname( __FILE__ ).'/../db.php';
        // 前のページから値を取得します。

            $inputName= $_POST['name1'];
            $inputStartDate=$_POST['start_date'];
            $inputStartTime=$_POST['start_time'];
            $inputEndDate=$_POST['end_date'];
            $inputEndTime=$_POST['end_time'];
            $inputAddress=$_POST['address'];
            $inputPurpose=$_POST['purpose'];
            $inputAir=$_POST['air'];

            $_SESSION['register_stdio_reserve'] = '';

            if( preg_match( '/^0[0-9]{9,10}\z/',  $_POST['phone_number']) ) {
                $inputPhone=(int)$inputPhone;
            }else{
                $_SESSION['register_workshop_reserve']="正しい電話番号をご入力ください";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/stdio_reserve/new/";</script>';
                exit();
            }


        try {            


                // SQL文を用意します。
                // :で始まる部分が後から値がセットされるプレースホルダです。
                // 複数回SQL文を実行する必要がある場合はここからexecute()までを 繰り返します。
                $dbh = DbUtil::Connect();
                $sql = 'INSERT INTO stdio_reservation (name, address, phone_number, start_date, start_time, end_date, end_time,
                purpose, air) VALUES(:name, :address, :phone_number, :start_date, :start_time, :end_date, :end_time,
                :purpose, :air)';
                // SQL文を実行する準備をします。
                $stmt = $dbh->prepare( $sql );
                // プレースホルダに実際の値をバインドします。
                //   ->bindValue( プレースホルダ名, バインドする値, データの型 )
                $stmt->bindValue( ':name', $inputName, PDO::PARAM_STR );
                $stmt->bindValue( ':address', $inputAddress, PDO::PARAM_STR );
                $stmt->bindValue( ':phone_number', $inputPhone, PDO::PARAM_INT );
                $stmt->bindValue( ':start_date', $inputStartDate, PDO::PARAM_STR );
                $stmt->bindValue( ':start_time', $inputStartTime, PDO::PARAM_INT );
                $stmt->bindValue( ':end_date', $inputEndDate, PDO::PARAM_STR );
                $stmt->bindValue( ':end_time', $inputEndTime, PDO::PARAM_INT );
                $stmt->bindValue( ':purpose', $inputPurpose, PDO::PARAM_STR );
                $stmt->bindValue( ':air', $inputAir, PDO::PARAM_INT );

                // SQL文を実行します。
                $stmt->execute();
                $_SESSION['register_stdio_reserve']="登録完了";                

                unset($inputName);
                unset($inputStartDate);
                unset($inputStartTime);
                unset($inputEndDate);
                unset($inputEndTime);
                unset($inputAddress);
                unset($inputPurpose);
                unset($inputAir);
                unset($_POST['name1']);
                unset($_POST['start_date']);
                unset($_POST['start_time']);
                unset($_POST['end_date']);
                unset($_POST['end_time']);
                unset($_POST['address']);
                unset($_POST['purpose']);
                unset($_POST['air']);

            }catch( PDOException $e ){
                echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                exit();
                }
    }else {
        $_SESSION['register_stdio_reserve']="入力に不備があります";
    }
    echo("HELLO");
    echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/stdio_reserve/new/";</script>';
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

add_filter('the_content', 'register_stdio_reserve');

?>
