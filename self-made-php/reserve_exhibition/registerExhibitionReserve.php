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
                <input type="text" name="name" class="inrow form-control" placeholder="香美太郎" maxlength="32" value="<?php echo $_SESSION['name']; ?>"> <br>
            <p>フリガナ:(必須)</p>
                <input type="text" name="name1" class="inrow form-control" placeholder="カミタロウ" maxlength="32" value="<?php echo $_SESSION['name1']; ?>"> <br>
            <hr>
            <p>メールアドレス:(必須)</p>
                <input type="text" name="mail" class="inrow form-control" placeholder="test@kami.museum.jp" maxlength="50" value="<?php echo $_SESSION['mail']; ?>"> <br>
            <p>電話番号:(必須,ハイフンを抜いて入力してください)</p>
                <input type="text" name="phone_number" class="inrow form-control" placeholder="1234567890" maxlength="16"> <br>
            <hr>
            <p>展覧会名:(必須)</p>
                <input type="text" name="exhibition_name" class="inrow form-control" placeholder="愛と勇気展 ~胸の傷が傷んでも~" value="<?php echo $_SESSION['exhibition_name']; ?>" maxlength="50"> <br>
            <p>展覧内容:(必須,展示物のジャンルや概要の記入をお願いします)</p>
                <input type="textarea" name="exhibition_body" class="inrow form-control" value="<?php echo $_SESSION['exhibition_name']; ?>" maxlength="300"> <br>
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
            <br>
            <!-- <input type="submit" name = "submit1" value="キャンセル">      -->
        </form>

<?php

if(isset($_POST["submit"])){
    session_start();

    $_SESSION['name']= $_POST['name'];
    $_SESSION['name1']= $_POST['name1'];
    $_SESSION['mail']= $_POST['mail'];
    $_SESSION['phone_number']= $_POST['phone_number'];
    $_SESSION['exhibition_name']=$_POST['exhibition_name'];
    $_SESSION['exhibition_body']=$_POST['exhibition_body'];

    if((! empty ($_POST['start_date']) ) & (! empty ($_POST['end_date']) ) & (! empty ($_POST['name'])) & (! empty ($_POST['name1'])) &  (! empty ($_POST['mail'])) & (! empty ($_POST['phone_number']))
    &  (! empty ($_POST['exhibition_name'])) & (! empty ($_POST['exhibition_body']))){


        include_once dirname( __FILE__ ).'/../../db.php';
        // 前のページから値を取得します。

            $inputName= $_POST['name'];
            $inputName1['name1']= $_POST['name1'];
            $inputAddress['mail']= $_POST['mail'];
            $inputNumber['phone_number']= $_POST['phone_number'];
            $inputTitle['exhibition_name']=$_POST['exhibition_name'];
            $inputBody['exhibition_body']=$_POST['exhibition_body'];

            $_SESSION['register_exhibition_reserve'] = '';

            if( is_numeric($_POST['phone_number']) ) {
                $inputNumber=$_POST['phone_number'];
            }else{
                $_SESSION['register_exhibition_reserve']="正しい電話番号をご入力ください";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "";</script>';
                exit();
            }

            //日付判定
            if($_POST['start_date'] > $_POST['end_date']){
                $_SESSION['register_exhibition_reserve']="使用する日時を正しくご入力ください";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "";</script>';
                exit();
            }else{
                $inputStartDate=$_POST['start_date'];
                $inputEndDate=$_POST['end_data'];
            }

        try {            
                // SQL文を用意します。
                // :で始まる部分が後から値がセットされるプレースホルダです。
                // 複数回SQL文を実行する必要がある場合はここからexecute()までを 繰り返します。
                $dbh = DbUtil::Connect();
                $sql = 'INSERT INTO exhibition_reserve (name, name1, mail, number, exhibition_name, body, start_date, end_date) VALUES(:name, :name1, :mail, :phone_number, :exhibition_name, exhibition_body, :start_date, :end_date)';
                // SQL文を実行する準備をします。
                $stmt = $dbh->prepare( $sql );
                // プレースホルダに実際の値をバインドします。
                //   ->bindValue( プレースホルダ名, バインドする値, データの型 )
                $stmt->bindValue( ':name', $inputName, PDO::PARAM_STR );
                $stmt->bindValue( ':name1', $inputName1, PDO::PARAM_STR );
                $stmt->bindValue( ':mail', $inputAddress, PDO::PARAM_STR );
                $stmt->bindValue( ':phone_number', $inputNumber, PDO::PARAM_INT );
                $stmt->bindValue( ':exhibition_name', $inputTitle, PDO::PARAM_STR );
                $stmt->bindValue( ':exhibition_body', $inputBody, PDO::PARAM_STR );
                $stmt->bindValue( ':start_date', $inputStartDate, PDO::PARAM_STR );
                $stmt->bindValue( ':end_data', $inputEndDate, PDO::PARAM_STR );

                // SQL文を実行します。
                $stmt->execute();
                $_SESSION['register_exhibition_reserve']="登録完了";                

                unset($inputName);
                unset($inputName1);
                unset($inputAddress);
                unset($inputNumber);
                unset($inputTitle);
                unset($inputBody);
                unset($inputStartDate);
                unset($inputEndDate);
                unset($_POST['name']);
                unset($_POST['name1']);
                unset($_POST['mail']);
                unset($_POST['phone_number']);
                unset($_POST['exhibition_name']);
                unset($_POST['exhibition_body']);
                unset($_POST['start_data']);
                unset($_POST['end_data']);
                unset($_SESSION['name']);
                unset($_SESSION['name1']);
                unset($_SESSION['mail']);
                unset($_SESSION['phone_number']);
                unset($_SESSION['exhibition_name']);
                unset($_SESSION['exhibition_body']);               

            }catch( PDOException $e ){
                echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                exit();
                }
    }else {
        $_SESSION['register_exhibition_reserve']="入力に不備があります";
    }
    echo '<script type="text/javascript">window.location.href = window.location.hreg = "";</script>';
    exit();

}

?>


<?php

  }
  else
  {
    return $content;
  }
}

add_filter('the_content', 'register_exhibition_reserve');

?>
