<?php
//実装時はコメント解除

function registrateNotificationDb($content) {
 if( is_page( 'registrateNotificationDb' ))  //固定ページ「sample_cal」の時だ け処理させる
 {


session_start();

if((! empty ($_POST['title']) ) & (! empty ($_POST['body']))){


    include_once dirname( __FILE__ ).'/../db.php';
    // 前のページから値を取得します。
        $inputtitle= $_POST['title'];
        $inputbody= $_POST['body'];

        $_SESSION['registrateNotification'] = '';

    try {
            
            //桁数の確認
            if( strlen($inputtitle) > 32 || strlen($inputbody) > 1024 ){
            $_SESSION['register_exhibition']=strlen($inputOrganizer)."入力文字数を超えています";
            echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://ec2-18-209-25-203.compute-1.amazonaws.com/registrateNotification";</script>';
                exit();
            }
        
            $_SESSION['registrateNotification']="登録完了";

            // SQL文を用意します。
            // :で始まる部分が後から値がセットされるプレースホルダです。
            // 複数回SQL文を実行する必要がある場合はここからexecute()までを 繰り返します。
            $dbh = DbUtil::Connect();
            $sql = 'INSERT INTO notice (title, body)
                    VALUES(:title, :body)';
            // SQL文を実行する準備をします。
            $stmt = $dbh->prepare( $sql );
            // プレースホルダに実際の値をバインドします。
            //   ->bindValue( プレースホルダ名, バインドする値, データの型 )
            $stmt->bindValue( ':title', $inputtitle, PDO::PARAM_STR );
            $stmt->bindValue( ':body', $inputbody, PDO::PARAM_STR );

            // SQL文を実行します。
            $stmt->execute();

            $_SESSION['registrateNotification']="登録完了";

            unset($inputtitle);
            unset($inputbody);
            unset($_POST['title']);
            unset($_POST['body']);

        }catch( PDOException $e ){
            echo( '接続失敗: ' . $e->getMessage() . '<br>' );
            exit();
            }
}else {
    $_SESSION['registrateNotification']="入力に不備があります";
}
echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://ec2-18-209-25-203.compute-1.amazonaws.com/registrateNotification";</script>';
exit();

 }
 else
 {
 return $content;
 }
 }

 add_filter('the_content', 'registrateNotificationDb');
 ?>