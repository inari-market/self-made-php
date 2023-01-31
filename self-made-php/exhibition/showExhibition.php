<?php
function show_exhibition($content) {
 if( is_page( 'exhibitions' ))  
 {

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>展示会情報表示ページ</title>
        <style type = "text/css">
    <!--
    .pos{
        position:absolute; bottom:0%; right:0%;
     }
    -->
    </style>
    </head>
    <body>

    <h1>展示会情報表示</h1>

        <?php
        include_once dirname( __FILE__ ).'/../../db.php';
            // データベースに接続します。
            $dbh = DbUtil::Connect();

            // SQL文を用意します。
            // :で始まる部分が後から値がセットされるプレースホルダです。
            // 複数回SQL文を実行する必要がある場合はここからexecute()までを>繰り返します。
            $sql = 'SELECT * FROM exhibition order by start asc';
            // SQL文を実行する準備をします。
            $stmt = $dbh->prepare( $sql );
            // SQL文を実行します。
            $stmt->execute();
            ?>

        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <div class="is-layout-flex wp-container-9 wp-block-columns">
            <div class="is-layout-flow wp-block-column">
                <?php
                    $img_url = "http://100.24.172.143/exhibition/";
                    echo '<figure class="wp-block-image size-large is-resized"><img decoding="async"  loading="lazy" src="' . $img_url . $row['photo_name'] .".png" . '" alt="画像が読み込めませんでした" width="600" height="600"></figure>';
                ?>
            </div>

            <div class="is-layout-flow wp-block-column">
                <h2><?php echo $row['exhibition_name']; ?></h2>
                <p>展示開催日：<?php echo $row['start']."日から".$row['end']; ?></p>
                <p>主催者：<?php echo $row['organizer']; ?></p>
                <p>概要：<?php echo htmlspecialchars($row['introduction'], ENT_QUOTES); ?></p>
            </div>
        </div>

    <?php } ?>
    </body>
</html>
<?php

  }
  else
  {
    return $content;
  }
}

add_filter('the_content', 'show_exhibition');

?>