<?php
//実装時はコメント解除

function register_audio($content) {
  if( is_page( 'audio/new' ))  //固定ページ「sample_cal」の時だけ処理させる
  {

?>

   <!-- 入力フォーム -->
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="Content-Type" content="width=device-width, initial-scale=1.0">
  <title>test</title>
  <style>
    div{
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
<div>
<h1>音声ファイル登録ページ</h1>
<form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="POST" enctype="multipart/form-data"  accept = "audio/mp3">
    <div><br>
      <p>音声ファイルを入力</p>
      <input type="text" name="filename" placeholder="ファイル名(.mp3を除く)を入力"> <br>
      <input type="file" name="upfile">
    </div>
    <div>
        <?php
            session_start();
            if(! empty($_SESSION['register_audio'])){
                echo("<br>".$_SESSION['register_audio']."<br>");
                unset($_SESSION['register_audio']);
            }else{
                echo("<br><br>");
            }
            ?><br>
      <input type="submit" name ="submit" value="送信">
    </div>
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
        $audio = $_POST["filename"].".mp3";
        $inputName=$_POST["filename"];

        $_SESSION['register_audio'] = '';
       
        if (is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
          if (move_uploaded_file($_FILES["upfile"]["tmp_name"], "/var/www/html/audio/ ". $audio)) {
              // SQL文を用意します。
              // :で始まる部分が後から値がセットされるプレースホルダです。
              // 複数回SQL文を実行する必要がある場合はここからexecute()までを 繰り返します。
              try{
                  $dbh = DbUtil::Connect();
                  $sql = 'INSERT INTO audio1 (filename) VALUES(:filename)';
                  // SQL文を実行する準備をします。
                  $stmt = $dbh->prepare( $sql );
                  // プレースホルダに実際の値をバインドします。
                  //   ->bindValue( プレースホルダ名, バインドする値, データの型 )
                  $stmt->bindValue( ':filename', $inputName, PDO::PARAM_STR );
                  // SQL文を実行します。
                  $stmt->execute();
              }catch( PDOException $e ){
                  echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                  exit();
              }
              unset($inputName);
              unset($_POST['filename']);

              chmod("/var/www/html/audio " . $audio, 0644);
              $_SESSION["register_audio"]= $audio . "をアップロードしました。";
              echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://ec2-18-209-25-203.compute-1.amazonaws.com/register_audio";</script>';
              exit();
          } else {
              $_SESSION["register_audio"]= "ファイルをアップロードできません。";
              echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://ec2-18-209-25-203.compute-1.amazonaws.com/register_audio";</script>';
              exit();
          }
        } else {
            $_SESSION["register_audio"]= "ファイルが選択されていません。";
            echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://ec2-18-209-25-203.compute-1.amazonaws.com/register_audio";</script>';
            exit();
      }
  }
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

add_filter('the_content', 'register_audio');

?>