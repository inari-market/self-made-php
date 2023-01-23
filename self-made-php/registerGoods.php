<?php
//実装時はコメント解除
//いったん放置
function register_goods($content) {
  if( is_page( 'goods/new' ))  //固定ページ「sample_cal」の時だけ処理させる
  {


?>
   <!-- 入力フォーム -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>商品登録ページ</title>
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
    </head>
    <body>
        <div class='l'>
    <h1>商品の入力フォーム</h1>
        <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="POST">

            <p>商品名</p>
                <input type="text" name="goods_name" placeholder="商品名を入力" maxlength="32" value="<?php echo $_SESSION['goods_name']; ?>"> <br>
            <p>価格</p>
                <input type="text" name="price" placeholder="価格を入力" value="<?php echo $_SESSION['price']; ?>"> <br>
            <p>商品概要</p>
                <input type="text" name="description" placeholder="商品概要を入力" maxlength="512" value="<?php echo $_SESSION['description']; ?>"> <br>
            <p>写真の名前</p>
                <input type="text" name="photo_name" placeholder="写真の名前を入力" maxlength="256" value="<?php echo $_SESSION['photo_name']; ?>"> <br>
            <p>商品イメージ</p>
                <input type="file" name="photo_img" accept="image/png, image/jpeg" > <br>
            
            <?php
            session_start();
            if(! empty($_SESSION['register_goods'])){
                echo("<br>".$_SESSION['register_goods']."<br>");
                unset($_SESSION['register_goods']);
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

    $_SESSION['goods_name']=$_POST['goods_name'];
    $_SESSION['price']= $_POST['price'];
    $_SESSION['description']=$_POST['description'];
    $_SESSION['photo_name']=$_POST['photo_name'];

    if((! empty ($_POST['goods_name']) ) & (! empty ($_POST['price'])) & (! empty ($_POST['description'])) 
        &  (! empty ($_POST['photo_name'])) ){


        include_once dirname( __FILE__ ).'/../db.php';
        // 前のページから値を取得します。

            $inputGoodsName= $_POST['goods_name'];
            $inputDescription=$_POST['description'];
            $inputPhotoName=$_POST['photo_name'];

            $_SESSION['register_goods'] = '';

            if( is_numeric($_POST['price']) ) {
                $inputPrice=$_POST['price'];
            }else{
                $_SESSION['register_goods']="正しい価格をご入力ください";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/goods/new/";</script>';
                exit();
            }

        try {            


                // SQL文を用意します。
                // :で始まる部分が後から値がセットされるプレースホルダです。
                // 複数回SQL文を実行する必要がある場合はここからexecute()までを 繰り返します。
                $dbh = DbUtil::Connect();
                $sql = 'INSERT INTO goods (goods_name, price, description, photo_name)
                        VALUES(:goods_name, :price, :description, :photo_name)';
                // SQL文を実行する準備をします。
                $stmt = $dbh->prepare( $sql );
                // プレースホルダに実際の値をバインドします。
                //   ->bindValue( プレースホルダ名, バインドする値, データの型 )
                $stmt->bindValue( ':goods_name', $inputGoodsName, PDO::PARAM_STR );
                $stmt->bindValue( ':price', $inputPrice, PDO::PARAM_INT );
                $stmt->bindValue( ':description', $inputDescription, PDO::PARAM_STR );
                $stmt->bindValue( ':photo_name', $inputPhotoName, PDO::PARAM_STR );

                // SQL文を実行します。
                $stmt->execute();
                $_SESSION['register_goods']="登録完了";  

                $img_url = "http://100.24.172.143/photo/";
                move_uploaded_file($_FILES['upimg']['tmp_name'], $img_url . $inputPhotoName.".png");
                $_SESSION['register_goods']="写真も登録完了";  

                unset($inputGoodsName);
                unset($inputPrice);
                unset($inputDescription);
                unset($inputPhotoName);
                unset($_POST['goods_name']);
                unset($_POST['price']);
                unset($_POST['description']);
                unset($_POST['photo_name']);
                unset($_SESSION['goods_name']);
                unset($_SESSION['price']);
                unset($_SESSION['description']);
                unset($_SESSION['photo_name']);

            }catch( PDOException $e ){
                echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                exit();
                }
    }else {
        $_SESSION['register_goods']="入力に不備があります";
    }

    echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/goods/new/";</script>';
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

add_filter('the_content', 'register_goods');

?>
