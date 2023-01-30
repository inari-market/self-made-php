<?php
//実装時はコメント解除
    function showNotification($content) {
        if( is_page( 'notifications/show/' )) { // 特定の固定ページで動作
            include_once dirname( __FILE__ ).'/../../db.php';
            $dbh = DbUtil::Connect(); // データベースに接続します。
            $sql = 'SELECT * FROM notice where id=:id'; 
            $stmt->bindValue( ':id', $_GET['id'], PDO::PARAM_STR );
            $stmt = $dbh->prepare( $sql ); 
            $stmt->execute(); // sqlの実行
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
?>  
            <ul>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?> 
                    <li><a href="notifications/show/?id=<?php echo $row['id']?>"> <?php echo $row['title']?></a></li>
                <?php } ?>
            <ul>				
            <h3><?php echo $row['title']?></h3>
            <p><?php echo $row['body'] ?></p>
<?php
        } else {
            return $content;
        }
    }   

    add_filter('the_content', 'showNotification');
?>