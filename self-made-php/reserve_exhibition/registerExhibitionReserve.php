<?php

function register_exhibition_reserve($content) {
  if( is_page( 'exhibition_reserves/new' ))
  {


?>
   <!-- 入力フォーム -->

    <?php // echo $content;?>
        <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="POST">

            <br>
            <hr>
            <p>氏名:(必須)</p>
                <input type="text" name="name" placeholder="香美太郎" maxlength="32" value="<?php echo $_SESSION['name']; ?>"> <br>
            <p>フリガナ:(必須)</p>
                <input type="text" name="name1" placeholder="カミタロウ" maxlength="32" value="<?php echo $_SESSION['name1']; ?>"> <br>
            <hr>
            <p>メールアドレス:(必須)</p>
                <input type="text" name="mail" placeholder="メールアドレスを入力" maxlength="50" value="<?php echo $_SESSION['mail']; ?>"> <br>
            <p>電話番号:(必須,ハイフンを抜いて入力してください)</p>
                <input type="text" name="phone_number" placeholder="1234567890" maxlength="16"> <br>
            <hr>
            <p>展覧会名:(必須)</p>
                <input type="text" name="exhibition_name" placeholder="愛と勇気展 ~胸の傷が傷んでも~" value="<?php echo $_SESSION['exhibition_name']; ?>" maxlength="50"> <br>
            <p>展覧内容:(必須,展示物のジャンルや概要の記入をお願いします)</p>
                <input type="text" name="exhibition_name" value="<?php echo $_SESSION['exhibition_name']; ?>" maxlength="300"> <br>
            <p>利用期間:(必須)</p>
                <input type="date" name="start_date"  min="<?php echo date('Y-m-d'); ?>" style = "display:inline-block">から
                <input type="date" name="end_date"  min="<?php echo date('Y-m-d'); ?>" style = "display:inline-block"> まで
            <hr>
            <?php
            session_start();
            if(! empty($_SESSION['register_exhibition_reserve'])){
                echo("<br>".$_SESSION['register_exhibition_reserve']."<br>");
                unset($_SESSION['register_exhibition_reserve']);
            }else{
                echo("<br><br>");
            }
            ?>

            <br>
            <input type="submit" name = "submit" value="予約する">
            <br>
            <input type="submit" name = "submit1" value="キャンセル">           
        </form>

<?php

if(isset($_POST["submit"])){
    session_start();

    $_SESSION['exhibition_name']= $_POST['exhibition_name'];
    $_SESSION['target']=$_POST['target'];
    $_SESSION['genru']=$_POST['genru'];
    $_SESSION['name1']= $_POST['name1'];
    $_SESSION['address']=$_POST['address'];

    if((! empty ($_POST['start_date']) ) & (! empty ($_POST['start_time'])) & (! empty ($_POST['end_date']) ) & (! empty ($_POST['end_time'])) 
    & (! empty ($_POST['name1'])) & (! empty ($_POST['phone_number'])) &  (! empty ($_POST['address'])) & (! empty ($_POST['money']))
    &  (! empty ($_POST['exhibition_name'])) & (! empty ($_POST['target'])) & (! empty ($_POST['genru']))){


        include_once dirname( __FILE__ ).'/../../db.php';
        // 前のページから値を取得します。

            $inputExhibitionName= $_POST['exhibition_name'];
            $inputTarget=$_POST['target'];
            $inputGenru=$_POST['genru'];
            $inputName= $_POST['name1'];
            $inputAddress=$_POST['address'];
            $inputMoney=$_POST['money'];

            $_SESSION['register_exhibition_reserve'] = '';

            if( is_numeric($_POST['phone_number']) ) {
                $inputPhone=$_POST['phone_number'];
            }else{
                $_SESSION['register_exhibition_reserve']="正しい電話番号をご入力ください";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/exhibition_reserves/new/";</script>';
                exit();
            }

            //日付判定
            if($_POST['start_date'] > $_POST['end_date']){
                $_SESSION['register_exhibition_reserve']="使用する日時を正しくご入力ください";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/exhibition_reserves/new/";</script>';
                exit();
            }else if(($_POST['start_date'] == $_POST['end_date']) && ($_POST['start_time'] >= $_POST['end_time'])){
                $_SESSION['register_exhibition_reserve']="使用する日時を正しくご入力ください";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/exhibition_reserves/new/";</script>';
                exit();
            }else{
                    $inputStartDate=$_POST['start_date'];
                    $inputStartTime=$_POST['start_time'];
                    $inputEndDate=$_POST['end_date'];
                    $inputEndTime=$_POST['end_time'];
            }

        try {            


                // SQL文を用意します。
                // :で始まる部分が後から値がセットされるプレースホルダです。
                // 複数回SQL文を実行する必要がある場合はここからexecute()までを 繰り返します。
                $dbh = DbUtil::Connect();
                $sql = 'INSERT INTO exhibition_reserve (name, address, phone_number, start_date, start_time, end_date, end_time,
                exhibition_name, target, genru, money) VALUES(:name, :address, :phone_number, :start_date, :start_time, :end_date, :end_time,
                :exhibition_name, :target, :genru, :money)';
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
                $stmt->bindValue( ':exhibition_name', $inputExhibitionName, PDO::PARAM_STR );
                $stmt->bindValue( ':target', $inputTarget, PDO::PARAM_STR );
                $stmt->bindValue( ':genru', $inputGenru, PDO::PARAM_STR );
                $stmt->bindValue( ':money', $inputMoney, PDO::PARAM_INT );

                // SQL文を実行します。
                $stmt->execute();
                $_SESSION['register_exhibition_reserve']="登録完了";                

                unset($inputName);
                unset($inputStartDate);
                unset($inputStartTime);
                unset($inputEndDate);
                unset($inputEndTime);
                unset($inputAddress);
                unset($inputPurpose);
                unset($inputExhibitionName);
                unset($inputGenru);
                unset($inputTarget);
                unset($inputMoney);
                unset($_POST['name1']);
                unset($_POST['start_date']);
                unset($_POST['start_time']);
                unset($_POST['end_date']);
                unset($_POST['end_time']);
                unset($_POST['address']);
                unset($_POST['purpose']);
                unset($_POST['money']);
                unset($_SESSION['name1']);
                unset($_SESSION['address']);
                unset($_SESSION['exhibition_name']);
                unset($_SESSION['target']);
                unset($_SESSION['genru']);

            }catch( PDOException $e ){
                echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                exit();
                }
    }else {
        $_SESSION['register_exhibition_reserve']="入力に不備があります";
    }
    echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/exhibition_reserves/new/";</script>';
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

add_filter('the_content', 'register_exhibition_reserve');

?>
