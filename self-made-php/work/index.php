<?php
    function works($content) {
        if( is_page('works')) { //特定の固定ページの時だけ処理させる 
            include_once dirname( __FILE__ ).'/../../db.php';
            try {
                // データベースに接続します。
                $dbh = DbUtil::Connect(); // db.phpのメソッドを使ってDBとのコネクションを確立
                $sql = 'select * from work '; //全部抜く
                $stmt = $dbh->prepare( $sql ); // SQL文を実行する準備をします。
                $stmt->execute(); // SQL文を実行します。

                // イテレータとかの準備
                $works = $stmt->fetchAll(); // 結果をすべて読み出します。
                $count = count($works);
                $row   = ceil($count / 3);
                $col   = $count % 3;
                $index = 0;

                echo '<div class="entry-body">';
                $img_url = 'http://52.54.93.120/img/work/'; // 画像の参照先
                for( $i = 0; $i < $row; $i++ ) {
                    echo '<div class="is-layout-flex wp-container-4 wp-block-columns">'; 
                    $j = 0;
                    while($j < 3) {
                        echo '<div class="is-layout-flow wp-block-column">';
                        if (($i+1) == $row && $j >= $col) {
                            // なにもしない
                        } else {
                    ?>
                        <figure class="wp-block-image size-large" id="sakuhin"><img decoding="async" src="<?php echo $img_url . $works[$index]['image']; ?>" alt="" class="wp-image-1108" sizes="(max-width: 1783px) 100vw, 1783px" width="1783" height="1782"></figure>
                        <h3 class="headline-002"><?php echo $works[$index]['name']?></h3>
                        <p>作者：<?php echo $works[$index]['author'];?></p>
                        <p>制作：<?php echo $works[$index]['year']?></p>
                    <?php
                        }
                        $j++;
                        $index++;
                        echo '</div>';
                    }
                    echo '</div>';
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
    add_filter('the_content', 'works');
?>



