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
                $img_url = 'http://52.54.93.120/img/author/'; // 画像の参照先
                for( $i = 0; $i < count($authors); $i++ ) { 

                    ?>
                    <!-- here -->
                    <!-- <div class="is-layout-flow wp-block-column" style="flex-basis:33.33%"><div class="wp-block-image">
                    <figure class="aligncenter size-full is-resized"><img decoding="async" src= "<?php echo $img_url . $authors[$i]['image'];?>" .  alt="" class="wp-image-1023" width="200" height="240"></figure></div></div>

                    <div class="is-layout-flow wp-block-column" style="flex-basis:66.66%">
                    <h3><?php echo $authors[$i]['name']?> -<?php echo $authors[$i]['furigana'] ?>-</h3>

                    <p>生年:<?php echo $authors[$i]['birthday']?></p>
                    <p>出身：<?php echo $authors[$i]['birthplace']?></p><br>
                    <p>代表作：<?php echo $authors[$i]['work']?></p>
                    <div class="button_line007"><a href="http://52.54.93.120/authors/show/?id=<?php echo $authors[$i]['id'];?>" data-type="page" data-id="1034">詳しく知る</a></div>
                    </div> -->
                    <!-- to here -->
                    <div class="is-layout-flex wp-container-3 wp-block-columns">
                        <div class="is-layout-flow wp-block-column" style="flex-basis:33.33%"><div class="wp-block-image">
                        <figure class="aligncenter size-full is-resized"><img decoding="async" src= "<?php echo $img_url . $authors[$i]['image'];?>" .  alt="" class="wp-image-1023" width="200" height="240"></figure></div></div>
                        <div class="is-layout-flow wp-block-column" style="flex-basis:66.66%">
                        <p>生年:<?php echo $authors[$i]['birthday']?></p>
                    <p>出身：<?php echo $authors[$i]['birthplace']?></p><br>
                    <p>代表作：<?php echo $authors[$i]['work']?></p>
                    <div class="button_line007"><a href="http://52.54.93.120/authors/show/?id=<?php echo $authors[$i]['id'];?>" data-type="page" data-id="1034">詳しく知る</a></div>
                        </div>
                        </div>
                        <!-- tohere -->


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



