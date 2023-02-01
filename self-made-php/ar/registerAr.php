<?php
//実装時はコメント解除
//いったん放置
function register_ar($content) {
  if( is_page( 'ars/new' ))  //固定ページ「sample_cal」の時だけ処理させる
  {


?>
   <!-- 入力フォーム -->
<!DOCTYPE html>
<html>
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
            input[type=text]{
                width:230px;
                height:30px;
            }
            input[name=description]{
                width:300px;
                height:30px;
            }
        -->
    </style>
        <body>
            <div class='l'>
                <h1>商品の入力フォーム</h1>

                    <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>"  enctype="multipart/form-data" method="POST">

                        <p>マーカー写真の入力</p>
                            <input type="text" name="marker" placeholder="マーカー名を入力" maxlength="32" value="<?php echo $_SESSION['goods_name']; ?>"> <br>
                            <input type="file" name="marker_img" > <br><br>
                        <p>オブジェクト写真の入力</p>
                            <input type="text" name="object" placeholder="オブジェクト名を入力" maxlength="256" value="<?php echo $_SESSION['photo_name']; ?>"> <br>
                            <input type="file" name="object_img" accept="image/png, image/jpeg" > <br><br>
                        <p>音声ファイルを入力</p>
                            <input type="text" name="sound" placeholder="ファイル名を入力"> <br><br>
                            <input type="file" name="upfile">
                        
                        <?php
                        session_start();
                        if(! empty($_SESSION['register_ar'])){
                            echo("<br>".$_SESSION['register_ar']."<br>");
                            unset($_SESSION['register_ar']);
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
if(isset($_POST["submit"])){
    session_start();

    $_SESSION['marker']= $_POST['marker'];
    $_SESSION['object']=$_POST['object'];
    $_SESSION['sound']=$_POST['sound'];

    if((! empty ($_POST['marker'])) & (! empty ($_POST['object'])) & (! empty ($_POST['sound'])) ){


        include_once dirname( __FILE__ ).'/../../db.php';
        // 前のページから値を取得します。

            

            $_SESSION['register_ar'] = '';


        try {            
            include_once dirname( __FILE__ ).'/../../db.php';
            $dbh = DbUtil::Connect();
            $sql = 'SELECT * FROM ar';
            $stmt = $dbh->prepare( $sql );
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                if($_POST['marker'] == $row["marker"]){
                    $_SESSION['register_ar']="マーカーのファイル名は過去に登録された写真の名前です";
                    echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/ars/new/";</script>';
                    exit();
                }elseif(false !== strstr($_FILES['object_img']['tmp_name'], '.patt')){
                    $_SESSION['register_ar']="マーカーのファイルが適切ではありません";
                    echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/ars/new/";</script>';
                    exit();

                }
                if($_POST['object'] == $row["object"]){
                    $_SESSION['register_ar']="オブジェクトのファイル名は過去に登録された写真の名前です";
                    echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/ars/new/";</script>';
                    exit();
                }
                if($_POST['sound'] == $row["sound"]){
                    $_SESSION['register_ar']="音声のファイル名は過去に登録された写真の名前です";
                    echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/ars/new/";</script>';
                    exit();
                }
            }
            $inputMarker=$_POST['marker'];
            $inputObject=$_POST['object'];
            $inputSound=$_POST['sound'];                

        }catch( PDOException $e ){
                    echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                    exit();
        }

            $img_url = "/var/www/html/ar/marker/";
            if(move_uploaded_file($_FILES['marker_img']['tmp_name'], $img_url . $inputMarker.".patt")){
                $_SESSION['register_ar']="マーカー写真の登録完了";  
            }else{
                $_SESSION['register_ar']='image:' . $_FILES['marker_img']['name'] . '<br>'.'type:'  . $_FILES['marker_img']['type'] . '<br>'.var_dump($_FILES).
                "マーカー写真の登録に失敗しました";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/ars/new/";</script>';
                exit();
            }

            $img_url = "/var/www/html/ar/object/";
            if(move_uploaded_file($_FILES['object_img']['tmp_name'], $img_url . $inputObject.".png")){
                $_SESSION['register_ar']="オブジェクト写真登録完了";  
            }else{
                $_SESSION['register_ar']='image:' . $_FILES['object_img']['name'] . '<br>'.'type:'  . $_FILES['object_img']['type'] . '<br>'.var_dump($_FILES).
                "オブジェクト写真の登録に失敗しました";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/ars/new/";</script>';
                exit();
            }

            $sound_url = "/var/www/html/ar/sound/";
            if (move_uploaded_file($_FILES["upfile"]["tmp_name"], $sound_url. $inputSound. ".mp3")) {
                $_SESSION['register_ar']="音声登録完了";  
            }else{
                $_SESSION['register_ar']="音声の登録に失敗しました";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/ars/new/";</script>';
                exit();
            }

        try {            
                $dbh = DbUtil::Connect();
                $sql = 'INSERT INTO ar (marker, object, sound)VALUES(:marker, :object, :sound)';
                $stmt = $dbh->prepare( $sql );
                $stmt->bindValue( ':marker', $inputMarker, PDO::PARAM_STR );
                $stmt->bindValue( ':object', $inputObject, PDO::PARAM_STR );
                $stmt->bindValue( ':sound', $inputSound, PDO::PARAM_STR );
                $stmt->execute();

                $_SESSION['register_ar']="登録完了";  

                unset($inputMarker);
                unset($inputObject);
                unset($inputSound);
                unset($_POST['marker']);
                unset($_POST['object']);
                unset($_POST['sound']);
                unset($_SESSION['marker']);
                unset($_SESSION['object']);
                unset($_SESSION['sound']);

            }catch( PDOException $e ){
                echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                exit();
                }
    }else {
        $_SESSION['register_ar']="入力に不備があります";
    }

    echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/ars/new/";</script>';
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

add_filter('the_content', 'register_ar');

?>
