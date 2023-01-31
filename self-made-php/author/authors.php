hoge
<?php
    function authors($content) {
        if( is_page('authors')) { //特定の固定ページの時だけ処理させる 
            include_once dirname( __FILE__ ).'/../../db.php';
            
            try {
                // データベースに接続します。
                $dbh = DbUtil::Connect(); // db.phpのメソッドを使ってDBとのコネクションを確立
                $sql = 'select * from author '; //全部抜く
                $stmt = $dbh->prepare( $sql ); // SQL文を実行する準備をします。
                $stmt->execute(); // SQL文を実行します。

                $authors = $stmt->fetchAll(); // 結果をすべて読み出します。

                echo '<div class="entry-body">';
                // echo '<div class="wp-block-columns">&nbsp;</div>'; // この行の意味はよくわかってない．調べたけどいらなさそう

                // イテレータとかの準備
                $count   = count( $authors ); // 作者の総数
                $row     = floor($count / 4 ) + 1; // 1行に4人ずつ表示したときに何行必要か
                $col     = $count % 4; // 最終行には何人表示されるか
                $img_url = 'http://52.54.93.120/img/author/'; // 画像の参照先
                $index   = 0; // 配列のインデックス

                // 詳細・修正・削除ボタンのCSS
                $css = 'display       : inline-block;  
                        border-radius : 5%;
                        font-size     : 9pt;
                        text-align    : center;
                        cursor        : pointer;
                        padding       : 12px 12px;
                        background    : #ffffff;
                        color         : rgba(26, 26, 255, 0.68);
                        line-height   : 1em;
                        transition    : .3s;
                        box-shadow    : 6px 6px 3px #666666;
                        border        : 2px solid #ffffff;';
        
                // 一行に４人表示する．(横並びに４人表示する)
                for( $i = 0; $i < $row; $i++ ) { 
                    ?>

                    <div class="is-layout-flex wp-container-3 wp-block-columns">
                        <?php 
                            $j = 0;
                            while($j < 4) {
                        ?>
                                <div class="is-layout-flow wp-block-column">
                                    <?php
                                        if(($i + 1) == $row && $j >= $col) { // 最終行かつ，最終行に全員分表示していたら
                                            // なにもしない                                                                         
                                        } else {
                                            echo '<h1>' . $authors[$index][name] . '</h1>';
                                            echo '<figure class="wp-block-image size-full is-resized"><img decoding="async" src="' . $img_url . $authors[$index][image] . '" alt="画像が読み込めませんでした"></figure>';
                                            echo '   <a href="https://inari-dev.tk/authors/show?id='   . $authors[$index][id] . '"style="' . $css . '">詳細</a>';
                                            echo ' | <a href="https://inari-dev.tk/authors/edit?id='   . $authors[$index][id] . '"style="' . $css . '">編集</a>';
                                            echo ' | <a href="https://inari-dev.tk/authors/delete?id=' . $authors[$index][id] . '"style="' . $css . '">削除</a>';
                                            $index++;
                                        }
                                    ?>
                                </div>
                        <?php
                                $j++; // ++jの方がいいのか？ 
                            }
                        ?>
                    </div>

                    <?php
                }
                echo '</div>';
                
            }catch( PDOException $e ){
                //echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                echo 'エラーが発生しました．以下のリンクから再度読み込んでください<br>';
                echo '<a href="" style="color:blue;">再度読み込み</a>';
                exit();
            }       
?>

<?php
            session_start();
            if(! empty($_SESSION['delete_exhibitio'])){
                echo("<br>".$_SESSION['delete_exhibitio']."<br>");
                unset($_SESSION['delete_exhibitio']);
            }else{
                echo("<br><br>");
            }
            ?>

<?php
            return $content;
        } else {
            return $content;
        }
    }   
    add_filter('the_content', 'authors');
?>



