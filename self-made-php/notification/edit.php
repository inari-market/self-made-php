<?php
    function edit_notification($content) {
        
        if( is_page( 'notification/edit' )) {//特定の固定ページの時だけ処理させる 

            include_once dirname( __FILE__ ).'/../../db.php';

            if($_POST['button'] == 'update') { // updateの処理
                try {
                    $dbh = DbUtil::Connect(); // db.phpのメソッドを使ってDBとのコネクションを確立
                    $sql = 'update notitce set title = :title, body = :body where id = :id';
                    $stmt = $dbh->prepare( $sql ); // SQL文を実行する準備をします。
                    
                    // プレースホルダに実際の値をバインドします。
                    $stmt->bindValue( ':id',       $_POST[id],           PDO::PARAM_STR );
                    $stmt->bindValue( ':title',    $_POST[notice_title], PDO::PARAM_STR );
                    $stmt->bindValue( ':body',     $_POST[notice_body],  PDO::PARAM_STR );
                    $stmt->execute(); // sqlの実行
                    
                } catch( PDOException $e ) {
                    echo 'エラーが発生しました．以下のリンクから再度読み込んでください<br>';
                    echo '<a href="" style="color:blue;">再度読み込み</a>';
                    exit();
                }
            }
            
            try { // editページの生成
                // データベースに接続します。
                $dbh = DbUtil::Connect(); // db.phpのメソッドを使ってDBとのコネクションを確立
                $sql = 'select * from notice where id = :id'; // 該当するカラムを抜く
                $stmt = $dbh->prepare( $sql ); // SQL文を実行する準備をします。

                $stmt->bindValue( ':id', $_GET['id'], PDO::PARAM_STR );
                $stmt->execute(); // SQL文を実行します。

                $notice = $stmt->fetch(); // 結果を読み出します。             

                echo '<div class="entry-body">';
                echo '<div class="wp-block-columns">&nbsp;</div>';

                $img_url = 'https://inari-dev.tk/img/author/'; // 画像の参照先
                $title   = $notice[title];
                $body = $notice[body];

                ?>

                <form name="regist_author" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data">
                    <input type="hidden" name = 'id' value="<?php echo $work[id];?>">
                    <h4>タイトル</h4>
                    <input type="text" name="notice_title"  size="8" value="<?php echo $name;?>">
                    <h4>内容</h4>
                    <input type="text" name="notice_body"     size="8" value="<?php echo $author;?>">
                    <h4>確認後に登録ボタンを押してください</h4>
                    <input type="submit" name="button" value="update">
                </form>  

                <?php
                echo '</div>';
                
            }catch( PDOException $e ){
                //echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                echo 'エラーが発生しました．以下のリンクから再度読み込んでください<br>';
                echo '<a href="" style="color:blue;">再度読み込み</a>';
                exit();
            }       
        } else {
            return $content;
        }
    }
    add_filter('the_content', 'edit_notification');
    
?>



