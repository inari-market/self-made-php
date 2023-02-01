<?php
//実装時はコメント解除
//いったん放置
function register_workshop($content) {
  if( is_page( 'workshops/new' ))  //固定ページ「sample_cal」の時だけ処理させる
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
    <h1>ワークショップ登録の入力フォーム</h1>
        <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="POST">

            <br>
            <p>ワークショップ名</p>
                <input type="text" name="workshop_name" placeholder="ワークショップ名を入力" maxlength="64" value="<?php echo $_SESSION['workshop_name']; ?>"> <br>
            <p>主催者</p>
                <input type="text" name="organizer" placeholder="主催者名を入力" maxlength="32" value="<?php echo $_SESSION['organizer']; ?>"> <br>
            <p>概要</p> 
                <textarea  name="introduction" cols="40" maxlength="1024" placeholder="ワークショップの概要を入力" value="<?php echo $_SESSION['introduction']; ?>"></textarea> <br>
            <p>参加可能人数</p>
                <input type="text" name="capacity" placeholder="参加可能人数を入力" maxlength="11" value="<?php echo $_SESSION['capacity']; ?>"> <br>
            <p>一人あたりの参加料金</p>
                <input type="text" name="cost" placeholder="一人あたりの参加料金を入力" maxlength="11" value="<?php echo $_SESSION['cost']; ?>"> <br>
            <p>開始日</p>
                <input type="date" name="start" value="<?php echo date('Y-m-d'); ?>"> <br><br>
            <p>終了日</p>
                <input type="date" name="end" value="<?php echo date('Y-m-d'); ?>"> <br><br>  
            <p>予約締切日</p>
                <input type="date" name="deadline" value="<?php echo date('Y-m-d'); ?>"> <br><br>           
            
            <?php
            session_start();
            if(! empty($_SESSION['register_workshop'])){
                echo("<br>".$_SESSION['register_workshop']."<br>");
                unset($_SESSION['register_workshop']);
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
if(isset($_POST['submit'])){
    session_start();
    $_SESSION['workshop_name']= $_POST['exhibition_name'];
    $_SESSION['organizer']=$_POST['organizer'];
    $_SESSION['introduction']=$_POST['introduction'];
    $_SESSION['capacity']=$_POST['capacity'];
    $_SESSION['cost']=$_POST['cost'];
    if((! empty ($_POST['workshop_name']) ) & (! empty ($_POST['start']))  &  (! empty ($_POST['end'])) 
        &  (! empty ($_POST['capacity'])) &  (! empty ($_POST['cost'])) &  (! empty ($_POST['deadline']))){


        include_once dirname( __FILE__ ).'/../../db.php';
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

            if(is_numeric($inputCapacity) & is_numeric($inputCost)) {
                $inputCapacity=(int)$inputCapacity;
                $inputCost=(int)$inputCost;
            }else{
                $_SESSION['register_workshop']="参加人数および料金は数値でご入力ください";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/workshops/new/";</script>';
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
                unset($_SESSION['exhibition_name']);
                unset($_SESSION['organizer']);
                unset($_SESSION['introduction']);
                unset($_SESSION['cost']);
                unset($_SESSION['capacity']);

            }catch( PDOException $e ){
                echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                exit();
                }
    }else {
        $_SESSION['register_workshop']="入力に不備があります";
    }
    echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://52.54.93.120/workshops/new/";</script>';
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

add_filter('the_content', 'register_workshop');

?>