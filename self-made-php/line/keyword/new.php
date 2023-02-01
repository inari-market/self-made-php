<?php
    function register_keyword($content) {
        if( is_page( 'line/keyword/new' )) { //　特定の固定ページの時だけ処理させる
?>
            <h1>キーワード登録</h1>
            <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="POST">
                <br>
                <p>キーワード</p>
                <input type="text" name="keyword" placeholder="キーワード" maxlength="32"> <br>
                <p>メッセージ</p> 
                <textarea  name="message" rows="4" cols="40" maxlength="1024" placeholder="メッセージを入力"></textarea>
                <p>画像</p>
                <input type="file" name="image" accept="image/*"><br>
                <?php
                    session_start();
                    if(! empty($_SESSION['register_keyword'])) {
                        echo("<br>".$_SESSION['register_keyword']."<br>");
                        unset($_SESSION['register_keyword']);
                    }else{
                        echo("<br><br>");
                    }
                ?>
                <br>
                <input type="submit" name="submit" value="登録">
            </form>
<?php

            if(isset($_POST['submit'])) {
                session_start();

                if((! empty ($_POST['keyword']) ) && (! empty ($_POST['message']))){

                    include_once dirname( __FILE__ ).'/../../../db.php';
                    // 前のページから値を取得します。
                    $inputKeyword = $_POST['keyword'];
                    $inputMessage = $_POST['message'];

                    $_SESSION['register_keyword'] = '';
                    try {
                   
                        $_SESSION['register_keyword']="登録完了";

                        $dbh = DbUtil::Connect();
                        $sql = 'INSERT INTO keyword (keyword, msg) VALUES(:keyword, :msg)';
                        $stmt = $dbh->prepare( $sql );
                        // プレースホルダに実際の値をバインドします。
                        $stmt->bindValue( ':keyword', $inputKeyword,   PDO::PARAM_STR );
                        $stmt->bindValue( ':msg',     $inputMessage, PDO::PARAM_STR );
                        $stmt->execute(); // SQL文を実行します。

                        $_SESSION['register_keyword']="登録完了";

                        unset($inputTitle);
                        unset($inputBody);
                        unset($_POST['keyword']);
                        unset($_POST['message']);

                    } catch( PDOException $e ){
                        echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                        exit();
                    }
                } else {
                    $_SESSION['register_keyword']="入力に不備があります";
                }

                echo '<script type="text/javascript">window.location.href = window.location.hreg = "";</script>';
                exit();
            }
            } else {
                return $content;
            }
    }

add_filter('the_content', 'register_keyword');

?>