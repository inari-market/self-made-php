<?php
    function notifications($content) {
        if( is_page( 'notifications' )) { // 特定の固定ページで動作
            include_once dirname( __FILE__ ).'/../../db.php';
            $dbh = DbUtil::Connect(); // データベースに接続します。
            $sql = 'SELECT * FROM notice order by id asc'; 
            $stmt = $dbh->prepare( $sql ); 
            $stmt->execute(); // sqlの実行
?>  
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?> 
                <li><a href="notifications/show/?id=<?php echo $row['id']?>"> <?php echo $row['title']?></a></li>
            <?php } ?>
<?php
        } else {
            return $content;
        }
    }   
    add_filter('the_content', 'notifications');
?>