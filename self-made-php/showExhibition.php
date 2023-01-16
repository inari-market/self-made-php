<?php
//実装時はコメント解除

function show_exhibition($content) {
 if( is_page( 'exhibitions' ))  //固定ページ「sample_cal」の時だけ処理させる
 {

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>展示会情報表示ページ</title>
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
    <h1>企画展情報表示</h1>

                <table width="90%" class ='c'>
                <tr>
                  <th>企画展ID</th>
                  <th>企画展名</th>
                  <th>開始日</th>
                  <th>終了日</th>
                  <th>主催者名</th>
                  <th>概要</th>
                  </tr>

        <?php
        include_once dirname( __FILE__ ).'/../db.php';
            // データベースに接続します。
            $dbh = DbUtil::Connect();

            // SQL文を用意します。
            // :で始まる部分が後から値がセットされるプレースホルダです。
            // 複数回SQL文を実行する必要がある場合はここからexecute()までを>繰り返します。
            $sql = 'SELECT * FROM exhibition_table';
            // SQL文を実行する準備をします。
            $stmt = $dbh->prepare( $sql );
            // SQL文を実行します。
            $stmt->execute();
            ?>

        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td><?php echo $row['exhibition_id']; ?></td>
            <td><?php echo $row['exhibition_name']; ?></td>
            <td><?php echo $row['start']; ?></td>
            <td><?php echo $row['end']; ?></td>
            <td><?php echo $row['organizer']; ?></td>
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

add_filter('the_content', 'show_exhibition');

?>