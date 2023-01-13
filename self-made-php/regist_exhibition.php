<?php
    function regist_exhibition($content) {
        
        if( is_page( 'regist_exhibition' )) {//特定の固定ページの時だけ処理させる 
            if( $_POST['button'] == '登録'){ // このページにPOSTでアクセスしたら   
                include_once dirname( __FILE__ ).'/db.php';
                
                try {
                    // データベースに接続します。
                    $dbh = DbUtil::Connect(); // db.phpのメソッドを使ってDBとのコネクションを確立
                    $sql = 'insert into exhibitions1(name, start, end, organizer, introduction) values(:name, :start, :end, :organizer, :introduction)';
                    $stmt = $dbh->prepare( $sql ); // SQL文を実行する準備をします。
        
                    $name         = $_POST['posted_name'];
                    $start        = $_POST['start'];
                    $end          = $_POST['end'];
                    $organizer    = $_POST['organizer'];
                    $introduction = $_POST['introduction'];

                    // プレースホルダに実際の値をバインドします。
                    $stmt->bindValue( ':name',         $name,         PDO::PARAM_STR );
                    $stmt->bindValue( ':start',        $start,        PDO::PARAM_STR );
                    $stmt->bindValue( ':end',          $end,          PDO::PARAM_STR );
                    $stmt->bindValue( ':organizer',    $organizer,    PDO::PARAM_STR );
                    $stmt->bindValue( ':introduction', $introduction, PDO::PARAM_STR );

                    // SQL文を実行します。
                    $stmt->execute();
                    
                    // ここから探索
                    $sql = 'SELECT * FROM exhibitions1';
                    $stmt = $dbh->prepare( $sql ); // SQL文を実行する準備をします。
                    $stmt->execute();

                    // 結果を1行読み出します。
                    $result = $stmt->fetch( PDO::FETCH_ASSOC );
                    // echo(createHtmlTable($result));
                    // データが存在すれば連想配列、存在しない場合は FALSE となっています。
                    if( $result === FALSE ){
                    // データが FALSE 、つまりデータが存在しない場合。
                    }else{
                    // 列名がキーとなった連想配列で取得できるため列名でアクセスできる。
                        $InputName=$result['name'];
                    }
                    echo '<p>登録完了しました</p>';
                    
                }catch( PDOException $e ){
                    // echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                    // $_POST['error'] = 'true';
                    echo 'エラーが発生しました．やりなおしてください．<br>';
                    echo '<a href="https://inari-dev.tk/sample_app" style="color:blue;">入力フォームへ戻る</a>';
                    // header("Location: https://inari-dev.tk/sample_app", true, 307);
                    exit();
                }

                $post_data = [ // ポストされたデータを変数に代入
                    'posted_name'  => $_POST['posted_name'], 
                    'start'        => $_POST['start'],
                    'end'          => $_POST['end'],
                    'organizer'    => $_POST['organizer'],
                    'introduction' => $_POST['introduction']
                ];

                $json_data = json_encode($post_data); // jsonに変換

                $ch = curl_init(); // 以下はcurlのフォーマットに従う
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                // APIのURI

                $url = 'https://script.google.com/macros/s/AKfycbydBlbDVB9vSvtiSzNyGwwoU1p1OMlUASn_KhYunqqM6uuT-zVBmK4WmI9SkVRcn_PW/exec';
                curl_setopt($ch, CURLOPT_URL, $url);  
                $result=curl_exec($ch);
                curl_close($ch);
            }
?>

<!-- 入力フォーム -->
<h1>企画展情報登録</h1>
<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <p>企画展名</p>
    <input type="text" name="posted_name"  size="8" value="<?php echo $_POST["posted_name"]?>">
    <p>開催開始日</p>
    <input type="text" name="start"        size="8" value="2000/1/1">
    <p>開催最終日</p>
    <input type="text" name="end"          size="8" value="2000/12/31">
    <p>主催者名</p>
    <input type="text" name="organizer"    size="8" value="<?php echo $_POST["organizer"]?>">
    <p>概要</p>
    <textarea          name="introduction" rows="4" cols="40"></textarea>
    <p>確認後に登録ボタンを押してください</p>
    <input type="submit" name="button" value="登録">
</form>

<?php
        } else {
            return $content;
        }
    }
    add_filter('the_content', 'regist_exhibition');
?>
