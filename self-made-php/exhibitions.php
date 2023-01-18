
<?php

    function exhibitions($content) {
        
        if( is_page( 'exhibitions' )) {//特定の固定ページの時だけ処理させる 

            include_once dirname( __FILE__ ).'/../db.php';
            
            try {
                // データベースに接続します。
                $dbh = DbUtil::Connect(); // db.phpのメソッドを使ってDBとのコネクションを確立
                $sql = 'select start, end, introduction from exhibitions1 order by start desc'; //昇順にソート
                $stmt = $dbh->prepare( $sql ); // SQL文を実行する準備をします。

                // SQL文を実行します。
                $stmt->execute();

                // 結果をすべて読み出します。
                $result = $stmt->fetchAll();
                
                $before;  // 前回の展示についての説明文を入れる変数
                $current; // 現在の展示   ``
                $next;    // 次回の展示   ``
                $now = strtotime('today');

                $count = count( $result )-1; // whileの中では次回の企画展のデータを参照するので，-1で十分
                $i = 0; 
                while( $i < $count ) { // 開始日 <= 今日 <= 終了日　となるデータを探す
                    $start = strtotime($result[$i][start]);
                    $end = strtotime($result[$i][end]);
                    if(($start <= $now) && ($now <= $end)) {
                        $before  = $result[$i+1][introduction];
                        $current = $result[$i][introduction];
                        $next    = $result[$i-1][introduction];
                        break;
                    }
                    ++$i;
                }
                
            }catch( PDOException $e ){
                //echo( '接続失敗: ' . $e->getMessage() . '<br>' );
                echo 'エラーが発生しました．以下のリンクから再度読み込んでください<br>';
                echo '<a href="https://inari-dev.tk/exhibitions" style="color:blue;">再度読み込み</a>';
                exit();
            }       
?>

<div class="entry-body">	
    <h2>【 今月の展示 】</h2>

    <div class="wp-block-columns">&nbsp;</div>

    <div class="is-layout-flex wp-container-3 wp-block-columns">
        <div class="is-layout-flow wp-block-column">
            <figure class="wp-block-image size-full is-resized"><img decoding="async" src="https://inari-dev.tk/img/dog.jpg" alt="すごく偉大なつぼ"></figure>
        </div>

        <div class="is-layout-flow wp-block-column">
            <p>ここに概要</p>
            <p>これは高橋太郎さんが作った偉大なつぼ<br>すごくすごく偉大なつぼ</p>
            <p><?php echo(htmlspecialchars($current, ENT_QUOTES));?></p>
        </div>
    </div>

    <p></p>

    <h2>【 来月の展示 】</h2>

    <div class="is-layout-flex wp-container-6 wp-block-columns">
        <div class="is-layout-flow wp-block-column">
            <figure class="wp-block-image size-full is-resized"><img decoding="async" loading="lazy" src="https://inari-dev.tk/img/cat.jpg" alt="あまりに美しい絵画"></figure>
        </div>

        <div class="is-layout-flow wp-block-column">
            <p>ここに概要</p>
            <p>鮪野刺身さんが描いた美しい絵画<br>あまりに美しい絵画<br></p>
            <p><?php echo(htmlspecialchars($next, ENT_QUOTES));?></p>
        </div>
    </div>

    <h2>【 先月の展示 】</h2>

    <div class="is-layout-flex wp-container-9 wp-block-columns">
        <div class="is-layout-flow wp-block-column">
            <figure class="wp-block-image size-large is-resized"><img decoding="async" loading="lazy" src="https://inari-dev.tk/img/rock1.JPG" alt="常設展示されている岩"></figure>
        </div>

        <div class="is-layout-flow wp-block-column">
            <p>ここに概要</p>
            <p>常設展示されている岩<br>とっても大きい</p>
            <p><?php echo(htmlspecialchars($before, ENT_QUOTES));?></p>
        </div>
    </div>

    <h2>【 過去の展示 】</h2>
    <ul>
        <li>2022年</li>
        <li>2021年</li>
        <li>2020年</li>
        <li>2019年</li>
        <li>2018年</li>
        <li>2017年</li>
    </ul>
    <p><!-- /wp:columns --></p>			
</div>

<?php 
        } else {
            return $content;
        }
    }
    add_filter('the_content', 'exhibitions');
    
?>



