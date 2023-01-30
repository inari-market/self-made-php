<?php
//実装時はコメント解除

function register_audio_db($content) {
    if( is_page( 'register_audio_db' ))  //固定ページ「sample_cal」の時だけ処理させる
    {
        include_once dirname( __FILE__ ).'../../db.php';
        if( empty($_POST["filename"])){
            $_SESSION["register_audio"]="ファイル名を指定してください 。";
            echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://ec2-18-209-25-203.compute-1.amazonaws.com/register_audio";</script>';
            exit();}else{
                $audio = $_POST["upfile"].".mp3";
                $inputName=$_POST["filename"];
            }
            if(filesize($_FILES["upfile"]["tmp_name"] > 3000000)){
                $_SESSION["register_audio"]="ファイルサイズが大きいです。";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://ec2-18-209-25-203.compute-1.amazonaws.com/register_audio";</script>';
                exit();
            }
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
            } else
            {
                $_SESSION["register_audio"]= "ファイルが選択されていません。";
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://ec2-18-209-25-203.compute-1.amazonaws.com/register_audio";</script>';
                exit();
            }


    }
    else
    {
        return $content;
    }
}

add_filter('the_content', 'register_audio_db');

?>