<?php

function register_exhibition($content) {
  if( is_page( 'exhibitions/new' ))
  {


?>

<html>
    
    <body>
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
        $inputName= $_POST['exhibition_name'];
        $inputStart= $_POST['start'];
        $inputEnd= $_POST['end'];
        $inputOrganizer= $_POST['organizer'];
        $inputIntroduction= $_POST['introduction'];
        $_SESSION['register_exhibition'] = '';

        try {            
            include_once dirname( __FILE__ ).'/../../db.php';
            $dbh = DbUtil::Connect();
            $sql = 'SELECT * FROM exhibition';
            $stmt = $dbh->prepare( $sql );
            $stmt->execute();
    
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                if($_POST['photo_name'] == $row["photo_name"]){
                    $_SESSION['register_exhibition']="過去に登録された写真の名前です";
                    echo '<script type="text/javascript">window.location.href = window.location.hreg = "";</script>';
                    exit();
                }
            }

            $inputPhotoName=$_POST['photo_name'];
        }catch( PDOException $e ){
            $_SESSION['register_exhibition']= '接続失敗: ' . $e->getMessage() . '<br>';
            exit();
        }

        $img_url = "/var/www/html/exhibition/";
        if(move_uploaded_file($_FILES['photo_img']['tmp_name'], $img_url . $inputPhotoName.".png")){
            $_SESSION['register_exhibition']="写真登録完了";  
        }else{
            $_SESSION['register_exhibition']="エラータイプ:".$_FILES['photo_img']['error'].
            "ファイルサイズ:".$_FILES['photo_img']['size'].
            $img_url . $inputPhotoName.".png=>"."写真の登録に失敗しました";
            echo '<script type="text/javascript">window.location.href = window.location.hreg = "";</script>';
            exit();
        }
        
        try {
                
            //桁数の確認
            if( strlen($inputName) > 64 || strlen($inputOrganizer) > 32 || strlen($inputIntroduction) > 160){
                $_SESSION['register_exhibition']=strlen($inputOrganizer)."入力文字数を超えています";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "";</script>';
                exit();
            }
                

            if($inputStart > $inputEnd){
                $tmp = $inputStart;
                $inputStart = $inputEnd;
                $inputEnd = $tmp;
            }
   
            $dbh = DbUtil::Connect();
            $sql = 'INSERT INTO exhibition (exhibition_name, start, end, organizer, introduction, photo_name)
                        VALUES(:name, :start, :end, :organizer, :introduction, :photo_name)';
            $stmt = $dbh->prepare( $sql );
            $stmt->bindValue( ':name', $inputName, PDO::PARAM_STR );
            $stmt->bindValue( ':start', $inputStart, PDO::PARAM_STR );
            $stmt->bindValue( ':end', $inputEnd, PDO::PARAM_STR );
            $stmt->bindValue( ':organizer', $inputOrganizer, PDO::PARAM_STR );
            $stmt->bindValue( ':introduction', $inputIntroduction, PDO::PARAM_STR );
            $stmt->bindValue( ':photo_name', $inputPhotoName, PDO::PARAM_STR );
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

        // googleカレンダーに登録
        $post_data = [ // ポストされたデータを変数に代入
            'name' => $inputName, 
            'start' => $inputStart,
            'end' => $inputEnd,
            'organizer' => $inpu,
        ];
        $json_data = json_encode($post_data); // jsonに変換
    
        $ch = curl_init(); // 以下はcurlのフォーマットに従う
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, 'https://script.google.com/macros/s/AKfycbzcTnP_nTvDzzDMaN6t9SSIdh5iAzFT-R-Xu-e8n8ag1lIEOwzKww3rolWZl-16tjS5/exec');  // APIのURI
        $result=curl_exec($ch);
        curl_close($ch);
        // ここまで
    }else {
        $_SESSION['register_exhibition']="入力に不備があります";
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

add_filter('the_content', 'register_exhibition');

?>