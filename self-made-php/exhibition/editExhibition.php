<?php

function edit_exhibition($content) {
 if( is_page( 'exhibitions/edit' ))
 {

?>

<?php
    $id = $_GET['id'];
    if (! empty($id)) {

        try {
            include_once dirname( __FILE__ ).'/../../db.php';
            $dbh = DbUtil::Connect();
            $sql = 'select * FROM exhibition where exhibition_id = :id';
            $stmt = $dbh->prepare( $sql );
            $stmt->bindValue( ':id', $id, PDO::PARAM_INT );
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            unlink('/var/www/html/exhibition/' . $result['photo_name'] . '.png');
            ?>

            <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data" method="POST">

            <br>
            <p>企画展名</p>
                <input type="text" name="exhibition_name" placeholder="企画展名を入力" maxlength="64" value="<?php echo $result['exhibition_name']; ?>"> <br>
            <p>開始日</p>
                <input type="date" name="start" value="<?php echo $result['start']; ?>"> <br><br>
            <p>終了日</p>
                <input type="date" name="end" value="<?php echo $result['end']; ?>"> <br><br>
            <p>主催者名</p>
                <input type="text" name="organizer" placeholder="主催者名を入力" maxlength="32" value="<?php echo $result['organizer']; ?>"> <br>
            <p>概要</p> 
                <textarea  name="introduction" rows="4" cols="40" maxlength="160" placeholder="企画展の概要を入力" value="<?php echo $result['introduction']; ?>"></textarea><br>
            <p>写真の名前</p>
                <input type="text" name="photo_name" placeholder="写真の名前を入力" maxlength="256" value="<?php echo $result['photo_name']; ?>"> <br>
            <p>写真</p>
                        <?php
                        $img_url = "http://52.54.93.120/exhibition/";
                        echo '<figure class="wp-block-image size-full is-resized"><img decoding="async" src="' . $img_url . $result['photo_name'] .".png" . '" alt="画像が読み込めませんでした" width="60" height="60"></figure>';
                        ?>
            <p>企画展イメージ</p>
                <input type="file" name="photo_img" accept="image/png, image/jpeg" > <br>

            <?php
                session_start();
                if(! empty($_SESSION['edit_exhibition'])){
                    echo("<br>".$_SESSION['edit_exhibition']."<br>");
                    unset($_SESSION['edit_exhibition']);
                }else{
                    echo("<br><br>");
                }
            ?>
            <br>
            <input type="submit" name = "submit" value="更新">
        </form>
<?php
            session_start();
            $_SESSION['edit_exhibition']="更新完了";
            unset($_GET['id']);
            echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://52.54.93.120/exhibitions/edit";</script>';
            exit();

        }catch( PDOException $e ){
            echo( '接続失敗: ' . $e->getMessage() . '<br>' );
            exit();
        }
    }


?>


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
        $_SESSION['edit_exhibition'] = '';

        try {            
            include_once dirname( __FILE__ ).'/../../db.php';
            $dbh = DbUtil::Connect();
            $sql = 'SELECT * FROM exhibition';
            $stmt = $dbh->prepare( $sql );
            $stmt->execute();
    
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                if($_POST['photo_name'] == $row["photo_name"]){
                    $_SESSION['edit_exhibition']="過去に登録された写真の名前です";
                    echo '<script type="text/javascript">window.location.href = window.location.hreg = "";</script>';
                    exit();
                }
            }

            $inputPhotoName=$_POST['photo_name'];
        }catch( PDOException $e ){
            $_SESSION['edit_exhibition']= '接続失敗: ' . $e->getMessage() . '<br>';
            exit();
        }

        $img_url = "/var/www/html/exhibition/";
        if(move_uploaded_file($_FILES['photo_img']['tmp_name'], $img_url . $inputPhotoName.".png")){
            $_SESSION['edit_exhibition']="写真登録完了";  
        }else{
            $_SESSION['edit_exhibition']="エラータイプ:".$_FILES['photo_img']['error'].
            "ファイルサイズ:".$_FILES['photo_img']['size'].
            $img_url . $inputPhotoName.".png=>"."写真の登録に失敗しました";
            echo '<script type="text/javascript">window.location.href = window.location.hreg = "";</script>';
            exit();
        }
        
        try {
                
            //桁数の確認
            if( strlen($inputName) > 64 || strlen($inputOrganizer) > 32 || strlen($inputIntroduction) > 160){
                $_SESSION['edit_exhibition']=strlen($inputOrganizer)."入力文字数を超えています";
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

             // googleカレンダーに登録
            $post_data = [ // ポストされたデータを変数に代入
                'name' => $inputName, 
                'start' => $inputStart,
                'end' => $inputEnd,
            ];
            $json_data = json_encode($post_data); // jsonに変換
        
            $ch = curl_init(); // 以下はcurlのフォーマットに従う
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, 'https://script.google.com/macros/s/AKfycbxm6yBrSs-QGrK011Y_MvizjcuSC0ZgGkZbUwfM1L8i8ubecIzM_nPMFw58s0un6ZUC/exec');  // APIのURI
            $result=curl_exec($ch);
            curl_close($ch);
            
            // sessionの片づけ    
            $_SESSION['edit_exhibition']="登録完了";

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
            $_SESSION['edit_exhibition']= '接続失敗: ' . $e->getMessage() . '<br>';
            exit();
        }
    }else {
        $_SESSION['edit_exhibition']="入力に不備があります";
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

add_filter('the_content', 'edit_exhibition');

?>