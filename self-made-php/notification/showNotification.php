<?php
//実装時はコメント解除
function show_notification($content) {
 if( is_page( 'notification' ))  //固定ページ「sample_cal」の時だけ処理させる
 {

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>お知らせ情報表示ページ</title>
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
    <h1>お知らせ情報表示</h1>

                <table width="90%" class ='c'>
                <tr>
                  <th>お知らせID</th>
                  <th>タイトル</th>
                  <th>内容</th>
                  </tr>

        <?php
        include_once dirname( __FILE__ ).'/../../db.php';
            // データベースに接続します。
            $dbh = DbUtil::Connect();
            $sql = 'SELECT * FROM notice';
            // SQL文を実行する準備をします。
            $stmt = $dbh->prepare( $sql );
            // SQL文を実行します。
            $stmt->execute();
            ?>

        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td><?php echo $row['notice_id']; ?></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['body']; ?></td>
            <td><?php echo htmlspecialchars($row['introduction'], ENT_QUOTES, 'UTF-8'); ?></td>
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

add_filter('the_content', 'show_notification');

?>