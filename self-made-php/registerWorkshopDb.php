<?php
//実装時はコメント解除

function register_workshop_db($content) {
 if( is_page( 'register_workshop_db' ))  //固定ページ「sample_cal」の時だ け処理させる
 {


session_start();

if((! empty ($_POST['workshop_name']) ) & (! empty ($_POST['start']))  &  (! empty ($_POST['end'])) 
     &  (! empty ($_POST['capacity'])) &  (! empty ($_POST['cost'])) &  (! empty ($_POST['deadline']))){


    include_once dirname( __FILE__ ).'/../db.php';
    // 前のページから値を取得します。
        
        $inputName= $_POST['workshop_name'];
        $inputIntroduction= $_POST['introduction'];
        $inputCapacity=$_POST['capacity'];
        $inputCost=$_POST['cost'];
        $inputOrganizer= $_POST['organizer'];
        $inputStart= $_POST['start'];
        $inputEnd= $_POST['end'];
        $inputDeadline= $_POST['deadline'];
        $_SESSION['register_workshop'] = '';

        if(is_int($inputCapacity) & is_int($inputCost)) {
            $inputCapacity=(int)$inputCapacity;
            $inputCost=(int)$inputCost;
        }else{
            $_SESSION['register_workshop']="参加人数および料金は数値でご入力ください";
            echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://ec2-44-212-247-129.compute-1.amazonaws.com/register_workshop";</script>';
            exit();
        }
    try {            

            if($inputStart > $inputEnd){
                $tmp = $inputStart;
                $inputStart = $inputEnd;
                $inputEnd = $tmp;
            }

            // SQL文を用意します。
            // :で始まる部分が後から値がセットされるプレースホルダです。
            // 複数回SQL文を実行する必要がある場合はここからexecute()までを 繰り返します。
            $dbh = DbUtil::Connect();
            $sql = 'INSERT INTO workshop (workshop_name, introduction, capacity, organizer, cost, start, end, deadline)
                    VALUES(:workshop_name, :introduction, :capacity, :organizer, :cost , :start, :end, :deadline)';
            // SQL文を実行する準備をします。
            $stmt = $dbh->prepare( $sql );
            // プレースホルダに実際の値をバインドします。
            //   ->bindValue( プレースホルダ名, バインドする値, データの型 )
            $stmt->bindValue( ':workshop_name', $inputName, PDO::PARAM_STR );
            $stmt->bindValue( ':introduction', $inputIntroduction, PDO::PARAM_STR );
            $stmt->bindValue( ':capacity', $inputCapacity, PDO::PARAM_INT );
            $stmt->bindValue( ':organizer', $inputOrganizer, PDO::PARAM_STR );
            $stmt->bindValue( ':cost', $inputCost, PDO::PARAM_INT );
            $stmt->bindValue( ':start', $inputStart, PDO::PARAM_STR );
            $stmt->bindValue( ':end', $inputEnd, PDO::PARAM_STR );
            $stmt->bindValue( ':deadline', $inputDeadline, PDO::PARAM_STR );

            // SQL文を実行します。
            $stmt->execute();

            $_SESSION['register_workshop']="登録完了";

            unset($inputName);
            unset($inputStart);
            unset($inputEnd);
            unset($inputOrganizer);
            unset($inputIntroduction);
            unset($inputCapacity);
            unset($inputCost);
            unset($inputDeadline);
            unset($_POST['exhibition_name']);
            unset($_POST['start']);
            unset($_POST['end']);
            unset($_POST['organizer']);
            unset($_POST['introduction']);
            unset($_POST['cost']);
            unset($_POST['capacity']);
            unset($_POST['deadline']);

        }catch( PDOException $e ){
            echo( '接続失敗: ' . $e->getMessage() . '<br>' );
            exit();
            }
}else {
    $_SESSION['register_workshop']="入力に不備があります";
}
echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://ec2-44-212-247-129.compute-1.amazonaws.com/register_workshop";</script>';
exit();
 //実装時はコメント解除

 }
 else
 {
 return $content;
 }
 }

 add_filter('the_content', 'register_workshop_db');
 ?>