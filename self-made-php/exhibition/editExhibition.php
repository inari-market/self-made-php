<?php
    function edit_exhibition($content) {

        if( is_page( 'exhibitions/edit' )) {//特定の固定ページの時だけ処理させる 
            include_once dirname( __FILE__ ).'/../../db.php';
            session_start();
            if(isset($_POST['update'])) { // updateの処理
                try {
                    $dbh = DbUtil::Connect(); // db.phpのメソッドを使ってDBとのコネクションを確立
                    $sql = 'update exhibition set exhibition_name = :exhibition_name, start = :start, end = :end, organizer = :organizer, introduction = :introduction where exhibition_id = :id';
                    $stmt = $dbh->prepare( $sql ); // SQL文を実行する準備をします。
                    
                    // プレースホルダに実際の値をバインドします。
                    $stmt->bindValue( ':id', $_POST['id'], PDO::PARAM_STR );
                    $stmt->bindValue( ':exhibition_name', $_POST['exhibition_name'], PDO::PARAM_STR );
                    $stmt->bindValue( ':start', $_POST['start'], PDO::PARAM_STR );
                    $stmt->bindValue( ':end', $_POST['end'], PDO::PARAM_STR );
                    $stmt->bindValue( ':organizer', $_POST['organizer'], PDO::PARAM_STR );
                    $stmt->bindValue( ':introduction', $_POST['introduction'], PDO::PARAM_STR );

                    $stmt->execute(); // sqlの実行
                    
                } catch( PDOException $e ) {
                    $_SESSION['edit_exhibition'] = '接続失敗: ' . $e->getMessage() . '<br>';
                    echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/exhibitions/edit/?"'.$_POST['id'].';</script>';
                    exit();
                }

                if($_FILES['image']['name'] != NULL) {
                    $image = $_POST['photo_name'] . '.png'; // 画像の名前をid.pngにする

                    if(move_uploaded_file($_FILES['image']['tmp_name'], '/var/www/html/exhibition/' . $image)) {
                        $dbh = DbUtil::Connect(); // db.phpのメソッドを使ってDBとのコネクションを確立
                        $sql = 'update exhibition set photo_name = :photo_name where exhibition_id = :id';
                        $stmt = $dbh->prepare( $sql ); // SQL文を実行する準備をします。                     
                        // プレースホルダに実際の値をバインドします。
                        $stmt->bindValue( ':photo_name', $_POST['photo_name'], PDO::PARAM_STR );
                        $stmt->execute(); // sqlの実行

                        $_SESSION['edit_exhibition'] = 'success';
                        echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/exhibitions/edit/?"'.$_POST['id'].';</script>';
                        exit();
                    } else {
                        $_SESSION['edit_exhibition'] = '画像の保存に失敗しました．編集ページから再度登録してください';
                        echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/exhibitions/edit/?"'.$_POST['id'].';</script>';
                        exit();
                    }

                }
            }
            
            try { // editページの生成
                // データベースに接続します。
                $dbh = DbUtil::Connect(); // db.phpのメソッドを使ってDBとのコネクションを確立
                $sql = 'select * from exhibition where exhibition_id = :id'; // 該当するカラムを抜く
                $stmt = $dbh->prepare( $sql ); // SQL文を実行する準備をします。

                $stmt->bindValue( ':id', $_GET['id'], PDO::PARAM_STR );
                $stmt->execute(); // SQL文を実行します。

                $result = $stmt->fetch(); // 結果を読み出します。             

                echo '<div class="entry-body">';
                echo '<div class="wp-block-columns">&nbsp;</div>';

                $img_url = 'http://100.24.172.143/exhibition/'; // 画像の参照先
                    ?>
                <html>
                <form name="regist_author" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data">
                    <input type="hidden" name = 'id' value="<?php echo $result['exhibition_id']?>">
                    <p>企画展名</p>
                        <input type="text" name="exhibition_name" placeholder="企画展名を入力" maxlength="64" value="<?php echo $result['exhibition_name']; ?>"> <br>
                    <p>開始日</p>
                        <input type="date" name="start" value="<?php echo $result['start']; ?>"> <br><br>
                    <p>終了日</p>
                        <input type="date" name="end" value="<?php echo $result['end']; ?>"> <br><br>
                    <p>主催者名</p>
                        <input type="text" name="organizer" placeholder="主催者名を入力" maxlength="32" value="<?php echo $result['organizer']; ?>"> <br>
                    <p>概要</p> 
                        <textarea  name="introduction" rows="4" cols="40" maxlength="160" placeholder="企画展の概要を入力" ><?php echo $result['introduction']; ?> </textarea><br>
                    <p>写真の名前</p>
                        <input type="text" name="photo_name" placeholder="写真の名前を入力" maxlength="256" value="<?php echo $result['photo_name']; ?>"> <br>
                    <p>写真</p>
                        <?php
                            $img_url = "http://100.24.172.143/exhibition/";
                            echo '<figure class="wp-block-image size-full is-resized"><img decoding="async" src="' . $img_url . $result['photo_name'] .".png" . '" alt="画像が読み込めませんでした" width="60" height="60"></figure>';
                        ?><br>
                    <p>写真の変更</p>
                        <input type="file" name="photo_img" accept="image/png, image/jpeg" > <br><br>

                        <?php
                            session_start();
                            if(! empty($_SESSION['edit_exhibition'])){
                                echo("<br>".$_SESSION['edit_exhibition']."<br>");
                                unset($_SESSION['edit_exhibition']);
                            }else{
                                echo("<br><br>");
                            }
                        ?>

                        <input type="submit" name = "update" value="更新"> <br>

                </form>  
                </html>
                <?php
                echo '</div>';
                
            }catch( PDOException $e ){
                $_SESSION['edit_exhibition'] =  '接続失敗: ' . $e->getMessage() . '<br>' ;
                echo '<script type="text/javascript">window.location.href = window.location.hreg = "http://100.24.172.143/exhibitions/edit/?"'.$_POST['id'].';</script>';
                exit();
            }       
        } else {
            return $content;
        }
    }
    add_filter('the_content', 'edit_exhibition');
    
?>