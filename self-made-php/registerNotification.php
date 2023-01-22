<?php
//実装時はコメント解除

function register_notification($content) {
  if( is_page( 'notification/new' ))  //固定ページ「sample_cal」の時だけ処理させる
  {


?>
   <!-- 入力フォーム -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>お知らせ情報登録ページ</title>
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
    <h1>お知らせ情報の入力</h1>
        <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="POST">

            <br>
            <p>タイトル</p>
                <input type="text" name="title" placeholder="タイトルを入力" maxlength="32"> <br>
            <p>内容</p> 
                <textarea  name="body" rows="4" cols="40" maxlength="1024" placeholder="お知らせの内容を入力"></textarea>
            <?php
            session_start();
            if(! empty($_SESSION['register_notification'])){
                echo("<br>".$_SESSION['register_notification']."<br>");
                unset($_SESSION['register_notification']);
            }else{
                echo("<br><br>");
            }
            ?>

            <br>
            <input type="submit" value="登録">
        </form>
        </div>

    </body>
</html>

<?php

if(isset($_POST['submit'])){
    session_start();

    if((! empty ($_POST['title']) ) & (! empty ($_POST['body']))){


        include_once dirname( __FILE__ ).'/../db.php';
        // 前のページから値を取得します。
            $inputTitle= $_POST['title'];
            $inputBody= $_POST['body'];

            $_SESSION['register_notification'] = '';

        try {
                
                //桁数の確認
                if( strlen($inputTitle) > 32 || strlen($inputBody) > 1024){
                $_SESSION['register_notification']=strlen($inputBody)."入力文字数を超えています";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://18.209.25.203/notification/new/";</script>';
                    exit();
                }
                
                $_SESSION['register_notification']="登録完了";

                // SQL文を用意します。
                // :で始まる部分が後から値がセットされるプレースホルダです。
                // 複数回SQL文を実行する必要がある場合はここからexecute()までを 繰り返します。
                $dbh = DbUtil::Connect();
                $sql = 'INSERT INTO notice (title, body) VALUES(:title, :body)';
                // SQL文を実行する準備をします。
                $stmt = $dbh->prepare( $sql );
                // プレースホルダに実際の値をバインドします。
                //   ->bindValue( プレースホルダ名, バインドする値, データの型 )
                $stmt->bindValue( ':title', $inputTitle, PDO::PARAM_STR );
                $stmt->bindValue( ':body', $inputBody, PDO::PARAM_STR );
                // SQL文を実行します。
                $stmt->execute();

                $_SESSION['register_notification']="登録完了";

                unset($inputTitle);
                unset($inputBody);
                unset($_POST['title']);
                unset($_POST['body']);
            
            }catch( PDOException $e ){
                echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                exit();
                }
    }else {
        $_SESSION['register_notification']="入力に不備があります";
    }
    echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://18.209.25.203/notification/new/";</script>';
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

add_filter('the_content', 'register_notification');

?>