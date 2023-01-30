<?php
//実装時はコメント解除

function register_audio($content) {
  if( is_page( 'audio/new' ))  //固定ページ「sample_cal」の時だけ処理させる
  {

?>
   <!-- 入力フォーム -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>音声情報登録ページ</title>
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
    <h1>音声情報の入力</h1>
        <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="POST">

            <br>
            <p>音声ファイルを入力</p>
              <input type="text" name="filename" placeholder="ファイル名(.mp3を除く)を入力"> <br>
              <input type="file" name="upfile">
            <?php
            session_start();
            if(! empty($_SESSION['register_audio'])){
                echo("<br>".$_SESSION['register_audio']."<br>");
                unset($_SESSION['register_audio']);
            }else{
                echo("<br><br>");
            }
            ?>
            <br>
            <input type="submit" name="submit" value="登録">
        </form>
        </div>

    </body>
</html>

<?php

if(isset($_POST['submit'])){
    session_start();

    if((! empty ($_POST['filename']) )){

        include_once dirname( __FILE__ ).'/../../db.php';
        // 前のページから値を取得します。
          $audio = $_POST["upfile"].".mp3";
          $inputName=$_POST["filename"];

          $_SESSION['register_audio'] = '';

        try {
                
                //桁数の確認
                if( strlen($inputName) > 32){
                $_SESSION['register_audio']=strlen($inputName)."入力文字数を超えています";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://18.209.25.203/audio/new/";</script>';
                    exit();
                }
                
                $_SESSION['register_audio']="登録完了";

                // SQL文を用意します。
                // :で始まる部分が後から値がセットされるプレースホルダです。
                // 複数回SQL文を実行する必要がある場合はここからexecute()までを 繰り返します。
                $dbh = DbUtil::Connect();
                $sql = 'INSERT INTO audio1 (filename) VALUES(:filename)';
                // SQL文を実行する準備をします。
                $stmt = $dbh->prepare( $sql );
                // プレースホルダに実際の値をバインドします。
                //   ->bindValue( プレースホルダ名, バインドする値, データの型 )
                $stmt->bindValue( ':filename', $inputName, PDO::PARAM_STR );
                // SQL文を実行します。
                $stmt->execute();

                $_SESSION['register_audio']="登録完了";

                unset($inputName);
                unset($_POST['filename']);

                chmod("/var/www/html/audio " . $audio, 0644);
            
            }catch( PDOException $e ){
                echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                exit();
                }
    }else {
        $_SESSION['register_audio']="入力に不備があります";
    }
    echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://18.209.25.203/audio/new/";</script>';
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