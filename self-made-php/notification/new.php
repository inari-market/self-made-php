<?php
//実装時はコメント解除
    function register_notification($content) {
        if( is_page( 'notifications/new' )) { //　特定の固定ページの時だけ処理させる
?>
            <h1>お知らせ情報の入力</h1>
            <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="POST">
                <br>
                <p>タイトル</p>
                <input type="text" name="title" placeholder="タイトルを入力" maxlength="32"> <br>
                <p>内容</p> 
                <textarea  name="body" rows="4" cols="40" maxlength="1024" placeholder="お知らせの内容を入力"></textarea>
                <?php
                    session_start();
                    if(! empty($_SESSION['register_notification'])) {
                        echo("<br>".$_SESSION['register_notification']."<br>");
                        unset($_SESSION['register_notification']);
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

                if((! empty ($_POST['title']) ) & (! empty ($_POST['body']))){

                    include_once dirname( __FILE__ ).'/../../db.php';
                    // 前のページから値を取得します。
                    $inputTitle= $_POST['title'];
                    $inputBody= $_POST['body'];

                    $_SESSION['register_notification'] = '';
                    try {
                    //桁数の確認
                        if( strlen($inputTitle) > 32 || strlen($inputBody) > 1024){
                            $_SESSION['register_notification']=strlen($inputBody)."入力文字数を超えています";
                            echo '<script type="text/javascript">window.location.href = window.location.hreg = "";</script>';
                            exit();
                        }

                        $_SESSION['register_notification']="登録完了";

                        $dbh = DbUtil::Connect();
                        $sql = 'INSERT INTO notice (title, body) VALUES(:title, :body)';
                        $stmt = $dbh->prepare( $sql );
                        // プレースホルダに実際の値をバインドします。
                        $stmt->bindValue( ':title', $inputTitle, PDO::PARAM_STR );
                        $stmt->bindValue( ':body', $inputBody, PDO::PARAM_STR );
                        $stmt->execute(); // SQL文を実行します。

                        $_SESSION['register_notification']="登録完了";

                        unset($inputTitle);
                        unset($inputBody);
                        unset($_POST['title']);
                        unset($_POST['body']);

                    } catch( PDOException $e ){
                        echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                        exit();
                    }
                } else {
                    $_SESSION['register_notification']="入力に不備があります";
                }

                echo '<script type="text/javascript">window.location.href = window.location.hreg = "";</script>';
                exit();
            }
            } else {
                return $content;
            }
    }

add_filter('the_content', 'register_notification');

?>