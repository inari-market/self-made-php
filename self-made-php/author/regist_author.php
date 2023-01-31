<?php
    function regist_author($content) {
        // if( is_page( 'regist-author' ) && is_user_logged_in()) {//特定の固定ページの時だけ処理させる 
        if( is_page( 'authors/new' )) {//特定の固定ページの時だけ処理させる 
            if( $_POST['button'] == 'create'){ // このページにPOSTでアクセスしたら  
                include_once dirname( __FILE__ ).'/../../db.php';
                try {
                    // データベースに接続します。
                    $dbh = DbUtil::Connect(); // db.phpのメソッドを使ってDBとのコネクションを確立
                    $sql = 'insert into author(name, furigana, birthday, introduction, birthplace, work) values(:name, :furigana, :birthday, :introduction, :birthplace, :work)';
                    $stmt = $dbh->prepare( $sql ); // SQL文を実行する準備をします。
        
                    // ポストのパラメータを変数へと代入．個人的に読みやすいから．
                    $name         = $_POST['posted_name'];
                    $furigana     = $_POST['furigana'];
                    $birthday     = $_POST['birthday'];    // とりあえずポストされた値を代入
                    if(empty($birthday)) $birthday = NULL; // 空文字ならNULLにする
                    $introduction = $_POST['introduction'];
                    $birthplace   = $_POST['birthplace'];
                    $work         = $_POST['work'];

                    // プレースホルダに実際の値をバインドします。
                    $stmt->bindValue( ':name',         $name,         PDO::PARAM_STR );
                    $stmt->bindValue( ':furigana',     $furigana,     PDO::PARAM_STR );
                    $stmt->bindValue( ':birthday',     $birthday,     PDO::PARAM_STR );
                    $stmt->bindValue( ':introduction', $introduction, PDO::PARAM_STR );
                    $stmt->bindValue( ':birthplace',   $birthplace,   PDO::PARAM_STR );
                    $stmt->bindValue( ':work',         $work,         PDO::PARAM_STR );

                    // SQL文を実行します。
                    $stmt->execute();
                    
                    // ここから画像の取り扱い
                    $img = $_FILES['image']['name'];
                    echo 'ファイルサイズ:' . $_FILES['image']['size'];

                    if ($img == NULL) {
                        echo 'image_name is NULL'; // Nullなら何もしない
                    } else { // 画像が添付されていれば保存する
                        $sql  = 'select max(id) as id from author'; // テーブルのidの最大値を
                        $stmt = $dbh->prepare( $sql ); // 
                        $stmt->execute();
                        $id = $stmt->fetch( PDO::FETCH_ASSOC ); // SQLの実行結果

                        $image = $id['id'] . '.png';// 画像の名前をid.pngにする

                        //画像を保存
                        move_uploaded_file($_FILES['image']['tmp_name'], '/var/www/html/img/author/' . $image);

                        // 画像の名前をDBに保存
                        $sql = 'update author set image = :image where id = :id';
                        $stmt = $dbh->prepare( $sql ); // SQL文を実行する準備をします。

                        $stmt->bindValue( ':image', $image,     PDO::PARAM_STR );
                        $stmt->bindValue( ':id',    $id['id'],  PDO::PARAM_STR );
                        $stmt->execute();
                    }
                    
                    echo '<p>登録完了しました</p>';
                    
                }catch( PDOException $e ){
                    echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                    // if($_POST[birthday] == NULL) echo 'NULL';
                    // echo 'birthday:' . $_POST['birthday'];
                    // echo 'エラーが発生しました．やりなおしてください．<br>';
                    // echo '<a href="https://inari-dev.tk/regist-author" style="color:blue;">入力フォームへ戻る </a>';
                    exit();
                }
            }
?>

<!-- 入力フォーム -->
<h1>作者情報登録</h1>
<form name="regist_author" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data">
           
    <h4>名前</h4>
    <input type="text" name="posted_name"  size="8"  placeholder="必須項目">

    <h4>ふりがな</h4>
    <input type="text" name="furigana"     size="8" placeholder="必須項目">

    <h4>生年</h4>
    <input type="text" name="birthday"     size="8" value="">

    <h4>紹介文</h4>
    <textarea          name="introduction" placeholder="必須項目" rows="4" cols="40"></textarea>

    <h4>出身地</h4>
    <input type="text" name="birthplace"   size="8">

    <h4>代表作</h4>
    <input type="text" name="work"         size="8">

    <h4>画像</h4>
    <input type="file" name="image" accept="image/*"><br>
    ※ファイルサイズは2M以下        
    <h4>確認後に登録ボタンを押してください</h4>
    <input type="submit" name="button" value="create">
</form>

<?php
        } else {
            return $content;
        }
    }
    add_filter('the_content', 'regist_author');
?>
