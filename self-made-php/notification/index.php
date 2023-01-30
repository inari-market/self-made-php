<?php
//実装時はコメント解除
    function notifications($content) {
        if( is_page( 'notifications' )) { // 特定の固定ページで動作
            include_once dirname( __FILE__ ).'/../../db.php';
            $dbh = DbUtil::Connect(); // データベースに接続します。
            $sql = 'SELECT * FROM notice'; 
            $stmt = $dbh->prepare( $sql ); 
            $stmt->execute(); // sqlの実行
?>  
            <ul>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?> 
                    <li><a href="/var/www/html/wp-content/themes/lightning/show/>?<?php echo $row['notice_id']?>"> <?php echo $row['title']?></a></li>
                <?php } ?>
            <ul>
<?php
        } else {
            return $content;
        }
    }   

    add_filter('the_content', 'notifications');
?>