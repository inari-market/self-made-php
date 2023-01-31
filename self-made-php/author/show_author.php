<?php

    function show_author($content) {
        
        if( is_page( 'authors/show' )) { //特定の固定ページの時だけ処理させる 

            include_once dirname( __FILE__ ).'/../../db.php';
            
            try {
                // データベースに接続します。
                $dbh = DbUtil::Connect(); // db.phpのメソッドを使ってDBとのコネクションを確立
                $sql = 'select * from author where id = :id'; // 該当するカラムを抜く
                $stmt = $dbh->prepare( $sql ); // SQL文を実行する準備をします。

                $stmt->bindValue( ':id', $_GET['id'], PDO::PARAM_STR );
                $stmt->execute(); // SQL文を実行します。

                $author = $stmt->fetch(); // 結果を読み出します。

                echo '<div class="entry-body">';
                echo '<div class="wp-block-columns">&nbsp;</div>';

                $img_url = 'http://52.54.93.120/img/author/'; // 画像の参照先
                $name         = $author[name];
                $furigana     = $author[furigana];
                $birthday     = $author[birthday];
                $introduction = $author[introduction];
                $birthplace   = $author[birthplace];
                $work         = $author[work];
                    ?>

                    <div class="is-layout-flex wp-container-3 wp-block-columns">
                        
                        <div class="is-layout-flow wp-block-column" style="flex-basis:33.33%">
                            <figure class="wp-block-image size-medium is-resized"><img decoding="async" src="<?php echo $img_url . $author[image]?>" alt="画像が読み込めませんでした" width="1000" height="1"></figure>
                        </div>
                        <div class="is-layout-flow wp-block-column" style="flex-basis:66.66%">
                            <h1>        <?php echo $name; ?></h1>
                            <p>id:<?php echo $_GET['id']; ?><p>
                            <p>ふりがな：<?php echo $furigana; ?></p> 
                            <p>誕生日：  <?php echo $birthday; ?></p> 
                            <p>紹介：    <?php echo $introduction; ?></p>
                            <p>出身：    <?php echo $birthplace; ?></p>
                            <p>代表作：  <?php echo $work; ?></p>
                        </div>
                    </div>

                    <!-- hear -->
                    <div class="is-layout-flex wp-container-3 wp-block-columns">
                        <div class="is-layout-flow wp-block-column" style="flex-basis:33.33%"><div class="wp-block-image">
                        <figure class="wp-block-image size-medium is-resized"><img decoding="async" src="<?php echo $img_url . $author[image]?>" alt="画像が読み込めませんでした" width="1000" height="1"></figure>
                        <div class="is-layout-flow wp-block-column" style="flex-basis:66.66%">
                        <h3>大野良一 – Ono Ryoichi –</h3>
                        <p>生年：  <?php echo $birthday; ?></p> 
                        <p>出身：    <?php echo $birthplace; ?></p>
                        <p>代表作：  <?php echo $work; ?></p>
                        <p>紹介：    <?php // echo $introduction; ?></p>

                        </div>
                        </div>
                    <!-- to here -->

                    <?php
                echo '</div>';
                
            }catch( PDOException $e ){
                //echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                echo 'エラーが発生しました．以下のリンクから再度読み込んでください<br>';
                echo '<a href="" style="color:blue;">再度読み込み</a>';
                exit();
            }       
?>

<?php 
            return $content;
        } else {
            return $content;
        }
    }
    add_filter('the_content', 'show_author');
    
?>

