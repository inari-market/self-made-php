<?php
    function edit_exhibition($content) {
        
        if( is_page( 'exhibitons/edit' )) {//特定の固定ページの時だけ処理させる 
            echo "hello";
            include_once dirname( __FILE__ ).'/../../db.php';

            if($_POST['button'] == 'update') { // updateの処理
                try {
                    $dbh = DbUtil::Connect(); // db.phpのメソッドを使ってDBとのコネクションを確立
                    $sql = 'update exhibition set exhibition_name = :exhibition_name, start = :start, end = :end, organizer = :organizer, introduction = :introduction, photo_name = :photo_name where exhibition_id = :id';
                    $stmt = $dbh->prepare( $sql ); // SQL文を実行する準備をします。
                    
                    // プレースホルダに実際の値をバインドします。
                    $stmt->bindValue( ':id', $_POST['id'], PDO::PARAM_STR );
                    $stmt->bindValue( ':exhibition_name', $_POST['exhibition_name'], PDO::PARAM_STR );
                    $stmt->bindValue( ':start', $_POST['start'], PDO::PARAM_STR );
                    $stmt->bindValue( ':end', $_POST['end'], PDO::PARAM_STR );
                    $stmt->bindValue( ':organizer', $_POST['organizer'], PDO::PARAM_STR );
                    $stmt->bindValue( ':introduction', $_POST['introduction'], PDO::PARAM_STR );
                    $stmt->bindValue( ':photo_name', $_POST['photo_name'], PDO::PARAM_STR );

                    $stmt->execute(); // sqlの実行
                    
                } catch( PDOException $e ) {
                    echo 'エラーが発生しました．以下のリンクから再度読み込んでください<br>';
                    echo '<a href="http://100.24.172.143/exhibitions/" style="color:blue;">再度読み込み</a>';
                    exit();
                }

                if($_FILES['image']['name'] != NULL) {
                    $image = $_POST['photo_name'] . '.png'; // 画像の名前をid.pngにする

                    if(move_uploaded_file($_FILES['image']['tmp_name'], '/var/www/html/exhibition/' . $image)) {
                        echo 'success';
                    } else {
                        echo '画像の保存に失敗しました．編集ページから再度登録してください';
                    }

                }
            }
            
            try { // editページの生成
                // データベースに接続します。
                $dbh = DbUtil::Connect(); // db.phpのメソッドを使ってDBとのコネクションを確立
                $sql = 'select * from exhibition where id = :id'; // 該当するカラムを抜く
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
                        <textarea  name="introduction" rows="4" cols="40" maxlength="160" placeholder="企画展の概要を入力" value="<?php echo $result['introduction']; ?>"></textarea><br>
                    <p>写真の名前</p>
                        <input type="text" name="photo_name" placeholder="写真の名前を入力" maxlength="256" value="<?php echo $result['photo_name']; ?>"> <br>
                    <p>企画展イメージ</p>
                        <input type="file" name="photo_img" accept="image/png, image/jpeg" > <br>
                </form>  
                </html>
                <?php
                echo '</div>';
                
            }catch( PDOException $e ){
                echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                echo 'エラーが発生しました．以下のリンクから再度読み込んでください<br>';
                echo '<a href="http://100.24.172.143/exhibitions" style="color:blue;">再度読み込み</a>';
                exit();
            }       
        } else {
            return $content;
        }
    }
    add_filter('the_content', 'edit_exhibition');
    
?>