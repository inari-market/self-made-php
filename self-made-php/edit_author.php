<?php
    function edit_author($content) {
        
        if( is_page( 'authors/edit' )) {//特定の固定ページの時だけ処理させる 

            include_once dirname( __FILE__ ).'/../db.php';

            if($_POST['button'] == 'update') { // updateの処理
                try {
                    $dbh = DbUtil::Connect(); // db.phpのメソッドを使ってDBとのコネクションを確立
                    $sql = 'update author set name = :name, furigana = :furigana, birthday = :birthday, introduction  = :introduction, birthplace = :birthplace, work = :work where id = :id';
                    $stmt = $dbh->prepare( $sql ); // SQL文を実行する準備をします。
                    
                    // プレースホルダに実際の値をバインドします。
                    $stmt->bindValue( ':id',           $_POST[id],           PDO::PARAM_STR );
                    $stmt->bindValue( ':name',         $_POST[posted_name],         PDO::PARAM_STR );
                    $stmt->bindValue( ':furigana',     $_POST[furigana],     PDO::PARAM_STR );
                    $stmt->bindValue( ':birthday',     $_POST[birthday],     PDO::PARAM_STR );
                    $stmt->bindValue( ':introduction', $_POST[introduction], PDO::PARAM_STR );
                    $stmt->bindValue( ':birthplace',   $_POST[birthplace],   PDO::PARAM_STR );
                    $stmt->bindValue( ':work',         $_POST[work],         PDO::PARAM_STR );

                    $stmt->execute(); // sqlの実行
                    // echo 'id:' . $_POST['id'];
                    unlink('/var/www/html/img/author/3.png');
                    
                } catch( PDOException $e ) {
                    echo 'id:'           . $_POST['id'] . '<br>';
                    echo 'name:'         . $_POST['posted_name'] . '<br>';
                    echo 'furigana:'     . $_POST['furigana'] . '<br>';
                    echo 'birthday:'     . $_POST['birthday'] . '<br>';
                    echo 'introduction:' . $_POST['introduction'] . '<br>';
                    echo 'birthplace:'   . $_POST['birthplace'] . '<br>';
                    echo 'work:'         . $_POST['work'] . '<br>';
                    echo 'エラーが発生しました．以下のリンクから再度読み込んでください<br>';
                    echo '<a href="https://inari-dev.tk/authors" style="color:blue;">再度読み込み</a>';
                    exit();
                }
                echo 'image:' . $_FILES[image][name] . '<br>';
                echo 'type:'  . $_FILES[image][type] . '<br>';
                var_dump($_FILES);

                if($_FILES[image][name] != NULL) {
                    $image = $_POST[id] . '.png'; // 画像の名前をid.pngにする
                    //画像を保存
                    // move_uploaded_file($_FILES['image']['tmp_name'], '/var/www/html/img/author/' . $image);
                    if(move_uploaded_file($_FILES['image']['tmp_name'], '/var/www/html/img/author/' . $image)) {
                        echo 'success';
                    } else {
                        echo '画像の保存に失敗しました．編集ページから再度登録してください';
                    }

                    // 画像の名前をDBに保存
                    $sql = 'update authors2 set image = :image where id = :id';
                    $stmt = $dbh->prepare( $sql ); // SQL文を実行する準備をします。

                    $stmt->bindValue( ':image', $image,     PDO::PARAM_STR );
                    $stmt->bindValue( ':id',    $_POST[id], PDO::PARAM_STR );
                    
                    $stmt->execute();
                    echo 'image:' . $image;
                }
            }
            
            try { // editページの生成
                // データベースに接続します。
                $dbh = DbUtil::Connect(); // db.phpのメソッドを使ってDBとのコネクションを確立
                $sql = 'select * from author where id = :id'; // 該当するカラムを抜く
                $stmt = $dbh->prepare( $sql ); // SQL文を実行する準備をします。

                $stmt->bindValue( ':id', $_GET['id'], PDO::PARAM_STR );
                $stmt->execute(); // SQL文を実行します。

                $author = $stmt->fetch(); // 結果を読み出します。             

                echo '<div class="entry-body">';
                echo '<div class="wp-block-columns">&nbsp;</div>';

                $img_url = 'https://inari-dev.tk/img/author/'; // 画像の参照先
                $name         = $author[name];
                $furigana     = $author[furigana];
                $birthday     = $author[birthday];
                $introduction = $author[introduction];
                $birthplace   = $author[birthplace];
                $work         = $author[work];
                    ?>

                <form name="regist_author" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data">
                    <input type="hidden" name = 'id' value="<?php echo $author[id]?>">
                    <h4>名前</h4>
                    <input type="text" name="posted_name"  size="8" value="<?php echo $name;?>">
                    <h4>ふりがな</h4>
                    <input type="text" name="furigana"     size="8" value="<?php echo $furigana;?>">
                    <h4>生年月日</h4>
                    <input type="text" name="birthday"     size="8" value="<?php echo $birthday;?>">
                    <h4>紹介文</h4>
                    <textarea          name="introduction" rows="4" cols="40"><?php echo $introduction?></textarea>
                    <h4>出身地</h4>
                    <input type="text" name="birthplace"   size="8" value="<?php echo $birthplace;?>">
                    <h4>代表作</h4>
                    <input type="text" name="work"         size="8" value="<?php echo $work;?>">
                    <h4>画像</h4>
                    <input type="file" name="image" accept="image/*"><br>
                    ※ファイルサイズは2M以下
                    <h4>確認後に登録ボタンを押してください</h4>
                    <input type="submit" name="button" value="update">
                </form>  

                <?php
                echo '</div>';
                
            }catch( PDOException $e ){
                //echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                echo 'エラーが発生しました．以下のリンクから再度読み込んでください<br>';
                echo '<a href="https://inari-dev.tk/authors" style="color:blue;">再度読み込み</a>';
                exit();
            }       
        } else {
            return $content;
        }
    }
    add_filter('the_content', 'edit_author');
    
?>



