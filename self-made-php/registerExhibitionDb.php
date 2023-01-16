<?php
//実装時はコメント解除

function register_exhibition_db($content) {
 if( is_page( 'register_exbition_db' ))  //固定ページ「sample_cal」の時だ け処理させる
 {


session_start();

if((! empty ($_POST['exhibition_name']) ) & (! empty ($_POST['start']))  &  (! empty ($_POST['end']))  &  (! empty ($_POST['introduction']))){


    include_once dirname( __FILE__ ).'/../db.php';
    // 前のページから値を取得します。
        $inputName= $_POST['exhibition_name'];
        $inputStart= $_POST['start'];
        $inputEnd= $_POST['end'];
        $inputOrganizer= $_POST['organizer'];
        $inputIntroduction= $_POST['introduction'];

        $_SESSION['register_exhibition'] = '';

    try {
            
            //桁数の確認
            if( strlen($inputName) > 64 || strlen($inputOrganizer) > 32 || strlen($inputIntroduction) > 160){
            $_SESSION['register_exhibition']=strlen($inputOrganizer)."入力文字数を超えています";
            echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://ec2-44-212-247-129.compute-1.amazonaws.com/register_exbition";</script>';
                exit();
            }
            

            if($inputStart > $inputEnd){
                $tmp = $inputStart;
                $inputStart = $inputEnd;
                $inputEnd = $tmp;
            }
            $_SESSION['register_exhibition']="登録完了";

            // SQL文を用意します。
            // :で始まる部分が後から値がセットされるプレースホルダです。
            // 複数回SQL文を実行する必要がある場合はここからexecute()までを 繰り返します。
            $dbh = DbUtil::Connect();
            $sql = 'INSERT INTO exhibition_table (exhibition_name, start, end, organizer, introduction)
                    VALUES(:name, :start, :end, :organizer, :introduction)';
            // SQL文を実行する準備をします。
            $stmt = $dbh->prepare( $sql );
            // プレースホルダに実際の値をバインドします。
            //   ->bindValue( プレースホルダ名, バインドする値, データの型 )
            $stmt->bindValue( ':name', $inputName, PDO::PARAM_STR );
            $stmt->bindValue( ':start', $inputStart, PDO::PARAM_STR );
            $stmt->bindValue( ':end', $inputEnd, PDO::PARAM_STR );
            $stmt->bindValue( ':organizer', $inputOrganizer, PDO::PARAM_STR );
            $stmt->bindValue( ':introduction', $inputIntroduction, PDO::PARAM_STR );
            // SQL文を実行します。
            $stmt->execute();

            $_SESSION['register_exhibition']="登録完了";

            unset($inputName);
            unset($inputStart);
            unset($inputEnd);
            unset($inputOrganizer);
            unset($inputIntroduction);
            unset($_POST['exhibition_name']);
            unset($_POST['start']);
            unset($_POST['end']);
            unset($_POST['organizer']);
            unset($_POST['introduction']);

        }catch( PDOException $e ){
            echo( '接続失敗: ' . $e->getMessage() . '<br>' );
            exit();
            }
}else {
    $_SESSION['register_exhibition']="入力に不備があります";
}
echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://ec2-44-212-247-129.compute-1.amazonaws.com/register_exbition";</script>';
exit();
 //実装時はコメント解除

 }
 else
 {
 return $content;
 }
 }

 add_filter('the_content', 'register_exhibition_db');
 ?>