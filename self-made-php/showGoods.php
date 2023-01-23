<?php
//実装時はコメント解除
function show_goods($content) {
 if( is_page( 'goods' ))  //固定ページ「sample_cal」の時だけ処理させる
 {

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>商品表示ページ</title>
        <style type = "text/css">
    <!--
    .c{
        text-align:center;
    }
    .pos{
        position:absolute; bottom:0%; right:0%;
     }
    -->
    </style>
    </head>
    <body>
        <div class='c'>
    <h1>商品表示</h1>

                <table width="90%" class ='c'>
                <tr>
                  <th>商品ID</th>
                  <th>商品名</th>
                  <th>価格</th>
                  <th>概要</th>
                  <th>写真</th>
                  </tr>

        <?php
        include_once dirname( __FILE__ ).'/../db.php';
            // データベースに接続します。
            $dbh = DbUtil::Connect();

            // SQL文を用意します。
            // :で始まる部分が後から値がセットされるプレースホルダです。
            // 複数回SQL文を実行する必要がある場合はここからexecute()までを>繰り返します。
            $sql = 'SELECT * FROM goods';
            // SQL文を実行する準備をします。
            $stmt = $dbh->prepare( $sql );
            // SQL文を実行します。
            $stmt->execute();
            ?>

        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td><?php echo $row['goods_id']; ?></td>
            <td><?php echo $row['goods_name']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <td>
            <?php
        $img_url = "http://100.24.172.143/photo/";
        echo '<figure class="wp-block-image size-full is-resized"><img decoding="async" src="' . $img_url . $row['photo_name'] .".png" . '" alt="画像が読み込めませんでした" width="60" height="60"></figure>';
        ?>
            </td>
        </tr>
    <?php } ?>

                </table>

</div>
    </body>
</html>
<?php

  }
  else
  {
    return $content;
  }
}

add_filter('the_content', 'show_goods');

?>