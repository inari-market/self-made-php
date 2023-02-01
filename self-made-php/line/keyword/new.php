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
                        echo("<br>".$_SESSION['register_keyword']."<br>image:");
                        echo $_SESSION['register_keyword_image'];
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

                        $img = $_FILES['image']['name'];
                        echo 'ファイルサイズ:' . $_FILES['image']['size'];
    
                        if ($img == NULL) {
                            echo 'image_name is NULL'; // Nullなら何もしない
                        } else { // 画像が添付されていれば保存する
                            $sql  = 'select max(id) as id from keyword'; // テーブルのidの最大値を
                            $stmt = $dbh->prepare( $sql ); // 
                            $stmt->execute();
                            $id = $stmt->fetch( PDO::FETCH_ASSOC ); // SQLの実行結果
                            
                            $image = $id['id'] . '.png'; // 画像の名前をid.pngにする
    
                            //画像を保存
                            if(move_uploaded_file($_FILES['image']['tmp_name'], '/var/www/html/img/keyword/' . $image)) {

                            } else {
                                $_SESSION['register_keyword_image'] = $image . "hoge";
                            } 
                            // 画像の名前をDBに保存
                            $sql = 'update keyword set image = :image where id = :id';
                            $stmt = $dbh->prepare( $sql ); // SQL文を実行する準備をします。
    
                            $stmt->bindValue( ':image', $image,     PDO::PARAM_STR );
                            $stmt->bindValue( ':id',    $id['id'],  PDO::PARAM_STR );
                            $stmt->execute();
                        }

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