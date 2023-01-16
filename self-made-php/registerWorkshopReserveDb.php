<?php
//実装時はコメント解除

function register_workshop_reserve_db($content) {
 if( is_page( 'register_workshop_reserve_db' ))  //固定ページ「sample_cal」の時だ け処理させる
 {

    echo("HELLO");

session_start();

if((! empty ($_POST['id']) ) & (! empty ($_POST['name']))  &  (! empty ($_POST['phone_number'])) 
     &  (! empty ($_POST['mail'])) ){


    include_once dirname( __FILE__ ).'/../db.php';
    // 前のページから値を取得します。
        
        $inputId= $_POST['id'];
        $inputName= $_POST['name'];
        $inputPhone=$_POST['phone_number'];
        $inputMail=$_POST['mail'];

        $_SESSION['register_workshop_reserve'] = '';

        if(is_numeric($inputPhone) & is_numeric($inputId)) {
            $inputPhone=(int)$inputPhone;
            $inputId=(int)$inputId;
        }else{
            $_SESSION['register_workshop_reserve']="電話番号は数値でご入力ください";
            echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://ec2-44-212-247-129.compute-1.amazonaws.com/register_workshop_reserve";</script>';
            exit();
        }
    try {            


            // SQL文を用意します。
            // :で始まる部分が後から値がセットされるプレースホルダです。
            // 複数回SQL文を実行する必要がある場合はここからexecute()までを 繰り返します。
            $dbh = DbUtil::Connect();
            $sql = 'INSERT INTO workshop (workshop_id, name, phone_number, mail)
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
            unset($_POST['name']);
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
echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://ec2-44-212-247-129.compute-1.amazonaws.com/register_workshop_reserve/";</script>';
exit();
 //実装時はコメント解除

 }
 else
 {
 return $content;
 }
 }

 add_filter('the_content', 'register_workshop_reserve_db');
 ?>