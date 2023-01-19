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
    
     input[type=number]{
        width:80px;
        height:30px;
    }

    input[name=name1]{
        width:230px;
        height:30px;
    }

    input[name=phone_number]{
        width:230px;
        height:30px;
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
                <input type="date" name="start_date" value="<?php echo $_SESSION['start_date']; ?>" min="<?php echo date('Y-m-d'); ?>" style = "display:inline-block">
                <input type="number" name="start_time" min="9" max="12" value="<?php echo $_SESSION['start_time']; ?>" style = "display:inline-block"> 時から
                <input type="date" name="end_date" value="<?php echo $_SESSION['end_date']; ?>" min="<?php echo date('Y-m-d'); ?>" style = "display:inline-block"> 
                <input type="number" name="end_time" min="12" max="17" value="<?php echo $_SESSION['end_time']; ?>" style = "display:inline-block">時まで <br><br>
            <p>使用目的</p>
                <input type="text" name="purpose" placeholder="使用目的を入力" value="<?php echo $_SESSION['purpose']; ?>"> maxlength="50"> <br>
            <p>冷暖房の使用有無</p>
                <input type="radio" name="air" value="1">使用する  <input type="radio" name="air" value="2">使用しない  <br><br>
            <p>氏名</p>
                <input type="text" name="name1" placeholder="氏名を入力" maxlength="32" value="<?php echo $_SESSION['start_date']; ?>"> <br>
            <p>住所</p>
                <input type="text" name="address" placeholder="住所を入力" maxlength="50" value="<?php echo $_SESSION['start_time']; ?>"> <br>
            <p>携帯電話番号</p>
                <input type="text" name="phone_number" placeholder="00012341234" maxlength="16" value="<?php echo $_SESSION['phone_number']; ?>"> <br>
            
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

            $_SESSION['name1']= $_POST['name1'];
            $_SESSION['address']=$_POST['address'];
            $_SESSION['purpose']=$_POST['purpose'];
            $_SESSION['start_date']= $_POST['start_date'];
            $_SESSION['start_time']=$_POST['start_time'];
            $_SESSION['end_date']= $_POST['end_date'];
            $_SESSION['end_time']=$_POST['end_time'];

            $inputName= $_POST['name1'];
            $inputAddress=$_POST['address'];
            $inputPurpose=$_POST['purpose'];
            $inputAir=$_POST['air'];

            $_SESSION['register_stdio_reserve'] = '';

            if( is_numeric($_POST['phone_number']) ) {
                $inputPhone=$_POST['phone_number'];
            }else{
                $_SESSION['register_stdio_reserve']="正しい電話番号をご入力ください";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/stdio_reserves/new/";</script>';
                exit();
            }

            //日付判定
            if($_POST['start_date'] > $_POST['end_date']){
                $_SESSION['register_stdio_reserve']="使用する日時を正しくご入力ください";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/stdio_reserves/new/";</script>';
                exit();
            }else if(($_POST['start_date'] == $_POST['end_date']) && ($_POST['start_time'] >= $_POST['end_time'])){
                $_SESSION['register_stdio_reserve']="使用する日時を正しくご入力ください";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/stdio_reserves/new/";</script>';
                exit();
            }else{
                if(($_POST['start_time'] != 9) && (($_POST['start_time'] != 12))){
                    $_SESSION['register_stdio_reserve']="使用を開始する時刻を正しくご入力ください";
                    echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/stdio_reserves/new/";</script>';
                    exit();
                }else if(($_POST['end_time'] != 12) && (($_POST['end_time'] != 17))){
                    $_SESSION['register_stdio_reserve']="使用を終了する時刻を正しくご入力ください";
                    echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/stdio_reserves/new/";</script>';
                    exit();
                }else{
                    $inputStartDate=$_POST['start_date'];
                    $inputStartTime=$_POST['start_time'];
                    $inputEndDate=$_POST['end_date'];
                    $inputEndTime=$_POST['end_time'];
                }
            }

        try {            


                // SQL文を用意します。
                // :で始まる部分が後から値がセットされるプレースホルダです。
                // 複数回SQL文を実行する必要がある場合はここからexecute()までを 繰り返します。
                $dbh = DbUtil::Connect();
                $sql = 'INSERT INTO stdio_reserve (name, address, phone_number, start_date, start_time, end_date, end_time,
                purpose, air) VALUES(:name, :address, :phone_number, :start_date, :start_time, :end_date, :end_time,
                :purpose, :air)';
                // SQL文を実行する準備をします。
                $stmt = $dbh->prepare( $sql );
                // プレースホルダに実際の値をバインドします。
                //   ->bindValue( プレースホルダ名, バインドする値, データの型 )
                $stmt->bindValue( ':name', $inputName, PDO::PARAM_STR );
                $stmt->bindValue( ':address', $inputAddress, PDO::PARAM_STR );
                $stmt->bindValue( ':phone_number', $inputPhone, PDO::PARAM_STR );
                $stmt->bindValue( ':start_date', $inputStartDate, PDO::PARAM_STR );
                $stmt->bindValue( ':start_time', $inputStartTime, PDO::PARAM_INT );
                $stmt->bindValue( ':end_date', $inputEndDate, PDO::PARAM_STR );
                $stmt->bindValue( ':end_time', $inputEndTime, PDO::PARAM_INT );
                $stmt->bindValue( ':purpose', $inputPurpose, PDO::PARAM_STR );
                $stmt->bindValue( ':air', $inputAir, PDO::PARAM_INT );

                // SQL文を実行します。
                $stmt->execute();
                $_SESSION['register_stdio_reserve']=$inputPhone."登録完了";                

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
                unset($_SESSION['name1']);
                unset($_SESSION['address']);
                unset($_SESSION['purpose']);
                unset($_SESSION['start_date']);
                unset($_SESSION['start_time']);
                unset($_SESSION['end_date']);
                unset($_SESSION['end_time']);

            }catch( PDOException $e ){
                echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                exit();
                }
    }else {
        $_SESSION['register_stdio_reserve']="入力に不備があります";
    }
    echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/stdio_reserves/new/";</script>';
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
