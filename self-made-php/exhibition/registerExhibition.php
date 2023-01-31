<?php
//実装時はコメント解除

function register_exhibition($content) {
  if( is_page( 'exhibitions/new' ))  //固定ページ「sample_cal」の時だけ処理させる
  {


?>
   <!-- 入力フォーム -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>企画展登録ページ</title>
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
    <h1>企画展情報の入力</h1>
        <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data" method="POST">

            <br>
            <p>企画展名</p>
                <input type="text" name="exhibition_name" placeholder="企画展名を入力" maxlength="64" value="<?php echo $_SESSION['exhibition_name']; ?>"> <br>
            <p>開始日</p>
                <input type="date" name="start" value="<?php echo date('Y-m-d'); ?>"> <br><br>
            <p>終了日</p>
                <input type="date" name="end" value="<?php echo date('Y-m-d'); ?>"> <br><br>
            <p>主催者名</p>
                <input type="text" name="organizer" placeholder="主催者名を入力" maxlength="32" value="<?php echo $_SESSION['organizer']; ?>"> <br>
            <p>概要</p> 
                <textarea  name="introduction" rows="4" cols="40" maxlength="160" placeholder="企画展の概要を入力" value="<?php echo $_SESSION['introduction']; ?>"></textarea><br>
            <p>写真の名前</p>
                <input type="text" name="photo_name" placeholder="写真の名前を入力" maxlength="256" value="<?php echo $_SESSION['photo_name']; ?>"> <br>
            <p>企画展イメージ</p>
                <input type="file" name="photo_img" accept="image/png, image/jpeg" > <br>

            <?php
            session_start();
            if(! empty($_SESSION['register_exhibition'])){
                echo("<br>".$_SESSION['register_exhibition']."<br>");
                unset($_SESSION['register_exhibition']);
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
    $_SESSION['exhibition_name']= $_POST['exhibition_name'];
    $_SESSION['organizer']=$_POST['organizer'];
    $_SESSION['introduction']=$_POST['introduction'];
    $_SESSION['photo_name']=$_POST['photo_name'];

    if((! empty ($_POST['exhibition_name']) ) & (! empty ($_POST['start']))  &  (! empty ($_POST['end']))  &  (! empty ($_POST['introduction']))
    &  (! empty ($_POST['photo_name']))){


        include_once dirname( __FILE__ ).'/../../db.php';
        // 前のページから値を取得します。
            $inputName= $_POST['exhibition_name'];
            $inputStart= $_POST['start'];
            $inputEnd= $_POST['end'];
            $inputOrganizer= $_POST['organizer'];
            $inputIntroduction= $_POST['introduction'];

            $_SESSION['register_exhibition'] = '';

            
            try {            
                include_once dirname( __FILE__ ).'/../../db.php';
                // データベースに接続します。
                $dbh = DbUtil::Connect();
    
                // SQL文を用意します。
                // :で始まる部分が後から値がセットされるプレースホルダです。
                // 複数回SQL文を実行する必要がある場合はここからexecute()までを>繰り返します。
                $sql = 'SELECT * FROM exhibition';
                // SQL文を実行する準備をします。
                $stmt = $dbh->prepare( $sql );
                // SQL文を実行します。
                $stmt->execute();
    
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    if($_POST['photo_name'] == $row["photo_name"]){
                        $_SESSION['register_exhibition']="過去に登録された写真の名前です";
                        echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/exhibitions/new";</script>';
                        exit();
                    }
                }

                $inputPhotoName=$_POST['photo_name'];
    
                    
    
            }catch( PDOException $e ){
                $_SESSION['register_exhibition']= '接続失敗: ' . $e->getMessage() . '<br>';
                        exit();
            }

                $img_url = "var/www/html/exhibition/";
                if(move_uploaded_file($_FILES['photo_img']['tmp_name'], $img_url . $inputPhotoName.".png")){
                    $_SESSION['register_exhibition']="写真登録完了";  
                }else{
                    $_SESSION['register_exhibition']="エラータイプ:".$_FILES['photo_img']['error'].
                    "ファイルサイズ:".$_FILES['photo_img']['size'].
                    $img_url . $inputPhotoName.".png=>".'image:' . $_FILES['photo_img']['name'] . '<br>'.'type:'  . $_FILES['photo_img']['type'] . '<br>'.var_dump($_FILES).
                    "写真の登録に失敗しました";
                    echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/exhibitions/new";</script>';
                    exit();
                }
        

        try {
                
                //桁数の確認
                if( strlen($inputName) > 64 || strlen($inputOrganizer) > 32 || strlen($inputIntroduction) > 160){
                $_SESSION['register_exhibition']=strlen($inputOrganizer)."入力文字数を超えています";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/exhibitions/new/";</script>';
                    exit();
                }
                

                if($inputStart > $inputEnd){
                    $tmp = $inputStart;
                    $inputStart = $inputEnd;
                    $inputEnd = $tmp;
                }
                echo("HELLO");
                // SQL文を用意します。
                // :で始まる部分が後から値がセットされるプレースホルダです。
                // 複数回SQL文を実行する必要がある場合はここからexecute()までを 繰り返します。
                $dbh = DbUtil::Connect();
                $sql = 'INSERT INTO exhibition (exhibition_name, start, end, organizer, introduction, photo_name)
                        VALUES(:name, :start, :end, :organizer, :introduction, :photo_name)';
                // SQL文を実行する準備をします。
                $stmt = $dbh->prepare( $sql );
                // プレースホルダに実際の値をバインドします。
                //   ->bindValue( プレースホルダ名, バインドする値, データの型 )
                $stmt->bindValue( ':name', $inputName, PDO::PARAM_STR );
                $stmt->bindValue( ':start', $inputStart, PDO::PARAM_STR );
                $stmt->bindValue( ':end', $inputEnd, PDO::PARAM_STR );
                $stmt->bindValue( ':organizer', $inputOrganizer, PDO::PARAM_STR );
                $stmt->bindValue( ':introduction', $inputIntroduction, PDO::PARAM_STR );
                $stmt->bindValue( ':photo_name', $inputPhotoName, PDO::PARAM_STR );
                // SQL文を実行します。
                $stmt->execute();

                $_SESSION['register_exhibition']="登録完了";

                unset($inputName);
                unset($inputStart);
                unset($inputEnd);
                unset($inputOrganizer);
                unset($inputIntroduction);
                unset($inputPhotoName);
                unset($_POST['exhibition_name']);
                unset($_POST['start']);
                unset($_POST['end']);
                unset($_POST['organizer']);
                unset($_POST['introduction']);
                unset($_POST['photo_name']);
                unset($_SESSION['exhibition_name']);
                unset($_SESSION['organizer']);
                unset($_SESSION['introduction']);
                unset($_SESSION['photo_name']);

            }catch( PDOException $e ){
                $_SESSION['register_exhibition']= '接続失敗: ' . $e->getMessage() . '<br>';
                exit();
                }
    }else {
        $_SESSION['register_exhibition']="入力に不備があります";
    }
    echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/exhibitions/new/";</script>';
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

add_filter('the_content', 'register_exhibition');

?>