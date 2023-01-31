<?php
function show_exhibition($content) {
 if( is_page( 'exhibitions' )) {
?>
    <h2>現在開催中の展示</h2>

        <?php
            include_once dirname( __FILE__ ).'/../../db.php';
            $dbh = DbUtil::Connect();
            $sql = 'SELECT * FROM exhibition where start <= now() and end >= now()';
            $stmt = $dbh->prepare( $sql );
            $stmt->execute();
            $result = $stmt->fetchAll();
            $count   = count( $result ); 

            if(count($resutl) == 0) {
                echo '<h6>現在開催中の展示はありません．次回の展示開催までお待ちください<h6>';
            } else {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
            ?>
                    <div class="is-layout-flex wp-container-9 wp-block-columns">
                        <div class="is-layout-flow wp-block-column">
                            <?php
                                $img_url = "/var/www/html/exhibition/";
                                echo '<figure class="wp-block-image size-large is-resized"><img decoding="async"  loading="lazy" src="' . $img_url . $row['photo_name'] .".png" . '" alt="画像が読み込めませんでした" width="600" height="600"></figure>';
                            ?>
                        </div>

                        <div class="is-layout-flow wp-block-column">
                            <h3><?php echo $row['exhibition_name']; ?></h3>
                            <p>展示開催日：<?php echo $row['start']."日から".$row['end']; ?></p>
                            <p>主催者：<?php echo $row['organizer']; ?></p>
                            <p>概要：<?php echo htmlspecialchars($row['introduction'], ENT_QUOTES); ?></p>
                        </div>
                    </div>
        <?php   }
            } 
        ?>

    <h2>今後開催される展示</h2>
        <?php
            include_once dirname( __FILE__ ).'/../../db.php';
            $dbh = DbUtil::Connect();
            $sql = 'SELECT * FROM exhibition where now() < start';
            $stmt = $dbh->prepare( $sql );
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="is-layout-flex wp-container-9 wp-block-columns">
                    <div class="is-layout-flow wp-block-column">
                        <?php
                            $img_url = "http://52.54.93.120/exhibition/";
                            echo '<figure class="wp-block-image size-large is-resized"><img decoding="async"  loading="lazy" src="' . $img_url . $row['photo_name'] .".png" . '" alt="画像が読み込めませんでした" width="600" height="600"></figure>';
                        ?>
                    </div>

                    <div class="is-layout-flow wp-block-column">
                        <h3><?php echo $row['exhibition_name']; ?></h3>
                        <p>展示開催日：<?php echo $row['start']."日から".$row['end']; ?></p>
                        <p>主催者：<?php echo $row['organizer']; ?></p>
                        <p>概要：<?php echo htmlspecialchars($row['introduction'], ENT_QUOTES); ?></p>
                    </div>
                </div>
        <?php } ?>

    <h4>過去に開催された展示</h4>
        <?php
            include_once dirname( __FILE__ ).'/../../db.php';
            $dbh = DbUtil::Connect();
            $sql = 'SELECT * FROM exhibition where end < now()';
            $stmt = $dbh->prepare( $sql );
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                <hr>
                <p><?php echo "展示会名：".$row['exhibition_name']."，主催者：".$row['organizer']; ?></p>
        <?php }

  } else {
    return $content;
  }
}

add_filter('the_content', 'show_exhibition');

?>